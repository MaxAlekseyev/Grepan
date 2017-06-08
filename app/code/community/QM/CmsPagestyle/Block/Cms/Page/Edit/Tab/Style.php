<?php

class QM_CmsPagestyle_Block_Cms_Page_Edit_Tab_Style
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    /**
     * Load Wysiwyg on demand and Prepare layout
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
    }

    protected function _prepareForm()
    {
        /** @var $model Mage_Cms_Model_Page */
        $model = Mage::registry('cms_page');

        /*
         * Checking if user have permissions to save information
         */
        if ($this->_isAllowedAction('save')) {
            $isElementDisabled = false;
        } else {
            $isElementDisabled = true;
        }

        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('page_');

        $fieldset = $form->addFieldset('style_fieldset', array('legend'=>Mage::helper('cms')->__('Style and Script'),'class'=>'fieldset-wide'));

        $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig(
            array('tab_id' => $this->getTabId())
        );

        $fieldset->addField('page_css', 'textarea', array(
            'name'      => 'page_css',
            'label'     => Mage::helper('cms')->__('Styles (CSS)'),
            'disabled'  => false,
            'value'     => $model->getPageCss(),
            'note'      => "<span style='color:red;font-weight:bold;'>Important!</span> Pure CSS code without tag &#8249;style&#8250;."
        ));

        $fieldset->addField('page_js', 'textarea', array(
            'name'      => 'page_js',
            'label'     => Mage::helper('cms')->__('Scripts (JS)'),
            'disabled'  => false,
            'value'     => $model->getPageJs(),
            'note'      => "<span style='color:red;font-weight:bold;'>Important!</span> Pure JavaScript code without tag &#8249;script&#8250;."
        ));

        $form->setValues($model->getData());
        $this->setForm($form);

        Mage::dispatchEvent('adminhtml_cms_page_edit_tab_style_prepare_form', array('form' => $form));

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return Mage::helper('cms')->__('CSS & JS');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('cms')->__('CSS & JS');
    }

    /**
     * Returns status flag about this tab can be shown or not
     *
     * @return true
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Returns status flag about this tab hidden or not
     *
     * @return true
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Check permission for passed action
     *
     * @param string $action
     * @return bool
     */
    protected function _isAllowedAction($action)
    {
        return Mage::getSingleton('admin/session')->isAllowed('cms/page/' . $action);
    }
}
