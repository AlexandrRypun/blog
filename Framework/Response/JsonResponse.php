<?
/**
 * Create class JsonResponse
 */
namespace Framework\Response;


class JsonResponse extends AResponse{

    protected $headers = array('Content-Type: application/json');
    protected $type = 'json';


    public function send(){
        header(implode($this->headers, '\n'));
        echo json_encode($this->content);
    }



}

?>