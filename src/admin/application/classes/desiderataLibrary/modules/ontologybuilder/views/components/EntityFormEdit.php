<?php
class desiderataLibrary_modules_ontologybuilder_views_components_EntityFormEdit extends gruppometa_easybook_views_components_FormEdit
{
    function __construct(&$application, &$parent, $tagName='', $id='', $originalId='')
    {
        parent::__construct($application, $parent, $tagName, $id, $originalId);

        if (!__Request::get('entityTypeId')) {
            return;
        }

        $entityTypeId = __Request::get('entityTypeId');

        $entityModel = org_glizy_objectFactory::createModel('desiderataLibrary.modules.ontologybuilder.models.Entity');
        $entityModel->load($entityTypeId);

        $c = &org_glizy_ObjectFactory::createComponent('org.glizy.components.Input', $this->_application, $this, 'glz:Input', '__type', '__type');
        $c->setAttribute('label', 'Tipo');
        $c->setAttribute('value', __Tp($entityModel->entity_name));
        $c->setAttribute('disabled', true);
        $this->addChild($c);

        $c = &org_glizy_ObjectFactory::createComponent('org.glizy.components.Hidden', $this->_application, $this, 'glz:Input', $entityTypeId, $entityTypeId);
        $c->setAttribute('name', 'entityTypeId');
        $c->setAttribute('value', $entityTypeId);
        $this->addChild($c);

        $entityId = __Request::get('entityId');
        $c = &org_glizy_ObjectFactory::createComponent('org.glizy.components.Hidden', $this->_application, $this, 'glz:Input', $entityId, $entityId);
        $c->setAttribute('name', 'entityId');
        $c->setAttribute('value', $entityId);
        $this->addChild($c);

        $id = '__isVisible';
        $c = &org_glizy_ObjectFactory::createComponent('org.glizy.components.Checkbox', $this->_application, $this, 'glz:Checkbox', $id, $id);
        $c->setAttribute('label', __Tp('Visible'));
        $c->setAttribute('data', 'type=checkbox');
        $this->addChild($c);

        $id = 'title';
        $c = &org_glizy_ObjectFactory::createComponent('org.glizy.components.Input', $this->_application, $this, 'glz:Input', $id, $id);
        $c->setAttribute('label', __Tp('Title'));
        $c->setAttribute('required', true);
        $c->setAttribute('cssClass', __Config::get('glizy.formElement.admCssClass'));
        $c->setAttribute('value', '');
        $this->addChild($c);

        if (__Config::get('ontologybuilder.content.subtitle.show')) {
            $id = 'subtitle';
            $c = &org_glizy_ObjectFactory::createComponent('org.glizy.components.Input', $this->_application, $this, 'glz:Input', $id, $id);
            $c->setAttribute('label', __Tp('Subtitle'));
            $c->setAttribute('cssClass', __Config::get('glizy.formElement.admCssClass'));
            $c->setAttribute('value', '');
            $this->addChild($c);
        }

        if (__Config::get('ontologybuilder.content.url.show')) {
            $id = 'url';
            $c = &org_glizy_ObjectFactory::createComponent('org.glizy.components.Input', $this->_application, $this, 'glz:Input', $id, $id);
            $c->setAttribute('label', __Tp('URL'));
            $c->setAttribute('cssClass', __Config::get('glizy.formElement.admCssClass'));
            $c->setAttribute('value', '');
            $this->addChild($c);
        }

        if (__Config::get('ontologybuilder.content.profile.show')) {
            $id = 'profile';
            $c = &org_glizy_ObjectFactory::createComponent('org.glizy.components.Input', $this->_application, $this, 'glz:Input', $id, $id);
            $c->setAttribute('label', __Tp('Profile'));
            $c->setAttribute('data', 'type=selectfrom;multiple=true;model=org.glizycms.groupManager.models.UserGroup;field=usergroup_name');
            $c->setAttribute('value', '');
            $this->addChild($c);
        }

        $entityTypeService = $application->retrieveProxy('desiderataLibrary.modules.ontologybuilder.service.EntityTypeService');

        $it = org_glizy_objectFactory::createModelIterator('desiderataLibrary.modules.ontologybuilder.models.EntityProperties');
        $it->load('entityPropertiesFromId', array('entityId' => $entityTypeId));

        foreach($it as $ar) {
            $attribute = $entityTypeService->getAttributeIdByAr($ar);

            switch ($ar->entity_properties_type) {
                case 'attribute.text':
                case 'attribute.externalimage':
                    $c = &org_glizy_ObjectFactory::createComponent('org.glizy.components.Input', $this->_application, $this, 'glz:Input', $attribute, $attribute);
                    $c->setAttribute('label', __Tp($ar->entity_properties_label_key));
                    $c->setAttribute('required', $ar->entity_properties_required == 1);
                    $c->setAttribute('type', 'text');
                    $c->setAttribute('cssClass', __Config::get('glizy.formElement.admCssClass'));
                    $c->setAttribute('value', '');
                    $this->addChild($c);
                    break;

                case 'attribute.int':
                    $c = &org_glizy_ObjectFactory::createComponent('org.glizy.components.Input', $this->_application, $this, 'glz:Input', $attribute, $attribute);
                    $c->setAttribute('label', __Tp($ar->entity_properties_label_key));
                    $c->setAttribute('required', $ar->entity_properties_required == 1);
                    $c->setAttribute('type', 'number');
                    $c->setAttribute('cssClass', __Config::get('glizy.formElement.admCssClass'));
                    $c->setAttribute('value', '');
                    $this->addChild($c);
                    break;

                case 'attribute.longtext':
                    $c = &org_glizy_ObjectFactory::createComponent('org.glizy.components.Input', $this->_application, $this, 'glz:Input', $attribute, $attribute);
                    $c->setAttribute('label', __Tp($ar->entity_properties_label_key));
                    $c->setAttribute('required', $ar->entity_properties_required == 1);
                    $c->setAttribute('type', 'multiline');
                    $c->setAttribute('rows', '10');
                    $c->setAttribute('cols', '90');
                    $c->setAttribute('cssClass', __Config::get('glizy.formElement.admCssClass'));
                    $c->setAttribute('value', '');
                    $this->addChild($c);
                    break;

               case 'attribute.descriptivetext':
                    $c = &org_glizy_ObjectFactory::createComponent('org.glizy.components.Input', $this->_application, $this, 'glz:Input', $attribute, $attribute);
                    $c->setAttribute('label', __Tp($ar->entity_properties_label_key));
                    $c->setAttribute('required', $ar->entity_properties_required == 1);
                    $c->setAttribute('type', 'multiline');
                    $c->setAttribute('rows', '10');
                    $c->setAttribute('cols', '90');
                    $c->setAttribute('htmlEditor', true);
                    $c->setAttribute('data', 'type=tinymce');
                    $c->setAttribute('cssClass', __Config::get('glizy.formElement.admCssClass'));
                    $c->setAttribute('value', '');
                    $this->addChild($c);
                    break;

                case 'attribute.date':
                    $c = &org_glizy_ObjectFactory::createComponent('org.glizy.components.Input', $this->_application, $this, 'glz:Input', $attribute, $attribute);
                    $c->setAttribute('label', __Tp($ar->entity_properties_label_key));
                    $c->setAttribute('required', $ar->entity_properties_required == 1);
                    $c->setAttribute('readOnly', true);
                    $c->setAttribute('type', 'text');
                    $c->setAttribute('data', 'type=date;date-format=yyyy-mm-dd');
                    $c->setAttribute('cssClass', __Config::get('glizy.formElement.admCssClass'));
                    $c->setAttribute('value', '');
                    $this->addChild($c);
                    break;

                case 'attribute.externallink':
                    $c = &org_glizy_ObjectFactory::createComponent('org.glizy.components.Input', $this->_application, $this, 'glz:Input', $attribute, $attribute);
                    $c->setAttribute('label', __Tp($ar->entity_properties_label_key));
                    $c->setAttribute('required', $ar->entity_properties_required == 1);
                    $c->setAttribute('type', 'url');
                    $c->setAttribute('cssClass', __Config::get('glizy.formElement.admCssClass'));
                    $c->setAttribute('value', '');
                    $this->addChild($c);
                    break;

                case 'attribute.internallink':
                    $c = &org_glizy_ObjectFactory::createComponent('org.glizycms.views.components.PagePicker', $this->_application, $this, 'cms:PagePicker', $attribute, $attribute);
                    $c->setAttribute('label', __Tp($ar->entity_properties_label_key));
                    $c->setAttribute('required', $ar->entity_properties_required == 1);
                    $c->setAttribute('cssClass', __Config::get('glizy.formElement.admCssClass'));
                    $c->setAttribute('value', '');
                    $this->addChild($c);
                    break;

                case 'attribute.openlist':
                    $c = &org_glizy_ObjectFactory::createComponent('org.glizy.components.Input', $this->_application, $this, 'glz:Input', $attribute, $attribute);
                    $c->setAttribute('label', __Tp($ar->entity_properties_label_key));
                    $c->setAttribute('required', $ar->entity_properties_required == 1);
                    $c->setAttribute('cssClass', __Config::get('glizy.formElement.admCssClass'));
                    $proxyParams = json_encode( array('entityTypeId' => $entityTypeId) );
                    $proxyParams = str_replace('"', '##', $proxyParams);
                    $c->setAttribute('data', 'type=selectfrom;multiple=false;add_new_values=true;proxy=desiderataLibrary.modules.ontologybuilder.models.proxy.EntityProxy;proxy_params='.$proxyParams);
                    $c->setAttribute('value', '');
                    $this->addChild($c);
                    break;

                case 'attribute.image':
                    $c = &org_glizy_ObjectFactory::createComponent('org.glizy.components.Input', $this->_application, $this, 'glz:Input', $attribute, $attribute);
                    $c->setAttribute('label', __Tp($ar->entity_properties_label_key));
                    $c->setAttribute('required', $ar->entity_properties_required == 1);
                    $c->setAttribute('data', 'type=mediapicker;mediatype=IMAGE;preview=true');
                    $c->setAttribute('size', '90');
                    $c->setAttribute('value', '');
                    $this->addChild($c);
                    break;

                case 'attribute.media':
                    $c = &org_glizy_ObjectFactory::createComponent('org.glizy.components.Input', $this->_application, $this, 'glz:Input', $attribute, $attribute);
                    $c->setAttribute('label', __Tp($ar->entity_properties_label_key));
                    $c->setAttribute('required', $ar->entity_properties_required == 1);
                    $c->setAttribute('data', 'type=mediapicker;mediatype=ALL;preview=false');
                    $c->setAttribute('size', '90');
                    $c->setAttribute('value', '');
                    $this->addChild($c);
                    break;

                case 'attribute.imagelist':
                    $c = &org_glizy_ObjectFactory::createComponent('org.glizy.components.Fieldset', $this->_application, $this, 'glz:Fieldset', $attribute, $attribute);
                    $c->setAttribute('label', __Tp($ar->entity_properties_label_key));
                    $c->setAttribute('required', $ar->entity_properties_required == 1);
                    $c->setAttribute('data', 'type=repeat;repeatMin=0;collapsable=false');

                    $subchild = &org_glizy_ObjectFactory::createComponent('org.glizy.components.Input', $this->_application, $this, 'glz:Input', 'attaches', 'attaches');
                    $subchild->setAttribute('label', 'Image');
                    $subchild->setAttribute('size', '90');
                    $subchild->setAttribute('data', 'type=mediapicker;mediatype=IMAGE;preview=true');

                    $c->addChild($subchild);
                    $this->addChild($c);
                    break;

                case 'attribute.medialist':
                    $c = &org_glizy_ObjectFactory::createComponent('org.glizy.components.Fieldset', $this->_application, $this, 'glz:Fieldset', $attribute, $attribute);
                    $c->setAttribute('label', __Tp($ar->entity_properties_label_key));
                    $c->setAttribute('required', $ar->entity_properties_required == 1);
                    $c->setAttribute('data', 'type=repeat;repeatMin=0;collapsable=false');

                    $subchild = &org_glizy_ObjectFactory::createComponent('org.glizy.components.Input', $this->_application, $this, 'glz:Input', 'attaches', 'attaches');
                    $subchild->setAttribute('label', 'Media');
                    $subchild->setAttribute('size', '90');
                    $subchild->setAttribute('data', 'type=mediapicker;mediatype=ALL;preview=false');

                    $c->addChild($subchild);
                    $this->addChild($c);
                    break;

                case 'attribute.module':
                    $c = &org_glizy_ObjectFactory::createComponent('org.glizy.components.Input', $this->_application, $this, 'glz:Input', $attribute, $attribute);
                    $c->setAttribute('label', __Tp($ar->entity_properties_label_key));
                    $c->setAttribute('required', $ar->entity_properties_required == 1);
                    $c->setAttribute('cssClass', __Config::get('glizy.formElement.admCssClass'));
                    $proxyParams = json_encode( array('moduleId' =>  $ar->entity_properties_params) );
                    $proxyParams = str_replace('"', '##', $proxyParams);
                    $c->setAttribute('data', 'type=selectfrom;multiple=true;proxy=desiderataLibrary.modules.ontologybuilder.models.proxy.ModelProxy;return_object=true;proxy_params='.$proxyParams);
                    $c->setAttribute('value', '');
                    $this->addChild($c);
                    break;

                default:
                    // se l'attributo è una relazione
                    if (!is_null($ar->entity_properties_target_FK_entity_id)) {
                        $c = &org_glizy_ObjectFactory::createComponent('org.glizy.components.Input', $this->_application, $this, 'glz:Input', $attribute, $attribute);
                        $c->setAttribute('label', __Tp($ar->entity_properties_label_key));
                        $c->setAttribute('required', $ar->entity_properties_required == 1);
                        $relation = $entityTypeService->getRelation($ar->entity_properties_type);
                        $c->setAttribute('data', 'type=entityselect;entity_type_id='.$ar->entity_properties_target_FK_entity_id.';cardinality='.$relation['cardinality']);
                        $c->setAttribute('cssClass', __Config::get('glizy.formElement.admCssClass'));
                        $c->setAttribute('value', '');
                        $this->addChild($c);
                        break;
                    }
            }
        }
    }

    public function render_html_onEnd($value='')
    {
        parent::render_html_onEnd($value);
        $language = $this->_application->getLanguage();
        $language = $language.'-'.strtoupper($language);

        $this->addOutputCode( org_glizy_helpers_JS::linkJSfile( __Paths::get('APPLICATION').'classes/desiderataLibrary/modules/ontologybuilder/views/js/locale/'.$language.'.js' ) );
        $this->addOutputCode( org_glizy_helpers_JS::linkJSfile( __Paths::get('APPLICATION').'classes/desiderataLibrary/modules/ontologybuilder/views/js/EntityFormEditEntitySelect.js' ) );
    }

    // function loadContent($name, $bindToField=NULL)
    // {
    //     if (strpos($name, '-')!==false) {
    //         list($parentId) = explode('-', $name);
    //         $value = parent::loadContent($parentId);
    //     }
    //     parent::loadContent($name, $bindToField);
    // }
}
