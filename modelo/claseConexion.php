<?php

class Conexion extends \mysqli
{
    public function getConexion(){
        $host = "localhost";
        $bdd = "proyecto_cuponera";
        $user = "root";
        $pass = "";
        try{
            $dsn = "mysql:host=$host;dbname=$bdd;charset=utf8";
            $dbh = new PDO($dsn,$user,$pass);
            return $dbh;
       }catch(PDOException  $e){
           echo "Fallo de conexión: " . $e->getMessage();
     }
    }
}
?>