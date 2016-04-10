<?php
class gruppometa_easybook_views_renderer_CellEditDelete extends GlizyObject
{
    function renderCell( $key, $value, $row )
	{
// TODO: spostare in un path più corretto non dentro contents
        $visible = true;
        $edit = true;
        $delete = true;

// TODO: posstare questa parte di codice in un classe comune
// e gestire in modo simile quando sono attivi i ruoli e quando no
        $application = org_glizy_ObjectValues::get('org.glizy', 'application');
        $user = $application->_user;
        $pageId = $application->getPageId();
        if (__Config::get('ACL_ROLES')) {

            if (!$user->acl($pageId, 'all')) {
                $visible = $user->acl($pageId, 'visible');
                $edit =  $user->acl($pageId, 'edit');
                $delete =  $user->acl($pageId, 'delete');

                if ($visible) {
                    $ar = org_glizy_objectFactory::createModel('org.glizycms.contents.models.DocumentACL');
                    $ar->load($key);

                    if ($ar->__aclEdit) {
                        $roles = explode(',', $ar->__aclEdit);
                        $edit = $delete = $user->isInRoles($roles);
                    }
                }
            }
        } else {
            $edit =  $user->acl($pageId, 'edit');
            $delete =  $user->acl($pageId, 'delete');
        }

        if ($visible && $edit) {
            $output = __Link::makeLinkWithIcon( 'actionsMVC_nomultisite',
                                                            'icon-pencil btn-icon',
                                                            array(
                                                                'title' => __T('GLZ_RECORD_EDIT'),
                                                                'id' => $key,
                                                                'action' => 'edit'  ) );
        }

        if ($visible && $delete) {
            $output .= org_glizy_Assets::makeLinkWithIcon(   'actionsMVCDelete_nomultisite',
                                                            'icon-trash btn-icon',
                                                            array(
                                                                'title' => __T('GLZ_RECORD_DELETE'),
                                                                'id' => $key,
                                                                'model' => $row->getClassName(false),
                                                                'action' => 'delete'  ),
                                                            __T('GLZ_RECORD_MSG_DELETE') );
        }

		return $output;
	}
}

