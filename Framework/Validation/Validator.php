<?
namespace Framework\Validation;

class Validator{

    protected $obj;

    public function __construct($obj){
        $this->post = $obj;
    }

    public function isValid(){

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