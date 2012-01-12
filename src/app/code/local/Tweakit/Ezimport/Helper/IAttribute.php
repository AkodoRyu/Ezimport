<?php

/**
 * Source Controller / manages source objects
 *
 * @author 		http://twitter.com/erfanimani
 * @copyright 	Copyright (c) 2012 TweakIT.eu
 * @link		https://github.com/erfanimani/Ezimport
 * @license		http://www.opensource.org/licenses/mit-license.html
 */

interface Tweakit_Ezimport_Helper_IAttribute {

	public function getName();
	
	public function getIdentifier();
	
	public function getPreview($content);
	
	public function setData(Mage_Catalog_Model_Product $product, $data); 

}
