<?php
/*
 *  Created on April 4, 2012
 *  Author Ivan Proskuryakov - volgodark@gmail.com - ecommerceoffice.com
 *  Copyright Proskuryakov Ivan. ecommerceoffice.com © 2011. All Rights Reserved.
 *  Single Use, Limited Licence and Single Use No Resale Licence ["Single Use"]
 */
?>
<?php

class Magazento_Megabile_Block_Admin_Item extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
        $this->_controller = 'admin_item';
        $this->_blockGroup = 'megabile';
        $this->_headerText = Mage::helper('megabile')->__('Megabile Export Tool');
        $this->_addButtonLabel = Mage::helper('megabile')->__('Add Profile');
        parent::__construct();
    }

}
