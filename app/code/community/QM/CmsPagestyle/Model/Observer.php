<?php

class QM_CmsPagestyle_Model_Observer
{
    public function cmsPageRender($observer)
    {
        $action = $observer->getControllerAction();
        $action->getLayout()->getUpdate()->addHandle('append_cms_page_styles');
    }
}