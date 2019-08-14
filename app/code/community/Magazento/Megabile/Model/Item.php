<?php
/*
 *  Created on April 4, 2012
 *  Author Ivan Proskuryakov - volgodark@gmail.com - ecommerceoffice.com
 *  Copyright Proskuryakov Ivan. ecommerceoffice.com © 2011. All Rights Reserved.
 *  Single Use, Limited Licence and Single Use No Resale Licence ["Single Use"]
 */
?>
<?php
class Magazento_Megabile_Model_Item extends Mage_Core_Model_Abstract
{
    const CACHE_TAG      = 'megabile_admin_item';
    protected $_cacheTag = 'megabile_admin_item';

    protected function _construct()
    {
        $this->_init('megabile/item');
    }
    
    


    public function prepareField($field) {
//      $field = htmlentities(trim($field));
//      $field = trim($field);
      return $field;
    }


    public function buildXML($profile) {
        
        $products               = explode(",",$profile->getProducts());
        $use_attributes         = $profile->getUseAttributes();
        $attributes             = explode(",",$profile->getAttributes());
        $description_field      = $profile->getDescriptionField();
        $root_category          = $profile->getRootCategory();
        $file                   = $profile->getFilename();
        $store_id               = $profile->getStoreId();
        $products_for_export    = $profile->getProductsForExport();
        
        if (!$products_for_export) {
            $products = Mage::getModel('megabile/data')->getProductIds($store_id);
        }

        // START EXPORT
        $io = new Varien_Io_File();
            $io->setAllowCreateFolders(true);
            $io->open(array('path' => Mage::getBaseDir() ));
            if ($io->fileExists($file) && !$io->isWriteable($file)) {
                Mage::throwException(Mage::helper('core')->__('File "%s" cannot be saved. Please, make sure the directory "%s" is writeable by web server.', $file, $this->getPath()));
            }

            $io->streamOpen($file);	
            $io->streamWrite('<?xml version="1.0"?>');
            $io->streamWrite('<xml date="'.date('Y-m-d H:i').'">');

                $io->streamWrite('<shop>' . "\n");

                    //CATEGORIES
                    $io->streamWrite('<categories>' . "\n");
                    $categories = Mage::getModel('catalog/category')
                                ->setStoreId($store_id)
                                ->getCollection()
                                ->addAttributeToSelect('*')
                                ;
                    foreach($categories as $category) {
                        $cat = Mage::getModel('catalog/category')->load($category->getId());
                        
                        if ($cat['name']) {
                            if ($cat['entity_id'] == $root_category) {
                                $cat['parent_id'] = 0;
                            }
                            $io->streamWrite('<category id="'.$cat['entity_id'].'" parentId="'.$cat['parent_id'].'" >'.$this->prepareField($cat['name']).'</category>' . "\n");                            
                        }

                        
                    }
                    $io->streamWrite('</categories>' . "\n");

                    //PRODUCTS
                    $io->streamWrite('<offers>'. "\n");
                    $i=0;
                        foreach ($products as $product_id) {

                            $cat_ids = 0;
                            $Product = Mage::getModel('catalog/product')->setStoreId($store_id)->load($product_id);
                            $parentIds = Mage::getResourceSingleton('catalog/product_type_configurable')->getParentIdsByChild($Product->getId());
                            $groupedParentId = $parentIds[0] ;


                            $price = $Product->getData('price');

                            if($groupedParentId)
                            {
                                $Product_parent = Mage::getModel('catalog/product')->setStoreId($store_id)->load($groupedParentId);
                                
//                                if($Product_parent->getImage() != 'no_selection') {
                                    $image = $Product_parent->getImageUrl();
//                                }
                              
                                $url = $Product_parent->getProductUrl();
                                $description = $Product_parent->getData($description_field);
                                $cat_ids = Mage::getResourceSingleton('catalog/product')->getCategoryIds($Product_parent);
                            } else {
//                                if($Product->getImage() != 'no_selection') {
                                    $image = $Product->getImageUrl();
//                                }         
                                
                                $url = $Product->getProductUrl();
                                $description = $Product->getData($description_field);
                                $cat_ids = Mage::getResourceSingleton('catalog/product')->getCategoryIds($Product);
                            }    
                            
                            if ($use_attributes) {
                                $extra_description='<br/><br/>';
                                foreach ($attributes as $attributeItem) {
                                    $value_label = $Product->getResource()->getAttribute($attributeItem)->setStoreId($store_id)->getFrontend()->getValue($Product);             
                                    $value_name = $Product->getResource()->getAttribute($attributeItem)->setStoreId($store_id)->getData('frontend_label');            
                                    if ($value_label && $value_name) $extra_description.= $value_name.": ".$value_label."<br/>";
                                }  
                                $description = $description.$extra_description;
                            }

                            

                            if (($cat_ids) && ($price > 0)) {     
                                $i++;                   
                                $cat = implode(',',$cat_ids);

                                $io->streamWrite("\t".'<offer id="'.$Product->getData('entity_id').'" >'. "\n");
                                    $io->streamWrite("\t\t".'<url>'.$url.'</url>'."\n");	
                                    $io->streamWrite("\t\t".'<price>'.$price.'</price>'."\n");	
                                    $io->streamWrite("\t\t".'<currencyId>RUR</currencyId>'."\n");	
                                    $io->streamWrite("\t\t<categoryId>\n");	
                                    foreach ($cat_ids as $cat_id) {
                                        $io->streamWrite("\t\t".'<cid>'.$cat_id.'</cid>'."\n");	
                                    }
                                        $io->streamWrite("\t\t".'<cid>'.$root_category.'</cid>'."\n");	
                                        
                                    $io->streamWrite("\t\t</categoryId>\n");	
                                    $io->streamWrite("\t\t".'<picture>'.$image.'</picture>'."\n");	
                                    $io->streamWrite("\t\t".'<name><![CDATA['.$this->prepareField($Product->getName()).']]></name>'."\n");	
                                    $io->streamWrite("\t\t".'<sku>'.$Product->getSku().'</sku>'."\n");
                                    $io->streamWrite("\t\t".'<description><![CDATA['.$this->prepareField($description).']]></description>'."\n");
                                $io->streamWrite('</offer>'. "\n");

                                //if ($i==10)break;

                            }
                        }						
                    $io->streamWrite('</offers>'. "\n");
            
                $io->streamWrite('</shop>'. "\n");
            $io->streamWrite('</xml>'. "\n");
            $io->streamClose();
            
            return $i;

        
//        var_dump($products);
//        var_dump($attributes);
//        var_dump($root_category);
//        var_dump($filename);
//        var_dump($store_id);
//        var_dump($products_for_export);
    }    
    
}
