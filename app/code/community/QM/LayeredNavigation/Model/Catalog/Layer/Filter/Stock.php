<?php
class QM_LayeredNavigation_Model_Catalog_Layer_Filter_Stock extends Mage_Catalog_Model_Layer_Filter_Abstract
{

	const FILTER_IN_STOCK = 1;
	const FILTER_OUT_OF_STOCK = 2;

    /**
     * Class constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->_requestVar = 'stock';
    }

    /**
     * Apply category filter to layer
     *
     * @param   Zend_Controller_Request_Abstract $request
     * @param   Mage_Core_Block_Abstract $filterBlock
     * @return  Mage_Catalog_Model_Layer_Filter_Category
     */
    public function apply(Zend_Controller_Request_Abstract $request, $filterBlock)
    {
        $filter = (int) $request->getParam($this->getRequestVar());
        if (!$filter || Mage::registry('am_stock_filter')) {
            return $this;
        }
        
        $select = $this->getLayer()->getProductCollection()->getSelect();
        
        if (strpos($select, 'cataloginventory_stock_status') === false) {
        	Mage::getResourceModel('cataloginventory/stock_status')
                ->addStockStatusToSelect($select, Mage::app()->getWebsite());
        } 
        
        if ($filter == self::FILTER_IN_STOCK) {
			$select->where('stock_status.stock_status = ?', 1);	
        } else {
        	$select->where('stock_status.stock_status = ?', 0);
        }
        
        $state = $this->_createItem($filter == self::FILTER_IN_STOCK ? Mage::helper('qm_layerednavigation')->__('In Stock') : Mage::helper('qm_layerednavigation')->__('Out of Stock'), $filter)
                        ->setVar($this->_requestVar);
                        
        $this->getLayer()->getState()->addFilter($state);
        
        Mage::register('am_stock_filter', true);
            
        return $this;
    }


    /**
     * Get filter name
     *
     * @return string
     */
    public function getName()
    {
        return Mage::helper('qm_layerednavigation')->__('Stock Filter');
    }

    /**
     * Get data array for building category filter items
     *
     * @return array
     */
    protected function _getItemsData()
    {
    	$data = array();
    	$status = $this->_getCount();
    	
    	$in_stock = array_keys($status);
    	$out_stock = array_values($status);
    	
    	$data[] = array(
        	'label' => Mage::helper('qm_layerednavigation')->__('In Stock'),
            'value' => self::FILTER_IN_STOCK,
            'count' => $in_stock[0],
		);
		$data[] = array(
        	'label' => Mage::helper('qm_layerednavigation')->__('Out of Stock'),
            'value' => self::FILTER_OUT_OF_STOCK,
            'count' => $out_stock[0],
		);		
        return $data;
    }
    
    protected function _getCount()
    {
    	$select = clone $this->getLayer()->getProductCollection()->getSelect();
    	
    	if (strpos($select, 'cataloginventory_stock_status') === false) {
        	Mage::getResourceModel('cataloginventory/stock_status')
                ->addStockStatusToSelect($select, Mage::app()->getWebsite());
        } 
        
		$select->reset(Zend_Db_Select::ORDER);
        $select->reset(Zend_Db_Select::LIMIT_COUNT);
        $select->reset(Zend_Db_Select::LIMIT_OFFSET);
        $select->reset(Zend_Db_Select::WHERE);
       
    	$sql = 'select  SUM(stock.salable) as in_stock, COUNT(stock.salable) - SUM(stock.salable) as out_stock from (' . $select->__toString()  . ') as stock';    	
		$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
		
        return $connection->fetchPairs($sql);            
    }
}