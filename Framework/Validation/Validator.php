<?
namespace Framework\Validation;

class Validator{
    
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