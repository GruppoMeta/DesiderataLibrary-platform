<?php

class desiderataLibrary_modules_importEPUB_importer_chain_standardChain_EndPoint extends desiderataLibrary_modules_importEPUB_importer_chain_BaseCommand {

    public function execute(desiderataLibrary_modules_importEPUB_importer_ChainParams $params, desiderataLibrary_modules_importEPUB_importer_ChainResult $result, &$tocEl) {
        $element = $params->domContext ? $params->domContext : $params->dom->documentElement;
       // if($element->nodeName === 'section' || $element->nodeName === 'aside')
      //  echo "Start from: ".$element->getAttribute('id')." - end to: ".$params->stopAtId."<br>";


        if($element->nodeName != "body" && $element->getAttribute('id') === $params->stopAtId){
        //    echo "ENDPOINT ".$element->getAttribute('id')."<br>";
            $result->setResultAndStop(null);
        }

    }

}
