<?php

abstract class desiderataLibrary_modules_importEPUB_importer_chain_BaseCommand implements desiderataLibrary_modules_importEPUB_importer_chain_ICommand {

    private $layoutMap;

    public function setConfig($config) {
        $this->layoutMap = $config->getLayoutMap();
    }

    protected function getNodeHtmlAsString($node) {
        $text = '';
        $node = $node instanceof \DOMNodeList ? $node->item(0) : $node;
        if ($node) {
            foreach ($node->childNodes as $n) {
                if ($node->nodeType == XML_ELEMENT_NODE && $n->tagName == 'br') {
                    $text .= ' ';
                    continue;
                }
                $text .= $n->textContent;
            }
        }
        return trim(preg_replace('/\s{2,}/u', ' ', $text));
    }

    protected function getNodeHtml($nodes) {
        $text = '';
        foreach ($nodes as $node) {
            $text .= $node->ownerDocument->saveHTML($node);
        }
        return $text;
    }

    protected function getNodeInnerHtml($node, $removeTags = null) {
        if ($removeTags == null) {
            //$removeTags = array('aside', 'section', 'header', 'h1', 'h2', 'figure');
            $removeTags = array();
        }

        $text = '';
        if ($node && $node->childNodes) {
            foreach ($node->childNodes as $n) {
                if (in_array($n->nodeName, $removeTags)) {
                    continue;
                } /*else if ($n->nodeName == 'div') {
                    $text .= $this->getNodeInnerHtml($n);
                }*/ else {
                    $text .= ltrim($node->ownerDocument->saveHTML($n));
                }
            }
        }

        return str_replace('<span class="pagebreak"></span>', '', $text);
    }

    protected function getLinkToTranslate($params, $node) {
        $aTags = $params->xpath->query(".//xhtml:a[not(ancestor::aside)]", $node);

        $toTranslate = array();

        foreach ($aTags as $a) {
            $href = $a->getAttribute("href");
            if ($href && strpos($href, 'http://') === false && strpos($href, 'mailto:') === false /*&& substr($href, 0, 1) !== "#"*/) {
                array_push($toTranslate, $href);
            }
        }

        return $toTranslate;
    }

    protected function getTextStyleTypeFromTitle($title, $default) {
        $types = array(
            'IL PROCESSO CHIAVE' => 'box1',
            'LE PAROLE DELLA GEOSTORIA' => 'box2',
            'LE PAROLE DEL CITTADINO' => 'box3',
            'CULTURA' => 'box4',
            'LEGGI LA CARTA' => 'box5',
            'FONTE ICONOGRAFICA' => 'box6',
            'FONTE SCRITTA' => 'box7',
        );
        return isset($types[$title]) ? $types[$title] : $default;
    }

    protected function getPdfPageReference($node) {
        $node = $node instanceof \DOMNodeList ? $node->item(0) : $node;
        return $node->getAttribute('data-pages');
    }

    protected function translateToText($layoutType) {
        $needTranslate = isset($this->layoutMap[$layoutType]) ? filter_var($this->layoutMap[$layoutType]->needConversionToText(), FILTER_VALIDATE_BOOLEAN) : false;
        // echo $layoutType." need translate? ".$needTranslate."<br>";
        return $needTranslate;
    }

    protected function getLayoutType($layoutType) {
        return isset($this->layoutMap[$layoutType]) ? $this->layoutMap[$layoutType]->getViewLayout() : $layoutType;
    }

    protected function getViewType($layoutType) {
        $layoutType = strtolower($layoutType);
        $viewType =  isset($this->layoutMap[$layoutType]) ? $this->layoutMap[$layoutType]->getViewType() : $layoutType;
        return $viewType;
    }

    /**
     * Controlla se il nodo in questione è solo un wrapper per altri contenuti o se contiene anche informazioni.
     * Si considera un nodo abstract se non ha testo e non ha immagini come suoi diretti figli.
     * @param type $node
     */
    protected function isNodeAbstract($node) {
        $isAbstract = false;
        if ($node) {
            $isAbstract = $this->getNodeInnerHtml($node, array('aside', 'section', 'header', 'h1', 'h2')) === '';
        }

        return $isAbstract;
    }

