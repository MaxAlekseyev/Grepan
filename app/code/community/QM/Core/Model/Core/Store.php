<?php

class QM_Core_Model_Core_Store extends Mage_Core_Model_Store
{
    public function getCurrentUrl($fromStore = true)
    {
        if (Mage::helper('qmcore')->isForceRemoveFromStore()) {
            $fromStore = false;
        }
        return parent::getCurrentUrl($fromStore);
    }

    /**
     * Round price
     *
     * @param mixed $price
     * @return double
     */
    public function roundPrice($price)
    {
        return round($price, 4);
    }
}
