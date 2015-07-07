<?php

/**
 * Log system events, exception, custom log messages etc
 *
 * @author Md. Sarwar Zahan <md.sarwar.zahan@gmail.com>
 *
 */
class Common_Zend_Log extends Zend_Log
{

    const DEBUG_BACKTRACE = 8; //For debug backtrace type

    /**
     * @var string
     */

    protected $_logRegistryName = 'logger';

    /**
     * Get the log object instance from registry
     * 
     * @return Common_Zend_Log
     */
    public function getLogRegistryInstance() 
    {
        return Zend_Registry::get($this->_logRegistryName);
    }

    /**
     * Get the log object instance to registry
     * 
     * @param string $registryName
     * @return void 
     */
    public function setLogRegistryInstance($registryName = 'logService') 
    {
        $this->_logRegistryName = $registryName;
        Zend_Registry::set($this->_logRegistryName, $this);
    }

    /**
     * Add a syslog writer
     * 
     * @param array $params
     * @param object $formatter
     * @return void 
     */
    public function addSyslogWriter($params = array(), Zend_Log_Formatter_Interface $formatter = null) 
    {
        $writer = new Zend_Log_Writer_Syslog($params);
        if ($formatter) {
            $writer->setFormatter($formatter);
        }
        $this->addWriter($writer);
    }

    /**
     * Add a stream writer
     * 
     * @param array $params
     * @param object $formatter
     * @return void 
     */
    public function addStreamWriter($params = array(), Zend_Log_Formatter_Interface $formatter = null) 
    {
        $writer = new Zend_Log_Writer_Stream($params['file_path_url'], $params['mode']);
        if ($formatter) {
            $writer->setFormatter($formatter);
        }
        $this->addWriter($writer);
    }

    /**
     * Add a Database table writer
     * 
     * @param array $params
     * @return void 
     */
    public function addDbWriter($params = array()) 
    {
        $writer = new Zend_Log_Writer_Db($params['db'], $params['table'], $params['columnMap']);
        $this->addWriter($writer);
    }
    
    /**
     * Add a Firebug writer
     * 
     * @return void 
     */
    public function addFirebugWriter() 
    {
        $writer = new Zend_Log_Writer_Firebug();
        $writer->setEnabled(true);
        $this->addWriter($writer);
    }

    /**
     * Add multiple writer
     * 
     * @param array $writers
     * @return void 
     */
    public function addWriters($writers = array()) 
    {
        foreach ($writers as $writer) {
            $this->addWriter($writer);
        }
    }

    /**
     * Add multiple filter
     * 
     * @param array $filters
     * @return void 
     */
    public function addFilters($filters = array()) 
    {
        foreach ($filters as $filter) {
            $this->addFilter($filter);
        }
    }

    /**
     * Remove previously added writer by type or index
     * 
     * @param mixed $writer
     * @return void 
     */
    public function removeWriter($writer) 
    {
        if ($writer instanceof Zend_Log_Writer_Abstract) {
            //Remove already added writer object
            foreach ($this->_writers as $key => $singleWriter) {
                if ($writer === $singleWriter) {
                    unset($this->_writers[$key]);
                }
            }
        } elseif (gettype($writer) == 'string') {
            //Remove by class type
            foreach ($this->_writers as $key => $singleWriter) {
                if ($writer == get_class($singleWriter)) {
                    unset($this->_writers[$key]);
                }
            }
        } elseif (gettype($writer) == 'integer') {
            //Remove by array index
            unset($this->_writers[$writer]);
        } else {
            throw new Zend_Log_Exception(
                    'Writer must be an instance of Zend_Log_Writer_Abstract'
                    . ' or you should pass a class type or array index'
            );
        }

        //Finally rearrange the array
        $this->_writers = array_values($this->_writers);
    }

    /**
     * Remove previously added filter by type or index
     * 
     * @param mixed $filter
     * @return void 
     */
    public function removeFilter($filter) 
    {
        if ($filter instanceof Zend_Log_Filter_Abstract) {
            //Remove already added filter object
            foreach ($this->_filters as $key => $singleFilter) {
                if ($filter === $singleFilter) {
                    unset($this->_filters[$key]);
                }
            }
        } elseif (gettype($filter) == 'string') {
            //Remove by class type
            foreach ($this->_filters as $key => $singleFilter) {
                if ($filter == get_class($singleFilter)) {
                    unset($this->_filters[$key]);
                }
            }
        } elseif (gettype($filter) == 'integer') {
            //Remove by array index
            unset($this->_filters[$filter]);
        } else {
            throw new Zend_Log_Exception(
                    'Writer must be an instance of Zend_Log_Filter_Abstract'
                    . ' or you should pass a type or array index'
            );
        }
    }

    /**
     * To trace debug messages which uses debug_backtrace() PHP function
     * 
     * @param string $message
     * @return void 
     */
    public function debugBacktrace($message = null) 
    {
        $backtrace = array_shift(debug_backtrace());
        unset($backtrace['object']);
        $backtrace = print_r($backtrace, true);
        $message = (array) $message;
        array_push($message, $backtrace);
        $message = array(implode(': ', $message));
        $this->__call('DEBUG_BACKTRACE', $message);
    }

}