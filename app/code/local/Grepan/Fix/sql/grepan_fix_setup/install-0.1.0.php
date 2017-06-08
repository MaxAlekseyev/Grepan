<?php

$this->startSetup();

$entityTypeId     = $this->getEntityTypeId('catalog_category');
$attributeSetId   = $this->getDefaultAttributeSetId($entityTypeId);
$attributeGroupId = $this->getDefaultAttributeGroupId($entityTypeId, $attributeSetId);

$this->removeAttribute('catalog_category', 'thumbnail');
$this->addAttribute('catalog_category', 'thumbnail', array(
    'type'              => 'varchar',
    'backend'           => 'catalog/category_attribute_backend_image',
    'frontend'          => '',
    'label'             => 'Thumbnail Image',
    'input'             => 'image',
    'class'             => '',
    'source'            => '',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'default'           => '',
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'visible_on_front'  => false,
    'unique'            => false,
    'group'             => "General Information",
    'sort_order'        => '5',
));

$this->endSetup();
