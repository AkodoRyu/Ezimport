<?php

/**
 * 
 * Installation script
 * 
 * To undo this installation script:
 * 
 * drop table ezimport_map;
 * drop table ezimport_source;
 * DELETE from core_resource where code = 'ezimport_setup';
 * 
 * and delete the ezimport folder in your /var/tmp
 * 
 * @author    	http://twitter.com/erfanimani
 * @copyright   Copyright (c) 2012 TweakIT.eu
 * @link        https://github.com/erfanimani/Ezimport
 * @license    	http://www.opensource.org/licenses/mit-license.html
 * 
*/
 

$installer = $this;
$installer->startSetup();

$installer->run("
    CREATE TABLE `{$installer->getTable('ezimport/source')}` (
      `id` int(11) NOT NULL auto_increment,
      `name` text,
      `url` text,
      `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP,
      PRIMARY KEY  (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

$installer->run("
    CREATE TABLE `{$installer->getTable('ezimport/map')}` (
      `id` int(11) NOT NULL auto_increment,
      `source_id` text,
      `helper_attribute` text,
      `source_attribute` text,
      `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP,
      PRIMARY KEY  (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");


$ezimport_dir = Mage::getBaseDir('tmp') . DS . 'ezimport';

$xml_dir = Mage::getBaseDir('tmp') . DS . 'ezimport' . DS . 'xml';

$tmp_img_dir = Mage::getBaseDir('tmp') . DS . 'ezimport' . DS . 'temp_img';

$errors = array();

if(!file_exists($ezimport_dir))
	$errors[] = mkdir($ezimport_dir);
		
if(!file_exists($xml_dir))
	$errors[] = mkdir($xml_dir);

if(!file_exists ( $tmp_img_dir ))
	$errors[] = mkdir($tmp_img_dir);

	
if(in_array(FALSE, $errors))
	Mage::getSingleton('adminhtml/session')->addWarning("The installation script coudnt create some Ezimport tmp directories (where xmls are saved and stuff). Please create them and make it writable for me.");
	
$installer->endSetup();

?>    