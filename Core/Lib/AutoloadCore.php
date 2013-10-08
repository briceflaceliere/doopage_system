<?php

namespace DoopageFramework\Core;

/**
 * Description of Autoload
 *
 * @author Kurt
 */
class Lib_AutoloadCore {
    
    /**
     * Instance principal de App
     * 
     * @var \DoopageFramework\Core\App 
     */
    protected $app;
    
    function __construct(\DoopageFramework\Core\App $app) {
        $this->app = $app;
    }
    
    /**
     * Methode pour l'autoload
     * 
     * @param string $className Class à charger
     */
    public function load($className){
        $className = ltrim($className, '\\');
        $fileName  = '';
        $namespace = '';
        if ($lastNsPos = strripos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
        }
        
        if(isset($this->app->getAppDirectory()['\\'.$namespace]))
        {
            $fileName = $this->app->getAppDirectory()['\\'.$namespace].DIRECTORY_SEPARATOR;
        }
        
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

        if(file_exists($fileName)) require_once $fileName;
    }
}

?>
