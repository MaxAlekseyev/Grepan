<?php

class QM_LayeredNavigation_Block_Catalog_Layer_Filter_Attribute extends Mage_Catalog_Block_Layer_Filter_Attribute
{
    const CSS_LABELONLY = 'layerednavigation_labelonly';
    const CSS_IMAGEONLY = 'layerednavigation_imagesonly';
    const CSS_IMAGELABEL = 'layerednavigation_imagelabel';
    const CSS_DROPDOWN = 'layerednavigation_dropdown';
    const CSS_TWOCOLUMN = 'layerednavigation_twocolumn';
    const CSS_CHOSERIMAGEONLY = 'layerednavigation_chooserimageonly';
    const CSS_CHOOSER_IMAGELABEL = 'layerednavigation_chooserimagelabel';

    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('qm/layerednavigation/attribute.phtml');
    }

    public function getItemsAsArray()
    {
        $items = array();
        foreach (parent::getItems() as $itemObject) {

            $item        = array();
            $item['url'] = $this->htmlEscape($itemObject->getUrl());

            if ($this->getSingleChoice()) {
                /** sinse @version 1.3.0 */
                $query       = array(
                    $this->getRequestValue()                                     => $itemObject->getIsSelected() ? null : $itemObject->getOptionId(),
                    Mage::getBlockSingleton('page/html_pager')->getPageVarName() => null
                );
                $item['url'] = Mage::helper('qm_layerednavigation/url')->getFullUrl($query);
            }
            $item['label'] = $itemObject->getLabel();
            $item['descr'] = $itemObject->getDescr();

            $item['count']      = '';
            $item['countValue'] = $itemObject->getCount();
            if (!$this->getHideCounts()) {
                $item['count'] = ' (' . $itemObject->getCount() . ')';
            }

            $item['image'] = '';
            if ($itemObject->getImage()) {
                $item['image'] = Mage::getBaseUrl('media') . 'layerednavigation/' . $itemObject->getImage();
            }

            if ($itemObject->getImageHover()) {
                $item['image_hover'] = Mage::getBaseUrl('media') . 'layerednavigation/' . $itemObject->getImageHover();
            }

            switch ($this->getDisplayType()) {
                case 0:
                    $item['css'] = self::CSS_LABELONLY;
                    break;
                case 1:
                    $item['css'] = self::CSS_IMAGEONLY;
                    break;
                case 2:
                    $item['css'] = self::CSS_IMAGELABEL;
                    break;
                case 3:
                    $item['css'] = self::CSS_DROPDOWN;
                    break;
                case 4:
                    $item['css'] = self::CSS_TWOCOLUMN;
                    break;
                case 5:
                    $item['css'] = self::CSS_CHOSERIMAGEONLY;
                    break;
                case 6:
                    $item['css'] = self::CSS_CHOOSER_IMAGELABEL;
                    break;
            }

            if ($itemObject->getIsSelected()) {
                $item['css'] .= '-selected';
                if (3 == $this->getDisplayType()) { //dropdown
                    $item['css'] = 'selected';
                }
            }

            if ($this->getSeoRel()) {
                $item['css'] .= '" rel="nofollow';
            }

            $item['chooser_img_url'] = Mage::helper('qm_layerednavigation/attributes')->getUrlFromChooser($itemObject);
            $item['chooser_img_hex'] = Mage::helper('qm_layerednavigation/attributes')->getHexFromChooser($itemObject);

            $items[] = $item;
        }

        $sortBy    = $this->getSortBy();
        $functions = array(1 => '_sortByName', 2 => '_sortByCounts');
        if (isset($functions[$sortBy])) {
            usort($items, array($this, $functions[$sortBy]));
        }

        // add less/more
        $max = $this->getMaxOptions();
        $i   = 0;
        foreach ($items as $k => $item) {
            $style = '';
            if ($max && (++$i > $max)) {
                $style = 'style="display:none" class="layerednavigation-attr-' . $this->getRequestValue() . '"';
            }
            $items[$k]['style'] = $style;
        }
        $this->setShowLessMore($max && ($i > $max));

        return $items;
    }

    public function _sortByName($a, $b)
    {
        $x = $a['label'];
        $y = $b['label'];
        if (is_numeric($x) && is_numeric($y)) {
            if ($x == $y) {
                return 0;
            }

            return ($x < $y ? 1 : -1);
        } else {
            return strcmp($x, $y);
        }
    }

    public function _sortByCounts($a, $b)
    {
        if ($a['countValue'] == $b['countValue']) {
            return 0;
        }

        return ($a['countValue'] < $b['countValue'] ? 1 : -1);
    }

    public function getRequestValue()
    {
        return $this->_filter->getAttributeModel()->getAttributeCode();
    }

    public function getItemsCount()
    {
        $cnt     = parent::getItemsCount();
        $showAll = !Mage::getStoreConfig('qm_layerednavigation/general/hide_one_value');

        return ($cnt > 1 || $showAll) ? $cnt : 0;
    }

    public function getRemoveUrl()
    {
        $query = array(
            $this->getRequestValue()                                     => null,
            Mage::getBlockSingleton('page/html_pager')->getPageVarName() => null // exclude current page from urls
        );

        $url = Mage::helper('qm_layerednavigation/url')->getFullUrl($query);

        return $url;
    }

    public function getAttrClass()
    {
        return Mage::helper('qm_layerednavigation/attributes')->getFilterCssClass($this->_filter);
    }
}