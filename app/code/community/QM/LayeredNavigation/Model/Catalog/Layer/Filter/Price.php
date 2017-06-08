<?php
class QM_LayeredNavigation_Model_Catalog_Layer_Filter_Price extends QM_LayeredNavigation_Model_Catalog_Layer_Filter_Price_Adapter
{
    /**
     * Display Types
     */
    const DT_DEFAULT    = 0;
    const DT_DROPDOWN   = 1;
    const DT_FROMTO     = 2;
    const DT_SLIDER     = 3;
    
    public function _construct()
    {    
        parent::_construct();
    }
    
    public function _srt($a, $b)
    {
        $res = ($a['pos'] < $b['pos']) ? -1 : 1;
        return $res;
    } 
    
    protected function _getCustomRanges()
    {
        $ranges = array();
        $collection = Mage::getModel('qm_layerednavigation/range')->getCollection()
            ->setOrder('price_frm','asc')
            ->load();
            
        $rate = Mage::app()->getStore()->getCurrentCurrencyRate(); 
        foreach ($collection as $range){
            $from = $range->getPriceFrm()*$rate;
            $to = $range->getPriceTo()*$rate;
            
            $ranges[$range->getId()] = array($from, $to);
        }
        
        if (!$ranges){
            echo "Please set up Custom Ranges in the Admin > Catalog > Improved Navigation > Ranges";
            exit;
        }
        
        return $ranges;
    }  
    
    public function calculateRanges()
    {
        return (Mage::getStoreConfig('qm_layerednavigation/general/price_type') == self::DT_DEFAULT
            || Mage::getStoreConfig('qm_layerednavigation/general/price_type') == self::DT_DROPDOWN);
    } 
    
    public function hideAfterSelection()
    {
        if (Mage::getStoreConfig('qm_layerednavigation/general/price_from_to')){
            return false;
        }
        if (Mage::getStoreConfig('qm_layerednavigation/general/price_type') == self::DT_SLIDER){
            return false;
        }
        return true;
    }
}