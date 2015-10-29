<?
/**
 * Create class Render
 */

namespace Framework\Renderer;


use Framework\DI\Service;

class Renderer {

    protected $layout;
    protected $content;

    /**
     * @param string $layout
     * @param mixed $content
     */

    public function __construct($layout, $content){
        $this->content = $content;

        if (file_exists($layout)){
            $this->layout = $layout;
        }else{
            $dir = Service::get('session')->get('path_to_view');
            $this->layout = '../src/Blog/views/'.$dir.'/'.$layout.'.php';
        }
    }

    /**
     * Method includes layout and substitutes data. Result is written into buffer and is returned
     * Method has realization of some callbacks
     *
     * @return string
     */

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

        $generateToken = function(){
            $token = Service::get('security')->generateToken();
            echo '<input type = "hidden" name = "token" value = "'.$token.'">';
        };

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