<?php
class desiderataLibrary_modules_importEPUB_importer_results_Text extends desiderataLibrary_modules_importEPUB_importer_results_BasePage {
	public $hideTitle;
	public $hideInIndex = false;
	public $layout;
	public $viewType;
	public $text;
	public $images;
	public $filePath;

	function __construct($title, $subTitle, $number, $hideTitle, $layout, $viewType, $text, $images = array(), $pdfPageReference = "NONE", $epubType) {
		$this->title = $title;
		$this->subTitle = $subTitle;
		$this->number = $number;
		$this->pageType = 'Text';
		$this->hideTitle = $hideTitle;
		$this->layout = $layout;
		$this->viewType = $viewType;
		$this->text = $text;
		$this->images = $images;
		$this->pdfPageReference = $pdfPageReference;
		$this->epubType = $epubType;
	}

	public function toJson() {
		$json = new StdClass;
		$json->__title = $this->title;
		$json->subtitle = $this->subTitle;
		$json->number = $this->number;
		$json->hideTitle = $this->hideTitle ? 1 : 0;
		$json->hideInIndex = $this->hideInIndex;
		$json->viewLayout = $this->layout;
		$json->viewStyle = $this->viewType;
		$json->text = $this->text;
		$json->images = $this->images;
		$json->pageType = $this->pageType;
		$json->inlineImages = $this->inlineImages;
		$json->pdfPage = $this->pdfPageReference;
		$json->epubType = $this->epubType;
		$json->questions = array();
		return $json;
	}

	public function printComponent() {
		$html = "<p>$this->text</p><b>Immagini:</b> " . (count($this->images));
		for ($i = 0; $i < count($this->images); $i++) {
			$img = $this->images[$i];
			$html .= "<p><i>";
			$html .= ($i + 1) . ")";
			$html .= $img->toString();
			$html .= "<br>";
			$html .= "</i></p>";
		}
		$html .= "<p>Layout: " . $this->layout . "</p>";
		$html .= "<p>ViewType: " . $this->viewType . "</p>";
		$html .= "<p>hideTitle: " . $this->hideTitle . "</p>";
		parent::printComponent($html);
	}
}
