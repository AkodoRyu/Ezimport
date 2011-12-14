<?php

#####################################################################################
#																					#
#																					#
#						Erfan Imani | July 2010 | TweakIT.eu						#
#																					#
#																					#
#####################################################################################

class Tweakit_Ezimport_Helper_Attributes_Baseimage implements Tweakit_Ezimport_Helper_IAttribute{

	public function getName() {
		return 'Base Image';	
	}
	
	public function getIdentifier() {
		return 'baseimage';	
	}

	public function getType() {
		return 'text';
	}

	public function getPreview($content) {
		return '<image width="200px" src="'.$content.'" alt="'.$content.' (image not available)" />';	
	}

	public function setData(Mage_Catalog_Model_Product $product, $data) {
		
		$visibility = array (
			'thumbnail',
			'small_image',
			'image'
		);

		if($data) {
			
			$ch = curl_init();
			curl_setopt ($ch, CURLOPT_URL, $data);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 0);
			$fileContents = curl_exec($ch);
			$content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
			curl_close($ch);
			
			//Zend_Debug::dump($content_type);die();
			
			if($content_type === 'image/jpeg') {
				
				$newImg = imagecreatefromstring($fileContents);

				$path = $filename = Mage::getBaseDir('tmp') . DS . 'ezimport' . DS . 'temp_img' . DS . md5( time() . rand(1,999)) . '.jpg';
				imagejpeg($newImg, $path, 100);
	
				$product->addImageToMediaGallery ($filename, $visibility, true, false); 	
			
				if(is_file($path))
					unlink($path);			
			
			}
			
			/*
			$imagePath = $data;
			
			$arr = explode(".", $imagePath);
			$file_extension = end($arr); //should be jpg
			
			//Zend_Debug::dump($imagePath);die();
						
			$handle = fopen($imagePath, "r");
			if ($handle) { 
			   while (!feof($handle)) { 
				   $buffer .= fgets($handle, 4096); 
			   }

			   fclose($handle); 
			} 
			
			
			
			if($mystring = fopen($filename, "wb")) { 
				$handle = fopen($filename, "wb"); 
				$numbytes = fwrite($handle, $buffer);
				
				fclose($handle);
			
			}
			
			if($numbytes>1){
				$product->addImageToMediaGallery ($filename, $visibility, true, false); 	
			}
						
			if(is_file($filename))
				unlink($filename);
			*/
		}		
		
	}
    
}
