<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2011 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * Items grid
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 */
class Magazento_Megabile_Block_Admin_Item_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('itemGrid');
        $this->setDefaultSort('item_id');

    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('megabile/item')->getCollection();
        /* @var $collection Mage_Item_Model_Mysql4_Item_Collection */
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('item_id', array(
            'header'    => Mage::helper('megabile')->__('ID'),
            'width'     => '50px',
            'index'     => 'item_id'
        ));

        $this->addColumn('link', array(
            'header'    => Mage::helper('megabile')->__('Files & Links'),
            'renderer'  => 'megabile/admin_item_grid_renderer_link',
        ));
        
        $this->addColumn('description_field', array(
            'header'    => Mage::helper('megabile')->__('Description'),
            'index'     => 'description_field',
        ));
        $this->addColumn('root_category', array(
            'header'    => Mage::helper('megabile')->__('Root Category'),
            'renderer'  => 'megabile/admin_item_grid_renderer_category',            
        ));
        
        $this->addColumn('products', array(
            'header'    => Mage::helper('megabile')->__('Products'),
            'renderer'  => 'megabile/admin_item_grid_renderer_products',            
        ));

        $this->addColumn('from_time', array(
            'header'    => Mage::helper('megabile')->__('Time Created'),
            'width'     => '150px',
            'index'     => 'from_time',
            'type'      => 'datetime',
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header'    => Mage::helper('megabile')->__('Store View'),
                'index'     => 'store_id',
                'type'      => 'store',
                'width' => '150px',
            ));
        }
        
        $this->addColumn('action', array(
            'header'   => Mage::helper('megabile')->__('Action'),
            'filter'   => false,
            'sortable' => false,
            'width'    => '200px',
            'renderer' => 'megabile/admin_item_grid_renderer_action'
        ));

        return parent::_prepareColumns();
    }

    /**
     * Row click url
     *
     * @return string
     */
//    public function getRowUrl($row)
//    {
//        return $this->getUrl('*/*/edit', array('item_id' => $row->getId()));
//    }

    
    protected function _afterLoadCollection() {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    protected function _filterStoreCondition($collection, $column) {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $this->getCollection()->addStoreFilter($value);
    }    
}
