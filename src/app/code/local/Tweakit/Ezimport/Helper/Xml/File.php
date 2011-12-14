<?php

class Tweakit_Ezimport_Helper_Xml_File extends Tweakit_Ezimport_Helper_Xml
{
	
	public function createFile($filename) {
			
		if(!(
			isset($_FILES[$filename]['name']) 
			and (file_exists($_FILES[$filename]['tmp_name']))
		)) {
			Mage::throwException($this->__('File not found.'));
		}
		
		$uploader = new Varien_File_Uploader($filename);
		
		$uploader->setAllowedExtensions(array('xml'));
	
		$uploader->setAllowRenameFiles(false);
		// setAllowRenameFiles(true) -> move your file in a folder the magento way
		// setAllowRenameFiles(true) -> move your file directly in the $path folder
		$uploader->setFilesDispersion(false);
			   
		$path = $this->getPath();
		$filename = $this->getNewXmlFileName();
		
		$uploader->save($path,$filename);
		
		return $path.$filename;
			
	}
	
}