<?php
require_once "./Model/tarjetaModel.php";
require_once "./api/ApiView.php";

class PFYapiController{
    private $model;
    private $view;
    


// Lee la variable asociada a la entrada estandar y la convierte en JSON


    function __construct(){
        $this->model = new tarjetaModel();
        $this->view = new ApiView();
        
    }

    function getBody(){ 
        $bodyString= file_get_contents("php://input");
        return json_decode($bodyString); 
    } 


    function getTarjetas($params = null){
        $id = $params[':ID'];
        $tarejtas = $this->model->getTarjetas($id);  
        if(!empty($tarejtas)){
            $this->view->response($tarejtas, 200);
        }else{
            $this->view->response("No hay tarjetas para mostrar", 404);
        }

    }

/**e)Como cliente quiero poder ver mi historial de actividades dado un intervalo de dos fechas
    } */
    function getActividades($params = null){
        $id = $params[':ID'];
        $FECHAS=[];
        if(isset($_GET['FechaIni'])){
            $FECHAS=['FechaIni']=$_GET['FechaIni'];
        }
        if(isset($_GET['FechaFin'])){
            $FECHAS=['FechaFin']=$_GET['FechaFin'];
        }
        $actividades = $this->modelActividades->getActividades($id,$FECHAS);  //EN EL Model se pondran estos atributos
        if(!empty($actividades)){
            $this->view->response($actividades, 200);
        }else{
            $this->view->response("No hay actividades para mostrar", 404);
        }

    }

    /**Como cliente quiero poder dar de baja una tarjeta */
    function deleteTarjeta($params = null){
        $id = $params[':ID'];
        $tarjeta= $this->modelTarjeta->getTarjeta($id);
        if($tarjeta){
            $this->modelTarjeta->deleteTrjeta($id);
            $this->view->response("la tarjeta con el id=$id fue eliminado", 200);
        }
        else{
            $this->view->response("la tarjeta  con el id=$id no existe", 404);
        }
        
    }





}