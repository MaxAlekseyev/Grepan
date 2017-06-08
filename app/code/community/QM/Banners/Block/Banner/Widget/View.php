<?php

/**
 * Banner widget block
 *
 * @category    QM
 * @package     QM_Banners
 *
 */
class QM_Banners_Block_Banner_Widget_View extends Mage_Core_Block_Template implements
    Mage_Widget_Block_Interface
{
    protected $_htmlTemplate = 'qm/banners/banner/widget/view.phtml';

    /**
     * Prepare a for widget
     *
     * @access protected
     * @return QM_Banners_Block_Banner_Widget_View
     *
     */
    protected function _beforeToHtml()
    {
        parent::_beforeToHtml();
        $bannerId = $this->getData('banner_id');
        if ($bannerId) {
            $banner = Mage::getModel('qm_banners/banner')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->load($bannerId);

            if ($banner->getStatus()) {
                $this->setCurrentBanner($banner);
                $this->setTemplate($this->_htmlTemplate);
            }
        }
        return $this;
    }
}
