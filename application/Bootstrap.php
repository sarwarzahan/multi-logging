<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initAutoloaders()
    {
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->setFallbackAutoloader(true);

        $default_loader = new Zend_Application_Module_Autoloader(array(
            'namespace' => '',
            'basePath' => APPLICATION_PATH
        ));
    }

    protected function _initConfiguration()
    {
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
        $registry = Zend_Registry::getInstance();
        $registry->set('configuration', $config);

        return $config;
    }

    protected function _initFrontRegistry()
    {
        $registry = Zend_Registry::getInstance();
        $config = $registry->configuration;

        $front = $this->bootstrap('frontController')->getResource('frontController');
        $front = Zend_Controller_Front::getInstance();
        $front->setParam('registry', $this->getContainer());
    }

    /**
     * Registers Log object for debugging
     *
     * See Zend_Log_Writer_Stream
     */
    function _initRegisterLogger()
    {
        //@TODO put the config in main configuration file
        $config = array ('syslog' => array('application' => 'application', 'facility' => LOG_LOCAL2),'stream' => array ('file_path_url' => 'log/debug.log' , 'mode' => null));
        $logger = LogService::getInstance($config);

        $registry = Zend_Registry::getInstance();
        $registry->set('logger', $logger);
    }
}

