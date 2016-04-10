<?php
class desiderataLibrary_modules_importEPUB_importer_results_Glossary
{
    public $term;
    public $definition;

    function __construct($term, $definition)
    {
        $this->term = $term;
        $this->definition = $definition;
    }

    public function printComponent($html = null){
        echo "<p>".$term." ".$this->definition."</p>";
    }

}