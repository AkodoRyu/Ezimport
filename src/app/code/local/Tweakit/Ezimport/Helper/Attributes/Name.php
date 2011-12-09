<?php

#####################################################################################
#																					#
#																					#
#						Erfan Imani | July 2010 | TweakIT.eu						#
#																					#
#																					#
#####################################################################################

class Tweakit_Ezimport_Helper_Attributes_Name implements Tweakit_Ezimport_Helper_IAttribute{

	public function getName() {
		return 'Name';	
	}
	
	public function getIdentifier() {
		return 'name';	
	}

	public function getType() {
		return 'varchar';
	}

	public function getPreview($content) {
		return $content;
	}

	public function setData(Mage_Catalog_Model_Product $product, $data) {

		$product->setName($data);
		
	}
    
}
