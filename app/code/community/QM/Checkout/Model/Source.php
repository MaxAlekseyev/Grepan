<?php

class QM_Checkout_Model_Source
{
    /**
     * Constants for password types
     */
    const PASSWORD_FIELD    = 'field';
    const PASSWORD_GENERATE = 'generate';
    const PASSWORD_PHONE    = 'phone';

    /**
     * Constants for location types
     */
    const LOCATION_ONE = 'one';
    const LOCATION_FEW = 'few';

    /**
     * Constants for checkbox types
     */
    const CHECKBOX_UNVISIBLE_ACTIVE    = 'unvisible_not_active';
    const CHECKBOX_UNVISIBLE_NOTACTIVE = 'unvisible_active';
    const CHECKBOX_UNCHECKED           = 'unchecked';
    const CHECKBOX_CHECKED             = 'checked';

    /**
     * Constants for dependent sections
     */
    const SECTION_NONE     = "";
    const SECTION_TOTALS   = 'totals';
    const SECTION_SHIPPING = 'shipping';
    const SECTION_PAYMENT  = 'payment';

    /**
     * Return a list of password types
     *
     * @return array
     */
    public function getPasswordTypes()
    {
        return array(
            self::PASSWORD_FIELD     => Mage::helper('qmcheckout')->__('Password Field on Checkout'),
            self::PASSWORD_PHONE     => Mage::helper('qmcheckout')->__('Password as Telephone Field'),
            self::PASSWORD_GENERATE  => Mage::helper('qmcheckout')->__('Auto Generate Password')
        );
    }

    /**
     * Return a list of location types
     *
     * @return array
     */
    public function getLocationTypes()
    {
        return array(
            self::LOCATION_ONE => Mage::helper('qmcheckout')->__('Country, Region, City as One Field'),
            self::LOCATION_FEW => Mage::helper('qmcheckout')->__('Country, Region, City as Different Fields')
        );
    }

    /**
     * Return a list of checkbox types
     *
     * @return array
     */
    public function getCheckboxTypes()
    {
        return array(
            self::CHECKBOX_UNVISIBLE_ACTIVE    => Mage::helper('qmcheckout')->__('Not Visible, Active'),
            self::CHECKBOX_UNVISIBLE_NOTACTIVE => Mage::helper('qmcheckout')->__('Not Visible, Not Active'),
            self::CHECKBOX_UNCHECKED           => Mage::helper('qmcheckout')->__('Visible, Unchecked'),
            self::CHECKBOX_CHECKED             => Mage::helper('qmcheckout')->__('Visible, Checked')
        );
    }

    /**
     * Return dependent sections of shipping method
     *
     * @return array
     */
    public function getShippingDependentSections()
    {
        return array(
            self::SECTION_NONE       => Mage::helper('qmcheckout')->__('Nothing'),
            self::SECTION_TOTALS     => Mage::helper('qmcheckout')->__('Totals'),
            self::SECTION_TOTALS . ',' . self::SECTION_PAYMENT => Mage::helper('qmcheckout')->__('Payment Methods, Totals')
        );
    }

    /**
     * Return dependent sections of payment method
     *
     * @return array
     */
    public function getPaymentDependentSections()
    {
        return array(
            self::SECTION_NONE       => Mage::helper('qmcheckout')->__('Nothing'),
            self::SECTION_TOTALS     => Mage::helper('qmcheckout')->__('Totals'),
            self::SECTION_TOTALS . ',' . self::SECTION_SHIPPING => Mage::helper('qmcheckout')->__('Shipping Methods, Totals')
        );
    }
}
