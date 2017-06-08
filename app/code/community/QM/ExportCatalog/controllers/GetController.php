<?php

class QM_ExportCatalog_GetController extends Mage_Core_Controller_Front_Action
{
    public function fileAction()
    {
        $configId = $this->getRequest()->getParam('id');

        $exportDir      = Mage::helper('qm_exportcatalog')->getExportDir();
        $exportFileName = Mage::getModel('qm_exportcatalog/config')->load((int)$configId)->getFileName();
        $exportFilePath = $exportDir . '/' . $exportFileName;

        if (!$exportFileName || !file_exists($exportFilePath)) {
            $this->norouteAction();
            return;
        }

        $content = file_get_contents($exportFilePath);
        $this->prepareFileResponse('text/xml; charset=windows-1251', $content);
    }

    protected function prepareFileResponse($contentType, $content)
    {
        $this->getResponse()
            ->setHttpResponseCode(200)
            ->setHeader('Content-type', $contentType, true)
            ->setHeader('Last-Modified', date('r'));

        if (!is_null($content)) {
            $this->getResponse()->setBody($content);
        }
        return $this;
    }
} 