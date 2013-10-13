<?php
namespace DoopageFramework\Core;

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/Lib/AutoloadCore.php';
require_once __DIR__.'/Lib/AutoloadApp.php';


class ServiceNotFoundException extends \InvalidArgumentException {}

/**
 * Application principal
 *
 * @author Flaceliere Brice <contact@b-flaceliere.fr>
 */
class App {
    
    /**
     * Instance de App pour le Signeton
     * @var \DoopageFramework\Core\App
     */
    protected static $instance;
    
    /**
     * Liste des service
     * @var mixed[]
     */
    protected $service = array();
    
    /**
     * Liste des application
     * @var string[]  
     */
    protected $appDirectory = array();
    
    /**
     * Active le cache pour la surcharge de class
     * 
     * @var bool 
     */
    protected $overrideCache = true;
    
    protected $autoloadApp;

    private function __construct(){
        if(ENV == 'DEV') $this->setOverrideCache(false);
        
        $this->addAppDirectory(__NAMESPACE__, dirname(__FILE__));
        
        $autoloadCore = new \DoopageFramework\Core\Lib_AutoloadCore($this);
        $this->autoloadApp = new \DoopageFramework\Core\Lib_AutoloadApp($this);
        
        spl_autoload_register(array($autoloadCore, 'load'), true, true);
        spl_autoload_register(array($this->autoloadApp, 'load'), true, true);
    }
    
    /**
     * Signeton retourne l'instance de la class courante
     * 
     * @return \DoopageFramework\Core\App
     */
    final public static function instance(){
        if(self::$instance instanceof \App) return self::$instance;
        else return new self();
    }
    
    /**
     * Ajoute une application
     * 
     * @param string $namespace Namespace de l'application
     * @param string $directory Dossier de l'application
     * @return \DoopageFramework\Core\App
     */
    final public function addAppDirectory($namespace, $directory) {
        array_unshift($this->appDirectory, array('namespace' => $namespace, 'folder' => realpath($directory)));
        
        return $this;
    }
    
    /**
     * Initialize le Core
     * 
     * @return \DoopageFramework\Core\App
     */
    private function init() {
        echo $this->autoloadApp->init();
        return $this;
    }
    
    /**
     * Execute l'application
     * 
     * @return \DoopageFramework\Core\App
     */
    final public function exec()
    {
        $this->init();
        
        return $this;
    }
    
    /**
     * Ajoute un service
     * 
     * @param string $name Ajoute un nom
     * @param mixed $instance Instance du service
     * @return \this
     */
    final public function addServiceSafe($name, $instance)
    {
        if(!isset($this->service[$name])) $this->service[$name] = $instance;
        
        return $this;
    }

    /**
     * Ajoute / Remplace un service
     * 
     * @param string $name Ajoute un nom
     * @param mixed $instance Instance du service
     * @return \this
     */
    final public function addService($name, $instance)
    {
        $this->service[$name] = $instance;
        
        return $this;
    }
    
    /**
     * Retourne une instance d'un service
     * 
     * @param string $name Nom du service 
     * @return mixed instance du service
     * @throws ServiceNotFoundException Lancer si le service n'est pas trouver
     */
    final public function getService($name)
    {
        if(isset($this->service[$name])) return $this->service[$name];
        else throw new ServiceNotFoundException('Service introuvable');
    }
    
    /**
     * Retourne la liste des dossiers et namespace de l'application
     * 
     * Format : array(
     *              array('namespace', 'dir')
     *          )
     * 
     * @return array[] liste des dossiers / namespaces des applications
     */
    public function getAppDirectory() {
        return $this->appDirectory;
    }
    
    /**
     * Retourne le dossier corespondnat au namespace
     * 
     * @param string $namespace
     * @return string 
     */
    public function getAppFolderByNamespace($namespace) {
        foreach($this->getAppDirectory() as $v){
            if($v['namespace'] == $namespace) return $v['folder'];
        }
        return "";
    }
    
    /**
     * Retourne le dossier de cache de l'application courante
     * 
     * @return string 
     */
    public function getAppCacheDir() {
        return $this->appDirectory[0]['folder'].DIRECTORY_SEPARATOR."Cache";
    }
    
    
    public function getOverrideCache() { return $this->overrideCache; }
    public function setOverrideCache($overrideCache) { $this->overrideCache = $overrideCache; return $this; }


}

?>
