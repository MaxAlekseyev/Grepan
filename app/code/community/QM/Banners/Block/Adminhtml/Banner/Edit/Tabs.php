<?php

/**
 * Banner admin edit tabs
 *
 * @category    QM
 * @package     QM_Banners
 *
 */
class QM_Banners_Block_Adminhtml_Banner_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * Initialize Tabs
     *
     * @access public
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('banner_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('qm_banners')->__('Banner'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return QM_Banners_Block_Adminhtml_Banner_Edit_Tabs
     *
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_banner',
            array(
                'label' => Mage::helper('qm_banners')->__('Banner'),
                'title' => Mage::helper('qm_banners')->__('Banner'),
                'content' => $this->getLayout()->createBlock(
                    'qm_banners/adminhtml_banner_edit_tab_form'
                )->toHtml(),
            )
        );

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addTab(
                'form_store_banner',
                array(
                    'label' => Mage::helper('qm_banners')->__('Store views'),
                    'title' => Mage::helper('qm_banners')->__('Store views'),
                    'content' => $this->getLayout()->createBlock(
                        'qm_banners/adminhtml_banner_edit_tab_stores'
                    )
                        ->toHtml(),
                )
            );
        }
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve banner entity
     *
     * @access public
     * @return QM_Banners_Model_Banner
     *
     */
    public function getBanner()
    {
        return Mage::registry('current_banner');
    }
}
