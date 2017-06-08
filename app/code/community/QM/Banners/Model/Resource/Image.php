<?php

/**
 * Image resource model
 *
 * @category    QM
 * @package     QM_Banners
 *
 */
class QM_Banners_Model_Resource_Image extends Mage_Core_Model_Resource_Db_Abstract
{

    /**
     * constructor
     *
     * @access public
     *
     */
    public function _construct()
    {
        $this->_init('qm_banners/image', 'entity_id');
    }
}
