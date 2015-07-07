<?php
/**
 * Manage Log for system events, exception, custom log messages etc
 *
 * @author Md. Sarwar Zahan <md.sarwar.zahan@gmail.com>
 *
 */
class LogService extends Common_Zend_Log
{

    /**
     * @var LogService instance
     */
    private static $_instance = null;
    
    /**
     * Initialization
     *
     * @param  array $config Array of options;
     * @return void
     */
    private function init(array $config)
    {
        if ($config['syslog']) {
            $this->addSyslogWriter($config['syslog']);
        }
        if ($config['stream']) {
            $this->addStreamWriter($config['stream']);
        }
        
        if (BG_SERVER_TYPE == 'development' || BG_SERVER_TYPE == 'testing') {
            $this->addFirebugWriter();
        }
        //Now put that in registry
        $this->setLogRegistryInstance('_logger');
    }

    public static function getInstance(array $config)
    {
        if (self::$_instance === null) {
            $instance = new self();
            $instance->init($config);
            self::$_instance = $instance;
        }
        return self::$_instance;
    }

}