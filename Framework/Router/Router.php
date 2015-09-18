<?
namespace Framework\Router;

/**
 * Class Router
 * @package Framework\Router
 */
    
class Router {
    
    private $validation;
    private $request;
    
    
    public function __construct(){
        $this->request = new \Framework\Request\Request;
    }

    /**
     * Finds necessary information from routes
     * @param null $routes
     * @return mixed
     */

    public function start($routes = null){
        $parts = explode('/', $this->request->getRequestInfo('uri'));
        array_shift($parts);
        $pattern = '/'.implode('/', $parts);

        if (!is_null($routes)) {
            foreach ($routes as $value) {
                if ($pattern == $value['pattern']){
                    $route = $value;
                }
            }
            if ($route) {
                return $route;
            }else{
                new \Exception('no route');
                die();
            }
        }else{
            new \Exception('no routes');
            die();
        }
    }
}
?>