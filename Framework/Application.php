<?php
/**
 * Create class Application
 */

namespace Framework;
session_start();
use Blog\Model\User;
use Framework\DI\Service;
use Framework\Exception\AccessException;
use Framework\Exception\ServerErrorException;
use Framework\Exception\HttpNotFoundException;
use Framework\Renderer\Renderer;
use Framework\Request\Request;
use Framework\Response\AResponse;
use Framework\Response\Response;
use Framework\Response\ResponseRedirect;
use Framework\Router\Router;
use Framework\Security\Security;
use Framework\Session\Session;


class Application {

    public $config;

    /**
     * Method records some objects into Service Locator
     *
     * @param $config
     */

    public function __construct($config){

        $sl = Service::getInstance();

        $sl->set('security', new Security());
        $sl->set('request', new Request());
        $this->config = include_once Service::get('security')->change_slashes($config);
        $sl->set('session', new Session());
        $sl->set('router', new Router($this->config['routes']));
        $sl->set('app', $this);
        try{
            $sl->set('db', new \PDO($this->config['pdo']['dsn'], $this->config['pdo']['user'], $this->config['pdo']['password']));
        }catch(\PDOException $e){
            echo $e->getMessage();die();
        }


    }

    /**
     * Method initiates the application's work
     *
     * @throws AccessException
     */
    
    public function run()
    {
        Service::get('security')->generateToken();

        try {
            if (!Service::get('security')->checkToken()) {
                throw new AccessException('tokens aren\'t the same');
            }

            //gets necessary information from Router
            $route = Service::get('router')->start();

            // if there are restrictions of rights, will check user's rights
            if (!empty($route['security'])) {
                $user = Service::get('session')->get('user');
                if (is_object($user)) {
                    if (array_search($user->getRole(), $route['security']) === false) {
                        throw new AccessException('access denied');
                    }
                } else {
                    Service::get('session')->setReturnUrl(Service::get('router')->buildRoute($route['_name']));
                    $redirect = new ResponseRedirect(Service::get('router')->buildRoute($this->config['security']['login_route']));
                    $redirect->send();
                }
            }

            $this->savePathToView($route['controller']);
            Service::get('session')->setReturnUrl(Service::get('request')->getRequestInfo('uri'));

            $vars = null;
            if (!empty($route['vars'])) $vars = $route['vars'];

            $response = $this->startController($route['controller'], $route['action'], $vars);


        }catch(AccessException $e){
            echo $e->getMessage();die();
        }catch(HttpNotFoundException $e){
            $redirect = new ResponseRedirect(Service::get('router')->buildRoute('/'));
            $redirect->send();
        }catch(ServerErrorException $e){
            $renderer = new Renderer($e->layout, array('message'=>$e->message, 'code'=>$e->code));
            $response = new Response($renderer->render());
            $response->send();
            die();
        }



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


    /**
     * Method starts necessary method of necessary controller with help of Reflection
     *
     * @param string $controller
     * @param string $action
     * @param array $vars
     * @throws HttpNotFoundException
     * @throws \Exception
     *
     * @return object
     */
    public function startController($controller, $action, $vars=array()){

        $controller = new $controller;
        $action = $action.'Action';

        $refl = new \ReflectionClass($controller);
        try{
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
                           throw new HttpNotFoundException('parameters for method '.$method->getName());
                        }

                    }
                    $response = $method->invokeArgs(new $controller, $parameters);
                }


            if ($response instanceof AResponse){
                return $response;

            }else{
               throw new ServerErrorException(500, 'Sory, server error', $this->config['error_500']);
            }

        }else{
            throw new HttpNotFoundException('method not found');
        }
        }catch (HttpNotFoundException $e){
            throw $e;
        }catch(ServerErrorException $e){
            $renderer = new Renderer($e->layout, array('message'=>$e->message, 'code'=>$e->code));
            $response = new Response($renderer->render());
            $response->send();
            die();
        }
    }


    /**
     * Method creates path to necessary View from controller's name and saves it into Session array
     *
     * @param string $controller
     */
    private function savePathToView($controller){
        $parts = explode('\\', $controller);
        $last_part = array_pop($parts);
        $path_to_view = str_replace('Controller', '', $last_part);
        Service::get('session')->addToSess('path_to_view', $path_to_view);
    }


}