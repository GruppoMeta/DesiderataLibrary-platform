<?php
class desiderataLibrary_modules_ontologybuilder_views_components_Graph extends org_glizy_components_Component
{
    private $language;
    private $localeService;

    function init()
    {
        // define the custom attributes
        $this->defineAttribute('generateLinks', false, false, COMPONENT_TYPE_BOOLEAN);

        // call the superclass for validate the attributes
        parent::init();


        $this->language = $this->_application->isAdmin() ? $this->_application->getEditingLanguage() : $this->_application->getLanguage();
        $this->localeService = $this->_application->retrieveProxy('desiderataLibrary.modules.ontologybuilder.service.LocaleService');
    }

    protected function escape($s, $trim=false)
    {
        return htmlentities($this->localeService->getTranslation($this->language, $s));
    }

    public function getGraph($entityTypeId, &$visited, &$edges)
    {

        if ($visited[$entityTypeId]) {
            return '';
        } else {
            $entityTypeService = $this->_application->retrieveProxy('desiderataLibrary.modules.ontologybuilder.service.EntityTypeService');
            $entityProperties = $entityTypeService->getEntityTypeProperties($entityTypeId);

            $graph = '';
            $color = __Config::get('ontologyBuilder.graph.shapeColor');
            $entityTypeName = $entityTypeService->getEntityTypeName($entityTypeId);

            if ($this->getAttribute('generateLinks')) {
                $entityResolver = org_glizy_objectFactory::createObject('desiderataLibrary.modules.ontologybuilder.EntityResolver');
                $ar = $entityResolver->getMenuVisibleEntity($entityTypeId);

                if ($ar) {
                    $url = 'URL="'.org_glizy_helpers_Link::makeUrl('link', array('pageId' => $ar->id, 'title' => $ar->title)).'"';
                }
            }

            // se è il nodo da cui inizia la ricerca ricorsiva
            if (count($visited) == 0) {
                $s = $url ? $url.', ' : '';
                $graph .= '"'.$this->escape($entityTypeName).'" ['.$s.'style="rounded,filled", height=0.4, color="'.$color.'", fillcolor="'.$color.'", fontcolor=white, fontsize=13];'.PHP_EOL;
            } else if ($url) {
                $graph .= '"'.$this->escape($entityTypeName).'" ['.$url.'];'.PHP_EOL;
            }

            $visited[$entityTypeId] = true;

            foreach ((array)$entityProperties as $entityProperty) {
                if ($entityProperty['entity_properties_target_FK_entity_id']) {
                    $toEntityTypeId = $entityProperty['entity_properties_target_FK_entity_id'];
                    $toEntityTypeName = $entityTypeService->getEntityTypeName($toEntityTypeId);
                    $label = __Tp('rel:'.$entityProperty['entity_properties_type']);
                    if (!$edges[$entityTypeName][$toEntityTypeName]) {
                        $edges[$entityTypeName][$toEntityTypeName] = true;
                        $graph .= '"'.$this->escape($entityTypeName).'" -> "'.$this->escape($toEntityTypeName).'" [label="'.$this->escape($label).'"];'.PHP_EOL;
                    }
                    $graph .= $this->getGraph($toEntityTypeId, $visited, $edges);
                }
            }

            $referenceRelations = $entityTypeService->getEntityTypeReferenceRelations($entityTypeId);

            foreach ((array)$referenceRelations as $referenceRelation) {
                if ($referenceRelation['entity_properties_target_FK_entity_id']) {
                    $toEntityTypeId = $referenceRelation['entity_properties_FK_entity_id'];
                    $toEntityTypeName = $entityTypeService->getEntityTypeName($toEntityTypeId);
                    $label = __Tp('rel:'.$referenceRelation['entity_properties_type']);
                    if (!$edges[$toEntityTypeName][$entityTypeName]) {
                        $edges[$toEntityTypeName][$entityTypeName] = true;
                        $graph .= '"'.$this->escape($toEntityTypeName).'" -> "'.$this->escape($entityTypeName).'" [label="'.$this->escape($label).'"];'.PHP_EOL;
                    }
                    $graph .= $this->getGraph($toEntityTypeId, $visited, $edges);
                }
            }

            $visited[$entityTypeId] = true;

            return $graph;
        }
    }

    public function render_html()
    {
        $graphCode = '';

        if (__Request::get('entityTypeId')) {
            $visited = array();
            $edges = array();
            $graphCode = $this->getGraph(__Request::get('entityTypeId'), $visited, $edges);
        }

        if (!$this->getAttribute('generateLinks')) {
            $html  = '<form id="myForm" method="post" class="form-horizontal row-fluid" >';
            $html .= '<label for="entityTypeId" class="control-label required">'.__T('Entity').'</label>';
            $html .= '<select id="entityTypeId" name="entityTypeId">';

            $it = org_glizy_objectFactory::createModelIterator('desiderataLibrary.modules.ontologybuilder.models.Entity', 'all');

            foreach ($it as $ar) {
                $selected = __Request::get('entityTypeId') == $ar->getId() ? 'selected="selected"' : '';
                $html .= '<option value="'.$ar->getId().'" '.$selected.'>'.$this->localeService->getTranslation($this->language, $ar->entity_name).'</option>';
            }

            $html .= '</select>';
            $html .= '<input class="submit btn btn-primary" type="submit" value="'.__T('Draw').'">';
            $html .= '</form>';
        }

        $html .= <<<EOD
<script type="text/vnd.graphviz" id="graph_script">
digraph "" {
    node [shape=rectangle, style=rounded, height=0.1, color="$color", fontname=Helvetica, fontsize=10, fontcolor="#34383a"];
    edge [arrowsize=0.75, color="#cbcfce", fontname=Helvetica, fontsize=8, fontcolor="#34383a"];
    $graphCode
}
</script>
<div id="graph_div">
</div>
<script>
    function src(id) {
        return document.getElementById(id).innerHTML;
    }

    var graphHtml = Viz(src("graph_script"), "svg");
    document.getElementById("graph_div").innerHTML = graphHtml.replace('<svg ', '<svg style="width:100%"');
</script>
EOD;
        $this->addOutputCode( org_glizy_helpers_JS::linkJSfile( __Paths::get('STATIC_DIR').'/viz.js-master/viz.js' ) );
        $this->addOutputCode( $html );
    }
}
