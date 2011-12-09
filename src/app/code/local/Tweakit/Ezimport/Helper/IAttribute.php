<?php

#####################################################################################
#																					#
#																					#
#						Erfan Imani | July 2010 | TweakIT.eu						#
#																					#
#																					#
#####################################################################################

interface Tweakit_Ezimport_Helper_IAttribute {

	public function getName();
	
	public function getIdentifier();
	
	public function getPreview($content);
	
	public function setData(Mage_Catalog_Model_Product $product, $data); 
	
	public function getType();
    
}
