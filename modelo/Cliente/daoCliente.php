<?php
    //Esta direccion, el directiorio superior se cambiará segun la carpeta final
    require_once '../modelo/claseConexion.php';

    class daoCliente{

        public function listaClientes(){
            $sql = "SELECT * FROM usuario WHERE CodRol=2";
            $cn = new Conexion();
            $dbh = $cn->getConexion();
            $stmt = $dbh->prepare($sql);
            $stmt->execute();
            $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $clientes;
        }

        public function mostrarCliente($id){
            $sql = "SELECT * FROM usuario WHERE CodRol=2 AND CodUsuario=:id";
            $cn = new Conexion();
            $dbh = $cn->getConexion();
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':id',$id);
            $stmt->execute();
            $cliente = $stmt->fetch();
            return $cliente;
        }
    }
?>