<?php

class ES_Custommenu_Adminhtml_CustommenuController extends Mage_Adminhtml_Controller_Action {

  /**
   * Controller setup action
   * 
   * @return \ES_Custommenu_Adminhtml_CustommenuController
   */
  protected function _initAction() {
    $this->loadLayout()
            ->_setActiveMenu('custommenu/items')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Menu Manager'), Mage::helper('adminhtml')->__('Menu Manager'));

    return $this;
  }

  /**
   * Custommenu index action
   * 
   */
  public function indexAction() {
    $this->_initAction()->renderLayout();
  }

  /**
   * Custommenu edit action
   * 
   */
  public function editAction() {
    $id = $this->getRequest()->getParam('id');
    $model = Mage::getModel('custommenu/custommenu')->load($id);

    if ($model->getId() || $id == 0) {
      $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
      if (!empty($data)) {
        $model->setData($data);
      }

      Mage::register('custommenu_data', $model);

      $this->loadLayout();
      $this->_setActiveMenu('custommenu/items');

      $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Menu Manager'), Mage::helper('adminhtml')->__('Menu Manager'));
      $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Menu News'), Mage::helper('adminhtml')->__('Menu News'));

      $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

      $this->_addContent($this->getLayout()->createBlock('custommenu/adminhtml_custommenu_edit'))
              ->_addLeft($this->getLayout()->createBlock('custommenu/adminhtml_custommenu_edit_tabs'));

      $this->renderLayout();
    } else {
      Mage::getSingleton('adminhtml/session')->addError(Mage::helper('custommenu')->__('Menu does not exist'));
      $this->_redirect('*/*/');
    }
  }

  /**
   * Custom menu new action forwarded to edit action
   * 
   */
  public function newAction() {
    $this->_forward('edit');
  }

  /**
   * Custom menu save action
   * 
   * @return type
   */
  public function saveAction() {
    if ($data = $this->getRequest()->getPost()) {

      $model = Mage::getModel('custommenu/custommenu');

      $model->setData($data)
              ->setId($this->getRequest()->getParam('id'));

      try {

        $model->save();
        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('custommenu')->__('Menu was successfully saved'));
        Mage::getSingleton('adminhtml/session')->setFormData(false);

        if ($this->getRequest()->getParam('back')) {
          $this->_redirect('*/*/edit', array('id' => $model->getId()));
          return;
        }
        $this->_redirect('*/*/');
        return;
      } catch (Exception $e) {
        Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        Mage::getSingleton('adminhtml/session')->setFormData($data);
        $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
        return;
      }
    }
    Mage::getSingleton('adminhtml/session')->addError(Mage::helper('custommenu')->__('Unable to find item to save'));
    $this->_redirect('*/*/');
  }

  public function deleteAction() {
    if ($this->getRequest()->getParam('id') > 0) {
      try {
        $model = Mage::getModel('custommenu/custommenu')->load($this->getRequest()->getParam('id'));
        $menuItemModel = Mage::getModel('custommenu/custommenu_items')->getCollection()->addFieldToFilter('menu_id', $model->getId());
        foreach ($menuItemModel as $val) {
          $val->delete();
        }
        $model->delete();

        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Menu was successfully deleted'));
        $this->_redirect('*/*/');
      } catch (Exception $e) {
        Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
      }
    }
    $this->_redirect('*/*/');
  }

  public function massDeleteAction() {

    $contactformIds = $this->getRequest()->getParam('custommenu');
    if (!is_array($contactformIds)) {
      Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select menu(s)'));
    } else {
      try {
        foreach ($contactformIds as $contactformId) {
          $model = Mage::getModel('custommenu/custommenu')->load($contactformId);
          $menuItemModel = Mage::getModel('custommenu/custommenu_items')->getCollection()->addFieldToFilter('menu_id', $model->getId());
          foreach ($menuItemModel as $val) {
            $val->delete();
          }
          $model->delete();
        }
        Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($contactformIds)
                )
        );
      } catch (Exception $e) {
        Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
      }
    }
    $this->_redirect('*/*/index');
  }

  public function massStatusAction() {
    $bannersliderIds = $this->getRequest()->getParam('custommenu');
    if (!is_array($bannersliderIds)) {
      Mage::getSingleton('adminhtml/session')->addError($this->__('Please select menu(s)'));
    } else {
      try {
        foreach ($bannersliderIds as $bannersliderId) {
          $bannerslider = Mage::getSingleton('custommenu/custommenu')
                  ->load($bannersliderId)
                  ->setStatus($this->getRequest()->getParam('status'))
                  ->setIsMassupdate(true)
                  ->save();
        }
        $this->_getSession()->addSuccess(
                $this->__('Total of %d record(s) were successfully updated', count($bannersliderIds))
        );
      } catch (Exception $e) {
        $this->_getSession()->addError($e->getMessage());
      }
    }
    $this->_redirect('*/*/index');
  }

}