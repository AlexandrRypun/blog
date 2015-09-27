<?php
namespace Framework;

use Framework\DI\Service;
use Framework\Exception\NotFoundExeption;
use Framework\Request\Request;
use Framework\Router\Router;
use Framework\Security\Security;
use Framework\Session\Session;

/**
 * Class Application
 * @package Framework
 */

class Application {
    private $config;
    private $request;

    
    public function __construct($config){
        $sl = Service::getInstance();
        $sl->set('security', new Security());
        $sl->set('session', new Session());
        $sl->set('router', new Router());

        $this->request = new Request();
        $this->config = include_once $this->request->change_slashes($config);
    }
    
     
    
    public function run(){
        $route = Service::get('router')->start($this->config['routes']);
        $controller = new $route['controller'];
        $action = $route['action'].'Action';
        if (!empty($route['id'])) $id = $route['id'];

        $this->startController($controller, $action, $id);
    }

    private function startController($controller, $action, $id){
        $refl = new \ReflectionClass($controller);
        if ($refl->hasMethod($action)) {
            $method = new \ReflectionMethod($controller, $action);
            $method->invoke(new $controller, $id);
        }else{
            new HttpNotFoundExeption('method');
        }
    }


}