<?
/**
 * Create abstract class AResponse
 */
namespace Framework\Response;


abstract class AResponse{

    protected $headers = array();
    protected $content;
    protected $code;


    public function __construct($content){
        $this->content = $content;
    }

    public function setHeader($header){
        $this->headers[] = $header;
    }

    public function setContent($content){
        $this->content = $content;
    }

    public function setCode($code){
        $this->code = $code;
    }

    public function getContent(){
        return $this->content;
    }

    public function send(){
        $this->setHeader('Charset: utf8');
        header(implode($this->headers, '\n'));
        echo $this->content;
    }

    public function getType(){
        return $this->type;
    }
}

?>