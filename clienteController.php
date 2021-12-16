<?php
require_once "./Model/clienteModel.php";
require_once "./Model/actividadModel.php";
require_once "./Model/tarjetadModel.php";
require_once "./View/clienteView.php";
require_once "./Helpers/AuthHelper.php";

class clienteController{
    private $modelo;
    private $modeloActividad;
    private $modeloTarjeta;
    private $vista;
    

    function __construct(){
        $this->modelo= new clienteModel();
        $this->modeloActividad= new actividadModel();
        $this->modeloTarjeta= new tarjetaModel();
        //$this->vista= new clienteView();
    }

    //Dar de alta un cliente nuevo al sistema
    function insertarCliente(){
        if ($this->authHelper->isLogged()) {
            if(isset($_POST["nombre"]) && $_POST["nombre"] != ""&&
                isset($_POST["dni"])&& $_POST["dni"] != "" &&
                isset($_POST["telefono"]) && $_POST["telefono"] != "" &&
                isset($_POST["dierccion"])&& $_POST["dierccion"] != ""
                &&
                isset($_POST["ejecutivo"])&& $_POST["ejecutivo"] != "")
                {

                 $repetido= $this->modelo->getCliente($_POST["dni"]);
                 if($repetido) {
                    $mensaje = "ya existe clinete con este dni ";
                    $this->view->showMensajeError($mensaje);
                 } else{
                    $this->modelo->addCliente($_POST["nombre"],$_POST["dni"],$_POST["telefono"],$_POST["direccion"],$_POST["ejecutivo"]);
                    $clienteNuevo=$this->modelo->getCliente($_POST["dni"]);
                    $ejecutivo=$clienteNuevo->ejecutivo;
                    $id_clinte=$clienteNuevo->id_cliente;
                    $this->modeloActividad->cargarTarjeta($id_clinte);
                     if($ejecutivo===1){
                         //se le debe asociar automáticamente una tarjeta del tipo ejecutiva empresarial. 
                     }
                 }          
             }
             else{
                $mensaje = "faltan completar campos ";
                $this->view->showMensajeError($mensaje);
            }

        }
        else{
            $mensaje = "no puede agregar cliente si no esta loggeado ";
            $this->view->showMensajeError($mensaje);
        }
    }


    /**Generar una tabla resumen de cuenta de un cliente determinado
Informar posibles errores
Se debe mostrar una lista detallada de las operaciones del cliente y el saldo actual de km
Se debe informar la lista de tarjetas asociadas
 */


    function tablaResumen($dni_cliente){
        $cliente=$this->modelo->getCliente($dni_cliente);
        if($cliente){
            $id_clinte=$cliente->id_cliente;
            $actividad=$this->modeloActividad->getActividades($id_clinte);                     
            $tarjetas=$this->modeloTarjeta->getTarjetas($id_clinte);

            if($actividad||$tarjetas) {
                $this->vista->mostrarResumen($actividad, $tarjetas,$cliente->nombre, $cliente->dni );
            }
            else{
                $mensaje = "no hay tarjetas o actividades de este cliente";
                $this->view->showMensajeError($mensaje);
            } 

        }
        else{
            $mensaje = "no existe este cliente";
            $this->view->showMensajeError($mensaje);
        }
    }


    /**TRANSFERENCIA RÁPIDA
La estación de servicio quiere ahora brindar un servicio para que sus clientes realicen transferencias de kms entre ellos de manera rápida. Para esto nos presentan el siguiente caso de uso:
Como usuario quiero poder realizar una transferencia rápida a otro usuario indicando sólo su DNI.
Implemente el requerimiento siguiendo el patrón MVC. No es necesario realizar las vistas, solo controlador(es), modelo(s) y las invocaciones a la vista. 
Se debe verificar que el cliente esté logueado.
Se debe verificar que el cliente originario tenga fondos suficientes en su cuenta. 
Se debe verificar que el cliente destinatario exista.
 */

    function tranferir($dni){
        if ($this->authHelper->isLogged()) {
            $dni_originario = $_SESSION['dni_user'];// supindo q se guarda esto
            $clienteOriginario=$this->modelo->getCliente($dni_originario);
            $id_cliente=$clienteOriginario->id;
            $actividad=$this->modeloActividad->getActividad($id_cliente);   

            if($actividad->kms>0){
                    $cliente=$this->modelo->getCliente($dni);//cliente destino
                    if($cliente){
                        $actividadDestino=$this->modeloActividad->getActividad($cliente->id);

                        $kmsDestino=$actividadDestino->kms;

                        //supongo q le quiero transferir 150
                        $this->modeloActividad->modificarActividad($kmsDestino+150,$cliente->id);// a los kms que tiene le sumo 150
                        //miro mis kms(suponiendo que tengo mas de 150)
                        $kmsOrigen=$actividad->kms;
                        $this->modeloActividad->modificarActividad($kmsOrigen-150,$id_cliente);//me descuento los kms q tranferi

                        
                    }
                    else{
                        $mensaje = "no existe este cliente";
                        $this->view->showMensajeError($mensaje);
                    }
                }
                else{
                    $mensaje = "no tiene fondos suficientes";
                    $this->view->showMensajeError($mensaje);
                }
        }
        else{            
            $mensaje = "no puede trasferir si no esta loggeado ";
            $this->view->showMensajeError($mensaje);
            
        }
    }


}