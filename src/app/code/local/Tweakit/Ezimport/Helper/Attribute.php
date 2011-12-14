<?php

#####################################################################################
#																					#
#																					#
#						Erfan Imani | July 2010 | TweakIT.eu						#
#																					#
#																					#
#####################################################################################

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