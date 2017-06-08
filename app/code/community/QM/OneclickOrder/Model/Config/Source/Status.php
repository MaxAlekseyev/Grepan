<?php

class QM_OneclickOrder_Model_Config_Source_Status extends Varien_Object
{
    const STATUS_PROCESSED  = 'processed';
    const STATUS_NEW        = 'new';

    public function getAllOptions()
    {
        if (!$this->_options){
            $this->_options = $this->toOptionArray();
        }
        return $this->_options;
    }

    public function getOptions()
    {
        $options = array();
        foreach ($this->toOptionArray() as $option){
            $options[$option['value']] = $option['label'];
        }
        return $options;
    }

    public function toOptionArray($withEmpty = false)
    {
        $hlp = Mage::helper('qmoneclickorder');
        $options = array(
            array('value'=>self::STATUS_NEW, 'label'=>$hlp->__('New')),
            array('value'=>self::STATUS_PROCESSED, 'label'=>$hlp->__('Processed')),
        );
        if ($withEmpty){
            array_unshift($options, array(
                'value' => '',
                'label' => $hlp->__('-- Please Select --')
            ));
        }
        return $options;
    }
}
