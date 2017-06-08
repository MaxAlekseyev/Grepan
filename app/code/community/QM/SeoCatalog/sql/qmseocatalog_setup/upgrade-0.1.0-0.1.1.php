<?php
$this->startSetup();
$attribute  = array(
    'group'                      => 'General Information',
    'label'                      => 'Alternative H1',
    'input'                      => 'text',
    'type'                       => 'varchar',
    'visible'                    => 1,
    'required'                   => false,
    'user_defined'               => 1,
    'global'                     => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
);
$this->addAttribute('catalog_category', 'alternative_h1', $attribute);
$this->endSetup();
