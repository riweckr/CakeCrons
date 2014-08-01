<?php 
/* cake_crons schema generated on: 2014-08-01 22:41:33 : 1406925693*/
class cake_cronsSchema extends CakeSchema {
	var $name = 'cake_crons';

	function before($event = array()) {
		return true;
	}

	function after($event = array()) {
	}

	var $cake_crons = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'task' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'priority' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 3),
		'next_start' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'interval' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'last_start' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'last_runtime' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10),
		'status' => array('type' => 'text', 'null' => false, 'default' => 'ready', 'length' => 7, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
}