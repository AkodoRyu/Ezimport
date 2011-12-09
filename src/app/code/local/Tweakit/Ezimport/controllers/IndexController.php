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
		//$handles = Mage::getSingleton('core/layout')->getUpdate()->getHandles();Zend_Debug::dump($handles);die();
		
		/*$block = new Tweakit_Ezimport_Block_Template();
		$block->setTemplate ('index/index.phtml');
		var_dump ($block->getTemplateFile());	*/	
		
		//$s = Mage::getModel('ezimport/source')->load(1)->delete();
		//$s->setName('test')->save();
		
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