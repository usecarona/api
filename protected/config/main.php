<?php

/**
 * Configuration file.
 *
 * @package api\config
 * @filesource
 */
$basePath = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..';

Yii::setPathOfAlias('com', getcwd() . DIRECTORY_SEPARATOR . 'protected' . DIRECTORY_SEPARATOR . 'components');

return array(
	'basePath' => $basePath,
	'name' => 'API do Use Carona',
	'preload' => array('log'),
	'import' => array(
		'application.models.*',
		'application.components.*'
	),
	'defaultController' => 'app',
	'modules' => array(
		'gii' => array(
			'class' => 'system.gii.GiiModule',
			'password' => 'usecarona41',
			'ipFilters' => array('*'),
		),
	),
	'components' => array(
		'user' => array(
			'allowAutoLogin' => true,
		),
		'urlManager' => array(
			'urlFormat' => 'path',
			'rules' => array (
				array('app/error', 'pattern' => 'error'), 
				array('<controller>/list', 'pattern' => '<controller>', 'verb' => 'GET'),
				array('<controller>/view', 'pattern' => '<controller>/<id:\w+(-\w+)*>', 'verb' => 'GET'),
				array('<controller>/create', 'pattern' => '<controller>/', 'verb' => 'POST'),
				array('<controller>/update', 'pattern' => '<controller>/<id:\w+(-\w+)*>', 'verb' => 'PUT'),
				array('<controller>/delete', 'pattern' => '<controller>/<id:\w+(-\w+)*>', 'verb' => 'DELETE'),
			),
		),
		'db' => array(
			'class' => 'CDbConnection',
			//'connectionString' => 'mysql:host=localhost;dbname=usecarona',
			//'username' => 'usecarona',
			//'password' => 'usecarona41',
			'connectionString' => 'pgsql:host=localhost;dbname=api',
			'username' => 'postgres',
			'password' => '',
			'charset' => 'utf8',
			//'emulatePrepare' => true, 
		),
		'errorHandler' => array(
			'errorAction' => 'app/error',
		),
		'log' => array(
			'class' => 'CLogRouter',
			'routes' => array(
				array(
					'class' => 'CFileLogRoute',
					'levels' => 'error',
					'logFile' => 'error.log',
				),
				array(
					'class' => 'CFileLogRoute',
					'levels' => 'warning',
					'logFile' => 'warning.log',
				),
				array(
					'class' => 'CFileLogRoute',
					'levels' => 'trace',
					'logFile' => 'trace.log',
				),
				array(
					'class' => 'CFileLogRoute',
					'levels' => 'info',
					'logFile' => 'info.log',
				),
				array(
					'class' => 'CFileLogRoute',
					'logFile' => 'vardump.log',
					'categories' => 'vardump',
					'levels' => 'trace',
				),
			),
		),
		'request' => array(
			'class' => 'application.components.HttpRequest',
		),
		'response' => array(
			'class' => 'application.components.HttpResponse',
		),
	),
);

