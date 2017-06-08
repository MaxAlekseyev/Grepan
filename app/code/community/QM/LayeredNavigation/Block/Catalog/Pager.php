<?php
class QM_LayeredNavigation_Block_Catalog_Pager extends Mage_Page_Block_Html_Pager
{
    public function getPagerUrl($params=array())
    {
        return $this->getParentBlock()->getPagerUrl($params);
    }
    
}