<?php
/*
 *  Created on Aug 16, 2011
 *  Author Ivan Proskuryakov - volgodark@gmail.com
 *  Copyright Proskuryakov Ivan. Magazento.com © 2011. All Rights Reserved.
 *  Single Use, Limited Licence and Single Use No Resale Licence ["Single Use"]
 */
?>
<?php

class Magazento_Megabile_Block_Admin_Item_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct() {
        parent::__construct();
        $this->setId('megabile_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('megabile')->__('Megabile Export'));
    }
    
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
    }
         
    protected function _beforeToHtml() {
        $this->addTab('form_section_item', array(
            'label' => Mage::helper('megabile')->__('General information'),
            'title' => Mage::helper('megabile')->__('General information'),
            'content' => $this->getLayout()->createBlock('megabile/admin_item_edit_tab_form')->toHtml(),
        ));
        
        $this->addTab('related', array(
            'label' => Mage::helper('catalog')->__('Products'),
            'url' => $this->getUrl('*/*/related', array('_current' => true)),
            'class' => 'ajax',
        ));        

        return parent::_beforeToHtml();
    }
    
    
    

}