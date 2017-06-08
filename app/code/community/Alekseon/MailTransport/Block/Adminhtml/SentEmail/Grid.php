<?php
/**
 * @author    Marcin Frymark
 * @email     contact@alekseon.com
 * @company   Alekseon
 * @website   www.alekseon.com
 */
class Alekseon_MailTransport_Block_Adminhtml_SentEmail_Grid extends Mage_Adminhtml_Block_Widget_Grid
{ 

   public function __construct()
    {
        parent::__construct();
        $this->setId('sent_email_grid');
        $this->setSaveParametersInSession(true);   
        $this->setUseAjax(true);
        $this->setDefaultSort('sent_at');
    }

	protected function _prepareColumns()
    {
	    $this->addColumn('sent_at', 
			array(
				'header' => Mage::helper('alekseon_mailTransport')->__('Sent At'),
				'index'  => 'sent_at',
                'type' => 'datetime',
                'width' => '100px',
            )
        );
    
        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', 
                array(
                    'header' => Mage::helper('alekseon_mailTransport')->__('Sent From (Store)'),
                    'index'  => 'store_id',
                    'type'      => 'store',
                    'display_deleted' => true,
                )
            );
        }

	    $this->addColumn('receipient', 
			array(
				'header'   => Mage::helper('alekseon_mailTransport')->__('Receipient'),
				'index'    => 'receipient',
                'renderer' => 'alekseon_mailTransport/adminhtml_sentEmail_grid_renderer_receipient',
                'sortable' => false,
            )
        );

	    $this->addColumn('subject', 
			array(
				'header' => Mage::helper('alekseon_mailTransport')->__('Subject'),
				'index'  => 'subject',
            )
        );
        
	    $this->addColumn('transport_type', 
			array(
				'header' => Mage::helper('alekseon_mailTransport')->__('Transport Type'),
				'index'  => 'transport_type',
                'type'   => 'options',
                'options' => Mage::getSingleton('alekseon_mailTransport/system_config_source_mailTransportTypes')->toOptionArray(true),
            )
        );
        
	    $this->addColumn('status', 
			array(
				'header' => Mage::helper('alekseon_mailTransport')->__('Status'),
				'index'  => 'status',
                'type'   => 'options',
                'options'   => array(0 => $this->__('Failed'), 1 => $this->__('Success')),
                'frame_callback' => array($this, 'decorateStatus')
            )
        );

		return parent::_prepareColumns();
	}

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('alekseon_mailTransport/sentEmail')->getCollection();
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    public function getRowUrl($item)
    {
        return $this->getUrl('*/*/view', array('id' => $item->getId()));
    }
    
    public function decorateStatus($value, $row, $column, $isExport)
    {
        if ($row->getStatus()) {
            $cell = '<span class="grid-severity-notice"><span>'.$value.'</span></span>';
        } else {
            $cell = '<span class="grid-severity-critical"><span>'.$value.'</span></span>';
        }
        return $cell;
    }
}
