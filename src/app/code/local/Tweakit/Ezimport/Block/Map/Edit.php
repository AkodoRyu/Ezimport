<?php

class Tweakit_Ezimport_Block_Map_Edit extends Tweakit_Ezimport_Block_Template
{
	
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('map/edit.phtml');

    }	
	
	public function getSaveUrl()
	{
		$model = Mage::getSingleton('ezimport/source');
		return Mage::helper("adminhtml")->getUrl("ezimport/map/save/", array("id"=>$model->getId()));
	}
	
	public function getDraggable()
	{
		return Mage::getSingleton('ezimport/source')->getProductAttributes();
	}

	public function getDroppable()
	{
		return Mage::helper('ezimport/attribute')->getAttributes();
	}
	
}