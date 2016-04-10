<?php

/**
 * Created by PhpStorm.
 * User: jacopo
 * Date: 01/10/15
 * Time: 14:33
 */
class desiderataLibrary_modules_importEPUB_importer_chain_customChain_FixImageTag extends desiderataLibrary_modules_importEPUB_importer_chain_BaseCommand {
    public function execute(desiderataLibrary_modules_importEPUB_importer_ChainParams $params, desiderataLibrary_modules_importEPUB_importer_ChainResult $result, &$tocEl) {
        if ($params->domContext && $params->domContext->nodeName == 'body') {
            $svgTags = $params->xpath->query("//svg",$params->domContext);
            $toRemove = array();
            if ($svgTags->length > 0) {
                foreach ($svgTags as $svg) {
                    $toRemove[] = $svg;
                    $images = $params->xpath->query("./image", $svg);
                    if ($images->length > 0) {
                        foreach ($images as $image) {
                            $img = $params->dom->createElement('img');
                            $img->setAttribute("width", $image->getAttribute("width"));
                            $img->setAttribute("height", $image->getAttribute("height"));
                            $img->setAttribute("src", $image->getAttribute("xlink:href"));
                            $toRemove[] = $image;
                            $svg->parentNode->insertBefore($img, $svg);
                        }
                    }
                }
                foreach ($toRemove as $element) {
                    $element->parentNode->removeChild($element);
                }
            }
        }
    }
}