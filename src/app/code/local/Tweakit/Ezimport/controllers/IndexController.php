<?php

class Tweakit_Ezimport_IndexController extends Mage_Adminhtml_Controller_Action
{
	public function _init()
	{
		
	}
	
    public function indexAction()
    {
        $this->loadLayout();
		
		$this->_setActiveMenu('ezimport');
		
		$this->renderLayout();
		
    }

	public function gridAction()
	{
		$this->loadLayout();
		$this->getResponse()->setBody(
			$this->getLayout()->createBlock('ezimport/source_grid')->toHtml()
		);
	}		
	
}