<?php
class QM_LayeredNavigation_Model_Observer
{
	private $cacheableBlocks = array(
		'QM_LayeredNavigation_Block_Catalog_Layer_View',
		'QM_LayeredNavigation_Block_Catalog_Layer_View_Top',
		'QM_LayeredNavigation_Block_Top'
	);
	
	const CUSTOM_CACHE_LIFETIME = 3600;
	
    public function handleControllerFrontInitRouters($observer) 
    {
        $observer->getEvent()->getFront()
            ->addRouter('layerednavigation', new QM_LayeredNavigation_Controller_Router());
    }
    
    public function handleCatalogControllerCategoryInitAfter($observer)
    {
        if (!Mage::getStoreConfig('qm_layerednavigation/seo/urls'))
            return;
            
        $controller = $observer->getEvent()->getControllerAction();
        $cat = $observer->getEvent()->getCategory();
        
        if (!Mage::helper('qm_layerednavigation/url')->saveParams($controller->getRequest())){
            if ($cat->getId()  == Mage::app()->getStore()->getRootCategoryId()){
                $cat->setId(0);
                return;
            } 
            else { 
                // no way to tell controler to show 404, do it manually
                $controller->getResponse()->setHeader('HTTP/1.1','404 Not Found');
                $controller->getResponse()->setHeader('Status','404 File not found');
        
                $pageId = Mage::getStoreConfig(Mage_Cms_Helper_Page::XML_PATH_NO_ROUTE_PAGE);
                if (!Mage::helper('cms/page')->renderPage($controller, $pageId)) {
                    header('Location: /');
                    exit;
                }  
                $controller->getResponse()->sendResponse();
                exit;                
            }
        } 
        
        if ($cat->getDisplayMode() == 'PAGE' && Mage::registry('qm_layerednavigation_current_params')){
            $cat->setDisplayMode('PRODUCTS');
        }  
    }
    
    public function handleLayoutRender()
    {
        $layout = Mage::getSingleton('core/layout');
        if (!$layout)
            return;
            
        $isAJAX = Mage::app()->getRequest()->getParam('is_ajax', false);
        $isAJAX = $isAJAX && Mage::app()->getRequest()->isXmlHttpRequest();
        if (!$isAJAX)
            return;
            
        $layout->removeOutputBlock('root');    
        Mage::app()->getFrontController()->getResponse()->setHeader('content-type', 'application/json');
            
        $page = $layout->getBlock('product_list');
        if (!$page){
            $page = $layout->getBlock('search_result_list');
        }
        
        if (!$page)
            return; 
            
        $blocks = array();
        foreach ($layout->getAllBlocks() as $b){
            if (!in_array($b->getNameInLayout(), array('layerednavigation.navleft','layerednavigation.navtop','layerednavigation.navright', 'layerednavigation.top'))){
                continue;
            }
            $b->setIsAjax(true);
            $html = $b->toHtml();
            if (!$html && false !== strpos($b->getBlockId(), 'layerednavigation-filters-'))
            {
                // compatibility with "shopper" theme
                // @see catalog/layer/view.phtml
                $queldorei_blocks = Mage::registry('queldorei_blocks');
                if ($queldorei_blocks AND !empty($queldorei_blocks['block_layered_nav']))
                {
                    $html = $queldorei_blocks['block_layered_nav'];
                }
            }
            $blocks[$b->getBlockId()] = $this->_removeAjaxParam($html);                        
        }
        
        if (!$blocks)
            return;

        $container = $layout->createBlock('core/template', 'qm_layerednavigation_container');
        $container->setData('blocks', $blocks);
        $container->setData('page', $this->_removeAjaxParam($page->toHtml()));
        
        $layout->addOutputBlock('qm_layerednavigation_container', 'toJson');
    }
    
    protected function _removeAjaxParam($html)
    {
        $sep = Mage::getStoreConfig('qm_layerednavigation/seo/special_char');
        $html = str_replace('is' . $sep . 'ajax=1&amp;', '', $html);
        $html = str_replace('is' . $sep . 'ajax=1&', '', $html);
        $html = str_replace('is' . $sep . 'ajax=1', '', $html);
        
        $html = str_replace('___SID=U', '', $html);
        
        return $html;
    }
        
	public function customBlockCache(Varien_Event_Observer $observer)
	{
  		try {
   			$event = $observer->getEvent();
   			$block = $event->getBlock();
   			$class = get_class($block);
   			$url =  'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
   
		   if (!Mage::registry('qm_layerednavigation_cache_key')) {
		   		$patterns = array(		   		
		   			'/[&,?]p=(\d+)/',
		   			'/[&,?]limit=(\d+)/',
		   			'/[&,?]dir=(\w+)/',
		   			'/[&,?]order=(\w+)/',
		   		);
		   		$url = preg_replace($patterns, '', $url);
		   		if (strpos($url, '?') === false && strpos($url, '&') !== false) {
		   			$url = preg_replace('/&/', '?', $url, 1);
		   		}
		   		Mage::register('qm_layerednavigation_cache_key', $url);
		   }
		   
		   
		   foreach ($this->cacheableBlocks as $item) {
		   		if ($class == $item) {
					$block->setData('cache_lifetime', self::CUSTOM_CACHE_LIFETIME);
			    	$block->setData('cache_key',  $class . Mage::registry('qm_layerednavigation_cache_key'));
			    	$block->setData('cache_tags', array('layerednavigation_block_' . $class));
		   		}
		   }
		} catch (Exception $e) {
			Mage::logException($e);
		}
 	}    
}