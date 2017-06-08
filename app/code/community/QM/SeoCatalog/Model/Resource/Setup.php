<?php

class QM_SeoCatalog_Model_Resource_Setup extends Mage_Eav_Model_Entity_Setup
{
    public function getDefaultEntities()
    {
        return array(
            'catalog_category'               => array(
                'entity_model'                   => 'catalog/category',
                'attribute_model'                => 'catalog/resource_eav_attribute',
                'table'                          => 'catalog/category',
                'additional_attribute_table'     => 'catalog/eav_attribute',
                'entity_attribute_collection'    => 'catalog/category_attribute_collection',
                'default_group'                  => 'General Information',
                'attributes'                     => array(
                    'cms_seo_block'       => array(
                        'type'                       => 'int',
                        'label'                      => 'CMS SEO Block',
                        'input'                      => 'select',
                        'source'                     => 'catalog/category_attribute_source_page',
                        'required'                   => false,
                        'sort_order'                 => 25,
                        'global'                     => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'group'                      => 'Display Settings',
                    ),
                )
            )
        );
    }
}