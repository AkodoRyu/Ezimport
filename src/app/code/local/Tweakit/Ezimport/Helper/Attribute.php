<?php

/**
 * Base attribute helper class
 *
 * @author 		http://twitter.com/erfanimani
 * @copyright 	Copyright (c) 2012 TweakIT.eu
 * @link		https://github.com/erfanimani/Ezimport
 * @license		http://www.opensource.org/licenses/mit-license.html
 */

class Tweakit_Ezimport_Helper_Attribute {

	public function getAttributes() {

		$folder = dir(Mage::getModuleDir('', 'Tweakit_Ezimport') . DS .'Helper' . DS . 'Attributes');
		
		while($folderEntry=$folder->read()){
			$target = explode('.', $folderEntry);
			if(end($target) == 'php' || end($target) == 'PHP' ) {
				try {
				  $attribute_classes[] = Mage::helper('ezimport/attributes_'.$target[0]);
				} catch (Exception $e) {
					Mage::throwException('Could not initialize attribute helper with name '.$target[0]);
				}
			}
		}
		
		$folder->close(); 
		
		return $attribute_classes;	
	}
	
	public function isValidAttribute($att) {
		
		$array = $this->getAttributes();
		
		foreach( $array as $object ) {
		
			if($object->getIdentifier() == $att)
				return true;
		
		}
		
		return false;
	
	}
    

}