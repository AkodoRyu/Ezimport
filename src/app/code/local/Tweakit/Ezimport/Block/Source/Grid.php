<?php

/**
* Source Grid
* This is the grid thats on the main page
*
* @author 		http://twitter.com/erfanimani
* @copyright 	Copyright (c) 2012 TweakIT.eu
* @link			https://github.com/erfanimani/Ezimport
* @license		http://www.opensource.org/licenses/mit-license.html
*/

class Tweakit_Ezimport_Block_Source_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('resourceGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('desc');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }

    protected function _prepareCollection()
    {
        $store = $this->_getStore();
        $collection = Mage::getModel('ezimport/source')->getCollection();

        if ($store->getId()) {
            $collection->addStoreFilter($store);
        }
        else {
        }

        $this->setCollection($collection);

        parent::_prepareCollection();
        return $this;
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('source_id');
        $this->getMassactionBlock()->setFormFieldName('source');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'=> Mage::helper('ezimport')->__('Delete'),
             'url'  => $this->getUrl('*/*/massDelete'),
             'confirm' => Mage::helper('ezimport')->__('Are you sure?')
        ));

        return $this;
    }

    protected function _prepareColumns()
    {
        $this->addColumn('id',
            array(
                'header'=> Mage::helper('ezimport')->__('ID'),
                'width' => '',
                'type'  => 'number',
                'index' => 'id',
        ));
		
        $this->addColumn('name',
            array(
                'header'=> Mage::helper('ezimport')->__('Name'),
                'index' => 'name',
        ));
		
        $this->addColumn('type',
            array(
                'header'=> Mage::helper('ezimport')->__('Type'),
                'index' => 'type',
        ));

        $this->addColumn('type',
            array(
                'header'=> Mage::helper('ezimport')->__('Location'),
                'index' => 'url',
        ));

        $this->addColumn('Date',
            array(
                'header'=> Mage::helper('ezimport')->__('Date'),
                'index' => 'timestamp',
        ));
		
        $this->addColumn('action',
            array(
                'header'    => '',
                'width'     => '',
                'type'      => 'action',
                'getter'     => 'getId',
                'actions'   => array(
                    array(
                        'caption' => Mage::helper('ezimport')->__('Map'),
                        'url'     => array(
                            'base'=>'*/map/edit'
                        ),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false
        ));
		
		$this->addColumn('action3',
            array(
                'header'    => '',
                'width'     => '',
                'type'      => 'action',
                'getter'     => 'getId',
                'actions'   => array(
                    array(
                        'caption' => Mage::helper('ezimport')->__('View products'),
                        'url'     => array(
                            'base'=>'*/source/view'
                        ),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false
        ));
		
        $this->addColumn('action2',
            array(
                'header'    => '',
                'width'     => '',
                'type'      => 'action',
                'getter'     => 'getId',
                'actions'   => array(
                    array(
                        'caption' => Mage::helper('ezimport')->__('Delete'),
                        'url'     => array(
                            'base'=>'*/source/delete'
                        ),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
				'confirm' => Mage::helper('ezimport')->__('Are you sure?')				
        ));

        return parent::_prepareColumns();
    }

}