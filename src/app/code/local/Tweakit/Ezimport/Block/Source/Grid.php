<?php

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
		
        $this->addColumn('action3',
            array(
                'header'    => '',
                'width'     => '',
                'type'      => 'action',
                'getter'     => 'getId',
                'actions'   => array(
                    array(
                        'caption' => Mage::helper('ezimport')->__('View/Import products'),
                        'url'     => array(
                            'base'=>'*/source/view'
                        ),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false
        ));
	
        $this->addColumn('action4',
            array(
                'header'    => '',
                'width'     => '',
                'type'      => 'action',
                'getter'     => 'getId',
                'actions'   => array(
                    array(
                        'caption' => Mage::helper('ezimport')->__('Import all products'),
                        'url'     => array(
                            'base'=>'*/source/processImport'
                        ),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
				'confirm' => Mage::helper('ezimport')->__('This will import all the products in this source to the Magento Catalog. Do you want to continue?')
        ));		
		
		
/*        $this->addColumn('subject_id',
            array(
                'header'=> Mage::helper('iprovider')->__('Subject'),
                'index' => 'subject_id',
                'options' => Mage::getSingleton('iprovider/subject')->getOptionArray(),
        ));
        $this->addColumn('date_time',
            array(
                'header'=> Mage::helper('iprovider')->__('Date'),
                'index' => 'date_time',
        ));
        $this->addColumn('message',
            array(
                'header'=> Mage::helper('iprovider')->__('Message'),
                'index' => 'message',
        ));
*/

        return parent::_prepareColumns();
    }

    /* Makes Grid clickable */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

    /* Makes row clickable */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/products', array(
            'store'=>$this->getRequest()->getParam('store'),
            'id'=>$row->getId())
        );
    }
}