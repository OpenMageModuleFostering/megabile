<?php

/*
 *  Created on Mar 16, 2011
 *  Author Ivan Proskuryakov - volgodark@gmail.com - Magazento.com
 *  Copyright Proskuryakov Ivan. Magazento.com © 2011. All Rights Reserved.
 *  Single Use, Limited Licence and Single Use No Resale Licence ["Single Use"]
 */
?>
<?php

class Magazento_Megabile_Model_Data {

    public function storeCategories4Form() {


        $collection = Mage::getModel('catalog/category')
                ->getCollection()
                ->addAttributeToSelect('*')
                ->addAttributeToSelect('name')
                ->addIsActiveFilter()
                ->addOrderField('path', 'ASC');
        
        $items = array();
        foreach ($collection as $value) {
            if ($value['name']) {
                $level = $value['level'] - 2;
                $label = @str_repeat("--", $level) . $value['name'];

                $v = array('label' => $label,
                    'value' => $value['entity_id']
                );
                array_push($items, $v);    
            }
        }
        return $items;
    }
    
    public function storeAttributes4Form() {

        $collection = Mage::getResourceModel('catalog/product_attribute_collection')->addVisibleFilter();
        $array = array();
        foreach ($collection as $item) {
            if ($item->getData('frontend_label')) {
                $data = array('value' => $item->getData('attribute_code'), 'label' => $item->getData('frontend_label'));
                array_push($array, $data);
            }
        }
        return $array;
    }
    
   
    public function getProductCollection($storeId) {
        $collection = Mage::getModel('catalog/product')->getCollection();

        Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($collection);
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
        $collection->addAttributeToFilter('small_image',array('notnull'=>'','neq'=>'no_selection'));
        
        $collection->addAttributeToSelect('*')
                ->addStoreFilter($storeId)
                ->addAttributeToFilter('price', array('gt' => 1))
                ->addAttributeToFilter('status', 1)   
                ->addAttributeToFilter('is_saleable', TRUE)
//                ->addAttributeToFilter('type_id', array('eq' => 'simple'))
//                ->addAttributeToFilter('type_id', array('neq' => 'configurable'))
                ->addAttributeToFilter('type_id', array('neq' => 'bundle'))
                ->addAttributeToFilter('type_id', array('neq' => 'grouped'))
                ->addAttributeToFilter('small_image', array('neq' => 'no_selection'))
                ; 
        
        return $collection;
    }
    
    
    public function getStoreProductsCount($storeId) {

        $collection = $this->getProductCollection($storeId);
        
        return $collection->getSize();
    }
    
    
 
    public function getAllProductsForStore($storeId) {
        $collection = $this->getProductCollection($storeId);
        return $collection->getSize();
    }
    
    public function getProductIds($storeId) {
        $collection = $this->getProductCollection($storeId);
        $array = array();
        foreach ($collection as $product) {
            $array[]=$product->getId();
        }
        return $array;
    }

}