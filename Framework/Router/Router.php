<?
namespace Framework\Router;

use Framework\Exception\HttpNotFoundExeption;
use Framework\Request\Request;

/**
 * Class Router
 * @package Framework\Router
 */
    
class Router {
    
    private $validation;
    private $request;
    
    
    public function __construct(){
        $this->request = new Request();
    }

    /**
     * Finds necessary information from routes
     * @param null $routes
     * @return mixed
     */

    public function start($routes = null){
        $parts = explode('/', $this->request->getRequestInfo('uri'));
        array_shift($parts);
        $str = '/'.implode('/', $parts);

        $result = $this->reg($str);

        if (!is_null($routes)) {
            foreach ($routes as $value) {
                if ($result['pattern'] == $value['pattern']){
                    $route = $value;
                }
            }
            if ($route) {
                $route['id'] = $result['id']; //add 'id' to result array
                return $route;
            }else{
                new HttpNotFoundExeption('pattern');
            }
        }else{
            new HttpNotFoundExeption('routes');
        }
    }

    private function reg($str){
        $id = null;
        $pattern = '/\/\d+/';

        preg_match_all($pattern, $str, $id);
        $id = (!is_null($id))? substr($id[0][0], 1) : null;

        $str = preg_replace($pattern, '/{id}', $str);

        return array('pattern'=>$str, 'id'=>$id);
    }

}
?>