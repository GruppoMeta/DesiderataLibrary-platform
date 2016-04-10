<?php
interface desiderataLibrary_modules_order_gateway_IGateway
{
    public function pay($userId, $orderId, $total);
    public function s2s();
}
