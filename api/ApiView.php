<?php


class ApiView{
    public function response($data,$status){
        header("Content-Type: application/json");//es para informar en que formato lo estamos mandando
        header("HTTP/1.1 " . $status . " " . $this->requestStatus($status));//setear el código de respuesta
        echo json_encode($data); //pero en formato JSON
    //aca vamos a convertir a JSON
    }

    private function requestStatus($code){
        $status = array(
          200 => "OK",
          404 => "Not found",
          500 => "Internal Server Error"
        );
        return (isset($status[$code])) ? $status[$code] : $status[500];
      }
}