<?
namespace Framework\Validation;

class Validator{

    protected $obj;
    protected $errors = array();


    public function __construct($obj){
        $this->obj = $obj;
    }

    public function isValid(){
        $rules = array();
        $rules = $this->obj->getRules();
        foreach($rules as $key=>$value){
            foreach ($value as $rule){
                $result = $rule->check($this->obj->$key);
                if ($result) $this->errors[$key] = $result;
            }
        }

        return (!$this->errors)?true:false;
    }

    public  function getErrors(){
        return $this->errors;
    }

    public function validString($string){
        $string = trim(htmlspecialchars(strip_tags($string)));
        return $string;
    }
    
    public function isNumber($val){
        if (is_numeric($val)){
            return true;
        }else{
            return false;
        }
    }
}
?>