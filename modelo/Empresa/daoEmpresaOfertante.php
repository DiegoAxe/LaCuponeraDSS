<?php
    //Esta direccion, el directiorio superior se cambiará segun la carpeta final
    require_once '../modelo/claseConexion.php';

    class daoEmpresaOfertante{
        public function insertarEmpleado($Nombre, $Apellido, $Correo, $Contra, $Telefono, $CodEmpresa){
            $cn = new Conexion();        
            $dbh = $cn->getConexion();
            $sql = "INSERT INTO usuario (Nombre, Apellido, Telefono, Correo, Contrasena, CodRol, CodEmpresa, Verificado) ";
            $sql .= " VALUES (:Nombre, :Apellido, :Telefono, :Correo, :Contra, 4, :Empresa, 0)";
            try{
                $stmt = $dbh->prepare($sql);
                $stmt->bindParam(':Nombre',$Nombre);
                $stmt->bindParam(':Apellido',$Apellido);
                $stmt->bindParam(':Telefono',$Telefono);
                $stmt->bindParam(':Correo',$Correo);
                $stmt->bindParam(':Contra',$Contra);
                $stmt->bindParam(':Empresa',$CodEmpresa);
                $stmt->execute();
            }catch(PDOException $e){
                echo "Error: " . $e->getMessage();
            }
        }  

        public function modificarEmpleado($CodUsuario, $Nombre, $Apellido, $Correo, $Telefono){
            $cn = new Conexion();
            $dbh = $cn->getConexion();
            $sql = "UPDATE usuario SET Nombre=:Nom, Apellido=:Apell, Telefono=:Tel, Correo=:Corr ";
            $sql .= "WHERE CodUsuario=:id";
            
            try{
                $stmt = $dbh->prepare($sql);
                $stmt->bindParam(':Nom',$Nombre);
                $stmt->bindParam(':Apell',$Apellido);
                $stmt->bindParam(':Tel',$Telefono);
                $stmt->bindParam(':Corr',$Correo);
                $stmt->bindParam(':id',$CodUsuario);
                $stmt->execute();
            }catch(PDOException $e){
                echo "Error: " . $e->getMessage();
            }

        }

        public function eliminarEmpleado($CodUsuario){
            $cn = new Conexion();        
            $dbh = $cn->getConexion();
            $sql = "DELETE FROM usuario WHERE CodUsuario=:cod";
            try{
                $stmt = $dbh->prepare($sql);
                $stmt->bindParam(':cod',$CodUsuario);
                $stmt->execute();
            }catch(PDOException $e){
                echo "Lo sentimos, no se puede borrar este usuario";
            }
        }

        public function listarPromoSegunEstado($empresa, $estado){
            $sql = "SELECT * FROM promocion WHERE CodEmpresa=:empresa AND Estado = :estado";
            $cn = new Conexion();
            $dbh = $cn->getConexion();
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':empresa',$empresa);
            $stmt->bindParam(':estado',$estado);
            $stmt->execute();
            $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $empleados;
        }

        public function contarCuponesVendidos($IdPromo){
            $sql = "SELECT COUNT(*) FROM cupon WHERE CodPromocion =:id";
            $cn = new Conexion();
            $dbh = $cn->getConexion();
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':id',$IdPromo);
            $stmt->execute();
            $cantVendida = $stmt->fetch();
            return $cantVendida;
        }

        public function obtenerComision($Empresa){
            $sql = "SELECT PorcentajeComision FROM empresa WHERE CodEmpresa = :empresa";
            $cn = new Conexion();
            $dbh = $cn->getConexion();
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':empresa',$Empresa);
            $stmt->execute();
            $Comision = $stmt->fetch();
            return $Comision;
        }

        public function descartarOferta($IdOferta){
            $cn = new Conexion();
            $dbh = $cn->getConexion();
            $sql = "UPDATE promocion SET Estado = 'Descartada' WHERE CodPromocion = :promo";
            
            try{
                $stmt = $dbh->prepare($sql);
                $stmt->bindParam(':promo',$IdOferta);
                $stmt->execute();
            }catch(PDOException $e){
                echo "Error: " . $e->getMessage();
            }
        }

        public function porIdPromocion($IdPromo){
            $sql = "SELECT * FROM promocion WHERE CodPromocion=:id";
            $cn = new Conexion();
            $dbh = $cn->getConexion();
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':id',$IdPromo);
            $stmt->execute();
            $promo = $stmt->fetch(PDO::FETCH_ASSOC);
            if($promo){
                return $promo;
            }else{
                return false;
            }
        }

        public function insertarPromocion($Titulo, $Descripcion, $Detalle, $Cantidad, $FInicio, $FVencimiento, $FLimite, $PRegular, $POferta, $CodEmpresa){
            $cn = new Conexion();        
            $dbh = $cn->getConexion();
            $sql = "INSERT INTO promocion (Titulo, FotoPromocion, Descripcion, Detalle, CantidadCupones, FechaInicio, ";
            $sql .= "FechaVencimiento, FechaLimiteCupon, PrecioRegular, PrecioOferta_Cupon, Estado, CodEmpresa) VALUES "; 
            $sql .= "(:titu, 'promocion.jpg', :descrip, :detal, :canti, :Finicio, :Fvenci, :Flimite, :Pregular, :Poferta, ";
            $sql .= "'En Espera de aprobación', :empresa)";
            try{
                $stmt = $dbh->prepare($sql);
                $stmt->bindParam(':titu',$Titulo);
                $stmt->bindParam(':descrip',$Descripcion);
                $stmt->bindParam(':detal',$Detalle);
                $stmt->bindParam(':canti',$Cantidad);
                $stmt->bindParam(':Finicio',$FInicio);
                $stmt->bindParam(':Fvenci',$FVencimiento);
                $stmt->bindParam(':Flimite',$FLimite);
                $stmt->bindParam(':Pregular',$PRegular);
                $stmt->bindParam(':Poferta',$POferta);
                $stmt->bindParam(':empresa',$CodEmpresa);
                $stmt->execute();
            }catch(PDOException $e){
                echo "Error: " . $e->getMessage();
            }
        }  

        public function modificarPromocion($Titulo, $Descripcion, $Detalle, $Cantidad, $FInicio, $FVencimiento, $FLimite, $PRegular, $POferta, $CodPromo){
            $cn = new Conexion();
            $dbh = $cn->getConexion();
            $sql = "UPDATE promocion SET Titulo=:titu, Descripcion=:descrip, Detalle=:detal, CantidadCupones=:canti, FechaInicio=:Finicio, ";
            $sql .= "FechaVencimiento=:Fvenci, FechaLimiteCupon=:Flimite, PrecioRegular=:Pregular, PrecioOferta_Cupon=:Poferta, "; 
            $sql .= "Estado='En Espera de aprobación' WHERE CodPromocion=:id";
            
            try{
                $stmt = $dbh->prepare($sql);
                $stmt->bindParam(':titu',$Titulo);
                $stmt->bindParam(':descrip',$Descripcion);
                $stmt->bindParam(':detal',$Detalle);
                $stmt->bindParam(':canti',$Cantidad);
                $stmt->bindParam(':Finicio',$FInicio);
                $stmt->bindParam(':Fvenci',$FVencimiento);
                $stmt->bindParam(':Flimite',$FLimite);
                $stmt->bindParam(':Pregular',$PRegular);
                $stmt->bindParam(':Poferta',$POferta);
                $stmt->bindParam(':id',$CodPromo);
                $stmt->execute();
            }catch(PDOException $e){
                echo "Error: " . $e->getMessage();
            }

        }

    }
?>
