<?
/**
 * Create class ResponseRedirect
 */
namespace Framework\Response;

class ResponseRedirect extends AResponse{

    public function __construct($link){
        $this->link = $link;
    }

    public function send(){
        header('Location: '.$this->link);
    }

}



?>
