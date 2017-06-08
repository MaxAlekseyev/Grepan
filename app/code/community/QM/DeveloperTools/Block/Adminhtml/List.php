<?php
class QM_DeveloperTools_Block_Adminhtml_List extends Mage_Adminhtml_Block_Widget_Grid {
    public function __construct() {
        parent::__construct();
        $this->setId('ExtensionConflictGrid');
        $this->setDefaultSort('ec_is_conflict');
        $this->setDefaultDir('DESC');
        $this->_parentTemplate = $this->getTemplate();
        $this->setEmptyText($this->__('No items'));
    }

    public function getGridParentHtml() {
        $templateName = Mage::getDesign()->getTemplateFilename($this->_parentTemplate, array('_relative' => TRUE));

        return $this->fetchView($templateName);
    }

    public function getRefreshUrl() {
        return $this->getUrl('*/*/refresh');
    }

    public function getUploadUrl() {
        return $this->getUrl('*/*/upload');
    }

    public function getDeleteVirtualModuleUrl() {
        return $this->getUrl('*/*/deleteVirtualModule');
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('qm_devtools/conflict')->getCollection();
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn('ec_core_module', array(
                                                'header' => Mage::helper('qm_devtools')->__('Core Module'),
                                                'index'  => 'ec_core_module'
                                           ));
        $this->addColumn('ec_core_class', array(
                                               'header' => Mage::helper('qm_devtools')->__('Core Class'),
                                               'index'  => 'ec_core_class'
                                          ));
        $this->addColumn('ec_rewrite_classes', array(
                                                    'header' => Mage::helper('qm_devtools')->__('Rewrite Classes'),
                                                    'index'  => 'ec_rewrite_classes'
                                               ));
        $this->addColumn('ec_is_conflict', array(
                                                'header'   => Mage::helper('qm_devtools')->__('Is Conflict'),
                                                'index'    => 'ec_is_conflict',
                                                'renderer' => 'QM_DeveloperTools_Block_Adminhtml_Widget_Grid_Column_Renderer_IsConflict',
                                                'align'    => 'center'
                                           ));

        return parent::_prepareColumns();
    }
}
