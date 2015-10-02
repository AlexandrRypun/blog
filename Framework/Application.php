<?php
namespace Framework;

use Framework\DI\Service;
use Framework\Exception\HttpNotFoundExeption;
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

        $vars = null;
        if (!empty($route['vars'])) $vars = $route['vars'];

        $this->startController($controller, $action, $vars);
    }

    private function startController($controller, $action, $vars){
        $refl = new \ReflectionClass($controller);
        if ($refl->hasMethod($action)) {
            $method = new \ReflectionMethod($controller, $action);
            $params = $method->getParameters();

            if (empty($params)) {
                $method->invoke(new $controller);
            }else{
                foreach ($params as $value){
                    if (isset($vars[$value->getName()])) {
                        $parameters[$value->getName()] = $vars[$value->getName()];
                    }else{
                        new HttpNotFoundExeption('parameters for method '.$method->getName());
                    }

                }
                $method->invokeArgs(new $controller, $parameters);
            }

        }else{
            new HttpNotFoundExeption('method');
        }
    }


}