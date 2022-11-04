<?php

namespace SuporteLogico\ApiManager;

class DatasReturn {
    private bool $sucess = false;
    private string $message = 'API não implementada';
    private array $datas = [];

    //public function __construct() {
    //  
    //}

    public function responseCodeHTTP(int $code=null): int {
        return http_response_code($code);
    }
    

    public function returnDatas( string $message = null, bool $sucess = null, array $datas = null, int $codigo_HTTP=-1 ) {
        if (isset($sucess))
          $this->sucess = $sucess;
        if (isset($message)) 
          $this->message =  $message;
        if (isset($datas)) 
          $this->datas =  $datas;
        if ($codigo_HTTP>0)  {
          $this->responseCodeHTTP($codigo_HTTP);
        }
        return get_object_vars($this); 
    }


}