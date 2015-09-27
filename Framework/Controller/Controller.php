<?

namespace Framework\Controller;



use Framework\Request\Request;

class Controller {
    
    public function __construct(){

    }

    protected function getRequest(){
        return new Request();
    }
    
}

?>