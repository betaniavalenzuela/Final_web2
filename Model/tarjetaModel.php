<?php
class tarjetaModel{
    private $db;

    function __construct(){
        $this->db= new PDO('mysql:host=localhost;'.'dbname=db_final;charset=utf8','root','');
    }


    function getTarjetas($id_cliente){
        $sentencia= $this->db->prepare("SELECT a.* FROM tarjeta A LEFT JOIN cliente b  ON a.id_cliente=b.id WHERE id_cliente=?");
            
        $sentencia->execute($id_cliente);
        $c=$sentencia->fetchAll(PDO::FETCH_OBJ);
        return $c;
}

}