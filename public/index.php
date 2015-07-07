<?php

error_reporting(E_ALL ^ E_NOTICE);

// Define path to application directory
defined('APPLICATION_PATH')
|| define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    realpath(APPLICATION_PATH . '/../vendor/zendframework/zendframework1/library'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);

$application->bootstrap()
    ->run();

// Use the following code to access logging functionality
/*
$registry = Zend_Registry::getInstance();
$logger = $registry->get('logger');
$logMessage = "Script execution started for " . $projectName . "\n";
echo $logMessage;
$logger->log($logMessage, NOTICE);*/