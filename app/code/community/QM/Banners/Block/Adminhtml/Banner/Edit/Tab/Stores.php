<?php

/**
 * store selection tab
 *
 * @category    QM
 * @package     QM_Banners
 *
 */
class QM_Banners_Block_Adminhtml_Banner_Edit_Tab_Stores extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return QM_Banners_Block_Adminhtml_Banner_Edit_Tab_Stores
     *
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setFieldNameSuffix('banner');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'banner_stores_form',
            array('legend' => Mage::helper('qm_banners')->__('Store views'))
        );
        $field = $fieldset->addField(
            'store_id',
            'multiselect',
            array(
                'name'     => 'stores[]',
                'label'    => Mage::helper('qm_banners')->__('Store Views'),
                'title'    => Mage::helper('qm_banners')->__('Store Views'),
                'required' => true,
                'values'   => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
            )
        );
        $renderer = $this->getLayout()->createBlock('adminhtml/store_switcher_form_renderer_fieldset_element');
        $field->setRenderer($renderer);
        $form->addValues(Mage::registry('current_banner')->getData());
        return parent::_prepareForm();
    }
}
