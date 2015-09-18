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
    }
    
    /**
     * Return necessary information from request
     */
    public function getRequestInfo($name){
        return $this->$name;
    }
    
    public function getValue($key){
        switch ($this->method) {
            case "GET":
                $value = $_GET[$key];
                break;
            case "POST":
                $value = $_POST[$key];
                break;
        }
        
        $v = new \Framework\Validation\Validator;
        return $v->validString($value);
    }
    
    public function addVars($vars, $method='get'){
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
    
    
   
   
}
?>