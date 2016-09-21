<?php

class ES_Custommenu_Block_Adminhtml_Custommenu_Items_Grid extends Mage_Adminhtml_Block_Widget_Grid {

  public function __construct() {
    parent::__construct();
    $this->setId('custommenu_itemGrid');
    $this->setDefaultSort('menu_id');
    $this->setDefaultDir('ASC');
    $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection() {
    $collection = Mage::getModel('custommenu/custommenu_items')->getCollection();
    $this->setCollection($collection);
    return parent::_prepareCollection();
  }

  protected function _prepareColumns() {
    $this->addColumn('custommenu_item_id', array(
        'header' => Mage::helper('custommenu')->__('ID'),
        'align' => 'right',
        'width' => '50px',
        'index' => 'custommenu_item_id',
    ));

    $this->addColumn('name', array(
        'header' => Mage::helper('custommenu')->__('Name'),
        'align' => 'left',
        'index' => 'name',
    ));
    $this->addColumn('menu_id', array(
        'header' => Mage::helper('custommenu')->__('Menu'),
        'align' => 'left',
        'index' => 'menu_id',
        'type' => 'options',
        'options' => Mage::getModel('custommenu/custommenu')->getGridInputSelectMenu()
    ));
    
    $this->addColumn('parent_id', array(
        'header' => Mage::helper('custommenu')->__('Parent'),
        'align' => 'left',
        'index' => 'parent_id',
        'type'  => 'options',
        'options' => Mage::getModel('custommenu/custommenu_items')->getGridInputSelectParentItem()
    ));
    $this->addColumn('url', array(
        'header' => Mage::helper('custommenu')->__('Url'),
        'align' => 'left',
        'index' => 'url',
    ));
    $this->addColumn('cms_id', array(
        'header' => Mage::helper('custommenu')->__('Page'),
        'align' => 'left',
        'index' => 'cms_id',
        'type'  => 'options',
        'options' => Mage::getModel('custommenu/custommenu_items')->getGridInputSelectPages()
    ));
    $this->addColumn('position', array(
        'header' => Mage::helper('custommenu')->__('Position'),
        'align' => 'left',
        'index' => 'position',
    ));

    $this->addColumn('status', array(
        'header' => Mage::helper('custommenu')->__('Status'),
        'align' => 'left',
        'width' => '80px',
        'index' => 'status',
        'type' => 'options',
        'options' => array(
            1 => 'Enabled',
            2 => 'Disabled',
        ),
    ));
    $this->addColumn('action', array(
        'header' => Mage::helper('custommenu')->__('Action'),
        'width' => '100',
        'type' => 'action',
        'getter' => 'getId',
        'actions' => array(
            array(
                'caption' => Mage::helper('custommenu')->__('Edit'),
                'url' => array('base' => '*/*/edit'),
                'field' => 'id'
            )
        ),
        'filter' => false,
        'sortable' => false,
        'index' => 'stores',
        'is_system' => true,
    ));

    return parent::_prepareColumns();
  }

  protected function _prepareMassaction() {
    $this->setMassactionIdField('custommenu_item_id');
    $this->getMassactionBlock()->setFormFieldName('custommenu');

    $this->getMassactionBlock()->addItem('delete', array(
        'label' => Mage::helper('custommenu')->__('Delete'),
        'url' => $this->getUrl('*/*/massDelete'),
        'confirm' => Mage::helper('custommenu')->__('Are you sure?')
    ));
    
    $statuses = Mage::getSingleton('custommenu/status')->getOptionArray();

    array_unshift($statuses, array('label' => '', 'value' => ''));
    $this->getMassactionBlock()->addItem('status', array(
        'label' => Mage::helper('custommenu')->__('Change status'),
        'url' => $this->getUrl('*/*/massStatus', array('_current' => true)),
        'additional' => array(
            'visibility' => array(
                'name' => 'status',
                'type' => 'select',
                'class' => 'required-entry',
                'label' => Mage::helper('custommenu')->__('Status'),
                'values' => $statuses
            )
        )
    ));
    return $this;
  }

  public function getRowUrl($row) {
    return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}