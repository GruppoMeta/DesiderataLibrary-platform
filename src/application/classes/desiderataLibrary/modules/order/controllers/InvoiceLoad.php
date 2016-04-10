<?php
class desiderataLibrary_modules_order_controllers_InvoiceLoad extends org_glizy_rest_core_CommandRest
{
    function execute()
    {
        if (!$this->user->isLogged()) {
            $result = desiderataLibrary_modules_models_vo_ErrorVO::Unauthorized();
        } else {
            $body = json_decode(__Request::getBody());

            $ar = __ObjectFactory::createModel('org.glizy.models.User');
            $ar->load($this->user->id);

            $result = array(
                'firstName' => $ar->user_firstName,
                'lastName' => $ar->user_lastName,
                'address' => $ar->user_address,
                'email' => $ar->user_email,
                'fiscalCode' => $ar->user_fiscalCode,
                'city' => $ar->user_city,
                'province' => $ar->user_province,
                'cap' => $ar->user_cap,
                'country' => $ar->user_country
            );
        }


        return $result;
    }
}