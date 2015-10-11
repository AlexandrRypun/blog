<?
/**
 * Create class Request
 */
namespace Framework\Request;
 

use Framework\Validation\Validator;

class Request {
    
    private $post;
    private $get;
    private $cookies;
    private $uri;
    private $script;
    private $params;
    private $method;
    
    
    
    public function __construct(){
        $this->post = $_POST;
        $this->get = $_GET;
        $this->cookies = $_COOKIE;
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->script = $_SERVER['SCRIPT_NAME'];
        $this->params = $_SERVER['QUERY_STRING'];
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->referer = $_SERVER['HTTP_REFERER'];

    }
    
    /**
     * Return necessary information from request
     */
    public function getRequestInfo($name){
        return $this->filter($this->$name);
    }
    
    public function addVars($vars=array(), $method='get'){

        $vars = $this->filter($vars);

        foreach ($vars as $key=>$value){
            if ($method == 'get'){
                $_GET[$key] = $value;
            }else if($method == 'post'){
                $_POST[$key] = $value;
            }
        }
    }

    public function change_slashes($str){
        $str = str_replace('\\', '/', $str);
        return $str;
    }

    public function post($var){
        return $this->filter($this->post[$var]);
    }

    public function get($var){
        return $this->filter($this->get[$var]);
    }

    public function isPost(){
        if ($this->method == 'POST') {
            return true;
        }else{
            return false;
        }
    }

    public function isGet(){
        if ($this->method == 'GET') {
            return true;
        }else{
            return false;
        }
    }

    private function filter($value){
        $pattern = '/<\s*\/*\s*\w*>|[\$`~#<>\[\]\{\}\\\*\^%]/';
        if (!empty($value)) {
            if (is_array($value)){
                foreach ($value as $key=>$val){
                    $value[$key] = preg_replace($pattern, '', $val);
                }
            }else{
                $value = preg_replace($pattern, '', $value);
            }
            return $value;
        }else{
            return null;
        }
    }
}
?>