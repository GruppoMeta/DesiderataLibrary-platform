<?php
class gruppometa_easybook_views_components_AddPublicationButton extends org_glizy_components_Component
{
    function init()
    {
        // define the custom attributes
        $this->defineAttribute('label',    	true, 	'',			COMPONENT_TYPE_STRING);
        parent::init();
    }

    function render()
    {
        $output = '<div class="btn-group">'.
                  '<a class="btn dropdown-toggle action-link" data-toggle="dropdown" href="#">'.
                  '<i class="icon-plus"></i> '.
                  $this->getAttribute('label').
                  '</a>'.
                  '<ul class="dropdown-menu left forced-left-position">';

        $pubType = gruppometa_easybook_Easybook::getPublicationInfos();
        foreach ($pubType as $v) {
            if ($this->_user->acl('easybook', 'show.publication.'.$v->type)) {
              $url = org_glizy_helpers_Link::makeUrl($v->routeUrlNew, array(), array('type' => $v->type));
              $output .= '<li><a href="'.$url.'">'.$v->label.'</a></li>';
            }
        }

        $output .= '</ul>'.
                   '</div>';

        $this->addOutputCode($output);
    }
}