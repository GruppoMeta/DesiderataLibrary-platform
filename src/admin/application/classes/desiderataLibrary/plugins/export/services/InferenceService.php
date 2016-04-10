<?php
class desiderataLibrary_plugins_export_services_InferenceService extends GlizyObject
{
	public function getRelatedContents($tags)
    {
        $result = array(
            'contents' => array(),
            'relatedContents' => array()
        );

        if (empty($tags)) {
            return $result;
        }

        $application = org_glizy_ObjectValues::get('org.glizy', 'application');

        $entityProxy = org_glizy_objectFactory::createObject('desiderataLibrary.modules.ontologybuilder.models.proxy.EntityProxy');
        $entityTypeService = $application->retrieveProxy('desiderataLibrary.modules.ontologybuilder.service.EntityTypeService');

        // Dal tag ontologico T ricava la/le entità "padri" aventi una relazione entrante nell'entità relativa al
        // tag ontologico T.
        // Per ogni entità "padre" ricavare i contenuti ontologici di tipo T e restituire gli elementi di lettura
        // taggati con questi contenuti ontologici.
        foreach ($tags as $tag) {
            if ($tag->type == 'Contenuto') {
                $result['contents'][] = $tag->text;

                $content = $entityProxy->loadContent($tag->id);
                $entityId = $content['entityTypeId'];

                // ricava i contenuti collegati dalle relazioni uscenti dall'entità $entityId
                $entityTypeProperties = $entityTypeService->getEntityTypeProperties($entityId);
                foreach((array) $entityTypeProperties as $entityTypeProperty) {
                    // se l'attributo è una relazione
                    if (!is_null($entityTypeProperty['entity_properties_target_FK_entity_id'])) {
                        $attribute = $entityTypeService->getAttributeIdByProperties($entityTypeProperty);
                        // contenuti collegati
                        foreach ($content[$attribute] as $referencedContent) {
                            $result['relatedContents'][] = $referencedContent['text'];
                        }
                    }
                }

                // ricava le entità padre nell'ontologia
                $it = __ObjectFactory::createModelIterator('desiderataLibrary.modules.ontologybuilder.models.EntityProperties')
                    ->load('entityRelationsFromId', array('entityId' => $entityId));

                // per ogni entità padre
                foreach ($it as $ar) {
                    // ricava i contenuti dell'entità referente
                    $it = __ObjectFactory::createModelIterator('desiderataLibrary.modules.ontologybuilder.models.EntityDocument')
                        ->load('allFromEntityId', array('entityId' => $ar->entity_id));

                    // per ogni contenuto referente
                    foreach ($it as $ar) {
                        $content = $entityProxy->loadContent($ar->getId());
                        $entityTypeProperties = $entityTypeService->getEntityTypeProperties($content['entityTypeId']);
                        foreach((array) $entityTypeProperties as $entityTypeProperty) {
                            // se l'attributo è una relazione
                            if ($entityTypeProperty['entity_properties_target_FK_entity_id'] == $entityId) {
                                $attribute = $entityTypeService->getAttributeIdByProperties($entityTypeProperty);
                                $found = false;
                                $candidateTags = array();

                                foreach ($content[$attribute] as $referencedContent) {
                                    // verifica se il contenuto referenziato è quello del tag in input
                                    if ($referencedContent['id'] == $tag->id) {
                                        $found = true;
                                    }
                                    $candidateTags[] = $referencedContent['text'];
                                }

                                // se il contenuto è correlato con il contenuto in $tag
                                if ($found) {
                                   $result['relatedContents'] = array_merge($result['relatedContents'], $candidateTags);
                                }
                            }
                        }
                    }
                }
            }
        }

        $result['relatedContents'] = array_unique($result['relatedContents']);

        return $result;
    }
}