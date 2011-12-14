<?php

class Tweakit_Ezimport_Model_Mysql4_Map extends Mage_Core_Model_Mysql4_Abstract{
	
    protected function _construct()
    {
        $this->_init('ezimport/map', 'id');
    }   
	
}