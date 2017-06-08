<?php
/**
 * @author    Marcin Frymark
 * @email     contact@alekseon.com
 * @company   Alekseon
 * @website   www.alekseon.com
 */
class Alekseon_MailTransport_Model_Resource_SentEmail_Correspondent_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('alekseon_mailTransport/sentEmail_correspondent');
    }

    /*
     * methods addFieldToFilter, _translateCondition have been copied from Varien_Data_Collection_Db from magento 1.9.0.1, 
     * to have OR conditions in filters in older versions of magento
     */
    public function addFieldToFilter($field, $condition = null)
    {
        if (!is_array($field)) {
            $resultCondition = $this->_translateCondition($field, $condition);
        } else {
            $conditions = array();
            foreach ($field as $key => $currField) {
                $conditions[] = $this->_translateCondition(
                    $currField,
                    isset($condition[$key]) ? $condition[$key] : null
                );
            }

            $resultCondition = '(' . join(') ' . Zend_Db_Select::SQL_OR . ' (', $conditions) . ')';
        }

        $this->_select->where($resultCondition);

        return $this;
    }

    protected function _translateCondition($field, $condition)
    {
        $field = $this->_getMappedField($field);
        return $this->_getConditionSql($field, $condition);
    }
}