<?php

/**
* Unit test script
*
* @author    	http://twitter.com/erfanimani
* @copyright    Copyright (c) 2012 TweakIT.eu
* @link        	https://github.com/erfanimani/Ezimport
* @license    	http://www.opensource.org/licenses/mit-license.html
*/

define("TEST_FILE_DIR", dirname($_SERVER["SCRIPT_FILENAME"]));
define("TEST_URL", "127.0.0.1");

ini_set('display_errors', 1);
//ini_set('memory_limit', '512M');
error_reporting(E_ALL | E_STRICT);

include "testify/testify.class.php";
include "magento/app/Mage.php";

Mage::app('default');

session_start(); 

$tf = new Testify("Testing Ezimport Magento module");

$tf->test("Basic installation tests", function($tf){

	$ezimport_dir = Mage::getBaseDir('tmp') . DS . 'ezimport';
	$xml_dir = Mage::getBaseDir('tmp') . DS . 'ezimport' . DS . 'xml';
	$tmp_img_dir = Mage::getBaseDir('tmp') . DS . 'ezimport' . DS . 'temp_img';

	$tf->assert(file_exists($ezimport_dir));	
	$tf->assert(file_exists($xml_dir));
	$tf->assert(file_exists ( $tmp_img_dir ));

});

$tf->test("The Source model", function($tf){

	/*
	 *  Basic (check if the module is installed correctly, if no files are missing, etc.)
	 */
	
	$tf->assert(Mage::getModel('ezimport/source') instanceof Tweakit_Ezimport_Model_Source);
	
	/*
	 * Database
	 */
	
	try{
		$source = new Tweakit_Ezimport_Model_Source();
		
		$name = "unit_test" . md5(time());
		
		$source->setName($name)
			->setUrl("some_url")
			->save();
		
		$id = $source->getId();
		
		unset($source);
		
		$source = Mage::getModel('ezimport/source')->load($id);
		
		$newname = $source->getName();
		$source->delete();
		
		$tf->assert($newname == $name);
		
	} catch (Exception $e) {
		$tf->fail();
	}
	
	unset($source);
	
	/*
	 * Empty name and
	 * Empty URL
	 */
	
	$source = new Tweakit_Ezimport_Model_Source();
	$source->setName("")
		->setUrl("");
	
	$validation = $source->validate();

	$tf->assert(in_array("The name cannot be empty.", $validation));
	$tf->assert(in_array("The URL cannot be empty.", $validation));
	
});

$tf->test("The Map model", function($tf){

	/*
	 * Basic
	 */
	
	$tf->assert(Mage::getModel('ezimport/map') instanceof Tweakit_Ezimport_Model_Map);
	
	/*
	 * Database
	 */
	
	$source = new Tweakit_Ezimport_Model_Source();
	$source->setName("unit_test" . md5(time()))
		->setUrl("some_url");
	
	if($source->validate())
		$source->save();
		
	$map = new Tweakit_Ezimport_Model_Map();
	$map->setSourceId($source->getId())
		->setSourceAttribute("_ATTRIBUTE_")
		->setHelperAttribute("baseimage");
		
	if($map->validate())
		$map->save();
		
	$id = $map->getId();
	$sourceId = $map->getSourceId();
	$sourceAttribute = $map->getSourceAttribute();
		
	unset($map);
	
	$map = Mage::getModel("ezimport/map")->load($id);
	
	$sourceId = $map->getSourceId();
	$sourceAttribute = $map->getSourceAttribute();
	
	$tf->assert($sourceAttribute == $map->getSourceAttribute() && $sourceId == $map->getSourceId());
	
	$map->delete();
	$source->delete();
	
	/*
	 * Non-existent Source relation, 
	 * Empty source_attribute and
	 * Empty helper_attribute
	 */
	
	$map = new Tweakit_Ezimport_Model_Map();
	$map->setSourceId(234523452) //lets take some random id that probably doesnt exist
		->setSourceAttribute("")
		->setHelperAttribute("");
		
	$validation = $map->validate();

	$tf->assert(in_array("Non-valid source id.", $validation));
	$tf->assert(in_array("The source attribute cannot be empty.", $validation));
	$tf->assert(in_array("The helper attribute cannot be empty.", $validation));
	
});

$tf->test("XML file helpers", function($tf){
	
	/*
	 * A correct XML
	 */
	try{
		$xmlfilehelper = Mage::Helper('ezimport/xml_url');
		$oldxmlfilepath = TEST_URL . DS . 'sample_correct.xml';
		$xmlfilepath = $xmlfilehelper->createFile($oldxmlfilepath);
		
		$valid = $xmlfilehelper->isValid($xmlfilepath);
		$tf->assert($valid === TRUE);

		unlink($xmlfilepath);
		
	} catch (exception $e){
		$tf->fail();
	}
	

	unset($oldxmlfilepath);
	unset($xmlfilepath);

	/*
	 * An invalid XML
	 */	
	try{
		
		$xmlfilehelper = Mage::Helper('ezimport/xml_url');
		$oldxmlfilepath = TEST_URL . DS . 'sample_invalid.xml';
		$xmlfilepath = $xmlfilehelper->createFile($oldxmlfilepath);
		
		$valid = $xmlfilehelper->isValid($xmlfilepath);		
		$tf->assert($valid === FALSE);
		
		unlink($xmlfilepath);
		
	} catch (exception $e){
		$tf->fail();
	}		
	
});

$tf->test("Importing products", function($tf){
				
	try{
 
		$xmlfilepath = Mage::Helper('ezimport/xml_url')->createFile(TEST_URL . DS . 'sample_correct.xml');
		
		$source = new Tweakit_Ezimport_Model_Source();
		$source->setName("unit_test" . md5(time()))
			->setUrl($xmlfilepath);
		
		if($source->validate())
			$source->save();
		
		$map_vendornr = new Tweakit_Ezimport_Model_Map();
		$map_vendornr->setSourceId($source->getId())
			->setSourceAttribute("VENDORNR")
			->setHelperAttribute("name");
		
		$map_naam = new Tweakit_Ezimport_Model_Map();
		$map_naam->setSourceId($source->getId())
			->setSourceAttribute("NAAM")
			->setHelperAttribute("description");
		
		if($map_vendornr->validate())
			$map_vendornr->save();
		
		if($map_naam->validate())
			$map_naam->save();
		
		$container = $source->getProductsContainer();
		$tf->assert(count($container) === 9);
		
		$product_attributes = $source->getProductAttributes();
		
		$tf->assert(
			array_key_exists("VENDORNR", $product_attributes) &&
			array_key_exists("LEVARTNR", $product_attributes) &&
			array_key_exists("NAAM", $product_attributes) &&
			array_key_exists("PRIJS", $product_attributes) &&
			array_key_exists("OMSCHRIJVING_KORT", $product_attributes) &&
			array_key_exists("MERK", $product_attributes));
		
		$mapped_products_array = $source->getMappedProductsArray();
		
		$tf->assert(
			count($mapped_products_array) === 9 &&
			$mapped_products_array[0]['name'] === 'CAC-SXHH7-U01' &&
			$mapped_products_array[0]['description'] === 'XIGMATEK Thorshammer');
		
		$map_vendornr->delete();
		$map_naam->delete();
		$source->delete();
		
	} catch (exception $e){
		$tf->fail();
	}
	

});

$tf->run();

?>