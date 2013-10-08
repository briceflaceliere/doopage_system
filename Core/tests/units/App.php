<?php

namespace DoopageFramework\Core\tests\units;

require_once __DIR__.'/../../App.php';

use \atoum;

/**
 * Test de App
 *
 * @author Flaceliere Brice <contact@b-flaceliere.fr>
 */
class App extends atoum
{
    public function testInstance(){
          $this->object(\DoopageFramework\Core\App::instance())
                    ->isInstanceOf('\\DoopageFramework\\Core\\App');
    }
    
    public function testAddAppDirectory(){
          $app = \DoopageFramework\Core\App::instance()
                    ->addAppDirectory('\\DoopageFramework\\User', __DIR__.'/../../../User/');
          
          $this->object($app)->isInstanceOf('\\DoopageFramework\\Core\\App');
          
          $this->array($app->getAppDirectory())
                    ->hasSize(2);
    }
    
    public function testGetAppDirectory(){
          $app = \DoopageFramework\Core\App::instance();
          
          $this->array($app->getAppDirectory())
                    ->hasSize(1);
          
          
          
         $app->addAppDirectory('\\DoopageFramework\\User', __DIR__.'/../../../User/');
         
         $this->array($app->getAppDirectory())
                    ->hasSize(2);
    }
    
    public function testInit(){
        $app = \DoopageFramework\Core\App::instance();
        
        $this->object($app->init())->isInstanceOf('\\DoopageFramework\\Core\\App');
    }
    
    public function testExec(){
        $app = \DoopageFramework\Core\App::instance();
        
        $this->object($app->init())->isInstanceOf('\\DoopageFramework\\Core\\App');
    }
    
}

?>
