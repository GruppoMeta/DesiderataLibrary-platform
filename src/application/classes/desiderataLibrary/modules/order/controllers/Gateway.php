<?php
class desiderataLibrary_modules_order_controllers_Gateway extends org_glizy_rest_core_CommandRest
{
    function execute($order, $total, $urlSuccess, $urlError)
    {
        if (!$this->user->isLogged()) {
            return desiderataLibrary_modules_models_vo_ErrorVO::Unauthorized();
        } else {

            $success = false;
            if ($order && $urlSuccess && $urlError) {
                // carica l'ordine
                $arOrder = __ObjectFactory::createModel('desiderataLibrary.modules.order.models.Order');
                if ($arOrder->find(array('order_code' => $order))) {
                    $gateway = org_glizy_ObjectFactory::createObject(__Config::get('desiderataLibrary.ecommerce.gateway'));
                    if ($gateway) {
                        if ($gateway->pay($arOrder->order_FK_user_id, $arOrder->order_id, $total)) {
                            $success = true;
                        }
                    }
                }
            }
        }

        org_glizy_helpers_Navigation::gotoUrl($success ? $urlSuccess : $urlError);
    }
}