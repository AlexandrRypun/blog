<?php

namespace Framework;

use Blog\Model\User;
use Framework\DI\Service;
use Framework\Exception\AccessException;
use Framework\Exception\ServerErrorException;
use Framework\Exception\HttpNotFoundExeption;
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
      /**  try {
            echo new \PDO($this->config['pdo']['dsn'], $this->config['pdo']['user'], $this->config['pdo']['password']);
        } catch (\PDOException $e) {
            echo 'No connect to db: ' . $e->getMessage();
        }*/
    }
    
     
    
    public function run(){
        $route = Service::get('router')->start();
        $this->savePathToView($route['controller']);

        $controller = new $route['controller'];
        $action = $route['action'].'Action';

        $vars = null;
        if (!empty($route['vars'])) $vars = $route['vars'];

        if  (!empty($route['security'])){
            $user = Service::get('session')->get('user');
            if ($user instanceof User) {
                if (array_search($user->getRole(), $route['security']) === false){
                    new AccessException('access denied');
                    die;
                }
            }else{
                $redirect = new ResponseRedirect(Service::get('router')->buildRoute('login'));
                $redirect->send();
            }

        }

        Service::get('session')->setReturnUrl($this->request->getRequestInfo('referer'));

        $response = $this->startController($controller, $action, $vars);

        if ($response instanceof AResponse){
            if ($response->getType() == 'html'){

                $renderer = new Renderer($this->config['main_layout'], $response->getContent());

                $response = new Response($renderer->render());

            }
            $response->send();
        }else{
            new ServerErrorException(500, 'Sory, server error', $this->config['error_500']);
        }




    }

    private function startController($controller, $action, $vars){
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

            return $response;

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