<?php
/*
 *  Created on Aug 16, 2011
 *  Author Ivan Proskuryakov - volgodark@gmail.com
 *  Copyright Proskuryakov Ivan. Magazento.com © 2011. All Rights Reserved.
 *  Single Use, Limited Licence and Single Use No Resale Licence ["Single Use"]
 */
?>
<?php

class Magazento_Megabile_Block_Admin_Item_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {


    protected function _prepareForm() {
        $model = Mage::registry('megabile_item');
        $form = new Varien_Data_Form(array('id' => 'edit_form_item', 'action' => $this->getData('action'), 'method' => 'post'));
        $form->setHtmlIdPrefix('item_');
        $fieldset = $form->addFieldset('base_fieldset', array('legend' => Mage::helper('megabile')->__('General'), 'class' => 'fieldset-wide'));
        if ($model->getItemId()) {
            $fieldset->addField('item_id', 'hidden', array(
                'name' => 'item_id',
            ));
        }
        
        
        $fieldset->addField('filename', 'text', array(
            'label' => Mage::helper('megabile')->__('Filename'),
            'note'  => Mage::helper('pdfproduct')->__('example: megabile_general.xml'),            
            'name'  => 'filename',
            'required' => true,
        ));
        
        $fieldset->addField('root_category', 'select', array(
            'label' => Mage::helper('megabile')->__('Root Category'),
            'name'  => 'root_category',
            'required' => true,
            'values' => Mage::getModel('megabile/data')->storeCategories4Form(),
            'style' => 'width:100%',                
        ));
        
        $fieldset->addField('products_for_export', 'select', array(
            'label' => Mage::helper('megabile')->__('Products For Export'),
            'name'  => 'products_for_export',
            'required' => true,
            'options' => array(
                '0' => Mage::helper('megabile')->__('All Produdcts'),
                '1' => Mage::helper('megabile')->__('Only Selected'),
            ),        
            'note'  => Mage::helper('pdfproduct')->__('If "only selected" you will need to select products'),                  
        ));        
        
        $fieldset->addField('use_attributes', 'select', array(
            'label' => Mage::helper('megabile')->__('Use attributes'),
            'name'  => 'use_attributes',
            'required' => true,
            'options' => array(
                '0' => Mage::helper('megabile')->__('Disabled'),
                '1' => Mage::helper('megabile')->__('Enabled'),
            ),            
        ));
        
        $fieldset->addField('product_attributes', 'multiselect', array(
            'label' => Mage::helper('megabile')->__('Extra attributes'),
            'note'  => Mage::helper('pdfproduct')->__('We will put them in the bottom of product description'),                    
            'name'  => 'product_attributes',
            'values' => Mage::getModel('megabile/data')->storeAttributes4Form(),            
            'required' => false,
           'style' => 'height:250px',            
        ));
        

        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('store_id', 'select', array(
                'label'    => Mage::helper('megabile')->__('Store View'),
                'title'    => Mage::helper('megabile')->__('Store View'),
                'name'     => 'store_id',
                'required' => true,
                'value'    => $model->getStoreId(),
                'values'   => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm()
            ));
        }
        else {
            $fieldset->addField('store_id', 'hidden', array(
                'name'     => 'store_id',
                'value'    => Mage::app()->getStore(true)->getId()
            ));
            $model->setStoreId(Mage::app()->getStore(true)->getId());
        }    
        

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

}
