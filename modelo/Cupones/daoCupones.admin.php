<?php
    //Esta direccion, el directiorio superior se cambiará segun la carpeta final
    require_once '../modelo/claseConexion.php';

    class daoCupones{
        
        public function mostrarCuponesCompleto($id){
            $sql = "SELECT * FROM ((cupon as c INNER JOIN promocion as p ON c.CodPromocion=p.CodPromocion) INNER JOIN empresa as emp ON p.CodEmpresa=emp.CodEmpresa) WHERE CodUsuario=:id";
            $cn = new Conexion();
            $dbh = $cn->getConexion();
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':id',$id);
            $stmt->execute();
            $cupones = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $cupones;
        }

        public function mostrarCupones($id){
            $sql = "SELECT * FROM cupon WHERE CodPromocion=:id";
            $cn = new Conexion();
            $dbh = $cn->getConexion();
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':id',$id);
            $stmt->execute();
            $cupones = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $cupones;
        }

        public function mostrarCuponesClientes($id){
            $sql = "SELECT * FROM cupon WHERE CodUsuario=:id";
            $cn = new Conexion();
            $dbh = $cn->getConexion();
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':id',$id);
            $stmt->execute();
            $cupones = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $cupones;
        }

        public function actualizarEstadoCupon($estado,$id){
            $sql = "UPDATE cupon SET EstadoCupon=:Estado WHERE CodCupon=:id";
            $cn = new Conexion();
            $dbh = $cn->getConexion();
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':Estado',$estado);
            $stmt->bindParam(':id',$id);
            $stmt->execute();
            //$cupones = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //return $cupones;
        }



    }
?>