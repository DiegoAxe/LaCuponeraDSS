<?php
    //Esta direccion, el directiorio superior se cambiará segun la carpeta final
    require_once '../modelo/claseConexion.php';

    class daoPromocion{
        
        /*
        public function listaRubro(){
            $sql = "SELECT * FROM promocon ORDER BY CodEmpresa";
            $cn = new Conexion();
            $dbh = $cn->getConexion();
            $stmt = $dbh->prepare($sql);
            $stmt->execute();
            $rubros = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $rubros;
        }
        */

        public function buscarPromociones($busqueda){
            $cn = new Conexion();
            $dbh = $cn->getConexion();
            $sql = "SELECT * FROM promocion WHERE Estado = 'Activa' AND Titulo LIKE '%".$busqueda."%' ORDER BY FechaInicio asc";
            $stmt = $dbh->prepare($sql);
            $stmt->execute();
            $promociones = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $promociones;
        }
        
        public function ActivarPromociones($fecha_hoy){
            $sql= "UPDATE promocion SET Estado = 'Activa' WHERE FechaInicio <=:fecha AND FechaVencimiento >=:fecha 
            AND Estado = 'Aprobada'";
            $cn = new Conexion();
            $dbh = $cn->getConexion();
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':fecha',$fecha_hoy);
            $stmt->execute();
        }

        public function VencerPromociones($fecha_hoy){
            $sql= "UPDATE promocion SET Estado = 'Pasada' WHERE FechaVencimiento <=:fecha AND Estado = 'Activa'";
            $cn = new Conexion();
            $dbh = $cn->getConexion();
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':fecha',$fecha_hoy);
            $stmt->execute();
        }

        public function listarPromociones($rubro){
            $cn = new Conexion();
            $dbh = $cn->getConexion();

            if ($rubro == 0){
                $sql = "SELECT * FROM promocion WHERE Estado = 'Activa' ORDER BY FechaInicio asc";
                $stmt = $dbh->prepare($sql);
            }else{
                $sql= "SELECT pro.* FROM promocion pro INNER JOIN empresa emp ON pro.CodEmpresa = emp.CodEmpresa 
                WHERE emp.CodRubro =:rubro AND pro.Estado = 'Activa' ORDER BY pro.FechaInicio asc";                
                $stmt = $dbh->prepare($sql);
                $stmt->bindParam(':rubro',$rubro);
            }

            $stmt->execute();
            $promociones = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $promociones;
        }

        public function mostrarPromociones($id){
            $sql = "SELECT * FROM promocion WHERE CodEmpresa=:id";
            $cn = new Conexion();
            $dbh = $cn->getConexion();
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':id',$id);
            $stmt->execute();
            $promociones = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $promociones;
        }

        public function mostrarPromocionesCat($id, $stat){
            $sql = "SELECT * FROM promocion WHERE CodEmpresa=:id AND Estado=:Estado";
            $cn = new Conexion();
            $dbh = $cn->getConexion();
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':id',$id);
            $stmt->bindParam(':Estado',$stat);
            $stmt->execute();
            $promociones = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $promociones;
        }

        public function actualizarEstado($id, $estado){
            $cn = new Conexion();        
            $dbh = $cn->getConexion();
            $sql = "UPDATE promocion SET Estado=:Estado WHERE CodPromocion=:Cod";
            try{
                $stmt = $dbh->prepare($sql);
                $stmt->bindParam(':Estado',$estado);
                $stmt->bindParam(':Cod',$id);
                $stmt->execute();
            }catch(PDOException $e){
                echo "No se puede eliminar al Administrador de la Empresa:";
            }
        }

        public function verEstado($id){
            $sql = "SELECT Estado FROM promocion WHERE CodPromocion=:id";
            $cn = new Conexion();
            $dbh = $cn->getConexion();
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':id',$id);
            $stmt->execute();
            $promociones = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $promociones;
        }


        public function diasRestantes($fecha1, $fecha2){
            $fechaFinal = new DateTime($fecha1);
            $fechaComprobar = new DateTime($fecha2);
            $diasRestantes = $fechaFinal->diff($fechaComprobar);
            return $diasRestantes;
        }

        public function diasRestantesHoy($fecha1){
            $fechaFinal = new DateTime("now");
            $fechaComprobar = new DateTime($fecha1);
            $diasRestantes = $fechaFinal->diff($fechaComprobar);
            return $diasRestantes;
        }

        function compararFechas($primera, $segunda){
            $valoresPrimera = explode ("-", $primera);   
            $valoresSegunda = explode ("-", $segunda); 

            $diaPrimera    = $valoresPrimera[2];  
            $mesPrimera  = $valoresPrimera[1];  
            $anyoPrimera   = $valoresPrimera[0]; 

            $diaSegunda   = $valoresSegunda[2];  
            $mesSegunda = $valoresSegunda[1];  
            $anyoSegunda  = $valoresSegunda[0];

            $diasPrimeraJuliano = gregoriantojd($mesPrimera, $diaPrimera, $anyoPrimera);  
            $diasSegundaJuliano = gregoriantojd($mesSegunda, $diaSegunda, $anyoSegunda);     

            if(!checkdate($mesPrimera, $diaPrimera, $anyoPrimera)){
            // "La fecha ".$primera." no es v&aacute;lida";
                return 0;
            }elseif(!checkdate($mesSegunda, $diaSegunda, $anyoSegunda)){
            // "La fecha ".$segunda." no es v&aacute;lida";
                return 0;
            }else{
                return  $diasPrimeraJuliano - $diasSegundaJuliano;
            } 

        }


        public function buscarPromo($id){
            $sql = "SELECT * FROM promocion WHERE CodPromocion=:id";
            $cn = new Conexion();
            $dbh = $cn->getConexion();
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':id',$id);
            $stmt->execute();
            $promociones = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $promociones;
        }
    }
?>