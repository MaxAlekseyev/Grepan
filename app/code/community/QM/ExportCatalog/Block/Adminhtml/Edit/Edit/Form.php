<?php

class QM_ExportCatalog_Block_Adminhtml_Edit_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm()
    {
        $configId = Mage::app()->getRequest()->getParam('id');

        //if we create new product id will be empty, and empty product will be load
        $configModel = Mage::getModel('qm_exportcatalog/config')->load($configId);

        $helper = Mage::helper('qm_exportcatalog');

        $form = new Varien_Data_Form(
            array(
                'id' => 'edit_form',
                'action' => $this->getUrl('*/*/save'),
                'method' => 'post',
                'enctype' => 'multipart/form-data'
            )
        );

        $form->setUseContainer(true);
        $this->setForm($form);

        $fieldset = $form->addFieldset('display', array(
                'legend' => $helper->__('Config settings'),
                'class' => 'fieldset-wide'
            )
        );

        if ($configId) {
            $fieldset->addField('url', 'text', array(
                    'name' => 'url',
                    'label' => $helper->__('Config url'),
                    'value' => $helper->getConfigUrl($configId),
                    'readonly' => true
                )
            );
        }

        $fieldset->addField('name', 'text', array(
                'name' => 'name',
                'label' => $helper->__('Name'),
            )
        );

        $fieldset->addField('add_not_in_stock', 'checkbox', array(
                'name' => 'add_not_in_stock',
                'onclick' => 'this.value = this.checked ? 1 : 0;',
                'label' => $helper->__('Add not in stock items'),
                'checked' => $configModel->getAddNotInStock(),
            )
        );

        $fieldset->addField('is_active', 'checkbox', array(
                'name' => 'is_active',
                'onclick' => 'this.value = this.checked ? 1 : 0;',
                'label' => $helper->__('Is active'),
                'checked' => $configModel->getIsActive(),
            )
        );

        $fieldset->addField('min_qty', 'text', array(
                'name' => 'min_qty',
                'label' => $helper->__('Min QTY'),
            )
        );

        $fieldset->addField('min_price', 'text', array(
                'name' => 'min_price',
                'label' => $helper->__('Min price'),
            )
        );

        $fieldset->addField('max_price', 'text', array(
                'name' => 'max_price',
                'label' => $helper->__('Max price'),
            )
        );

        $fieldset->addField('bid', 'text', array(
                'name' => 'bid',
                'label' => $helper->__('bid'),
            )
        );

        $fieldset->addField('cbid', 'text', array(
                'name' => 'cbid',
                'label' => $helper->__('cbid'),
            )
        );

        $renderer = Mage::getBlockSingleton('qm_exportcatalog/adminhtml_template_element_renderer_treeview', 'renderer');

        $fieldset->addField('export_catalogs', 'hidden', array(
                'name' => 'export_catalogs',
                'label' => $helper->__('Catalogs'),
            )
        )->setRenderer($renderer);

        $fieldset->addField('id', 'hidden', array(
                'name' => 'id',
            )
        );

        $form->addValues(array(
                'name' => $configModel->getName(),
                'add_not_in_stock' => $configModel->getAddNotInStock(),
                'is_active' => $configModel->getIsActive(),
                'min_qty' => $configModel->getMinQty(),
                'min_price' => $configModel->getMinPrice(),
                'max_price' => $configModel->getMaxPrice(),
                'bid' => $configModel->getBid(),
                'cbid' => $configModel->getCbid(),
                'export_catalogs' => $configModel->getExportCatalogs(),
                'id' => $configModel->getId()
            )
        );

        return parent::_prepareForm();
    }
}