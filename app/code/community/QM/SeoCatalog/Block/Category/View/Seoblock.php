<?php

class QM_SeoCatalog_Block_Category_View_Seoblock extends Mage_Core_Block_Template
{
    public function getCurrentCategory()
    {
        if (!$this->hasData('current_category')) {
            $this->setData('current_category', Mage::registry('current_category'));
        }
        return $this->getData('current_category');
    }

    public function getSeoBlockHtml()
    {
        if (!$this->getData('cms_block_html')) {
            $html = $this->getLayout()->createBlock('cms/block')
                ->setBlockId($this->getCurrentCategory()->getCmsSeoBlock())
                ->toHtml();
            $this->setData('cms_block_html', $html);
        }
        return $this->getData('cms_block_html');
    }

    public function canShowBlock()
    {
        return $this->getCurrentCategory()->getCmsSeoBlock();
    }
}