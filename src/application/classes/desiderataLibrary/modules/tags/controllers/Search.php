<?php
class desiderataLibrary_modules_tags_controllers_Search extends org_glizy_rest_core_CommandRest
{
    function execute($topics)
    {
        $result = array();

        if (!$this->user->isLogged()) {
            $result = desiderataLibrary_modules_models_vo_ErrorVO::Unauthorized();
        } else {
            $it = __ObjectFactory::createModelIterator('desiderataLibrary.modules.tags.models.Tags')
                ->load('getContents', array(
                    'params' => array(
                        ':user_id' => $this->user->id
                    ),
                    'replace' => array(
                        '##tagdetail_keyword##' => "('".implode("', '", $topics)."')"
                    )
                ));

            $result = array(
                'results' => array(),
                'categories' => array()
            );

            desiderataLibrary_models_vo_ResultVO::init();

            foreach($it as $ar) {
                $resultVO = __ObjectFactory::createObject('desiderataLibrary.models.vo.ResultVO');
                $resultVO->createFromVolumeIdContentId($this->user->id, $ar->tag_volume_id, $ar->tag_content_id);
                $result['results'][] = $resultVO;
            }

            $topicsMap = array_fill_keys(array_map('strtolower',$topics), true);

            $it = __ObjectFactory::createModelIterator('desiderataLibrary.modules.tags.models.Tags')
                ->load('getCategories', array('params' => array(
                    ':user_id' => $this->user->id,
                )));

            foreach($it as $ar) {
                $label = $ar->tagdetail_keyword;
                $result['categories'][] = array(
                    'label' => $label,
                    'selected' => $topicsMap[strtolower($label)] ? true : false
                );
            }
        }

        return $result;
    }
}