    protected function removeNode($node) {
        $node->parentNode->removeChild($node);
    }

    protected function moveNodeTo($node, $dest) {
        $newNode = $node->cloneNode(true);

        $dest->appendChild($newNode);

        $this->removeNode($node);

    }

    protected function copyNodeTo($node, $dest) {
        $newNode = $node->cloneNode(true);

        $dest->appendChild($newNode);

    }
    /**
     *
     * @param type $node Nodo che si vuole spostare
     * @param type $before Il node verrà inserito subito prima l'elemento $before
     * @param type $dest L'elemento di cui il nodo che spostiamo diventerà figlio
     */
    protected function moveNodeBefore($node, $before, $dest) {
        $node = $node instanceof \DOMNodeList ? $node->item(0) : $node;
        if (!$node) {
            return;
        }

        $newNode = $node->cloneNode(true);

        $dest->insertBefore($newNode, $before);
        $this->removeNode($node);
    }

    protected function getFiguresFromNodes($node, $params) {
        $images = array();
        $figures = $params->xpath->query(".//xhtml:figure", $node);

        foreach ($figures as $figure) {
            $imagePathHd = $params->xpath->evaluate("string(.//xhtml:img/@data-srcset)", $figure);

            $imagePath = $params->xpath->evaluate("string(.//xhtml:img/@src)", $figure);

            $imageCaption = $this->getNodeInnerHtml($params->xpath->query(".//xhtml:figcaption", $figure)->item(0));
            if (!$imageCaption) {
                // ho tolto: | .//xhtml:p ma in alcuni testi potrebbe riservire
                $imageCaption = $this->getNodeInnerHtml($params->xpath->query(".//*[contains(@class, 'caption')] | .//*[@class='figcaption1'] | .//*[@class='fig-caption'] | .//xhtml:p[@class='para fig-caption'] | ./xhtml:p[not(descendant::xhtml:img)]", $figure)->item(0));
            }

            $tmp = org_glizy_objectFactory::createObject('desiderataLibrary.modules.importEPUB.importer.results.Image', $imagePath, $params->folder, $imageCaption);
            $tmp->setHdSrc($imagePathHd);
            $images[] = $tmp;
        }

        return $images;
    }

    protected function getInlineImagesFromNodes($node, $params) {
        $images = array();

        if ($removeTags == null) {
            $removeTags = array('aside', 'section', 'header', 'h1', 'h2', 'figure');
        }

        if ($node && $node->childNodes) {
            foreach ($node->childNodes as $n) {
                if (in_array($n->nodeName, $removeTags)) {
                    continue;
                } else if ($n->nodeName == 'img') {
                    $src = urldecode($n->getAttribute('src'));
                    $hdSrc = $n->getAttribute('data-srcset');
                    $tmp = org_glizy_objectFactory::createObject('desiderataLibrary.modules.importEPUB.importer.results.Image', $src, $params->folder, $n->getAttribute('alt'));
                    $tmp->setHdSrc($hdSrc);
                    array_push($images, $tmp);
                } else if ($n->nodeName == 'image') {
                    $src = urldecode($n->getAttribute('xlink:href'));
                    $hdSrc = $n->getAttribute('data-srcset');
                    $tmp = org_glizy_objectFactory::createObject('desiderataLibrary.modules.importEPUB.importer.results.Image', $src, $params->folder, $n->getAttribute('alt'));
                    $tmp->setHdSrc($hdSrc);
                    array_push($images, $tmp);
                } else {
                    $images = array_merge($images, $this->getInlineImagesFromNodes($n, $params));

                }
            }
        }

        return $images;

    }

    protected function getEpubType($node) {
        $node = $node instanceof \DOMNodeList ? $node->item(0) : $node;
        return $node->getAttribute("epub:type");
    }

}
