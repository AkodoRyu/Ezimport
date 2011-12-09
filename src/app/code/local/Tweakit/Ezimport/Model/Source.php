<?php

#####################################################################################
#																					#
#																					#
#						Erfan Imani | Juli 2011 | TweakIT.eu						#
#																					#
#																					#
#####################################################################################

class Tweakit_Ezimport_Model_Source extends Mage_Core_Model_Abstract 
{
 
    protected function _construct()
    {
        $this->_init('ezimport/source');
    }    
	
	public function delete()
	{
		if(file_exists($this->getUrl()))
			unlink($this->getUrl());		
		
		return parent::delete();
	}
	
	public function findProductsContainer($array) {

		if(sizeof($array)>2) {
			return $array;	
		}
		else {
			
			if(is_array($array)) {
				
				foreach( $array as $key => $value ) {
				
					if(is_array($value)) {
						
						return $this->findProductsContainer($value);
					}
				
				}
			
			}
			
		}
		
	}	
	
	public function getProductsContainer()
	{
		$xmlObj = new Varien_Simplexml_Config($this->getUrl());		
		$array = $xmlObj->getNode();
		
		if(!$array) {
			Mage::throwException('XML file has no attributes');
		}	
		
		//Zend_Debug::dump($array);die();
		
		return $this->findProductsContainer($array);
	}
	
	public function getProductAttributes()
	{
		
		$xmlObj = new Varien_Simplexml_Config($this->getUrl());		
		$array = $xmlObj->getNode()->asArray();
		
		if(!$array) {
			Mage::throwException('XML file has no attributes');
		}	
		
		/*		
		$prodContainer = $this->findProductsContainer($array);
		
		if(!$prodContainer) {
			Mage::throwException('Could not find the product container. Try formatting the XML in a more simple way ;)');
		}
		
		Zend_Debug::dump($prodContainer);die();
		*/
		
		foreach($array as $key => $value ) {
			$array = array_unique($value);
		}
		
		#$config = new Zend_Config_Xml($this->getUrl());
		
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
		
			Deze foreach maakt een conversie tabel. Zoiets dus:
			
			Array	{
						["name"] 			=> "NAAM"
						["description"] 	=> "OMSCHRIJVING"
						["foto"]			=> ""
						["Extra data"] 		=> "OMSCHRIJVING_KORT"
					}
		
			De key representeerd de templateveldnaam (attribute) en de value de naam van het xml veld (resource attribute).
		
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
		

		/*
		
			Deze functie returnt een array met assosiative arrays met key's uit de product template. 
			Dus Array 	{
						
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