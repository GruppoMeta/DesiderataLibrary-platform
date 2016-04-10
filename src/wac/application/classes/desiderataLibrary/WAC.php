<?php
class desiderataLibrary_WAC
{
    public static function getEditorId()
    {
        $user = org_glizy_ObjectValues::get('org.glizy', 'user');
        if ($user->groupId !== desiderataLibrary_modules_userManager_enum_Group::ADMIN) {
            $ar = __ObjectFactory::createModel('org.glizy.models.User');
            $ar->load($user->id);
            return $ar->user_FK_editor_id;
        }

        return null;
    }


}

