<?php
class gruppometa_easybook_views_filters_Publication implements org_glizy_components_interfaces_ISearchFilters
{
     public function getFilters($filters)
     {
        $tempFilters = array();
        if ($filters['filterTitle']) {
            $tempFilters['menudetail_title'] = $filters['filterTitle'];
        }

        if ($filters['filterType']) {
            $tempOR = array();
            $filterType = explode(',', $filters['filterType']);
            foreach($filterType as $v) {
                $tempOR[] = array('field' => 'menu_pageType', 'condition' => '=', 'value' => $v);
            }
            if (count($tempOR)) {
                $tempFilters['__OR__'] = $tempOR;
            }
        }

        $user = org_glizy_ObjectValues::get('org.glizy', 'user');

        // se l'utente non è del gruppo amministratori
        if ($user->groupId !== 1) {
            $ar = __ObjectFactory::createModel('org.glizy.models.User');
            $ar->load($user->id);
            $tempFilters[] = array(
                array('field' => 't6.document_index_int_name', 'condition' => '=', 'value' => 'publisherid')
            );
            $tempFilters[] = array(
                array('field' => 't6.document_index_int_value', 'condition' => '=', 'value' => $ar->user_FK_editor_id)
            );
        }

        return $tempFilters;
    }
}
