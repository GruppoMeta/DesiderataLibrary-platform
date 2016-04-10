<?php

class desiderataLibrary_modules_importEPUB_importer_chain_standardChain_StartPoint extends desiderataLibrary_modules_importEPUB_importer_chain_BaseCommand {

    public function execute(desiderataLibrary_modules_importEPUB_importer_ChainParams $params, desiderataLibrary_modules_importEPUB_importer_ChainResult $result, &$tocEl) {
        if ($params->domContext && $params->domContext->nodeName == 'body') {
            $title = $params->title;
            $number =$params->number;
            $invalidTags = array("h1", "h2");
            // controllo identità inserisci tutti gli a dentro l'array child...
            if ($params->title === $tocEl['epub-id']) {
                $hrefs = $params->xpath->query('.//*[@href]', $params->domContext);
                foreach ($hrefs as $href) {
                    //$toclEl['hrefs'][] = pathinfo($href->nodeValue, PATHINFO_BASENAME);
                    //$tocEl['resultTitles'][$href->nodeValue] = pathinfo($href->getAttribute('href'), PATHINFO_BASENAME);
                    //$tocEl['conversion'][pathinfo($href->getAttribute('href'), PATHINFO_BASENAME)] = '';
                    $tocEl['resultTitles'][$href->nodeValue] = $href->getAttribute('href');
                    $tocEl['conversion'][$href->getAttribute('href')] = '';
                }
            }
            $text = $this->getNodeInnerHtml($params->domContext, $invalidTags);
            // Il TOC va saltato.
            if ($params->title !== $tocEl['epub-id']) {
                // Rimuovi collegamenti sugli <a/>, a patto che non siano http o mailto.
                $re = "/<a.*?href=[\\'\\\"](?!http|mailto).*?[\\'\\\"].*?>(.*?)<\\/a>/";
                if (preg_match_all($re, $text, $matches)) {
                    $text = preg_replace($re, "$1", $text);
                }
            }
            $inlineImages = $this->getInlineImagesFromNodes($params->domContext, $params);
            $images = $this->getFiguresFromNodes($params->domContext, $params);
            $pageResult = org_glizy_objectFactory::createObject('desiderataLibrary.modules.importEPUB.importer.results.Text',
                $title, "", $number, false, "", "", $text, $images, "", "");
            $pageResult->setInlineImages($inlineImages);
            $pageResult->filePath = $params->filePath;
            $result->setResultAndChild($pageResult, $params->domContext);
        }
    }

    // Questa funzione può essere utilizzata per identificare i separatori. La casistica è stata impostata sulla
    // base di "Navi di bronzo" dell'editore Carlo Delfino. Probabilmente non è esaustiva.
    private function isNotEmpty($element, $params) {
        $children = $params->xpath->query("./descendant::*", $element);
        $invalidTags = array("h1", "h2", "h3", "div");
        $validChildCount = 0;
        foreach ($children as $child) {
            if (in_array($child->nodeName, $invalidTags)) {
                continue;
            }
            if ($child->textContent !== '') {
                $validChildCount++;
            }
        }

        if ($validChildCount == 0) {
            echo "VUOTO!<br />";
            return false;
        }
        return true;
    }

}
