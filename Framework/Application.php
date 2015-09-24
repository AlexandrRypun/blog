<?php
namespace Framework;
use Framework\Request\Request;
use Framework\Router\Router;

/**
 * Class Application
 * @package Framework
 */

class Application {
    private $config;
    private $router;
    private $request;

    
    public function __construct($config){
        $this->request = new Request();
        $this->router = new Router();
        $this->config = include_once $this->request->change_slashes($config);
    }
    
     
    
    public function run(){
        $route = $this->router->start($this->config['routes']);
        $controller = new $route['controller'];
        $action = $route['action'].'Action';
        if ($condition){
            //form $params
        }else{
            $params = null;
        }
        $this->startController($controller, $action, $params);
    }

    private function startController($controller, $action, $params){
        $refl = new \ReflectionClass($controller);
        if ($refl->hasMethod($action)) {
            $method = new \ReflectionMethod($controller, $action);
            $method->invoke(new $controller, $params);
        }else{
            new \Exception('no method');
        }
    }


}