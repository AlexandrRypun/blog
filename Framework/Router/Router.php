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

        $uri =  $this->request->getRequestInfo('uri');

        if (!is_null($routes)) {
            foreach ($routes as $value) {
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

}
?>