<?php
class desiderataLibrary_modules_order_gateway_Simulate extends desiderataLibrary_modules_order_gateway_AbstractGateway implements desiderataLibrary_modules_order_gateway_IGateway
{
    private $orderId;
    private $userId;

    public function pay($userId, $orderId, $total)
    {
        $this->closeOrder($orderId, '', '');

        // simula la conferma s2s ed invia  l'email
        $this->orderId = $orderId;
        $this->userId = $userId;
        $this->s2s();

        return true;
    }

    public function s2s()
    {
        $this->sendEmail($this->userId, $this->orderId);
        $this->addLicenses($this->userId, $this->orderId);
    }
}
