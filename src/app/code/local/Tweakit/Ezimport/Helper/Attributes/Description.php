<?php

#####################################################################################
#																					#
#																					#
#						Erfan Imani | July 2010 | TweakIT.eu						#
#																					#
#																					#
#####################################################################################

class Tweakit_Ezimport_Helper_Attributes_Description implements Tweakit_Ezimport_Helper_IAttribute{

	public function getName() {
		return 'Description';	
	}
	
	public function getIdentifier() {
		return 'description';	
	}

	public function getType() {
		return 'text';
	}

	public function getPreview($content) {
		return $content;	
	}

	public function setData(Mage_Catalog_Model_Product $product, $data) {
		
		$product->setDescription($data);
		
	}
    
}
