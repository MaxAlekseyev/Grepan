<?php
class QM_DeveloperTools_Block_Adminhtml_Widget_Grid_Column_Renderer_IsConflict extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {
    public function render(Varien_Object $row) {
        if (!$row->getec_is_conflict()) {
            $retour = '<font color="green">No</font>';
        } else {
            $retour = '<font color="red">Yes</font><br>';
            $url    = $this->getUrl('*/*/displayFix', array('ec_id' => $row->getId()));
            $retour .= '<a href="' . $url . '">Display fix</a>';
        }

        return $retour;
    }
}