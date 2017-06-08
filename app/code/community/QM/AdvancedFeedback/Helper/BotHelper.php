<?php

class QM_AdvancedFeedback_Helper_BotHelper extends Mage_Core_Helper_Abstract
{
    const MIN_FIELDS                                 = 7;
    const MAX_FIELDS                                 = 10;
    const CHARACTERS                                 = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const AFTER_ELEMENT_HTML_NAME_PLACEHOLDER_REGEXP = '/\{\{name\}\}/';
    const SESSION_DATA_KEY                           = 'qm_protect_from_bots';

    protected $_sessionFormData;

    /**
     * @param int $length
     * @return string
     */
    protected function _generateRandomString($length = 5)
    {
        $characters       = self::CHARACTERS;
        $charactersLength = strlen($characters);

        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * Replace name placeholder to new name
     *
     * @param $html
     * @param $name
     * @return string
     */
    protected function _proceedAfterElementHtml($html, $name)
    {
        if (!$html) {
            return '';
        }
        return preg_replace(self::AFTER_ELEMENT_HTML_NAME_PLACEHOLDER_REGEXP, $name, $html);
    }

    /**
     * Generate new field with random name
     *
     * @param $field
     * @return Varien_Object
     */
    protected function _getNewField($field)
    {
        $name = $this->_generateRandomString();

        return new Varien_Object(array(
                                     'name' => $name,
                                     'label' => $field->getLabel(),
                                     'after_element_html' => $this->_proceedAfterElementHtml($field->getAfterElementHtml(), $name),
                                     'additional' => $field->getAdditional()
                                 )
        );
    }


    /**
     * @param $field
     * @param $newFieldsCollection
     * @return array fake field names
     */
    protected function _generateFakeFields($field, &$newFieldsCollection)
    {
        $fakeFieldsNames = array();
        //add original field
        array_splice(
            $newFieldsCollection,
            rand(0, count($newFieldsCollection) - 1),
            0,
            array(new Varien_Object(array(
                'name' => $field->getName(),
                'label' => $field->getLabel(),
                'after_element_html' => $this->_proceedAfterElementHtml($field->getAfterElementHtml(), $field->getName()),
                'additional' => str_replace('required-enry', '', $field->getData('additional/class')),
                'for_bot' => $field->getForBot()
            )))
        );

        array_push($fakeFieldsNames, $field->getName());
        /*$fieldCount = rand(self::MIN_FIELDS, self::MAX_FIELDS);
        while (--$fieldCount) {
            array_push($newFieldsCollection, $this->_getNewField($field));
        }*/
        return $fakeFieldsNames;
    }

    /**
     * @param $formName
     * @param $formName
     * @return Varien_Object data from session
     * @throws Exception
     */
    protected function _getSessionFormData($formName, $formName)
    {
        if (!$this->_sessionFormData) {
            $session = Mage::getSingleton("core/session");

            $sessionData = $session->getData(self::SESSION_DATA_KEY);
            if (!is_array($sessionData) || !isset($sessionData[$formName])) {
                throw new Exception('No session data');
            }

            $this->_sessionFormData = new Varien_Object($sessionData[$formName]);
        }

        return $this->_sessionFormData;
    }

    /**
     * @param $formData
     * @param $formName
     * @param $inputName
     */
    public function getInputValue($formData, $formName, $inputName)
    {
        $sessionFormData = $this->_getSessionFormData($formData, $formName);

        return $sessionFormData('name_relations/' . $inputName);
    }

    /**
     * @param $formData
     * @param $formName
     */
    public function decodeData($formData, $formName)
    {
        $sessionFormData      = $this->_getSessionFormData($formData, $formName);
        $fakeFieldsCollection = $sessionFormData->getFakeFields();

        foreach ($fakeFieldsCollection as $fakeField) {
            unset($formData[$fakeField]);
        }

        $nameRelations = $sessionFormData->getNameRelations();

        $decodedFormData = array();
        $formData        = new Varien_Object($formData);
        foreach ($nameRelations as $name => $fakeName) {
            $value = $formData->getData($fakeName);

            $decodedFormData[$name] = $value;
        }

        return $decodedFormData;
    }


    /**
     * @param $formData Varien_Object
     * @param $formName
     * @throws Exception if fields not generated
     */
    public function checkIsBot($formData, $formName)
    {
        $sessionFormData = $this->_getSessionFormData($formData, $formName);

        $formData             = new Varien_Object($formData);
        $fakeFieldsCollection = $sessionFormData->getFakeFields();

        foreach ($fakeFieldsCollection as $fakeField) {
            if ($formData->getData($fakeField)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $fieldsCollection array(array(array(name, label, after_element_html{use {{name}}, it will be replaced to new name}, additional(class))))
     * @param $formName string unique form name
     * @return array array(Varien_Object[name, label, after_element_html, additional(class)])
     */
    public function encodeFields($fieldsCollection, $formName)
    {
        $newFieldsCollection = array();
        $fieldNameRelations  = array();
        $fakeFieldNames      = array();

        foreach ($fieldsCollection as $field) {
            $field    = new Varien_Object($field);
            $newField = $this->_getNewField($field);

            $fieldNameRelations[$field->getName()] = $newField->getName();

            array_push($newFieldsCollection, $newField);

            $fieldFakeFieldsNames = $this->_generateFakeFields($field, $newFieldsCollection);
            $fakeFieldNames       = array_merge($fakeFieldNames, $fieldFakeFieldsNames);
        }

        $session = Mage::getSingleton("core/session");

        $sessionData = $session->getData(self::SESSION_DATA_KEY);
        if (!is_array($sessionData)) {
            $sessionData = array();
        }

        $sessionData[$formName] = array(
            'name_relations' => $fieldNameRelations,
            'fake_fields' => $fakeFieldNames
        );

        $session->setData(self::SESSION_DATA_KEY, $sessionData);

        return $newFieldsCollection;
    }
}
