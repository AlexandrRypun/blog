<?

namespace Framework\Controller;



use Framework\DI\Service;
use Framework\Renderer\Renderer;
use Framework\Request\Request;
use Framework\Response\Response;
use Framework\Response\ResponseRedirect;

abstract class Controller {

    protected function getRequest(){
        return new Request();
    }

    protected function render($layout, $content){
        $renderer = new Renderer($layout, $content);
        return new Response($renderer->render());
    }

    protected function redirect($url, $msg = ''){
        if ($msg){
            Service::get('session')->addToSess('msg', $msg);
        }
        return new ResponseRedirect($url);
    }

    protected function generateRoute($name, $params = array()){
        $router = Service::get('router');
        return $router->buildRoute($name, $params);
    }
}


?>