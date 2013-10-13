<?php

namespace DoopageFramework\Core;

/**
 * Description of Autoload
 *
 * @author Kurt
 */
class Lib_AutoloadApp {
    
    /**
     * Instance principal de App
     * 
     * @var \DoopageFramework\Core\App 
     */
    protected static $app;
    protected static $cacheFile;
            
    function __construct(\DoopageFramework\Core\App $app) {
        self::$app = $app;
        
       
    }
    
    function init(){
        self::$cacheFile = self::$app->getAppCacheDir().DIRECTORY_SEPARATOR.'overrideClass.cache.php';
        
        if(self::$app->getOverrideCache()){
            if(file_exists(self::$cacheFile)) require_once self::$cacheFile;
        }
    }
    
    /**
     * Methode pour l'autoload
     * 
     * @param string $className Class à charger
     */
    public function load($className){
        
        $filePath = ltrim($className, '\\');
        
        if(substr($filePath, 0, 4) == "App\\"){
            $filePath = substr($filePath, 4);
            
            if ($lastNsPos = strripos($filePath, '\\')) {
                $filePath = substr($filePath, $lastNsPos + 1);
            }
            
            $classNameSave = $filePath;
            $filePath = str_replace('_', DIRECTORY_SEPARATOR, $filePath) . '.php';
            
            
            foreach(self::$app->getAppDirectory() as $appDir)
            {
                $dir = $appDir['folder'].DIRECTORY_SEPARATOR.$filePath;
                
                if(file_exists($dir)){
                    
                    require_once $dir;
                    $this->createOverrideClass($classNameSave, $appDir['namespace']);
                    break;
                }
            }
        }
    }
    
    private function createOverrideClass($appClassName, $overrideNamespace){
        if(substr($overrideNamespace, 0, 1) != "\\") $overrideNamespace = "\\".$overrideNamespace;
        $appClassName =  ltrim($appClassName, '\\');
        
        $createClass = "namespace App{ class $appClassName extends $overrideNamespace\\$appClassName {} }";
        
        eval($createClass);
        
        if(self::$app->getOverrideCache()){
            if(!file_exists(self::$cacheFile)) mkdir (dirname (self::$cacheFile), 0777, true);
            $fichierCache = fopen(self::$cacheFile, 'a+');
            if(ftell($fichierCache) == 0) fputs($fichierCache, "<?php \n");
            fputs($fichierCache, $createClass."\n");
            fclose($fichierCache);
        }
    }
}

?>
