<?php

class desiderataLibrary_modules_importEPUB_importer_TocItem {

	public $title;
	public $number;
	public $file;
	public $absFile;
	public $hash;
	public $childs = array();
	public $unit = false;
	public $dataPages;
	public $playOrder;
	public $id;
	public $startNode = null;
	public $endPoint = null;

	private $xpath;

	public function setToc($epubFolder, $title, $file, $hash) {
		$this->setTitle($title);
		$this->file = $file;
		$this->hash = $hash;
		$this->setAbsFile($epubFolder . '/OEBPS/' . $this->file);
	}

	public function setTitle($title) {
		preg_match_all('/^(\d+)?(.*)/', $title, $matches);
		$this->title = ltrim($matches[2][0], '■ ');
		$this->number = trim($matches[1][0]);
	}

	public function setFile($src) {
		list($file, $hash) = @explode('#', $src);
		$this->file = $file;
		$this->hash = $hash;
	}

	public function setChilds($childs) {
		$this->childs = $childs;
	}

	public function haveChild() {
		return count($this->childs) > 0 ? true : false;
	}

	public function setAbsFile($src) {
		$this->absFile = $src;
	}

	public function getFolder() {
		return pathinfo($this->absFile, PATHINFO_DIRNAME);
	}

	public function getFile() {
		return $this->file;
	}

    public function getXpath() {
        return $this->xpath;
    }

	public function setFromNavPoint($epubFolder, \DomNode $node) {
		$this->setTitle($node->getElementsByTagName('navLabel')->item(0)->getElementsByTagName('text')->item(0)->nodeValue);
		$content = $node->getElementsByTagName('content')->item(0);
		$src = $content->getAttribute('src');

		$this->setFile($src);
		//$this->setAbsFile($epubFolder . '/OEBPS/' . $this->file);
        $this->setAbsFile($epubFolder . '/' . $this->file);

		$this->id = $node->getAttribute('id');
		$this->playOrder = $node->getAttribute('playOrder');
		$this->dataPages = explode(',', $node->getAttribute('data-pages'));
	}

 	public function setFalseNavPoint($epubFolder, $title, $path, $id, $playOrder) {
		$this->setTitle($title);
		$this->setFile($path);
		//$this->setAbsFile($epubFolder . '/OEBPS/' . $this->file);
        $this->setAbsFile($epubFolder . '/' . $this->file);
		$this->id = $id;
		$this->playOrder = $playOrder;
		$this->dataPages = '';
	}

	public function setStartPoint($xpath) {
		$this->xpath = $xpath;
		$node = $this->getNodeFromId($xpath, $this->hash);
		if (!$node) {
            //echo "Impossibile trovare un body padre di questo id: " . $this->hash . " nel file" . $this->file . "<br />";
		}
		$this->startNode = $node;
	}

	public function getNodeFromId($xpath, $id) {
        $node = null;
		if (!$id) {
			$node = $xpath->query("//body")->item(0);
		} else {
			$node = $xpath->query("//*[@id='$id']")->item(0);
			if ($node && $node->nodeName != 'body') {
				$node = $xpath->query("parent::body | ancestor::body", $node)->item(0);
			}
		}
		return $node;
	}

	public function setEndPoint($xpath, $endToc, $map) {
        return;
		if (!$endToc) {
			return;
		}
		//echo "<br>".$endToc->absFile ." -- ". $this->absFile."<br>";
		if ($endToc->absFile != $this->absFile) {
            return;
        }
        $parent = $this->getNodeFromId($xpath, $endToc->hash);
		$this->endPoint = $parent->getAttribute('id');
		if (!$this->endPoint) {
            //echo 'La sezione che contiene '.$endToc->hash.' non ha id<br />';
			$this->endPoint = "endPointFakeId".$map;
            //echo "FakeId Univoco <br />";
            //echo $this->endPoint."<br />";
            $parent->setAttribute('id', "endPointFakeId".$map);
		}

		//echo "Parsing da bloccare su ID : $this->endPoint <br><br>";
	}

	public function refreshStartNode() {
		$this->setStartPoint($this->xpath);
	}

	public function toString() {
		$obj = new stdClass();
		$obj->title = $this->title;
		$obj->absFile = $this->absFile;
		$obj->startAtId = $this->startNode->getAttribute('id');
		$obj->endAtId = $this->endPoint;
		$obj->child = array();
		$i = 0;
		foreach ($this->childs as $c) {
			$obj->child[$i] = $c->toString();
			$i++;
		}

		return $obj;
	}

	public function printChildren() {
		$i = 0;
        $s = "";
		$s .= "Composizione dei figli dell'elemento $this->title:\n";
		$s .= 'Elementi: '.count($this->childs)."\n";
		foreach ($this->childs as $children) {
			$i++;
            $s .= "=== ELEMENTO PADRE ====\n";
            $s .= 'ID:'.$children->id."\n";
            $s .= 'Title:'.$children->title."\n";
            $s .= 'Number:'.$children->number."\n";
            $s .= 'PlayOrder:'.$children->playOrder."\n";
            $s .= 'File:'.$children->file."\n";
            $s .= 'AbsFile:'.$children->absFile."\n";
            $s .= 'Hash:'.$children->hash."\n";
			if ($children->startNode && $children->startNode->hasAttribute('id')) {
                $s .= 'StartNode:'.$children->startNode->getAttribute('id')."\n";
			}
			if ($children->endPoint) {
                $s .= 'EndPoint:'.$children->endPoint."\n";
			}
			if ($children->haveChild()) {
				$children->printChildren();
			}
		}
        return $s;
	}

}
