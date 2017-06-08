<?php
class QM_LayeredNavigation_Block_Featured extends Mage_Core_Block_Template
{
    public function getItems()
    {
        $items = array();
        // get filter ID by attribute code 
        $id = Mage::getResourceModel('qm_layerednavigation/filter')
            ->getIdByCode($this->getAttributeCode());
        if ($id){
            $items = Mage::getResourceModel('qm_layerednavigation/value_collection')
                ->addFieldToFilter('is_featured', 1)
                ->addFieldToFilter('filter_id', $id)
                ->addValue();  
                
            if ($this->getRandom()){
                $items->setOrder('rand()');
            } 
            else {
            	$items->setOrder('featured_order', 'asc');
                $items->setOrder('value', 'asc');    
                $items->setOrder('title', 'asc');    
            }  
             
            if ($this->getLimit()){
                $items->setPageSize(intVal($this->getLimit()));
            }   
                
            $hlp = Mage::helper('qm_layerednavigation/url');
            $base = Mage::getBaseUrl('media') . 'layerednavigation/';
            foreach ($items as $item){
                if ($item->getImgBig())
                    $item->setImgBig($base . $item->getImgBig());   
                
                $attrCode = $this->getAttributeCode();
                $optLabel = $item->getValue() ? $item->getValue() : $item->getTitle();
                $optId    = $item->getOptionId();
                $item->setUrl($hlp->getOptionUrl($attrCode, $optLabel, $optId));   
            }
        }
        return $items;
    }
}