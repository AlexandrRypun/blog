<?
namespace Framework\Renderer;

use Framework\Application;
use Framework\DI\Service;

class Renderer {

    protected $layout;
    protected $content;


    public function __construct($layout, $content){
        $this->content = $content;

        if (file_exists($layout)){
            $this->layout = $layout;
        }else{
            $dir = Service::get('session')->get('path_to_view');
            $this->layout = '../src/Blog/views/'.$dir.'/'.$layout.'.php';
        }
    }

    public  function  render(){

        $getRoute = function ($name){
            return Service::get('router')->buildRoute($name);
        };

        $user = Service::get('session')->get('user');

        $include = function($controller, $action, $params = array()){
            $response = Service::get('app')->startController($controller, $action, $params);
            if ($response) $response->send();
        };

        $route = Service::get('router')->start();

        ob_start();
        if (is_array($this->content)){
            extract($this->content);
        }else{
            $content = $this->content;
        }
        include $this->layout;
        return ob_get_clean();
    }


}

?>