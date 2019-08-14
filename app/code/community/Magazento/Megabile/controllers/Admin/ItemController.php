<?php
/*
 *  Created on Dec 6, 2012
 *  Author Ivan Proskuryakov - volgodark@gmail.com - Magazento.com
 *  Copyright Proskuryakov Ivan. Magazento.com © 2012. All Rights Reserved.
 *  Single Use, Limited Licence and Single Use No Resale Licence ["Single Use"]
 */
?>
<?php

class Magazento_Megabile_Admin_ItemController extends Mage_Adminhtml_Controller_Action {

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
    
    
    /**
     * Related part
     */    
    public function relatedAction() {
        
        $this->loadLayout();
        $this->getLayout()->getBlock('megabile.related.grid');
        $this->renderLayout();
    }

    public function relatedgridAction() {

        $this->loadLayout();
        $this->getLayout()->getBlock('megabile.related.grid');
        $this->renderLayout();
    }
    
    
    public function indexAction() {

        $this->_initAction()
                ->_addContent($this->getLayout()->createBlock('megabile/admin_item'))
                ->renderLayout();
    }
    
    public function newAction() {
        $this->_forward('edit');
    }

    public function editAction() {
        if (Mage::helper('megabile')->versionUseAdminTitle()) {
            $this->_title($this->__('megabile'));
        }
        $id = $this->getRequest()->getParam('item_id');
        $model = Mage::getModel('megabile/item');
	
		
        
				
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('megabile')->__('This item no longer exists'));
                $this->_redirect('*/*/');
                return;
            }
        } 
        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }
                
        Mage::register('megabile_item', $model);
        $this->_initAction()
                ->_addBreadcrumb($id ? Mage::helper('megabile')->__('Edit rule') : Mage::helper('megabile')->__('New Event'), $id ? Mage::helper('megabile')->__('Edit rule') : Mage::helper('megabile')->__('New rule'))
                ->_addContent($this->getLayout()->createBlock('megabile/admin_item_edit')->setData('action', $this->getUrl('*/admin_item/save')))
                ->_addLeft($this->getLayout()->createBlock('megabile/admin_item_edit_tabs'))
                ->renderLayout();
        
        
    }

    public function deleteAction() {
        if ($id = $this->getRequest()->getParam('item_id')) {
            try {
                $model = Mage::getModel('megabile/item');
                $model->load($id);
                $model->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('megabile')->__('Item was successfully deleted'));
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('item_id' => $id));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('megabile')->__('Unable to find a item to delete'));
        $this->_redirect('*/*/');
    }

    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('megabile/item');
    }

    public function wysiwygAction() {
        $elementId = $this->getRequest()->getParam('element_id', md5(microtime()));
        $content   = $this->getLayout()->createBlock('adminhtml/catalog_helper_form_wysiwyg_content', '', array(
                    'editor_element_id' => $elementId
                ));
        $this->getResponse()->setBody($content->toHtml());
    }

    public function massDeleteAction() {
        $itemIds = $this->getRequest()->getParam('massaction');
        if(!is_array($itemIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('megabile')->__('Please select item(s)'));
        } else {
            try {
                foreach ($itemIds as $itemId) {
                    $mass = Mage::getModel('megabile/item')->load($itemId);
                    $mass->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('megabile')->__(
                        'Total of %d record(s) were successfully deleted', count($itemIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function saveAction() {
        if ($data = $this->getRequest()->getPost()) {
            $model = Mage::getModel('megabile/item');
            
            if (isset($data['related_prodlist'])) {
                $data['products'] = implode(",",$data['related_prodlist']);
            }
            $data['attributes'] = implode(",",$data['product_attributes']);            
            
            $model->setData($data);
            try {
                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('megabile')->__('Item was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('item_id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('item_id' => $this->getRequest()->getParam('item_id')));
                return;
            }
        }
        $this->_redirect('*/*/');
    }
}