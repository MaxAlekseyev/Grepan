<?php

/**
 * Image collection resource model
 *
 * @category    QM
 * @package     QM_Banners
 *
 */
class QM_Banners_Model_Resource_Image_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected $_joinedFields = array();

    /**
     * constructor
     *
     * @access public
     * @return void
     *
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('qm_banners/image');
    }

    /**
     * Get SQL for get record count.
     * Extra GROUP BY strip added.
     *
     * @access public
     * @return Varien_Db_Select
     *
     */
    public function getSelectCountSql()
    {
        $countSelect = parent::getSelectCountSql();
        $countSelect->reset(Zend_Db_Select::GROUP);
        return $countSelect;
    }
}
