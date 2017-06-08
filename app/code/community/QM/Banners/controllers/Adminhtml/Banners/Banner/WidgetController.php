<?php

/**
 * Banner admin widget controller
 *
 * @category    QM
 * @package     QM_Banners
 *
 */
class QM_Banners_Adminhtml_Banners_Banner_WidgetController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Chooser Source action
     *
     * @access public
     * @return void
     *
     */
    public function chooserAction()
    {
        $uniqId = $this->getRequest()->getParam('uniq_id');
        $grid = $this->getLayout()->createBlock(
            'qm_banners/adminhtml_banner_widget_chooser',
            '',
            array(
                'id' => $uniqId,
            )
        );
        $this->getResponse()->setBody($grid->toHtml());
    }
}
