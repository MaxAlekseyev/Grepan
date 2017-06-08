<?php

/**
 * Banner edit form tab
 *
 * @category    QM
 * @package     QM_Banners
 *
 */
class QM_Banners_Block_Adminhtml_Banner_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _getAfterFormJs()
    {
        $wrongDimensionFormatMessage = Mage::helper('qm_banners')->__('Invalid dimension format. Use format like 123px or 34.3% or leave blank');
        return <<<EOD
<script>
Validation.add('validate-dimension', '$wrongDimensionFormatMessage', function(value) {
    return !value || /^(\d*[.])?\d+%$/.test(value) || /^\d+px$/.test(value);
});
</script>
EOD;

    }
    /**
     * prepare the form
     *
     * @access protected
     * @return QM_Banners_Block_Adminhtml_Banner_Edit_Tab_Form
     *
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('banner_');
        $form->setFieldNameSuffix('banner');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'banner_form',
            array('legend' => Mage::helper('qm_banners')->__('Banner'))
        );

        $fieldset->addField(
            'name',
            'text',
            array(
                'label' => Mage::helper('qm_banners')->__('Name'),
                'name' => 'name',
                'class' => 'required-entry'
            )
        );

        $fieldset->addField(
            'width',
            'text',
            array(
                'label' => Mage::helper('qm_banners')->__('Width'),
                'name' => 'width',
                'note' => Mage::helper('qm_banners')->__('Add px or % to value or leave blank.'),
                'class' => 'validate-dimension',

            )
        );

        $fieldset->addField(
            'height',
            'text',
            array(
                'label' => Mage::helper('qm_banners')->__('Height'),
                'name' => 'height',
                'note' => Mage::helper('qm_banners')->__('Add px or % to value or leave blank.'),
                'class' => 'validate-dimension',

            )
        );

        $fieldset->addField(
            'css_class',
            'text',
            array(
                'label' => Mage::helper('qm_banners')->__('Css class'),
                'name' => 'css_class'
            )
        );

        $fieldset->addField(
            'status',
            'select',
            array(
                'label' => Mage::helper('qm_banners')->__('Status'),
                'name' => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('qm_banners')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('qm_banners')->__('Disabled'),
                    ),
                ),
            )
        );

        $fieldset->addField(
            'store_render_images',
            'hidden',
            array(
                'name' => 'store_renders',
                'after_element_html' => Mage::app()->getLayout()->createBlock(
                    'QM_Banners_Block_Adminhtml_Banner_Helper_Render_Content', 'qm_banners_render')
                    ->setBanner(Mage::registry('current_banner'))->setStoreId(Mage::registry('current_store'))->toHtml()
            )
        );

        $fieldset->addField(
            'after_form_js',
            'hidden',
            array(
                'name' => 'after_form_js',
                'after_element_html' => $this->_getAfterFormJs()
            )
        );


        $fieldset->addField(
            'render_store_id',
            'hidden',
            array(
                'name' => 'render_store_id',
            )
        );

        if (Mage::app()->isSingleStoreMode()) {
            $fieldset->addField(
                'store_id',
                'hidden',
                array(
                    'name' => 'stores[]',
                    'value' => Mage::app()->getStore(true)->getId()
                )
            );
            Mage::registry('current_banner')->setStoreId(Mage::app()->getStore(true)->getId());
        }
        $formValues = Mage::registry('current_banner')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getBannerData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getBannerData());
            Mage::getSingleton('adminhtml/session')->setBannerData(null);
        } elseif (Mage::registry('current_banner')) {
            $formValues = array_merge($formValues, Mage::registry('current_banner')->getData());
        }
        $formValues = array_merge($formValues, array('render_store_id'=>Mage::registry('current_store')));
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
