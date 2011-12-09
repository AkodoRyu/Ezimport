<?php

class Tweakit_Ezimport_Helper_Xml extends Mage_Core_Helper_Abstract
{
	public function isValid($file){
		
		$xml = new XMLReader();
		$xml->open($file);
		$xml->setParserProperty(XMLReader::VALIDATE, true);
		
		$valid = $xml->isValid();

		$xml->close();
		
		return $valid;
	}
	
	public function getNewXmlFilePath(){
		$path = $this->getPath() . $this->getNewXmlFileName();
		//if(is_writable($path) === false)
			//throw new Exception("Cannot write file to ". $path . ". Check permissions.");
		
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