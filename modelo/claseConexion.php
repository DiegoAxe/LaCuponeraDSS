<?php

class Conexion extends \mysqli
{
    public function getConexion(){
        $host = "sql310.epizy.com";
        $bdd = "epiz_33902531_lacuponera";
        $user = "epiz_33902531";
        $pass = "HymCR7f2nsns2z";
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