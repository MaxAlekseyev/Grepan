<?php

class QM_NewPostV2_Helper_Currency extends Mage_Core_Helper_Abstract
{
    const NEWPOST_PRICE_CURRENCY_CODE = 'UAH';
    protected $_storeCurrencyCode;
    protected $_isNewPostCurrencyAllow = null;
    protected $_helper;

    public function __construct()
    {
        $this->_helper            = Mage::helper('qm_newpostv2');
        $this->_storeCurrencyCode = Mage::app()->getStore()->getCurrentCurrencyCode();
    }

    public function convert($cost, $from, $to)
    {
        if (!$this->isNewPostCurrencyAllow()) {
            return $cost;
        }

        $fromToRate = Mage::getModel('directory/currency')->getCurrencyRates($from, $to);
        if ($fromToRate) {
            return ($cost * $fromToRate[$to]);
        }

        $toFromRate = Mage::getModel('directory/currency')->getCurrencyRates($to, $from);
        if ($toFromRate) {
            return ($cost / $toFromRate[$from]);
        }

        $this->_helper->logError('Undefined rate from ' . $from . 'to ' . $to);

        return 0;
    }

    private function _convertCurrency($price, $fromCurCode, $toCurCode)
    {
        $store = Mage::app()->getStore();
        $baseCode = $store->getBaseCurrencyCode();

        if ($baseCode == $fromCurCode) {
            return Mage::helper('directory')->currencyConvert($price, $fromCurCode, $toCurCode);
        }

        $ratio = Mage::helper('directory')->currencyConvert(1, $baseCode, $fromCurCode);

        return Mage::helper('directory')->currencyConvert($price / $ratio, $baseCode, $toCurCode);
    }

    /**
     * From magento to newpost
     * @param $cost
     * @return float
     */
    public function convertCurrencyOut($cost)
    {
        $store = Mage::app()->getStore();

        $baseCurrencyCode = $store->getBaseCurrencyCode();

        if ($baseCurrencyCode == self::NEWPOST_PRICE_CURRENCY_CODE) {
            return $cost;
        }

        return $this->_convertCurrency($cost, $baseCurrencyCode, self::NEWPOST_PRICE_CURRENCY_CODE);
    }

    /**
     * From newpost to magento
     * @param $cost
     * @return float
     */
    public function convertCurrencyIn($cost)
    {
        $store = Mage::app()->getStore();

        $baseCurrencyCode = $store->getBaseCurrencyCode();

        if ($baseCurrencyCode == self::NEWPOST_PRICE_CURRENCY_CODE) {
            return $cost;
        }

        return $this->_convertCurrency($cost, self::NEWPOST_PRICE_CURRENCY_CODE, $baseCurrencyCode);
    }

    public function isNewPostCurrencyAllow()
    {
        if ($this->_isNewPostCurrencyAllow === null) {
            $this->_isNewPostCurrencyAllow = in_array(
                self::NEWPOST_PRICE_CURRENCY_CODE,
                Mage::getModel('directory/currency')->getConfigAllowCurrencies()
            );
        }

        if (!$this->_isNewPostCurrencyAllow) {
            $this->_helper->logError('Uah currency not allow');
            $this->_helper->logError(Mage::getModel('directory/currency')->getConfigAllowCurrencies());
        }

        return $this->_isNewPostCurrencyAllow;
    }
}