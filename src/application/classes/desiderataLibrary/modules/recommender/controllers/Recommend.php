<?php
class desiderataLibrary_modules_recommender_controllers_Recommend extends org_glizy_rest_core_CommandRest
{
    function execute($volume_id, $content_id)
    {
        $result = array();

        if (!$this->user->isLogged()) {
            $result = desiderataLibrary_modules_models_vo_ErrorVO::Unauthorized();
        } else {
            $result = $this->recommend($volume_id, $content_id, $this->user->id);
        }

        return $result;
    }

    public function recommend($volumeId, $contentId, $userId)
    {
        $url = __Config::get('desiderataLibrary.recommender.host').'/recommender/recommendation';
        $params = array(
            'publication_id' => $volumeId,
            'content_id' => $contentId,
            'user_id' => $userId,
            'content_recommendations' => 10
        );

        $request = org_glizy_ObjectFactory::createObject('org.glizy.rest.core.RestRequest', $url, 'POST', json_encode($params), 'application/json');
        $request->execute();
        $response = json_decode($request->getResponseBody());

        $result = array();

        desiderataLibrary_models_vo_ResultVO::init();

        foreach ($response->content_recommendations as $recommendation) {
            $resultVO = __ObjectFactory::createObject('desiderataLibrary.models.vo.ResultVO');
            $resultVO->createFromVolumeIdContentId($this->user->id, $recommendation->publication_id, $recommendation->content_id);
            $resultVO->score = $recommendation->score;
            $result[] = $resultVO;
        }

        return $result;
    }
}