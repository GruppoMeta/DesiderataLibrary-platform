<?php
class desiderataLibrary_modules_importEPUB_importer_results_BasePage {
	public $depth;
	public $parent;
	public $title;
	public $subTitle;
	public $number;
	public $pageType;
	public $pdfPageReference;
	public $epubType;
	public $notSavable = false;
	public $inlineImages;

	public function printComponent($html = null) {
		$marginLeft = ($this->depth * 20) . "px";
		$bg = $this->notSavable ? "#ff0000" : "#ffffff";
		echo "<div style='border:1px solid #ccc; margin:10px 0; padding:5px; margin-left:$marginLeft;background-color: $bg' >";
		echo "<h3>$this->number ) $this->title --- " . $this->depth . " " . $this->pageType . " " . get_class($this) . "</h3>";
		echo "<h4>$this->subTitle</h4>";
		echo "<p><b>EpubType: </b>" . $this->epubType . "</p>";
		echo "<p><b>Riferimento pagina pdf:</b>: " . $this->pdfPageReference . "</p>";
		echo "<p><b>ID:</b> " . $this->id . "</p>";
		echo "<p><b>Parent:</b> " . $this->parent . "</p>";
		if ($html != null) {
			echo $html;
		}
		if ($this->inlineImages) {
			echo "<b>Numero immagini inline: </b>" . count($this->inlineImages);
		}
		echo "</div>";
	}

	public function setInlineImages($inlineImages) {
		$this->inlineImages = $inlineImages;
	}

	public function setNotSavable() {
		$this->notSavable = true;
	}

}