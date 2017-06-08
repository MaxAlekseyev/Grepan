<?php

class QM_OneclickOrder_Model_Config_Source_Fieldtype extends Varien_Object
{
    const FIELD_TYPE_EMAIL           = '1';
    const FIELD_TYPE_PHONE           = '2';
    const FIELD_TYPE_EMAIL_OR_PHONE  = '3';
    const FIELD_TYPE_EMAIL_AND_PHONE = '4';

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
            array('value'=>self::FIELD_TYPE_EMAIL, 'label'=>$hlp->__('Email')),
            array('value'=>self::FIELD_TYPE_PHONE, 'label'=>$hlp->__('Phone')),
            array('value'=>self::FIELD_TYPE_EMAIL_OR_PHONE, 'label'=>$hlp->__('Email or Phone (as One Field)')),
            array('value'=>self::FIELD_TYPE_EMAIL_AND_PHONE, 'label'=>$hlp->__('Email and Phone (as Different Fields'))
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
