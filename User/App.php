<?php
namespace DoopageFramework\User;

require_once __DIR__.'/../Core/App.php';

/**
 * Description of App
 *
 * @author Kurt
 */
 class App extends \DoopageFramework\Core\App {
     
    public function init()
    {
        $this->addAppDirectory(__NAMESPACE__, basename(__FILE__));
        
        parent::Init();
    }
    
    
}

?>
