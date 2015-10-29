<?
/**
 * Create class Router
 */

namespace Framework\Router;

use Framework\Exception\HttpNotFoundException;
use Framework\DI\Service;


    
class Router {

    private $routes;
    
    
    public function __construct($routes = null){
        $this->routes = $routes;
    }

    /**
     * Method finds necessary information from routes
     *
     * @return array
     * @throws HttpNotFoundException
     */

    public function start(){

        $uri =  Service::get('request')->getRequestInfo('uri');

        try{
            if (!is_null($this->routes)) {
                foreach ($this->routes as $key=>$value) {

                    //if pattern has parameters, the method will call 'patToReg()' to transform pattern to regexp
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
                     throw new HttpNotFoundException('route not found');
                }

            }else{
                throw new HttpNotFoundException('routes not found');
            }
        }catch (HttpNotFoundException $e){
            throw $e;
        }
    }


    /**
     * Method transforms pattern to regexp
     *
     * @param array $route
     * @return array
     */
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


    /**
     * Method defines values of variables
     *
     * @param string $pattern
     * @param array $keys
     * @param string $uri
     *
     * @return array
     */

    private function getVars($pattern, $keys, $uri){

        preg_match('~'.$pattern.'~i', $uri, $matches);

        foreach ($keys as $key=>$value){
            if (isset($matches[$key+1])){
                $vars[trim($value, '{}')] = $matches[$key+1];
            }
        }

        return $vars;
    }

    /**
     * Method creates link from routename
     *
     * @param string $name
     * @param array $params
     * @return string
     * @throws HttpNotFoundException
     */

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
            throw new HttpNotFoundException('route');
        }

        return $pattern;
    }

}
?>