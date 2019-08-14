<?php
/*
 *  Created on April 4, 2012
 *  Author Ivan Proskuryakov - volgodark@gmail.com - ecommerceoffice.com
 *  Copyright Proskuryakov Ivan. ecommerceoffice.com © 2011. All Rights Reserved.
 *  Single Use, Limited Licence and Single Use No Resale Licence ["Single Use"]
 */
?>
<?php

class Magazento_Megabile_Model_Mysql4_Item extends Mage_Core_Model_Mysql4_Abstract {

    protected function _construct() {
        $this->_init('megabile/item', 'item_id');
    }

    protected function _beforeSave(Mage_Core_Model_Abstract $object) {
        $dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM);
        $object->setFromTime(Mage::getSingleton('core/date')->gmtDate());        
        return $this;
    }
    
    protected function _afterLoad(Mage_Core_Model_Abstract $object) {

        $attr = $object->getData('attributes');
        $object->setData('product_attributes', $attr);
        return parent::_afterLoad($object);
    }    
    
}