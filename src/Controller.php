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

$http_header = apache_request_headers();
$token = isset($http_header['Authorization']) ? $http_header['Authorization'] : '';
if (empty($token))
  $token = isset($http_header['authorization']) ? $http_header['authorization'] : '';

//include_once('IController.php');  

use SuporteLogico\ApiManager\IController;

class Controller implements IController  {
    private int $method = IController::MTGET;
    private $ret = array( "sucesso" => false, "mensagem" => "API não implementada" );  

    public function __construct() {
        $this->getMethodHTTP();
    }

    public function retorno( string $mensagem = null, bool $sucesso = null, array $dados = null ) {
        if (isset($sucesso))
          $this->ret["sucesso"] = $sucesso;
        if (isset($mensagem)) 
          $this->ret["mensagem"] =  $mensagem;
        if (isset($dados)) 
          $this->ret["dados"] =  $dados;
        return $this->ret; 
    }

    public function retornoToJSON( $mensagem = null, $sucesso = null, $dados = null ) {
        $this->retorno( $mensagem, $sucesso, $dados );
        if (!$this->ret["sucesso"]) {
          if (empty($this->ret["mensagem"])) {
            $this->ret["mensagem"] = 'Nada processado';
          }
        }
        return json_encode($this->ret); 
    }
  
    public function pegarObjetoJson() {
        header('Content-Type: application/json; charset=utf-8');  
        $json = file_get_contents('php://input');
        $obj = json_decode($json);
        return $obj;
  
    }

    public function pegarPost() {
        $obj = array();
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
          $obj = $_POST;
          if (empty($obj)) {
            $obj = $this->pegarObjetoJson();
          }
        };
        return (object) $obj;
    }
 

    public function getMethod() {
      return $this->methodToStr();
    }
    
      
    private function getMethodHTTP() {
        switch ($_SERVER["REQUEST_METHOD"]) {
            case 'GET':
                $this->method = IController::MTGET;
                break;
            case 'POST':
                $this->method = IController::MTPOST;
                break;
            case 'PUT':
                $this->method = IController::MTPUT;
                break;
            case 'PATCH':
                $this->method = IController::MTPATCH;
                break;
            case 'DELETE':
                $this->method = IController::MTDELETE;
                break;
            case 'OPTIONS':
                $this->method = IController::MTOPTIONS;
                break;
            default:
                $this->method = IController::MTNIL;
                break;
        }
    }

    private function methodToStr() {
        switch ($this->method) {
            case IController::MTGET:
                $result = 'GET';
                break;
            case IController::MTPOST:
                $result = 'POST';
                break;
            case IController::MTPUT;
                $result = 'PUT';
                break;
            case IController::MTPATCH;
                $result = 'PATCH';
                break;
            case IController::MTDELETE;
                $result = 'DELETE';
                break;
            case IController::MTOPTIONS;
                $result = 'OPTIONS';
                break;
            default:
                $result = '';
                break;
        }
        return $result;
    }

}
