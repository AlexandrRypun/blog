<?
namespace Framework\Router;

use Framework\Exception\HttpNotFoundExeption;
use Framework\Request\Request;

/**
 * Class Router
 * @package Framework\Router
 */
    
class Router {

    private $request;
    private $routes;
    
    
    public function __construct($routes = null){
        $this->request = new Request();
        $this->routes = $routes;
    }

    /**
     * Finds necessary information from routes
     * @param null $routes
     * @return mixed
     */

    public function start(){

        $uri =  $this->request->getRequestInfo('uri');

        if (!is_null($this->routes)) {
            foreach ($this->routes as $key=>$value) {
                if (strpos($value['pattern'], '{')) {
                    $res = $this->patToReg($value);
                    $pattern = $res[0];
                    $vars = $this->getVars($pattern, $res[1], $uri);
                }else{
                    $pattern = $value['pattern'];
                }

                if (preg_match('~^'.$pattern.'$~', $uri)) {
                    $route = $value;
                    break;
                }

            }

            if (!empty($route)){
                $route['_name'] = $key;
                if (!empty($vars)) $route['vars'] = $vars;
                return $route;
            }else{
                new HttpNotFoundExeption('route');
            }

        }else{
            new HttpNotFoundExeption('routes');
        }
    }


    private function patToReg($route = array()){

        $pattern = '/\{[\w\d_]+\}/Ui';
        preg_match_all($pattern, $route['pattern'], $matches);


        foreach ($matches[0] as $value){
            if(array_key_exists(trim($value, '{}'), $route['_requirements'])) {
                $replacement[] = '('.$route['_requirements'][trim($value, '{}')].')';
            }
        }

        $str = str_replace($matches[0], $replacement, $route['pattern']);

        return array($str, $matches[0]);
    }


    private function getVars($pattern, $keys, $uri){

        preg_match('~'.$pattern.'~i', $uri, $matches);

        foreach ($keys as $key=>$value){
            if (isset($matches[$key+1])){
                $vars[trim($value, '{}')] = $matches[$key+1];
            }
        }

        return $vars;
    }

    public function buildRoute($name, $params = array()){

        if (!is_null($this->routes)){
            $pattern = null;
            foreach($this->routes as $key=>$value){
                if ($key == $name){
                    $pattern = $value['pattern'];
                    if (strpos($pattern, '{') && !empty($params)) {

                        $p = '/(\{[\w\d_]+\})/Ui';
                        preg_match($p, $value['pattern'], $matches);

                        foreach ($params as $k=>$v){
                            $pattern = str_replace($matches[$k+1], $v, $pattern);
                        }
                    }
                    break;
                }

            }
            if (is_null($pattern)) $pattern = '/';

        }else{
            new HttpNotFoundExeption('route');
        }

        return $pattern;
    }

}
?>