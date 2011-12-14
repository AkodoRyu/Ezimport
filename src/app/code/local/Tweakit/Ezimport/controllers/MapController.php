<?php

class Tweakit_Ezimport_MapController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout();
		
		$this->_setActiveMenu('ezimport');
		
		$this->renderLayout();
    }

	public function editAction(){
		
		$get = $this->getRequest()->getParams();
		
		try {
			if (empty($get['id'])) {
				Mage::throwException($this->__('Invalid form data.'));
			}

			$model = Mage::getSingleton('ezimport/source')->load($get['id']);
			
			$this->loadLayout();			
			
			$this->_setActiveMenu('ezimport');
			
			$this->renderLayout();			
			
		} catch (Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			$this->_redirect('*/*');
		}
	
	}
	
	public function saveAction() {
		
		$params = $this->getRequest()->getParams();
		$id = $params['id'];
		
		try {
			if ( empty($id) ) {
				Mage::throwException($this->__('Invalid data.'));
			}		
			
			//First delete all existing maps
			$map_collection = Mage::getModel('ezimport/map')
			->getCollection()
			->addFieldToFilter('source_id',$id);
			
			foreach($map_collection as $value) {
				$value->delete();
			}
			
			//Then
			$maps = json_decode($params['data'], true);
			
			foreach($maps as $key => $value) {
				
				if(empty($value)) continue;
				
				$model = new Tweakit_Ezimport_Model_Map();
				
				$model->setSourceId($params['id'])
					->setHelperAttribute(trim($key))
					->setSourceAttribute(trim($value))
					->save();
			}
			
			$message = $this->__('Your form has been submitted successfully.');
			Mage::getSingleton('adminhtml/session')->addSuccess($message);
		} catch (Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
		}
		
		$this->_redirect('*/index');

	}	
	
}