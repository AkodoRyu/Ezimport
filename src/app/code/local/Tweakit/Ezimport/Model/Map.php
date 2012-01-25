<?php

/**
 * Map Model
 *
 * @author 		http://twitter.com/erfanimani
 * @copyright 	Copyright (c) 2012 TweakIT.eu
 * @link		https://github.com/erfanimani/Ezimport
 * @license		http://www.opensource.org/licenses/mit-license.html
 */

class Tweakit_Ezimport_Model_Map extends Mage_Core_Model_Abstract 
{
 
    protected function _construct()
    {
        $this->_init('ezimport/map');
    }    
	
    public function validate()
    {
        $errors = array();
		
		$sourceData = Mage::getModel('ezimport/source')->load($this->getSourceId())->getData();
		
		//If the loaded source has an emtpy data array, we can assume that it doesnt exist, and thus is invalid
		if(empty($sourceData)){
			$errors[] = 'Non-valid source id.';
		}

        if (!Zend_Validate::is( trim($this->getSourceAttribute()) , 'NotEmpty')) {
            $errors[] = 'The source attribute cannot be empty.';
        }

        if (!Zend_Validate::is( trim($this->getHelperAttribute()) , 'NotEmpty')) {
            $errors[] = 'The helper attribute cannot be empty.';
        }
		
        if (empty($errors)) {
            return true;
        }
        return $errors;
    } 
	
}