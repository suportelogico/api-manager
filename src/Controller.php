<?php

namespace SuporteLogico\ApiManager;

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
      header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS");         
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
      header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
      // header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Authorization, Accept, Client-Security-Token, Accept-Encoding");
    exit(0);
}


header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


use SuporteLogico\ApiManager\IController;
use SuporteLogico\ApiManager\DatasReturn;

abstract class Controller implements IController  {
    private string $method = 'GET';
    private ?DatasReturn $return = null; 
    private array $infheader = [];
    private string $_authorization = '';
    private string $_contentType = '';
    private string $errors = '';
    private int $controllerContentType = IController::CT_APPLICATION_OCTET_STREAM;

    public function __construct() {
        $this->getMethodHTTP();
        $this->return = new DatasReturn; 
        $http_header = getallheaders(); //apache_request_headers();
        if (is_array( $http_header )) { 
            $this->infheader = $http_header; 
            if ($this->infheader) {
                $this->_authorization = isset($this->infheader['Authorization']) ? $this->infheader['Authorization'] : '';
                if (empty($this->_authorization)) 
                  $this->_authorization = isset($this->infheader['authorization']) ? $this->infheader['authorization'] : '';
                $this->_contentType   = isset($this->infheader['Content-Type']) ? $this->infheader['Content-Type'] : '';                  
            }
        }
    }

    public function getServerHeader() {
        return $this->infheader;
    }

    protected function getErrors(){
        return $this->errors;
    }

    protected function setContentType( int $contentType ) {
        $this->controllerContentType = $contentType;
    }

    protected function getAuthorization(){
        return $this->_authorization;
    }

    protected function getContentType(){
        return $this->_contentType;
    }

    protected function returnDatas( string $message = null, bool $sucess = null, $datas = null, int $codeHTTP=-1 ) {
        return $this->return->returnDatas( $message, $sucess, $datas, $codeHTTP );
    }

    /**
     * returnToJSON()
     * @parameters: string, bool, mixed
     * @return 
     */
    protected function returnDatasToJSON( string $message = null, bool $sucess = null, $datas = null, int $codeHTTP=-1 ) {
        return json_encode($this->returnDatas( $message, $sucess, $datas, $codeHTTP )); 
    }

    protected function responseCodeHTTP(int $code=null): int {
        $this->return->responseCodeHTTP($code);
    }
  
    /**
     * getContentsToJson() {
     */
    protected function getContentsFile(): string {
        $cont = '';
        
        switch ($this->controllerContentType) {
            case IController::CT_APPLICATION_JSON :
                if ($this->getContentType()=='application/json') {
                    //header('Content-Type: application/json; charset=utf-8');  
                    $cont = file_get_contents('php://input');
                } else {
                    //responseCodeHTTP(400);
                    //$this->errors = 'O content-type tem que ser application/json.';
                    $cont = '';
                }    
                break;
            case IController::CT_APPLICATION_JSON :
                break;
            case IController::CT_APPLICATION_OCTET_STREAM :
                break;
            case IController::CT_TEXT_HTML :
                break;
            case IController::CT_TEXT_PLAIN :
                break;
            default:
                $cont = '';
                break;
        }
        return $cont;
    }

    protected function getContentsToObj() {
        return json_decode($this->getContentsFile());
    }

    private function contentTypeToStr( int $contentType ): string {
       
        switch ($contentType) {
            case IController::CT_APPLICATION_JSON :
                return "application/json";
                break;
            case IController::CT_TEXT_HTML :
                return "text/html";
                break;
            default:
                return "";
                break;
        }
    }

    protected function validate( string $method, int $controllerContentType ) {
        $this->controllerContentType = $controllerContentType;
        $token = $this->getAuthorization();
        if ($token=='') {
            $this->returnDatas("Este usuário não tem autorização para acessar a aplicação .",false,null,503);
            return false;
        }
        
        $contentType = $this->getContentType();
        if ( $contentType!==$this->contentTypeToStr($controllerContentType) ) { // 'application/json'
            $this->returnDatas("Não foi possível criar. O tipo de conteúdo deve ser ".$this->contentTypeToStr($controllerContentType).".",false,null,503);
            return false;
        }
        
        
        if ($this->getMethod()!==$method) {
            $this->returnDatas("Não foi possível criar. Método deve ser $method",false,null,404);
            return false;
        }
        return true;
    }


    protected function getGet() {
        $obj = array();
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
          $obj = $_GET;
        };
        return (object) $obj;
    }

    protected function getPost() {
        $obj = array();
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
          $obj = $_POST;
          if (empty($obj)) {
            $obj = $this->getContentsToJson();
          }
        };
        return (object) $obj;
    }
 
    protected function getPut() {
        $obj = array();
        if ($_SERVER["REQUEST_METHOD"] == "PUT") {
            $obj = $_POST;
          if (empty($obj)) {
            $obj = $this->getContentsToJson();
          }
        };
        return (object) $obj;
    }

    protected function getPatch() {
        $obj = array();
        if ($_SERVER["REQUEST_METHOD"] == "PATCH") {
            $obj = $_PATCH;
          if (empty($obj)) {
            $obj = $this->getContentsToJson();
          }
        };
        return (object) $obj;
    }

    protected function getMethod() {
      return $this->method;
    }
    
      
    protected function getMethodHTTP() {
        $this->method = $_SERVER["REQUEST_METHOD"];
    }

}
