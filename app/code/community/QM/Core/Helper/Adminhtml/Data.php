<?php

class QM_Core_Helper_Adminhtml_Data extends Mage_Adminhtml_Helper_Data
{
    public function removeTags($html)
    {
        if (version_compare(phpversion(), '5.5.0', '>=')) {
            $html = preg_replace_callback("# <(?![/a-z]) | (?<=\s)>(?![a-z]) #xi",
                function ($matches) {
                    return htmlentities($matches);
                },
                $html
            );
            $html = strip_tags($html);
            return htmlspecialchars_decode($html);
        } else {
            return parent::removeTags($html);
        }
    }
}