<?php

/**
 * Banner admin edit form
 *
 * @category    QM
 * @package     QM_Banners
 *
 */
class QM_Banners_Block_Adminhtml_Banner_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * constructor
     *
     * @access public
     * @return void
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->_blockGroup = 'qm_banners';
        $this->_controller = 'adminhtml_banner';
        $this->_updateButton(
            'save',
            'label',
            Mage::helper('qm_banners')->__('Save Banner')
        );
        $this->_updateButton(
            'delete',
            'label',
            Mage::helper('qm_banners')->__('Delete Banner')
        );
        $this->_addButton(
            'saveandcontinue',
            array(
                'label'   => Mage::helper('qm_banners')->__('Save And Continue Edit'),
                'onclick' => 'saveAndContinueEdit()',
                'class'   => 'save',
            ),
            -100
        );
        $this->_formScripts[] = "
            function saveAndContinueEdit() {
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    /**
     * get the edit form header
     *
     * @access public
     * @return string
     *
     */
    public function getHeaderText()
    {
        if (Mage::registry('current_banner') && Mage::registry('current_banner')->getId()) {
            return Mage::helper('qm_banners')->__(
                "Edit Banner '%s'",
                $this->escapeHtml(Mage::registry('current_banner')->getName())
            );
        } else {
            return Mage::helper('qm_banners')->__('Add Banner');
        }
    }
}
