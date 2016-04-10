<?php
class gruppometa_easybook_controllers_publication_New extends org_glizy_mvc_core_Command
{
    public function execute($type)
    {
        // TODO controllo permessi

        // controlla se il tipo è corretto
        $pubInfo = gruppometa_easybook_Easybook::getPublicationInfoForType($type);
        if ($pubInfo!==false) {
            $routeUrl = $pubInfo->routeUrl;
            $title = 'Nuova pubblicazione';
            $menuProxy = org_glizy_ObjectFactory::createObject('org.glizycms.contents.models.proxy.MenuProxy');
            $pubId = $menuProxy->addMenu($title, 0, $type);

            // forza il siteId della pubblicazione
            $ar = org_glizy_ObjectFactory::createModel('org.glizycms.core.models.Menu');
            $ar->load($pubId);
            $ar->menu_FK_site_id = $pubId;
            $ar->save();

            // salva il primo contenuto altrimenti la pubblicazione non è visibile nell'elenco
            __Config::set('MULTISITE_ENABLED', true);
            org_glizy_ObjectValues::set('org.glizy', 'siteId', $pubId);
            $contentProxy = org_glizy_objectFactory::createObject('org.glizycms.contents.models.proxy.ContentProxy');
            $contentVO = $contentProxy->getContentVO();
            $contentVO->setId($pubId);
            $contentVO->setTitle($title);
            if ($this->user->groupId!=1) {
                $ar = __ObjectFactory::createModel('org.glizy.models.User');
                $ar->load($this->user->id);
                $arEditor = __ObjectFactory::createModel('desiderataLibrary.models.Editor');
                $arEditor->load($ar->user_FK_editor_id);

                $contentVO->setIndexFields(array('publisher@id' => 'int'));
                $contentVO->publisher = array('id' => $ar->user_FK_editor_id, 'text' => $arEditor->editor_name);
            }

            $contentProxy->saveContent($contentVO,
                                    org_glizy_ObjectValues::get('org.glizy', 'editingLanguageId'),
                                    __Config::get('glizycms.content.history')
                                );

            $this->log('Pubblicazione aggiunta correttamente #id:'.$pubId, GLZ_LOG_INFO, 'easybook' );
            $this->changePage($routeUrl, array('menu_id' => $pubId));
        } else {
            $this->logAndMessage('Tipo di pubblicazione non valido', '', GLZ_LOG_ERROR, 'easybook' );
            $this->changeAction('index');
        }
    }
}