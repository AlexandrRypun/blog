<?
namespace Framework\Response;

/**
 * Interface Iresponse
 * @package Framework\Response
 */

Interface IResponse{

    function setHeader();

    function setContent();

    function getContent();

    function send();
}

?>