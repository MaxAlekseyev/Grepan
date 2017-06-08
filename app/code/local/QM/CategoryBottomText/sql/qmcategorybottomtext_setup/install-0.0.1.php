<?php
$this->startSetup();
$this->addAttribute(Mage_Catalog_Model_Category::ENTITY, 'bottom_text', array(
    'group'         => 'General Information',
    'input'         => 'textarea',
    'type'          => 'text',
    'label'         => 'Bottom SEO Text',
    'backend'       => '',
    'visible'       => true,
    'required'      => false,
    'wysiwyg_enabled' => true,
    'visible_on_front' => true,
    'is_html_allowed_on_front' => true,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
));

$this->endSetup();