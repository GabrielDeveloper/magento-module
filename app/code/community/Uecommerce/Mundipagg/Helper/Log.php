<?php

/**
 * Log helper
 * @author Ruan Azevedo <razvedo@mundipagg.com>
 */
class Uecommerce_Mundipagg_Helper_Log extends Mage_Core_Helper_Abstract
{
    private $level;
    private $method;
    private $logLabel = '';
    private $addHostName = false;
    private $logger;
    private $logPath;

    public function __construct($method = '')
    {
        $this->method = $method;
        $this->addHostName = Mage::getStoreConfig('payment/mundipagg_standard/logHost') == '1';
        $this->logger = Mage::helper('mundipagg/logger');

        $this->logPath = Mage::getStoreConfig('payment/mundipagg_standard/logPath');
        if (Mage::getStoreConfig('payment/mundipagg_standard/logNonDefaultLogPath') != '1') {
            $this->logPath = Mage::getBaseDir('var') . DS . 'log';
        }
    }

    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    public function setLogLabel($logLabel)
    {
        $this->logLabel = $logLabel;
        return $this;
    }

    public function getLogLabel()
    {
        return $this->logLabel;
    }

    public function info($msg)
    {
        $this->level = Zend_Log::INFO;
        $this->write($msg);
    }

    public function debug($msg)
    {
        $this->level = Zend_Log::DEBUG;
        $this->write($msg);
    }

    public function warning($msg)
    {
        $this->level = Zend_Log::WARN;
        $this->write($msg);
    }

    public function error($msg, $logExceptionFile = false)
    {
        $exception = new Exception($msg);
        $this->level = Zend_Log::ERR;
        $this->write($msg);

        if ($logExceptionFile) {
            Mage::logException($exception);
        }
    }

    private function write($msg)
    {
        $debugIsEnabled = intval(Mage::getStoreConfig('payment/mundipagg_standard/debug'));

        if ($debugIsEnabled === false) {
            return;
        }

        $version = Mage::helper('mundipagg')->getExtensionVersion();

        $file = "Mundipagg_Integracao_" . date('Y-m-d');
        if ($this->addHostName) {
            $file .= '_' . gethostname();
        }
        $file .= '.log';

        $method = $this->method;
        $newMsg = "v{$version} ";

        if (!empty($method)) {
            $logLabel = $this->logLabel;

            if (!empty($logLabel)) {
                $newMsg .= "[{$this->method}] {$this->logLabel} | {$msg}";
            } else {
                $newMsg .= "[{$this->method}] {$msg}";
            }
        } else {
            $newMsg .= $msg;
        }

        $this->logger->log($newMsg, $this->level, $file, $this->logPath);
    }
}
