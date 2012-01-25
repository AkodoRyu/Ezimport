<?php

/**
 * Source Model
 *
 * @author 		http://twitter.com/erfanimani
 * @copyright 	Copyright (c) 2012 TweakIT.eu
 * @link		https://github.com/erfanimani/Ezimport
 * @license		http://www.opensource.org/licenses/mit-license.html
 */

class Tweakit_Ezimport_Model_Source extends Mage_Core_Model_Abstract 
{
 
    protected function _construct()
    {
        $this->_init('ezimport/source');
    }    
	
	public function delete()
	{
		//Delete the XML file associated with the source object
		if(file_exists($this->getUrl()))
			unlink($this->getUrl());		
		
		return parent::delete();
	}
	
    public function validate()
    {
        $errors = array();

        if (!Zend_Validate::is( trim($this->getName()) , 'NotEmpty')) {
            $errors[] = 'The name cannot be empty.';
        }

        if (!Zend_Validate::is( trim($this->getUrl()) , 'NotEmpty')) {
            $errors[] = 'The URL cannot be empty.';
        }

        if (empty($errors)) {
            return true;
        }
        return $errors;
    }

	/**
	 * Recursive function, Finds the container in the XML which direct children are product-containers
	 * 
	 * @return Array
	 */
	public function findProductsContainer(Varien_Simplexml_Element $array) {

		if(sizeof($array)>1) {
			return $array;	
		}
		else {
			
			/*
			if(is_array($array)) {
				
				foreach( $array as $key => $value ) {
				
					if(is_array($value)) {
						
						return $this->findProductsContainer($value);
					}
				
				}
			
			}*/
			
			if($array instanceof Varien_Simplexml_Element){
				
				foreach( $array->children() as $key => $value ){
					
					if($array instanceof Varien_Simplexml_Element){
						
						return $this->findProductsContainer($value);
						
					}
					
				}
				
			}
			
		}
		
	}
	
	/**
	 * Uses findProductsContainer
	 */
	public function getProductsContainer()
	{
		$xmlObj = new Varien_Simplexml_Config($this->getUrl());		
		$array = $xmlObj->getNode();

		if(!$array) {
			Mage::throwException('XML file has no attributes');
		}
		
		return $this->findProductsContainer($array);
	}
	
	/*
	 * Returns an array with the product attributes found in the XML
	 * 
	 */
	public function getProductAttributes()
	{
		
		/*$xmlObj = new Varien_Simplexml_Config($this->getUrl());		
		$array = $xmlObj->getNode()->asArray();
		
		if(!$array) {
			Mage::throwException('XML file has no tags');
		}*/
		
		/*		
		$prodContainer = $this->findProductsContainer($array);
		
		if(!$prodContainer) {
			Mage::throwException('Could not find the product container. Try formatting the XML in a more simple way ;)');
		}
		
		Zend_Debug::dump($prodContainer);die();
		*/
		
		$prodContainer = $this->getProductsContainer()->asArray();
		
		foreach($prodContainer as $key => $value ) {
			$array = array_unique($value);
		}
		
		if($array) {
			foreach($array as $key => $value){
				$array[$key] = array('name' => $key);	
			}
		}
		
		#Zend_Debug::dump($array);
		
		return $array;
	}
	
	public function getMappedProductsArray()
	{

		/*
		
			This foreach loops makes a conversion table. Something like:
			
			Array	{
						["name"] 			=> "NAAM"
						["description"] 	=> "OMSCHRIJVING"
						["foto"]			=> ""
						["Extra data"] 		=> "OMSCHRIJVING_KORT"
					}
		
		    The key represents the attribute helpers and the value represents the xml tag
		
		*/	

		$attributes = Mage::helper('ezimport/attribute')->getAttributes();
		$maps = Mage::getModel('ezimport/map')->getCollection()->addFieldToFilter('source_id', $this->getId());

		$conversion_table = array();

		foreach($attributes as $att_value) {
			
			$conversion_table[$att_value->getIdentifier()] = '';
			
			foreach($maps as $map_value) {

				if($map_value->getHelperAttribute() == $att_value->getIdentifier())
					$conversion_table[$att_value->getIdentifier()] = $map_value->getSourceAttribute();
				
			}
				
		}
		
		//Zend_Debug::dump($conversion_table); die();
		
		/*

		    The next bit retuns an array with assosiative arrays with keys from the attribute helpers and product value's from the xml 
		 
			So something like:
		 			 	{
						
						[0] => Array 	{
										
										['name'] 			=> 'Cooler Master' 
										['description']		=> 'Good airflow etc.'
										['foto']			=> '/path/to/picture.jpg'
										['foto2']			=> ''
										
										}
						
						}
						
		*/		
		
		$productscontainer = $this->getProductsContainer();
		$i = 0;
		$products = array();
		
		foreach($productscontainer as $product) {
			
			foreach($product as $product_attribute_key => $product_attribute_value) {
			
				foreach($conversion_table as $conversion_table_key => $conversion_table_value) {
					
					if($conversion_table_value == $product_attribute_key){
						$products[$i][$conversion_table_key] = trim($product_attribute_value);
					}						
					
				}
				
			}
			$i++;
		}
		
		return $products;
				
	}

}