<?php


return [

	'path' => public_path() . '/backups/',

	'mysql' => [
		'dump_command_path' => '/usr/bin/',
		//'dump_command_path' => 'C:/xampp/mysql/bin/',
		//'restore_command_path' => 'C:/xampp/mysql/bin/',
		'restore_command_path' => '/usr/bin/',
	],

	's3' => [
		'path' => ''
	],

    'compress' => true,
];

