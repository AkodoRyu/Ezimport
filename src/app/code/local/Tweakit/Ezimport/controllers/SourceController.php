<?php

class Tweakit_Ezimport_SourceController extends Mage_Adminhtml_Controller_Action
{

    public function indexAction()
    {
		$this->loadLayout();
		
		$this->_setActiveMenu('ezimport');
		
		$this->renderLayout();
    }
	
	public function newAction()
	{
		$this->loadLayout();
		
		$this->_setActiveMenu('ezimport');

		$this->renderLayout();
	}
	
	public function viewAction()
	{
		$get = $this->getRequest()->getParams();
		
		try {
			if (empty($get['id'])) {
				Mage::throwException($this->__('Invalid data.'));
			}
				
			$source = Mage::getSingleton('ezimport/source')->load($get['id']);
			
			$this->loadLayout();
	
			$this->_setActiveMenu('ezimport');
	
			$this->renderLayout();			
			
		} catch (Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			$this->_redirect('*/*');
		}
	
	}	
	
	public function processNewXmlAction() {
		
        $post = $this->getRequest()->getPost();

		try {

			if (empty($post)) {
				Mage::throwException($this->__('Invalid form data.'));
			}
			
			$r = $post['r'];
			
			$source = Mage::getModel('ezimport/source');
			$source->setName($r['name']);

			switch($r['type']) {
				
				case 'url' :
					$source->setUrl(
						Mage::Helper('ezimport/xml_url')
							->createFile($r['url'])
					);
					break;
					
				case 'file' :
					$source->setUrl(
						Mage::Helper('ezimport/xml_file')
							->createFile('uploadedfile')
					);				
					break;
					
				case 'ftp' :
					$source->setUrl(
						Mage::Helper('ezimport/xml_ftp')
							->createFile( $r['url'] , $r['username'] , $r['password'] , $r['port'] )
					);				
					break;
					
				case 'directinput' :
					$source->setUrl(
						Mage::Helper('ezimport/xml_input')
							->createFile($r['directinput'])
					);
					break;
										
				default :
					Mage::throwException($this->__('Upload type not recognized.'));
			}

			if(!Mage::Helper('ezimport/xml')->isValid($source->getUrl())){
				Mage::throwException($this->__('XML file is not valid'));
			}
			
			$source->save();
			
			$message = $this->__('Your form has been submitted successfully.');
			Mage::getSingleton('adminhtml/session')->addSuccess($message);			

		} catch (Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
		}
		$this->_redirect('*/*');				
	}
	
	public function processNewCsvAction() {
		$post = $this->getRequest()->getPost();

		try {
			if ( empty($post['r']) && empty($post['r']['type'])) {
				Mage::throwException($this->__('Invalid data.'));
			}
			
			$r 		= $post['r'];
			$helper = Mage::Helper('csvimporter');
			
			if ( empty($r['name']) ) {
				Mage::throwException($this->__('Name is empty.'));
			}
			
			switch ( number_format($r['seperator'])  ) {

				case 1 :
					$seperator = ',';
					break;
					
				case 2 :
					$seperator = '|';
					break;
					
				default :
				case 0	:
					$seperator  = ';';
			}				
			
			switch($r['type']) {
				
				case 'url' :
					$array = $helper->import_by_url($seperator, $r['url']);
					break;
					
				case 'file' :
					$array = $helper->import_by_file($seperator, 'uploadedfile');
					break;
					
				case 'ftp' :
					$array = $helper->import_by_ftp($seperator, $r['url'], $r['username'], $r['password'], $r['port']);					
					break;
					
				case 'directinput' :
					$array = $helper->import_by_directinput($seperator, $r['directinput']);
					break;
										
				default :
					Mage::throwException($this->__('Upload type not recognized.'));
			}
			
			if(!is_null($array) && $array) {				
				$resource = new Tweakit_Customcatalog_Model_Resource();
				$resource->setName($r['name'])
						->setType($r['type'])
						->setUrl($r['url'])
						->setUsername($r['username'])
						->setPassword($r['password'])
						->setPort($r['port'])
						->setCache(serialize($array))
						->save();

				$message = $this->__('Your form has been submitted successfully.');
				Mage::getSingleton('adminhtml/session')->addSuccess($message);
			}
			else {
				Mage::throwException($this->__('The XML file could not be parsed.'));
			}
		} catch (Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
		}
		$this->_redirect('*/*');			
	}
	
	public function deleteAction(){
		$get = $this->getRequest()->getParams();
		try {
			if (empty($get['id'])) {
				Mage::throwException($this->__('Invalid form data.'));
			}
				
			Mage::getModel('ezimport/source')->load($get['id'])->delete();

			$message = $this->__('Your form has been submitted successfully.');
			Mage::getSingleton('adminhtml/session')->addSuccess($message);
		} catch (Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
		}
		$this->_redirect('*/*');
	}	
	
	public function gridAction()
	{
		$this->loadLayout();
		$this->getResponse()->setBody(
			$this->getLayout()->createBlock('ezimport/source_grid')->toHtml()
		);
	}	
	
	public function massDeleteAction() {
				
		$sources = $this->getRequest()->getParam('source');
		
		try {
			if (empty($sources)) {
				Mage::throwException($this->__('Invalid form data.'));
			}
			
			foreach($sources as $id) {
				Mage::getModel('ezimport/source')->load((int)$id)->delete();
			}
			
			$message = $this->__('Your form has been submitted successfully.');
			Mage::getSingleton('adminhtml/session')->addSuccess($message);
		} catch (Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
		}
		$this->_redirect('*/*');
	}	
	
	public function processImportAction()
	{
		$id 		= $this->getRequest()->getParam('id');
		$source 	= Mage::getModel('ezimport/source')->load($id);
		$attributes = Mage::helper('ezimport/attribute')->getAttributes();
		$maps 		= Mage::getModel('ezimport/map')->getCollection()->addFieldToFilter('source_id', $id);
		
		try{
			
			$products = $source->getMappedProductsArray();	

			foreach($products as $product) {
				
				$magento_product = new Mage_Catalog_Model_Product();
				$productDefaultSetId = Mage::getModel('catalog/product')->getDefaultAttributeSetId();
				
				$magento_product->setAttributeSetId($productDefaultSetId)
					->setTypeId('simple')
					->setWebsiteIDs(array(1))
					->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
					->setStatus(2)
					->setTaxClassId(0)
					->setCreatedAt(strtotime('now'));
					//->setCategoryIds($category_id_array);
					
						foreach($attributes as $attribute) {

							$attribute->setData($magento_product, $product[$attribute->getIdentifier()]);
							
						}
						
						//Zend_Debug::dump( $magento_product->getName() . $magento_product->getDescription());
						
					$magento_product->save();

			}			

			$message = $this->__('Products have been successfully imported.');
			Mage::getSingleton('adminhtml/session')->addSuccess($message);			
			
		} catch(Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
		}
		
		$this->_redirect('*/*');
		
	}

}