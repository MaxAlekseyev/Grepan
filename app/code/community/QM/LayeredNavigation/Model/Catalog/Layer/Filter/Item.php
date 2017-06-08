<?php
class QM_LayeredNavigation_Model_Catalog_Layer_Filter_Item extends Mage_Catalog_Model_Layer_Filter_Item
{
    /**
     * Get filter item url
     *
     * @return string
     */
    public function getUrl()
    {
        $query = array(
            $this->getFilter()->getRequestVar()=>$this->getValue(),
            Mage::getBlockSingleton('page/html_pager')->getPageVarName() => null // exclude current page from urls
        ); 
        $url = Mage::helper('qm_layerednavigation/url')->getFullUrl($query);
        return $url;
    }
    
    
    public function getRemoveUrl()
    {
        $query = array(
            $this->getFilter()->getRequestVar() => $this->getFilter()->getResetValue(),
            Mage::getBlockSingleton('page/html_pager')->getPageVarName() => null // exclude current page from urls
        );
        
        $url = Mage::helper('qm_layerednavigation/url')->getFullUrl($query);
        return $url;        
    } 

}