<?php
class desiderataLibrary_service_PublicationService extends GlizyObject
{
    public function isInLibrary($volume_id)
    {
        $licenses = gruppometa_easybook_EasybookFE::getLicenses();
        return $licenses ? in_array($volume_id, $licenses) : false;
    }

    public function getContentInfo($volume_id, $content_id)
    {
        $ar = __ObjectFactory::createModelIterator('desiderataLibrary.models.Publication')
            ->load('getContentInfo', array(
                'params' => array(
                    'content_id' => $content_id
                )
            ))
            ->first();

        return array(
            'publicationTitle' => $ar->publicationTitle,
            'contentTitle' => $ar->contentTitle
        );
    }
}