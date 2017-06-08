<?php
/**
 * QM Observer
 */

class QM_Pagelinks_Model_Observer
{
    public function httpResponseSendBefore($observer)
    {
        /* If it's ajax request exit */
        $request = Mage::app()->getRequest();
        if ($request->isXmlHttpRequest()) {
            return true;
        }

        /* get response html */
        $response = $observer->getResponse();
        $html = $response->getBody();

        /*get current url and prepare it */
        $currentUrl = Mage::helper('core/url')->getCurrentUrl();
        $currentUrl = str_replace('/', '\\/', $currentUrl);

        /* try remove href */
        try {
            $html = preg_replace(
                '/(href="'.$currentUrl.'")/',
                '',
                $html
            );
            $response->setBody($html);
        } catch (Exception $e) {
            Mage::log($e->getMessage(), null, 'qm_pagelinks.log');
        }
    }
}