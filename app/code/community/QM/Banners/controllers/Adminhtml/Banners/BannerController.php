<?php

/**
 * Banner admin controller
 *
 * @category    QM
 * @package     QM_Banners
 *
 */
class QM_Banners_Adminhtml_Banners_BannerController extends QM_Banners_Controller_Adminhtml_Banners
{
    const IMAGE_FOLDER = 'qm_banners';

    /**
     * init the banner
     *
     * @access protected
     * @return QM_Banners_Model_Banner
     */
    protected function _initBanner()
    {
        $bannerId = (int)$this->getRequest()->getParam('id');
        $banner   = Mage::getModel('qm_banners/banner');
        if ($bannerId) {
            $banner->load($bannerId);
        }
        Mage::register('current_banner', $banner);
        Mage::register('current_store', (int)$this->getRequest()->getParam('store'));
        return $banner;
    }

    /**
     * default action
     *
     * @access public
     * @return void
     *
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->_title(Mage::helper('qm_banners')->__('Banners'))
            ->_title(Mage::helper('qm_banners')->__('Banner'));
        $this->renderLayout();
    }

    /**
     * grid action
     *
     * @access public
     * @return void
     *
     */
    public function gridAction()
    {
        $this->loadLayout()->renderLayout();
    }

    /**
     * edit banner - action
     *
     * @access public
     * @return void
     *
     */
    public function editAction()
    {
        $bannerId = $this->getRequest()->getParam('id');
        $banner   = $this->_initBanner();
        if ($bannerId && !$banner->getId()) {
            $this->_getSession()->addError(
                Mage::helper('qm_banners')->__('This banner no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getBannerData(true);
        if (!empty($data)) {
            $banner->setData($data);
        }
        Mage::register('banner_data', $banner);
        $this->loadLayout();
        $this->_title(Mage::helper('qm_banners')->__('Banners'))
            ->_title(Mage::helper('qm_banners')->__('Banner'));
        if ($banner->getId()) {
            $this->_title($banner->getName());
        } else {
            $this->_title(Mage::helper('qm_banners')->__('Add banner'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new banner action
     *
     * @access public
     * @return void
     *
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * Update or create image data for default render
     *
     * @param $banner
     * @throws Exception
     */
    protected function _updateDefaultRender($banner)
    {
        $defRender = $banner->getDefaultRender();
        if (!$defRender->getId()) {
            $defRender = Mage::getModel('qm_banners/render');
            $defRender->setBannerId($banner->getId());
        }

        $imageCollection        = $banner->getAllImages();
        $oldDefRenderImagesData = new Varien_Object($defRender->getImageData());

        $newDefRenderImagesData = array();
        foreach ($imageCollection as $image) {
            $imageData            = array();
            $imageData['exclude'] = (bool)$oldDefRenderImagesData->getData($image->getImageId() . '/exclude');
            $imageData['alt']     = (string)$oldDefRenderImagesData->getData($image->getImageId() . '/alt');
            $imageData['offset']  = (string)$oldDefRenderImagesData->getData($image->getImageId() . '/offset');

            $newDefRenderImagesData[$image->getImageId()] = $imageData;
        }

        $defRender->setImages(Zend_Json::encode($newDefRenderImagesData));
        $defRender->save();
    }

    protected function _saveBannerRenders($banner, $data)
    {
        if ($data->getRenderStoreId() != 0) {
            $this->_updateDefaultRender($banner);
        }

        $render = $banner->getRealRenderForStore($data->getRenderStoreId());
        if (!$render->getId()) {
            $render->setBannerId($banner->getId());
            $render->setStoreId($data->getRenderStoreId());
        }

        $imageCollection = $banner->getAllImages();

        $renderImagesData = array();
        foreach ($imageCollection as $image) {
            $imageData                              = array();
            $imageData['exclude']                   = (bool)$data->getData('images/exclude-from-store/' . $image->getImageId());
            $imageData['alt']                       = $data->getData('images/alt/' . $image->getImageId());
            $imageData['offset']                    = $data->getData('images/offset/' . $image->getImageId());
            $renderImagesData[$image->getImageId()] = $imageData;
        }

        $render->setImages(Zend_Json::encode($renderImagesData));

        if ($data->getData('use_default_html_render')) {
            $render->setHtmlRender(QM_Banners_Model_Render::DEFAULT_STORE_RENDER_VALUE);
        } else {
            $render->setHtmlRender($data->getData('html_render'));
        }

        if ($data->getData('use_default_render_css')) {
            $render->setCssStyle(QM_Banners_Model_Render::DEFAULT_STORE_RENDER_VALUE);
        } else {
            $render->setCssStyle($data->getData('render_css'));
        }

        $render->save();
    }

    /**
     * Upload image for render
     * @param $render
     * @param $key
     * @return string
     * @throws Exception
     */
    protected function _uploadImage($key)
    {
        $uploader = new Varien_File_Uploader(
            array(
                'name' => $_FILES['banner']['name']['images']['image'][$key],
                'type' => $_FILES['banner']['type']['images']['image'][$key],
                'tmp_name' => $_FILES['banner']['tmp_name']['images']['image'][$key],
                'error' => $_FILES['banner']['error']['images']['image'][$key],
                'size' => $_FILES['banner']['size']['images']['image'][$key]
            )
        );

        $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png')); // or pdf or anything

        $uploader->setAllowRenameFiles(true);

        $uploader->setFilesDispersion(false);

        $path = Mage::getBaseDir('media') . DS . self::IMAGE_FOLDER . DS;

        $saveResult = $uploader->save($path, $_FILES['banner']['name']['images']['image'][$key]);

        return self::IMAGE_FOLDER . DS . $saveResult['file'];
    }

    /**
     * @param $banner
     * @param $data
     */
    protected function _saveBannerImages($banner, $data)
    {
        if (is_array($data->getData('images/image_id'))) {
            foreach ($data->getData('images/image_id') as $imageId) {
                if ($data->getData('images/remove/' . $imageId)) {
                    continue;
                }
                $image = Mage::getModel('qm_banners/image')->load($data->getData('images/id/' . $imageId));

                if (!$image->getId()) {
                    $image->setBannerId($banner->getId());
                    $image->setImageId($imageId);
                }

                if ($_FILES['banner']['name']['images']['image'][$imageId]) {
                    $originPath = $this->_uploadImage($imageId);
                    $image->setOriginPath($originPath);
                }

                $image->save();
            }
        }

        if (is_array($data->getData('images/remove'))) {
            foreach ($data->getData('images/remove') as $imageId => $temp) {
                $image = Mage::getModel('qm_banners/image')->load($data->getData('images/id/' . $imageId));

                if ($image->getId()) {
                    $image->delete();
                }
            }
        }
    }

    /**
     * save banner - action
     *
     * @access public
     * @return void
     *
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('banner')) {
            try {
                $banner = $this->_initBanner();
                $banner->addData($data);
                $banner->save();
                $dataWrapper = new Varien_Object($data);
                $this->_saveBannerImages($banner, $dataWrapper);
                $this->_saveBannerRenders($banner, $dataWrapper);

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('qm_banners')->__('Banner was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $banner->getId(), 'store' => $dataWrapper->getRenderStoreId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setBannerData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('qm_banners')->__('There was a problem saving the banner.')
                );
                Mage::getSingleton('adminhtml/session')->setBannerData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('qm_banners')->__('Unable to find banner to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete banner - action
     *
     * @access public
     * @return void
     *
     */
    public function deleteAction()
    {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $banner = Mage::getModel('qm_banners/banner');
                $banner->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('qm_banners')->__('Banner was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('qm_banners')->__('There was an error deleting banner.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('qm_banners')->__('Could not find banner to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete banner - action
     *
     * @access public
     * @return void
     *
     */
    public function massDeleteAction()
    {
        $bannerIds = $this->getRequest()->getParam('banner');
        if (!is_array($bannerIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('qm_banners')->__('Please select banner to delete.')
            );
        } else {
            try {
                foreach ($bannerIds as $bannerId) {
                    $banner = Mage::getModel('qm_banners/banner');
                    $banner->setId($bannerId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('qm_banners')->__('Total of %d banner were successfully deleted.', count($bannerIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('qm_banners')->__('There was an error deleting banner.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass status change - action
     *
     * @access public
     * @return void
     *
     */
    public function massStatusAction()
    {
        $bannerIds = $this->getRequest()->getParam('banner');
        if (!is_array($bannerIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('qm_banners')->__('Please select banner.')
            );
        } else {
            try {
                foreach ($bannerIds as $bannerId) {
                    $banner = Mage::getSingleton('qm_banners/banner')->load($bannerId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d banner were successfully updated.', count($bannerIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('qm_banners')->__('There was an error updating banner.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * export as csv - action
     *
     * @access public
     * @return void
     *
     */
    public function exportCsvAction()
    {
        $fileName = 'banner.csv';
        $content  = $this->getLayout()->createBlock('qm_banners/adminhtml_banner_grid')
            ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as MsExcel - action
     *
     * @access public
     * @return void
     *
     */
    public function exportExcelAction()
    {
        $fileName = 'banner.xls';
        $content  = $this->getLayout()->createBlock('qm_banners/adminhtml_banner_grid')
            ->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as xml - action
     *
     * @access public
     * @return void
     *
     */
    public function exportXmlAction()
    {
        $fileName = 'banner.xml';
        $content  = $this->getLayout()->createBlock('qm_banners/adminhtml_banner_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Check if admin has permissions to visit related pages
     *
     * @access protected
     * @return boolean
     *
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('promo/qm_banners/banner');
    }
}
