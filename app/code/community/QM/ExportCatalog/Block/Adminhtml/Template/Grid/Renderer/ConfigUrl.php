<?php

class QM_ExportCatalog_Block_Adminhtml_Template_Grid_Renderer_ConfigUrl extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        return $this->_getValue($row);
    }

    protected function _getValue(Varien_Object $row)
    {
        $out = '';
        $id  = $row->getData($this->getColumn()->getIndex());

        if ($id) {
            $url = Mage::helper('qm_exportcatalog')->getConfigUrl($id);
            $out = "<a href='$url'>$url</a>";
        }
        return $out;
    }
} 