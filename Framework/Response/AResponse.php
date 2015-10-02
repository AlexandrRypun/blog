<?
namespace Framework\Response;

/**
 * Class AResponse
 * @package Framework\Response
 */

abstract class AResponse{

    private $headers = array();
    private $content;
    private $code;


    function setHeader($title, $value){
        $this->headers[$title] = $value;
    }

    function setContent($content){
        $this->content = $content;
    }

    function setCode($code){
        $this->code = $code;
    }

    function getContent(){

    }

    function send(){

    }
}

?>