<?php
class actividadModel{
    private $db;

    function __construct(){
        $this->db= new PDO('mysql:host=localhost;'.'dbname=db_final;charset=utf8','root','');
    }
    function cargarTarjeta($id_clinte){
        $sentencia= $this->db->prepare("UPDATE actividad SET kms=200 WHERE id_cliente=? ");
        $sentencia->execute(array($id_clinte));
    }
//trae todas las actividades asociadas a un cliente
    function getActividades($id_clinte){
        $sentencia= $this->db->prepare("SELECT a.* FROM actividad A LEFT JOIN cliente b  ON a.id_cliente=b.id WHERE id_cliente=?");
        
        $sentencia->execute($id_clinte);
        $c=$sentencia->fetchAll(PDO::FETCH_OBJ);
        return $c;

    }
    
    function getActividad($id_clinte){
        $sentencia= $this->db->prepare("SELECT a.* FROM actividad A LEFT JOIN cliente b  ON a.id_cliente=b.id WHERE id_cliente=?");
        
        $sentencia->execute($id_clinte);
        $c=$sentencia->fetch(PDO::FETCH_OBJ);
        return $c;

    }
// esta funcion se usa para transferir
    function modificarActividad($monto, $id_cliente){
        $sentencia= $this->db->prepare("UPDATE actividad SET kms=? WHERE id_cliente=? ");
        $sentencia->execute(array($monto,$id_cliente));
    }
}