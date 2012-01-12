<?php

class Tweakit_Ezimport_Block_Source extends Mage_Adminhtml_Block_Widget_Container
{

    public function __construct()
    {
        parent::__construct();
        //$this->setTemplate('source.phtml');
    }

	/**
	* Overwrite our own design folder
	 * 
	 * I got this from Alan Storm, so your templates can stay in your code/local/ dir instead of the design/adminhtml/default/default but it doesnt seem to work in 1.6 for some reason..
	 * 
	*/
	/*public function fetchView($fileName)
	{		
		$this->setScriptPath(
			Mage::getModuleDir('', 'Tweakit_Ezimport') .
			DS .
			'design'
		);

		return parent::fetchView($this->getTemplate());
	}*/	

    /**
     * Prepare button and grid
     *
     * @return Mage_Adminhtml_Block_Catalog_Product
     */
    protected function _prepareLayout()
    {
        $this->_addButton('add_new', array(
            'label'   => Mage::helper('ezimport')->__('Add new XML'),
            'onclick' => "setLocation('{$this->getUrl('*/source/new')}')",
            'class'   => 'add'
        ));

        $this->setChild('grid', $this->getLayout()->createBlock('ezimport/source_grid', 'source.grid'));
        return parent::_prepareLayout();
    }

    /**
     * Deprecated since 1.3.2
     *
     * @return string
     */
    public function getAddNewButtonHtml()
    {
        return $this->getChildHtml('add_new_button');
    }

    /**
     * Render grid
     *
     * @return string
     */
    public function getGridHtml()
    {
        return $this->getChildHtml('grid');
    }

    /**
     * Check whether it is single store mode
     *
     * @return bool
     */
    public function isSingleStoreMode()
    {
        if (!Mage::app()->isSingleStoreMode()) {
               return false;
        }
        return true;
    }
}
