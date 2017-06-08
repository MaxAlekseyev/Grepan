<?php

$this->startSetup();
$this->getConnection()
    ->addColumn($this->getTable('qmoneclickorder/order'),
                'email',
                array(
                    'comment'  => 'Email',
                    'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
                    'nullable' => false,
                    'length'   => 255,
                )
    );

$this->removeAttribute('catalog_product', 'enable_one_click_order');
$this->addAttribute(
    'catalog_product',
    'enable_one_click_order',
    array(
        'group'                      => 'Prices',
        'frontend'                   => '',
        'class'                      => '',
        'label'                      => 'Enable one click order',
        'input'                      => 'select',
        'type'                       => 'int',
        'source'                     => 'eav/entity_attribute_source_boolean',
        'global'                     => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
        'is_visible'                 => 1,
        'required'                   => 0,
        'searchable'                 => 0,
        'filterable'                 => 0,
        'unique'                     => 0,
        'comparable'                 => 0,
        'visible_on_front'           => 0,
        'user_defined'               => 1,
        'visible_in_advanced_search' => false,
        'is_filtrable'               => 0,
        'is_filterable_in_search'    => 0,
    )
);
$this->endSetup();
