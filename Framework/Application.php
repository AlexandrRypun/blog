<?php

namespace Framework;
session_start();
use Blog\Model\User;
use Framework\DI\Service;
use Framework\Exception\AccessException;
use Framework\Exception\ServerErrorException;
use Framework\Exception\HttpNotFoundExeption;
use Framework\Model\DB;
use Framework\Renderer\Renderer;
use Framework\Request\Request;
use Framework\Response\AResponse;
use Framework\Response\Response;
use Framework\Response\ResponseRedirect;
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
        $this->request = new Request();
        $this->config = include_once $this->request->change_slashes($config);

        $sl = Service::getInstance();

        $sl->set('security', new Security());
        $sl->set('session', new Session());
        $sl->set('router', new Router($this->config['routes']));
        $sl->set('db', new \PDO($this->config['pdo']['dsn'], $this->config['pdo']['user'], $this->config['pdo']['password']));
        $sl->set('app', $this);
    }
    
     
    
    public function run(){
        $route = Service::get('router')->start();
        $this->savePathToView($route['controller']);

        $vars = null;
        if (!empty($route['vars'])) $vars = $route['vars'];

        if  (!empty($route['security'])){
            $user = Service::get('session')->get('user');
            if (is_object($user)) {
                if (array_search($user->getRole(), $route['security']) === false){
                    new AccessException('access denied');
                }
            }else{
                $redirect = new ResponseRedirect(Service::get('router')->buildRoute($this->config['security']['login_route']));
                $redirect->send();
            }

        }

        Service::get('session')->setReturnUrl($this->request->getRequestInfo('referer'));

        $response = $this->startController($route['controller'], $route['action'], $vars);

        if ($response->getType() == 'html'){

            $flush = (Service::get('session')->get('flush'))?Service::get('session')->get('flush'):array();
            Service::get('session')->delFromSess('flush');

            $content['content'] = $response->getContent();
            $content['flush'] = $flush;

            $renderer = new Renderer($this->config['main_layout'],$content);

            $response = new Response($renderer->render());

        }

        $response->send();
    }

    public function startController($controller, $action, $vars=array()){

        $controller = new $controller;
        $action = $action.'Action';

        $refl = new \ReflectionClass($controller);
        if ($refl->hasMethod($action)) {
            $method = new \ReflectionMethod($controller, $action);
            $params = $method->getParameters();

            if (empty($params)) {
                $response = $method->invoke(new $controller);
            }else{
                foreach ($params as $value){
                    if (isset($vars[$value->getName()])) {
                        $parameters[$value->getName()] = $vars[$value->getName()];
                    }else{
                        new HttpNotFoundExeption('parameters for method '.$method->getName());
                    }

                }
                $response = $method->invokeArgs(new $controller, $parameters);
            }

            if ($response instanceof AResponse){
                return $response;

            }else{
                new ServerErrorException(500, 'Sory, server error', $this->config['error_500']);
            }

        }else{
            new HttpNotFoundExeption('method');
        }
    }

    private function savePathToView($controller){
        $parts = explode('\\', $controller);
        $last_part = array_pop($parts);
        $path_to_view = str_replace('Controller', '', $last_part);
        Service::get('session')->addToSess('path_to_view', $path_to_view);
    }


}