<?php

class Tweakit_Ezimport_Block_Source_View extends Tweakit_Ezimport_Block_Source
{

    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('source/view.phtml');
    }

	public function getImportUrl()
	{
		$model = Mage::getSingleton('ezimport/source');
		return Mage::helper("adminhtml")->getUrl("ezimport/source/processImport/", array("id"=>$model->getId()));
	}

    protected function _prepareLayout()
    {
        /*
		 * Uncomment to get a direct import button. 
		 * I didnt think this was a good idea because you first need to view the products to know if the interpreting has been successfull, after that you can import
		 * 
		 * $this->_addButton('add_new', array(
            'label'   => Mage::helper('ezimport')->__('Import'),
            'onclick' => "setLocation('{$this->getUrl('* /source/processImport')}')",
            'class'   => 'add',
            'confirm' => Mage::helper('ezimport')->__('This will import all the products in this source to the Magento Catalog. Do you want to continue?')
        ));*/

        return parent::_prepareLayout();
    }

	public function getHelperAttribute($name)
	{
		return Mage::helper('ezimport/attributes_'.$name);
	}

	public function getAttributes()
	{
		return Mage::helper('ezimport/attribute')->getAttributes();
	}

	public function getProducts()
	{
		return Mage::getSingleton('ezimport/source')->getMappedProductsArray();
	}	

}
