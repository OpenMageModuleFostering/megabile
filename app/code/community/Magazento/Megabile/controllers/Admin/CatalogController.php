<?php
/*
 *  Created on Dec 6, 2012
 *  Author Ivan Proskuryakov - volgodark@gmail.com - Magazento.com
 *  Copyright Proskuryakov Ivan. Magazento.com © 2012. All Rights Reserved.
 *  Single Use, Limited Licence and Single Use No Resale Licence ["Single Use"]
 */
?>
<?php

class Magazento_Megabile_Admin_CatalogController extends Mage_Adminhtml_Controller_Action {

    protected function _initAction() {
        
        if (Mage::helper('megabile')->versionUseAdminTitle()) {
            $this->_title($this->__('MEGABILE'));
        }
        
        
        $this->loadLayout()
                ->_setActiveMenu('megabile')
                ->_addBreadcrumb(Mage::helper('megabile')->__('megabile'), Mage::helper('megabile')->__('megabile'))
                ->_addBreadcrumb(Mage::helper('megabile')->__('megabile Items'), Mage::helper('megabile')->__('megabile Items'))
        ;
        return $this;
    }
    
    

    public function saveAction() {
         $id = $this->getRequest()->getParam('id');
         if ($id) {
//            try {
                $profile = Mage::getModel('megabile/item')->load($id);
                $count = Mage::getModel('megabile/item')->buildXML($profile);
                Mage::getSingleton('adminhtml/session')->addSuccess($count .' products has been exported');
//            } catch (Exception $e) {
//                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
//            }                 
            
            
         }
        $this->_redirect('*/admin_item/index', array('item_id' => $id));
        return;         
    }
}