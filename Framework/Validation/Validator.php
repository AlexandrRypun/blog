<?
namespace Framework\Validation;

class Validator{

    protected $obj;

    public function __construct($obj){
        $this->obj = $obj;
    }

    public function isValid(){
       /** $rules = array();
        $rules = $this->obj->getRules();
        foreach($rules as $key=>$value){
            foreach ($value as $rule){
                $rule->check($this->obj->$key);
            }
        }*/
        return true;
    }

    public  function getErrors(){

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