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
            $sql = "SELECT cup.*, promo.* FROM cupon cup INNER JOIN promocion promo ON 
                cup.CodPromocion = promo.CodPromocion WHERE CodUsuario=:id";
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

        public function solicitud_Cupones($Dui,$CodigoCupon){
           $sql = "SELECT cup.CodCupon, promo.Titulo, promo.Descripcion, usu.DUI, usu.Nombre, usu.Apellido, 
           cup.EstadoCupon FROM (( cupon cup INNER JOIN usuario usu ON cup.CodUsuario = usu.CodUsuario) INNER JOIN 
           promocion promo ON cup.CodPromocion = promo.CodPromocion) WHERE cup.CodCupon = :CuponID 
           AND usu.DUI = :Dui AND usu.CodRol = 2";
           $cn = new Conexion();
           $dbh = $cn->getConexion();
           $stmt = $dbh->prepare($sql);
           $stmt->bindParam(':CuponID',$CodigoCupon);
           $stmt->bindParam(':Dui',$Dui);
           $stmt->execute();
           $cuponObtenido = $stmt->fetchAll(PDO::FETCH_ASSOC);
           return $cuponObtenido;
        }

        public function VencerCupones($fecha_hoy){
            $sql= "UPDATE cupon AS cup INNER JOIN promocion AS promo ON cup.CodPromocion = promo.CodPromocion 
            SET cup.EstadoCupon = 'Vencido' WHERE promo.FechaLimiteCupon <=:fecha AND cup.EstadoCupon = 'Disponible'";
            $cn = new Conexion();
            $dbh = $cn->getConexion();
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':fecha',$fecha_hoy);
            $stmt->execute();
        }

        public function CanjearCupones($IdCupon, $fecha_hoy){
            $sql= "UPDATE cupon SET EstadoCupon='Canjeado', FechaCanje =:fechaHoy  WHERE CodCupon =:CuponID";
            $cn = new Conexion();
            $dbh = $cn->getConexion();
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':fechaHoy',$fecha_hoy);
            $stmt->bindParam(':CuponID',$IdCupon);
            $stmt->execute();
        }

    }
?>