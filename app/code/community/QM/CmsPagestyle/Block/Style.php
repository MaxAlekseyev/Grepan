<?php

class QM_CmsPagestyle_Block_Style extends Mage_Core_Block_Template
{
    protected function _getCurrentPage()
    {
        return Mage::getSingleton('cms/page');
    }

    public function _toHtml()
    {
        $page = $this->_getCurrentPage();
        return "<style>\n". $page->getPageCss() ."\n</style>\n" .
               "<script type=\"text/javascript\">\n". $page->getPageJs() ."\n</script>\n";
    }
}