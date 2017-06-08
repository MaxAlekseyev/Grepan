<?php

class QM_ExportCatalog_Block_Adminhtml_Template_Element_Renderer_Treeview extends Mage_Adminhtml_Block_Catalog_Category_Tree
    implements Varien_Data_Form_Element_Renderer_Interface
{

    protected $_categoryIds;

    public function __construct()
    {
        $this->setUseAjax(false);
        $this->_withProductCount = true;
        $this->setTemplate('qm_exportcatalog/catalog/category/tree.phtml');
    }

    public function getTreeJson($parenNodeCategory = NULL)
    {
        $rootCategory = Mage::getModel('catalog/category')->load(1);

        $categoryArray = $this->_getCategoryJson($rootCategory);

        $json = Mage::helper('core')->jsonEncode(isset($categoryArray['children']) ? $categoryArray['children'] : array());
        return $json;
    }

    /**
     * Get JSON of a category
     *
     * @param $category
     * @return string
     */
    protected function _getCategoryJson($category)
    {
        $item         = array();
        $item['text'] = $category->getName();

        $item['id']    = $category->getId();

        $item['cls'] = 'folder ' . ($category->getIsActive() ? 'active-category' : 'no-active-category');

        $item['checked'] = $this->_isCategoryChecked($category->getId());

        if ($category->hasChildren()) {
            $item['children'] = array();
                foreach (Mage::getModel('catalog/category')->getCategories($category->getId()) as $child) {
                    $item['children'][] = $this->_getCategoryJson($child);
                }
        }

        if ($category->getLevel() < 2) {
            $item['expanded'] = true;
        }

        return $item;
    }

    protected function _isCategoryChecked($categoryId)
    {
        return in_array($categoryId, $this->_categoryIds);
    }

    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $this->_categoryIds = $this->_getCategoryIds($element);

        return $this->toHtml() . $element->getElementHtml();
    }

    protected function _getCategoryIds(Varien_Data_Form_Element_Abstract $element)
    {
        $json = $element->getValue();

        if (!$json) {
            return array();
        }

        return json_decode($json);
    }

    protected function _prepareLayout()
    {

    }

    protected function _isCategoryMoveable($node)
    {
        return false;
    }

}