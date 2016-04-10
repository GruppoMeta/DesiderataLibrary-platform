<?php
class desiderataLibrary_modules_order_controllers_Checkout extends org_glizy_rest_core_CommandRest
{
    // Questo servizio memorizza l'ordine fatto dall'utente nel DB
    function execute()
    {
        $result = null;
        if (!$this->user->isLogged()) {
            $result = desiderataLibrary_modules_models_vo_ErrorVO::Unauthorized();
        } else {
            $user = __Request::get('user');
            $cart = __Request::get('cart');
            $urlSuccess = __Request::get('urlSuccess');
            $urlError = __Request::get('urlError');

            if ($user && $cart && $urlSuccess && $urlError) {

                if ($this->saveUserInfo($user)) {
                    $order = $this->createOrder($cart);
                    if ($order) {
                        $url = __Link::makeUrl('order.pay', array(), array('order' => $order['code'],
                                                                            'total' => $order['total'],
                                                                            'urlSuccess' => $urlSuccess,
                                                                            'urlError' => $urlError));
                        return desiderataLibrary_modules_order_models_vo_ResponseVO::OK($url);
                    }
                }

                if (!$result) {
                    $result = desiderataLibrary_modules_models_vo_ErrorVO::InternalServerError();
                }

            } else {
                $result = desiderataLibrary_modules_models_vo_ErrorVO::MissingRequiredParameters();
            }
        }

        return $result;
    }

    private function saveUserInfo($user)
    {
        $ar = __ObjectFactory::createModel('org.glizy.models.User');
        $ar->load($this->user->id);
        $ar->user_firstName = $user->firstName;
        $ar->user_lastName = $user->lastName;
        $ar->user_address = $user->address;
        $ar->user_email = $user->email;
        $ar->user_fiscalCode = $user->fiscalCode;
        $ar->user_city = $user->city;
        $ar->user_province = $user->province;
        $ar->user_cap = $user->cap;
        $ar->user_country = $user->country;
        return $ar->save();
    }

    private function createOrder($cart)
    {
        $arOrder = __ObjectFactory::createModel('desiderataLibrary.modules.order.models.Order');
        $arOrder->order_date = new org_glizy_types_DateTime();
        $arOrder->order_state = 'open';
        $arOrder->order_FK_user_id = $this->user->id;
        $orderId = $arOrder->save();
        $orderCode = 'DL-'.str_pad( $orderId, 6, '0', STR_PAD_LEFT);
        $arOrder->order_code = $orderCode;
        if ($arOrder->save()) {
            $contentProxy = org_glizy_objectFactory::createObject('org.glizycms.contents.models.proxy.ContentProxy');
            $arItem = __ObjectFactory::createModel('desiderataLibrary.modules.order.models.OrderItem');
            $total = 0;
            foreach($cart as $item) {
                // legge le infomazioni della pubblicazione
                $content = $contentProxy->readContentFromMenu($item, gruppometa_easybook_EasybookFE::getLanguage());

                $price = floatval($content->price);
                $total += $price;
                $arItem->emptyRecord();
                $arItem->orderitem_FK_order_id = $orderId;
                $arItem->orderitem_price = number_format($price, 2);
                $arItem->orderitem_FK_publication_id = $item;
                $arItem->orderitem_publicationTitle = $content->__title;
                $arItem->orderitem_FK_license_id = 0;
                $arItem->save();
            }

            return array('code' => $orderCode, 'total' => number_format($total, 2));
        } else {
            return false;
        }
    }
}