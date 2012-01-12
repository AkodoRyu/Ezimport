<?php

class Tweakit_Ezimport_Helper_Xml_Url extends Tweakit_Ezimport_Helper_Xml
{
	
	public function createFile($url) {
		
		if( empty($url) ) {
			Mage::throwException($this->__('Url is empty.'));
		}
		
		//zend filter check if this is a valid url
		//...
		
		//  Initialize the cURL session
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);

		//Create a new file
		$file_path = $this->getNewXmlFilePath();
		$fp = fopen($file_path, 'wb');
		
		// Save to file
		curl_setopt($ch, CURLOPT_FILE, $fp);
		
		// Execute the cURL session
		curl_exec ($ch);
				
		$content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
		#Zend_Debug::dump($content_type);die();
		
		//Close cURL session and file
		curl_close ($ch);
		fclose($fp);		
		
		if($content_type === false){
			if(file_exists($file_path))
				unlink($file_path);
			Mage::throwException($this->__('URL does not contain a valid file.'));
		}
				
		return $file_path;			
	}
	
}