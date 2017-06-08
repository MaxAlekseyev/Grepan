<?php

class QM_AdvancedFeedback_Model_Config_Source_Spam extends Varien_Object
{
    const SPAM    = 1;
    const NO_SPAM = 0;

    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = $this->toOptionArray();
        }
        return $this->_options;
    }

    public function getOptions()
    {
        $options = array();
        foreach ($this->toOptionArray() as $option) {
            $options[$option['value']] = $option['label'];
        }
        return $options;
    }

    public function toOptionArray($withEmpty = false)
    {
        $hlp     = Mage::helper('qmadvfeedback');
        $options = array(
            array('value' => self::SPAM, 'label' => $hlp->__('Spam')),
            array('value' => self::NO_SPAM, 'label' => $hlp->__('No Spam')),
        );
        if ($withEmpty) {
            array_unshift($options, array(
                'value' => '',
                'label' => $hlp->__('-- Please Select --')
            ));
        }
        return $options;
    }
}
