<?php

namespace SuporteLogico\ApiManager;

use SuporteLogico\ApiManager\Controller;

class ControllerTester extends Controller { 
    
    public function __construct() {
        parent::__construct();
    } 

    public function create() {
        
        if ( !($this->validate("POST",IController::CT_APPLICATION_JSON)) ) {
            return $this->returnDatasToJSON();
        }
       

        $dados = $this->getContentsToObj();
        if (!$dados) {
            return $this->returnDatasToJSON("Não foi possível criar. Os dados não foram informados.",false,null,404 );
        }

        $nome  = isset($dados->nome)  ? $dados->nome  : '';
        $idade = isset($dados->idade) ? $dados->idade : 0;
        if ((!empty($nome)) && ($idade>0) ) {
            return $this->returnDatasToJSON( "Criado com sucesso.",true,null,200 );
        } else {
            return $this->returnDatasToJSON( "Não foi possível criar. O Nome e a idade tem que ser informado.",false,null,404 );
        }
    }


};