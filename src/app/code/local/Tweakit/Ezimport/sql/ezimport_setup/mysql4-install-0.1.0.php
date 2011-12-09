<?php

/**
 * To undo this script:
 * 
 * DELETE from core_resource where code = 'ezimport_setup';
 * 
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

$installer->endSetup();

?>    