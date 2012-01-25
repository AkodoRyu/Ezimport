
<?php

class Tweakit_Ezimport_Helper_Xml_Input extends Tweakit_Ezimport_Helper_Xml
{
	
	public function createFile($content) {
		//Zend_Debug::dump($content);die();
		//Create a new file
		$file_path = $this->getNewXmlFilePath();
		$fp = fopen($file_path, 'wb');
		
		fwrite($fp, $content);
		
		fclose($fp);
		
		return $file_path;

	}
	
}