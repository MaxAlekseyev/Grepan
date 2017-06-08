<?php
/**
 * @author    Marcin Frymark
 * @email     contact@alekseon.com
 * @company   Alekseon
 * @website   www.alekseon.com
 */
class Alekseon_MailTransport_Block_Adminhtml_SentEmail_Grid_Renderer_Receipient extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    protected $_receipients;

    public function render(Varien_Object $row)
    {
        if (is_null($this->_receipients)) {
            $this->_receipients = array();
            $sentMailsIds = $this->getColumn()->getGrid()->getCollection()->getAllIds();
            $receipients = Mage::getModel('alekseon_mailTransport/sentEmail_correspondent')
                            ->getCollection()
                            ->addFieldToFilter('type', Alekseon_MailTransport_Model_SentEmail_Correspondent::TYPE_RECEIPIENT)
                            ->addFieldToFilter('sentemail_id', array('in' => $sentMailsIds));
            foreach($receipients as $receipient) {
                $this->_receipients[$receipient->getSentemailId()][] = $receipient->getName() . ' &lt;' . $receipient->getEmail() . '&gt;';
            }
        }

        if (isset($this->_receipients[$row->getId()])) {
            return implode('<br/>', $this->_receipients[$row->getId()]);
        }
        return;
    }
}
