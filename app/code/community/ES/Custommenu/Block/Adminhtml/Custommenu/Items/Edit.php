<?php

class ES_Custommenu_Block_Adminhtml_Custommenu_Items_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'custommenu_item_id';
        $this->_blockGroup = 'custommenu';
        $this->_controller = 'adminhtml_custommenu_items';
        
        $this->_updateButton('save', 'label', Mage::helper('custommenu')->__('Save Menu Item'));
        $this->_updateButton('delete', 'label', Mage::helper('custommenu')->__('Delete Menu Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('custommenu_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'custommenu_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'custommenu_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('custommenu_data') && Mage::registry('custommenu_data')->getId() ) {
            return Mage::helper('custommenu')->__("Edit Menu Item '%s'", $this->htmlEscape(Mage::registry('custommenu_data')->getName()));
        } else {
            return Mage::helper('custommenu')->__('Add Menu Item');
        }
    }
}