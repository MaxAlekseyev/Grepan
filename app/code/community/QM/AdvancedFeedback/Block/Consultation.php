<?php

class QM_AdvancedFeedback_Block_Consultation extends Mage_Core_Block_Template
{
    const ROUTE_CALLBACK_ADD = 'advfeedback/consultation/add';

    public function getPostActionUrl()
    {
        return $this->helper('qmadvfeedback')->getPostActionUrl(self::ROUTE_CALLBACK_ADD);
    }

    public function canShowBlock()
    {
        return Mage::getStoreConfig('qmadvfeedback/consultation/enabled');
    }

    public function isAjaxEnabled()
    {
        return (int)Mage::getStoreConfig('qmadvfeedback/consultation/enable_ajax');
    }

    public function canShowPreferredTime()
    {
        return Mage::getStoreConfig('qmadvfeedback/consultation/show_preferred');
    }

    public function canShowComment()
    {
        return Mage::getStoreConfig('qmadvfeedback/consultation/show_comment');
    }

    public function getTypeOfContentField()
    {
        $configType = Mage::getStoreConfig('qmadvfeedback/consultation/typeof_contact_field');

        switch ($configType) {
            case QM_AdvancedFeedback_Model_Config_Source_Fieldtype::FIELD_TYPE_EMAIL:
                return 'email';
            case QM_AdvancedFeedback_Model_Config_Source_Fieldtype::FIELD_TYPE_PHONE:
                return 'phone';
            case QM_AdvancedFeedback_Model_Config_Source_Fieldtype::FIELD_TYPE_EMAIL_OR_PHONE:
                return 'emailorphone';
            case QM_AdvancedFeedback_Model_Config_Source_Fieldtype::FIELD_TYPE_EMAIL_AND_PHONE:
                return 'emailandphone';
        }
    }

    public function getFieldsColection()
    {
        $oldFields = array(
            array(
                'name' => 'name',
                'label' => $this->__('Name'),
                'additional' => array ('class'=>'required-entry'),
                'for_bot' => true
            )
        );

        if ($this->getTypeOfContentField() == 'email') {
            array_push($oldFields, array(
                'name'  => 'email',
                'label' => $this->__('Email'),
                'additional' => array ('class'=>'required-entry validate-email'),
                'for_bot' => true
            ));
        }

        if ($this->getTypeOfContentField() == 'phone') {
            array_push($oldFields, array(
                'name' => 'telephone',
                'label' => $this->__('Telephone'),
                'after_element_html' => '<script type="text/javascript">
                    //<![CDATA[
                    jQuery(function ($) {
                        $("#callback\\\\:{{name}}").mask("' . $this->helper('qmadvfeedback')->getPhoneMask() . '");
                    });
                    //]]>
                </script>',
                'additional' => array ('class'=>'required-entry'),
                'for_bot' => true
            ));
        }

        if ($this->getTypeOfContentField() == 'emailorphone') {
            array_push($oldFields, array(
                'name'  => 'emailorphone',
                'label' => $this->__('Email or Telephone'),
                'additional' => array ('class'=>'required-entry validate-emailorphone'),
                'for_bot' => true
            ));
        }

        if ($this->getTypeOfContentField() == 'emailandphone') {
            array_push($oldFields, array(
                'name'  => 'email',
                'label' => $this->__('Email'),
                'additional' => array ('class'=>'required-entry validate-email'),
                'for_bot' => true
            ));
            array_push($oldFields, array(
                'name'  => 'telephone',
                'label' => $this->__('Telephone'),
                'additional' => array ('class'=>'required-entry'),
                'for_bot' => true
            ));
        }

        if ($this->canShowPreferredTime()) {
            array_push($oldFields, array(
                'name'  => 'preferred_time',
                'label' => $this->__('Preferred Time'),
                'for_bot' => true
            ));
        }

        if ($this->canShowComment()) {
            array_push($oldFields, array(
                'name'  => 'comment',
                'label' => $this->__('Comment'),
                'for_bot' => true
            ));
        }

        return Mage::helper('qmadvfeedback/botHelper')->encodeFields($oldFields, 'qmadvancedfeedback:consultation');
    }
}