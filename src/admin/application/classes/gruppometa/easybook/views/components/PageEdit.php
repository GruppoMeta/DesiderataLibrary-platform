<?php
class gruppometa_easybook_views_components_PageEdit extends org_glizycms_contents_views_components_PageEdit
{
    public function process() {
        if ($this->getAttribute('mode')=='container') {
            $this->emptySrc = __Routing::makeUrl('linkChangeAction', array( 'action' => $this->getAttribute('initialState'))).'?multisite=1'.gruppometa_easybook_Easybook::getSiteId();
            $this->editSrc = __Routing::makeUrl('linkChangeAction', array( 'action' => $this->getAttribute('editState'))).'?multisite=1'.gruppometa_easybook_Easybook::getSiteId().'&menuId=';

        } else {
            parent::process();
        }
    }

    // QUICK-FIX andrebbe fatto nel controller gruppometa_easybook_controllers_pageEdit_Edit ma non va
    public function setPublisher($data) {
        $menuProxy = org_glizy_ObjectFactory::createObject('org.glizycms.contents.models.proxy.MenuProxy');
	    $menu = $menuProxy->getMenuFromId($this->menuId, org_glizy_ObjectValues::get('org.glizy', 'editingLanguageId'));

    	if ($menu->menu_pageType === 'Publication' || $menu->menu_pageType === 'PublicationPdf') {
		    // se non è stato settato l'editore (nuova pubblicazione) e se l'utente è del gruppo supervisori
    		if (!$data->publisher && $this->_user->groupId === 2) {
                $ar = __ObjectFactory::createModel('org.glizy.models.User');
                $ar->load($this->_user->id);

    			$arEditor = __ObjectFactory::createModel('desiderataLibrary.models.Editor');
    			$arEditor->load($ar->user_FK_editor_id);

    			$publisher = new StdClass();
    			$publisher->id = $ar->user_FK_editor_id;
    			$publisher->text = $arEditor->editor_name;
    			$data->publisher = $publisher;
    		}
    	}
    }

    public function setData($data)
    {
        $this->setPublisher($data);

        if (property_exists($data, 'layout') && !property_exists($data, 'viewLayout')) {
            $data->viewLayout = 'layout_3_1';
        }

        if (property_exists($data, 'layout') && !property_exists($data, 'viewStyle')) {
            $data->viewStyle = strpos($data->layout, 'box')!==false ? $data->layout : 'box_default';
        }

        parent::setData($data);
    }

    public function render_html_onEnd($value='')
    {
        if ($this->getAttribute('mode')!='container') {
            parent::render_html_onEnd();
            $staticPath = __Paths::get('STATIC_DIR');
            $this->addOutputCode( org_glizy_helpers_JS::linkJSfile( $staticPath.'jquery/jquery.tooltipster/jquery.tooltipster.min.js' ) );
            $this->addOutputCode( org_glizy_helpers_CSS::linkCSSfile( $staticPath.'jquery/jquery.tooltipster/css/tooltipster.css' ) );
        }
    }

    protected function getMediaPickerUrl()
    {
        return '"index.php?pageId=MediaArchive_picker&multisite=1'.gruppometa_easybook_Easybook::getSiteId().'"';
    }

    protected function getTinyMceUrls()
    {
        return array(
                        'ajaxUrl' => GLZ_HOST.'/'.$this->getAjaxUrl(),
                        'mediaPicker' => GLZ_HOST.'/'.'index.php?pageId=MediaArchive_picker&multisite=1'.gruppometa_easybook_Easybook::getSiteId(),
                        'mediaPickerTiny' => GLZ_HOST.'/'.'index.php?pageId=MediaArchive_pickerTiny&multisite=1'.gruppometa_easybook_Easybook::getSiteId(),
                        'imagePickerTiny' => GLZ_HOST.'/'.'index.php?pageId=MediaArchive_pickerTiny&mediaType=IMAGE&multisite=1'.gruppometa_easybook_Easybook::getSiteId(),
                        'imageResizer' => GLZ_HOST.'/'.'getImage.php',
                        'root' => GLZ_HOST.'/',
            );
    }

    public function getAjaxUrl() {
        $url = parent::getAjaxUrl();
        $newUrl = '';
        if (__Request::exists('menu_id')) {
            $newUrl = '&menu_id='.__Request::get('menu_id');
        }
        $newUrl .= '&multisite=1&action=';
        return str_replace('&action=', $newUrl, $url);
    }
}

