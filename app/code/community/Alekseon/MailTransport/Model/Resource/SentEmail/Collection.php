<?php
/**
 * @author    Marcin Frymark
 * @email     contact@alekseon.com
 * @company   Alekseon
 * @website   www.alekseon.com
 */
class Alekseon_MailTransport_Model_Resource_SentEmail_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('alekseon_mailTransport/sentEmail');
    }
    
    public function addFieldToFilter($field, $condition = null)
    {
        if ($field == 'receipient') {
            $correspondents = Mage::getModel('alekseon_mailTransport/sentEmail_correspondent')
                                ->getCollection()
                                ->addFieldToFilter('type', Alekseon_MailTransport_Model_SentEmail_Correspondent::TYPE_RECEIPIENT)
                                ->addFieldToFilter(array('name', 'email'), array($condition, $condition));
                                
            $sentEmailsIds = array();
            foreach($correspondents as $correspondent) {
                $sentEmailsIds[] = $correspondent->getSentemailId();
            }
                                
            $field = $this->getResource()->getIdFieldName();
            $condition = array('in' => $sentEmailsIds);
        }
        
        return parent::addFieldToFilter($field, $condition);
    }
}