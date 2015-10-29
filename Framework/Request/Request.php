<?
/**
 * Create class Request
 */
namespace Framework\Request;


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
     * Method returns necessary information from request
     *
     * @return string
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

    /**
     * Method returns value of variable from POST request
     *
     * @param string $var
     * @return array|bool
     */

    public function post($var){
        return ($this->filter($this->post[$var]))?$this->filter($this->post[$var]):false;
    }

    /**
     * Method returns value of variable from GET request
     *
     * @param string $var
     * @return array|bool
     */

    public function get($var){
        return ($this->filter($this->get[$var]))?$this->filter($this->get[$var]):false;
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

    /**
     * Method deletes special characters from value
     *
     * @param string|array $value
     * @return string|array|null
     */

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