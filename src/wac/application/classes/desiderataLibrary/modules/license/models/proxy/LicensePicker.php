<?php
class desiderataLibrary_modules_license_models_proxy_LicensePicker
{
    public function findTerm($fieldName, $model, $query, $term, $proxyParams)
    {
        $result = array();

        $languageId = 1;
        $it = org_glizy_ObjectFactory::createModelIterator('org.glizycms.core.models.Menu');
        $it->load('autocompletePagePicker', array('search' => '%'.$term.'%', 'languageId' => $languageId, 'menuId' => '', 'pageType' => ''))
            ->where("t1.menu_parentId", 0)
            ->limit(0, 1000);

        $user = org_glizy_ObjectValues::get('org.glizy', 'user');
        if ($user->groupId==desiderataLibrary_modules_userManager_enum_Group::EDITOR) {
            // legge l'editore dell'utente
            $arUser = __ObjectFactory::createModel('org.glizy.models.User');
            $arUser->load($user->id);

            $it->leftJoin('t1', 'documents_index_int_tbl', 't03', 't1.menu_id = t03.document_index_int_value AND t03.document_index_int_name = "id"')
                ->leftJoin('t1', 'documents_index_int_tbl', 't05', 't03.document_index_int_FK_document_detail_id = t05.document_index_int_FK_document_detail_id AND t05.document_index_int_name = "publisherid"')
                ->where('t05.document_index_int_value', $arUser->user_FK_editor_id);
        }

        foreach($it as $ar) {
            $result[] = array(
                'id' => $ar->menu_id,
                'text' => $ar->menudetail_title
            );
        }

        return $result;
    }
}
