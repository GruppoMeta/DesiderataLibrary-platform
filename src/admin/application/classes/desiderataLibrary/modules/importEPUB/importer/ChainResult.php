<?php

class desiderataLibrary_modules_importEPUB_importer_ChainResult {

	const STATE_RUNNING = true;
	const STATE_BREAK = false;
	const STATE_CHILD = 'child';
	const STATE_STOP_CHILD = "stop";

	const ROOT_NODE = -1;
	public $status;
	public $results = array();
	public $childDom = null;
	private $parents = array();

	private $resultItemIds = 0;
	function __construct() {
		$this->status = self::STATE_RUNNING;

	}

	public function setResultAndStop($pageResult) {
		$this->addResult($pageResult);
		$this->status = self::STATE_STOP_CHILD;
	}
	public function setResultAndBreak($pageResult) {
		$this->addResult($pageResult);
		$this->status = self::STATE_BREAK;
	}

	public function setResultAndChild($pageResult, \DomNode $dom) {
		$this->addResult($pageResult);
		$this->status = self::STATE_CHILD;

		$this->childDom = $dom;
	}

	public function getChildDom() {
		$this->status = self::STATE_RUNNING;
		$dom = $this->childDom;
		$this->childDom = null;
		return $dom;
	}

	public function getResult() {
		return $this->results;
	}

	public function getResultItem($item) {
		return count($this->results) > $item ? $this->results[$item] : null;
	}

	public function increaseParent() {
		$this->parents[] = $this->resultItemIds - 1;
	}

	public function decreaseParent() {
		array_pop($this->parents);
	}


	private function addResult($result) {
		if ($result) {

			$result->id = $this->resultItemIds;
			if (count($this->parents) === 0) {
				$result->parent = self::ROOT_NODE;
			} else {
				$result->parent = $this->parents[count($this->parents) - 1];
			}

			//$result->parent = $this->resultItemIds > 0 ? $this->parents[count($this->parents)-1] :  self::ROOT_NODE;
			$result->depth = count($this->parents);
			$this->resultItemIds++;
			$this->results[] = $result;

		}
	}

	public function addChildrenToParent($childs, $idParent, $depthParent) {
		//echo "AddChild:<br>";

		$parentPos = array();
		foreach ($childs as $c) {

			$c->id = count($this->results);
			if ((!$c->parent && $c->parent !== 0) || $c->parent === self::ROOT_NODE) {
				// echo "$c->title toParent ".$c->parent."<br>";
				$c->parent = $idParent;
				$c->depth = $depthParent + 1;
			} else {
				//  echo "$c->title to ".$parentPos[$c->parent]."<br>";
				$c->parent = $parentPos[$c->parent];
				$c->depth += $depthParent + 1;
			}

			$this->results[] = $c;
			$parentPos[] = count($this->results) - 1;
			$i++;
		}
		//  echo "<br><br>";

	}

	/**
	 *
	 * Aggiunge un figlio alla lista risultati
	 */
	public function addChildToParent(&$child, $idParent, $depthParent) {
		$child->id = count($this->results);
		$child->parent = $idParent;
		$child->depth = $depthParent + 1;
		$this->results[] = $child;
	}

        // Questo metodo inserisce un elemento nell'array dei risultati
        public function addElementToResults(&$element, $id, $idParent, $depthParent) {
            // $element è l'elemento che deve essere inserito, gli altri parametri sono i riferimenti per l'inserimento
            $element->id = $id;
            $element->parent = $idParent;
            $element->depth = $depthParent;

            $upToId = array_slice($this->results, 0, $id, true);
            $upToEnd = array_slice($this->results, $id, count($this->results) - $id, true);
            $insertion = array($id => $element);
            $this->results = array_merge($upToId, $insertion, $upToEnd);

            echo $this->results[$id]->pageType.' '.$this->results[$id]->title.'<br />';
            echo $this->results[$id+1]->pageType.' '.$this->results[$id+1]->title.'<br />';
            // verificare se il contenuto da wrappare ha dei figli, generalmente sono i box aside
            $this->results[$id + 1]->id++;
            $this->results[$id + 1]->parent++;
            $this->results[$id + 1]->depth++;

            for ($i = $id + 2; $i < count($this->results); $i++) {
                echo $i.'<br />';
                echo 'ID elemento '.$this->results[$i]->id.'<br />';
                $this->results[$i]->id++;
                echo 'ID aumentato '.$this->results[$i]->id.'<br />';
                if ($this->results[$i]->parent >= $id) {
                    echo 'Parent di '.$this->results[$i]->id.' originale: '.$this->results[$i]->parent.'<br />';
                    $this->results[$i]->parent++;
                    echo 'Parent aumentato: '.$this->results[$i]->parent.'<br />';
                }
                echo 'Profondità del risultato corrente '.$this->results[$i]->depth.'<br/>';
                echo 'Profondità di suo padre '.$this->results[$this->results[$i]->parent]->depth.'<br />';
                if ($this->results[$i]->depth == $this->results[$this->results[$i]->parent]->depth) {
                    $this->results[$i]->depth++;
                }
            }

	}

	public function changeResultItem($index, $newResult) {
		$this->results[$index] = $newResult;
	}

	public function setItemNotSaveble($index) {

		//Riordino i valori parent
		$this->results[$index]->setNotSavable();

	}

	public function wrapResultItem($indexToWrap, $wrapper) {
                $id = $this->results[$indexToWrap]->id;
                $this->results[$indexToWrap]->parent = $wrapper->id;
                $this->results[$indexToWrap]->depth = $wrapper->depth + 1;
                for ($i = $indexToWrap + 1; $i < count($this->results); $i++) {
                    if ($this->results[$i]->parent == $id) {
                        echo 'NODO FIGLIO DI TEXT CON TITOLO '.$this->results[$i]->title.' E DEPTH '.$this->results[$i]->depth.'<br />';
                        $this->results[$i]->depth++;
                        echo 'DEPTH AGGIORNATA: '.$this->results[$i]->depth.'<br />';
                    }
                }
	}

}