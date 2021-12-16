<?php
class clienteModel{
    private $db;

    function __construct(){
        $this->db= new PDO('mysql:host=localhost;'.'dbname=db_final;charset=utf8','root','');
    }
    
    //para los repeidos
    function getCliente($dni){
        $sentencia= $this->db->prepare("SELECT * FROM cliente  WHERE dni=?");
        $sentencia->execute($dni);
        $c=$sentencia->fetch(PDO::FETCH_OBJ);
        return $c;
        
    }

    
    function addCliente($nombre,$dni,$telefono, $direccion,$ejecutivo){
        
        $sentencia= $this->db->prepare('INSERT INTO cliente(nombre,dni,telefono, direccion,ejecutivo) VALUES(?,?,?,?,?)');
        $sentencia->execute(array($nombre,$dni,$telefono, $direccion,$ejecutivo));
    }
}