<?php

class ES_Custommenu_Block_Adminhtml_Custommenu_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

  protected function _prepareForm() {
    $form = new Varien_Data_Form();
    $this->setForm($form);
    $fieldset = $form->addFieldset('custommenu_form', array('legend' => Mage::helper('custommenu')->__('Menu Information')));

    $fieldset->addField('name', 'text', array(
        'label' => Mage::helper('custommenu')->__('Name'),
        'required' => true,
        'name' => 'name',
    ));

    $fieldset->addField('label', 'text', array(
        'label' => Mage::helper('custommenu')->__('Label'),
        'required' => true,
        'name' => 'label',
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