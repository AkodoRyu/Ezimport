<?php

class Tweakit_Ezimport_Block_Source_New extends Tweakit_Ezimport_Block_Source
{
	
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('source/new.phtml');
    }

}
