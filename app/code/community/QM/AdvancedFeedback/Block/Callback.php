<?php

class QM_AdvancedFeedback_Block_Callback extends Mage_Core_Block_Template
{
    const ROUTE_CALLBACK_ADD = 'advfeedback/callback/add';

    public function getPostActionUrl()
    {
        return $this->helper('qmadvfeedback')->getPostActionUrl(self::ROUTE_CALLBACK_ADD);
    }

    public function canShowBlock()
    {
        return Mage::getStoreConfig('qmadvfeedback/callback/enabled');
    }

    public function isAjaxEnabled()
    {
        return (int)Mage::getStoreConfig('qmadvfeedback/callback/enable_ajax');
    }

    public function canShowPreferredTime()
    {
        return Mage::getStoreConfig('qmadvfeedback/callback/show_preferred');
    }

    public function canShowComment()
    {
        return Mage::getStoreConfig('qmadvfeedback/callback/show_comment');
    }

    public function getFieldsColection()
    {
        $oldFields = array(
            array(
                'name' => 'name',
                'label' => $this->__('Name'),
                'additional' => array ('class'=>'required-entry'),
                'for_bot' => true
            ),
            array(
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
            )
        );

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

        return Mage::helper('qmadvfeedback/botHelper')->encodeFields($oldFields, 'qmadvancedfeedback:callback');
    }
}