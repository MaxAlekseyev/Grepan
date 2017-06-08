<?php

/**
 * Banners default helper
 *
 * @category    QM
 * @package     QM_Banners
 *
 */
class QM_Banners_Helper_Data extends Mage_Core_Helper_Abstract
{
    const IMAGE_BY_INDEX_REGEXP = '/{{image\[(\d)\]}}/';
    const ALT_BY_INDEX_REGEXP = '/{{alt\[(\d)\]}}/';

    const FOREACH_BODY_REGEXP = '/{{foreach}}(.*){{endforeach}}/s';

    const FOREACH_ALT_INDEX_REGEXP = '/{{alt\[i\]}}/';
    const FOREACH_IMAGE_INDEX_REGEXP = '/{{image\[i\]}}/';
    /**
     * convert array to options
     *
     * @access public
     * @param $options
     * @return array
     *
     */
    public function convertOptions($options)
    {
        $converted = array();
        foreach ($options as $option) {
            if (isset($option['value']) && !is_array($option['value']) &&
                isset($option['label']) && !is_array($option['label'])) {
                $converted[$option['value']] = $option['label'];
            }
        }
        return $converted;
    }
}
