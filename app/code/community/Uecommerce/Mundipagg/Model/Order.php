<?php

class Uecommerce_Mundipagg_Model_Order extends Mage_Sales_Model_Order
{

    public function logTrace($state, $status = false)
    {
        $helperLog = new Uecommerce_Mundipagg_Helper_Log(__METHOD__);
        $helperLog->setLogLabel("Status change on {$this->getIncrementId()}");
        $helperLog->info("State: " . $this->getState() ." - Status: " . $this->getStatus() . " >> State: " . $state . ' - Status: ' . ($status ? $status : "false" ) . "");
        $helperLog->info( "\n" .(new Exception())->getTraceAsString());

    }

    protected function _setState($state, $status = false, $comment = '', $isCustomerNotified = null)
    {
        $this->logTrace($state, $status);
        return parent::_setState($state, $status, $comment, $isCustomerNotified, true);
    }


    public function addStatusToHistory($status, $comment = '', $isCustomerNotified = false)
    {
        $this->logTrace("",  $status);
        return parent::addStatusToHistory($status, $comment, $isCustomerNotified);
    }


    public function addStatusHistoryComment($comment, $status = false)
    {
        $this->logTrace("",  $status);
        return parent::addStatusHistoryComment($comment, $status);
    }
}  
