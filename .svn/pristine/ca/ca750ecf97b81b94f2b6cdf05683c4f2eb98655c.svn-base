<?php

class ES_Custommenu_Block_Adminhtml_Custommenu_Items_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

  protected function _prepareForm() {
    $form = new Varien_Data_Form();
    $this->setForm($form);
    $fieldset = $form->addFieldset('custommenu_items_form', array('legend' => Mage::helper('custommenu')->__('Menu Item Information')));

    $fieldset->addField('name', 'text', array(
        'label' => Mage::helper('custommenu')->__('Name'),
        'required' => true,
        'name' => 'name',
    ));
    $fieldset->addField('menu_id', 'select', array(
        'label' => Mage::helper('custommenu')->__('Menu'),
        'name' => 'menu_id',
        'required' => true,
        'values' => Mage::getModel('custommenu/custommenu')->getInputSelectMenu()
    ));
    $fieldset->addField('parent_id', 'select', array(
        'label' => Mage::helper('custommenu')->__('Parent'),
        'required' => false,
        'values' => Mage::getModel('custommenu/custommenu_items')->getInputSelectParentItem(),
        'name' => 'parent_id',
    ));
    $fieldset->addField('url', 'text', array(
        'label' => Mage::helper('custommenu')->__('Url'),
        'required' => false,
        'name' => 'url',
    ));
    $fieldset->addField('cms_id', 'select', array(
        'label' => Mage::helper('custommenu')->__('Page'),
        'required' => false,
        'values' => Mage::getModel('custommenu/custommenu_items')->getInputSelectPages(),
        'name' => 'cms_id',
    ));

    $fieldset->addField('category_id', 'select', array(
        'label' => Mage::helper('custommenu')->__('Category'),
        'required' => false,
        'values' => Mage::getModel('xpbase/xpbase_category')->toOptionArray(),
        'name' => 'category_id',
    ));
    $fieldset->addField('position', 'text', array(
        'label' => Mage::helper('custommenu')->__('Position'),
        'required' => true,
        'name' => 'position',
    ));
    $fieldset->addField('target', 'select', array(
        'label' => Mage::helper('custommenu')->__('Target'),
        'name' => 'target',
        'values' => array(
            array(
                'value' => '',
                'label' => Mage::helper('custommenu')->__('Current Window'),
            ),
            array(
                'value' => '_blank',
                'label' => Mage::helper('custommenu')->__('New Window'),
            )
        ),
    ));
    
    $fieldset->addField('status', 'select', array(
        'label' => Mage::helper('custommenu')->__('Status'),
        'name' => 'status',
        'values' => array(
            array(
                'value' => 1,
                'label' => Mage::helper('custommenu')->__('Enabled'),
            ),
            array(
                'value' => 2,
                'label' => Mage::helper('custommenu')->__('Disabled'),
            ),
        ),
    ));

    $form->setValues(Mage::registry('custommenu_data')->getData());

    return parent::_prepareForm();
  }

}