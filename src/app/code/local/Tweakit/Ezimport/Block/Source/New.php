<?php
/**
 * Catalog manage products block
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Tweakit_Ezimport_Block_Source_New extends Tweakit_Ezimport_Block_Source
{
    /**
     * Set template
     */
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('source/new.phtml');
    }

}
