<?php
class desiderataLibrary_modules_order_gateway_AbstractGateway
{
    public function sendEmail($userId, $orderId)
    {
        $arOrder = org_glizy_ObjectFactory::createModel('desiderataLibrary.modules.order.models.Order');
        $arOrder->load($orderId);

        $arUser = org_glizy_ObjectFactory::createModel('org.glizy.models.User');
        $arUser->load($userId);

        // invia l'email di conferma
        $orders = array();
        $ordersInternal = array();
        $it = org_glizy_ObjectFactory::createModelIterator( 'desiderataLibrary.modules.order.models.OrderItem' )
            ->load('orderDetails', array(
                        'userId' => $userId,
                        'orderId' => $orderId
                        ));
        foreach($it as $ar) {
            $stringOrder = $ar->orderitem_publicationTitle.'<br />';
            $orders[] = $stringOrder;

            $stringOrder = $ar->orderitem_publicationTitle.'<br />';
            $ordersInternal[] = $stringOrder;
        }

        $emailInfo = org_glizy_helpers_Mail::getEmailInfoStructure();
        $emailInfo[ 'ORDER_NUM' ] = $arOrder->order_code;
        $emailInfo[ 'ORDER_DATE' ] = $arOrder->order_date;
        $emailInfo[ 'USER' ] = $arUser->user_firstName.' '.$arUser->user_lastName;
        $emailInfo[ 'USER_EMAIL' ] = $arUser->user_email;
        $emailInfo[ 'USER_ADDRESS' ] = $arUser->user_address;
        $emailInfo[ 'USER_CITY' ] = $arUser->user_city;
        $emailInfo[ 'USER_ZIP' ] = $arUser->user_cap;
        $emailInfo[ 'USER_STATE' ] = $arUser->user_country;
        $emailInfo[ 'USER_CF' ] = $arUser->user_fiscalCode;
        $emailInfo[ 'SITE_NAME' ] = __Config::get('APP_NAME');

        // email esterna
        $emailInfo[ 'ORDERS' ] = implode( '<br />', $orders );
        $emailInfo[ 'EMAIL' ] = $arUser->user_email;
        $emailInfo[ 'FIRST_NAME' ] = $arUser->user_firstName;
        $emailInfo[ 'LAST_NAME' ] = $arUser->user_lastName;
        org_glizy_helpers_Mail::sendEmailFromTemplate( 'ecommConfirmExternal', $emailInfo );

        $emailInfo[ 'ORDERS' ] = implode( '<br />', $ordersInternal );
        $emailInfo[ 'EMAIL' ] = __Config::get('desiderataLibrary.ecommerce.email');
        $emailInfo[ 'FIRST_NAME' ] = '';
        $emailInfo[ 'LAST_NAME' ] = '';
        org_glizy_helpers_Mail::sendEmailFromTemplate( 'ecommConfirmInternal', $emailInfo );
    }

    public function closeOrder($orderId, $transactionCode, $bankAnswer)
    {
        $arOrder = org_glizy_ObjectFactory::createModel('desiderataLibrary.modules.order.models.Order');
        $arOrder->load($orderId);
        $arOrder->order_state = 'completed';
        $arOrder->order_transactionCode = $transactionCode;
        $arOrder->order_bankAnswer = $bankAnswer;
        $arOrder->save();
    }

    public function addLicenses($userId, $orderId)
    {
        $licenseService = __ObjectFactory::createObject('desiderataLibrary.modules.license.service.LicenseService');
        $it = __ObjectFactory::createModelIterator('desiderataLibrary.modules.order.models.OrderItem')
                ->load('orderDetails', array('orderId' => $orderId, 'userId' => $userId));

        $licenses = array();
        foreach($it as $ar) {
            $licenses[] = $ar->orderitem_FK_publication_id;
            $licenseService->addLicense($ar->orderitem_FK_publication_id, $userId);
        }

        gruppometa_easybook_EasybookFE::setLicenses(array_merge(gruppometa_easybook_EasybookFE::getLicenses(), $licenses));
    }
}
