<?php

class Tweakit_Ezimport_Block_Map_Edit extends Tweakit_Ezimport_Block_Template
{
	
    /**
     * Set template
     */
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('map/edit.phtml');

    }	
	
	protected function _prepareLayout()
	{
				
	}
	
	public function getSaveUrl()
	{
		$model = Mage::getSingleton('ezimport/source');
		return Mage::helper("adminhtml")->getUrl("ezimport/map/save/", array("id"=>$model->getId()));
	}
	
	public function getDraggable()
	{
		//haal de xml op, doe unique array, en return deze array
		/*$model = Mage::getSingleton('customcatalog/resource');
		$array = $model->getUniqueArray();
		if($array) {
			foreach($array as $key => $value){
				$array[$key] = array('name' => $key);	
			}
		}			
		
		return $array;*/
				
		return Mage::getSingleton('ezimport/source')->getProductAttributes();
	}

	public function getDroppable()
	{
		return Mage::helper('ezimport/attribute')->getAttributes();
	}
	
}