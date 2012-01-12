<?php

/**
 * Xml helper
 *
 * @author 		http://twitter.com/erfanimani
 * @copyright 	Copyright (c) 2012 TweakIT.eu
 * @link		https://github.com/erfanimani/Ezimport
 * @license		http://www.opensource.org/licenses/mit-license.html
 */

class Tweakit_Ezimport_Helper_Xml extends Mage_Core_Helper_Abstract
{
	
	public function isValid($file){
		
		$xmlObj = new Varien_Simplexml_Config($file);
		$array = $xmlObj->getNode();

		if($array instanceof Varien_Simplexml_Element)
			return true;
		
		return false;
	}
	
	public function getNewXmlFilePath(){
			
		$dir = $this->getPath();
		$path = $dir . $this->getNewXmlFileName();
		
		if(file_exists($dir) === false)
			throw new Exception("Cannot write file to ". $path . ". Check permissions.");
		
		return $path;
	}
	
	public function getNewXmlFileName(){
		
		$filename = (string)time() . (string)rand(1,9999);
		$filename = md5($filename);
		$filename .=  '.xml' ;
		
		return $filename;		
	}
	
	public function getPath(){
		
		$path = Mage::getBaseDir('tmp') . DS . 'ezimport' . DS . 'xml' . DS;
		return $path;
	}
	
}