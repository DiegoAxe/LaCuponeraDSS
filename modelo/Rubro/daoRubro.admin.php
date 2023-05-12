<?php
    //Esta direccion, el directiorio superior se cambiará segun la carpeta final
    require_once '../modelo/claseConexion.php';

    class daoRubro{
        public function insertar($rubro){
            $cn = new Conexion();        
            $dbh = $cn->getConexion();
            $sql = "INSERT INTO rubro (NombreRubro, Descripcion) ";
            $sql .= " VALUES (:NombreRubro, :Descripcion)";
            try{
                $stmt = $dbh->prepare($sql);
                $stmt->bindParam(':NombreRubro',$rubro->NombreRubro);
                $stmt->bindParam(':Descripcion',$rubro->Descripcion);
                $stmt->execute();
            }catch(PDOException $e){
                echo "Error: " . $e->getMessage();
            }
        }  

        public function modificar($rubro){
            $cn = new Conexion();
            $dbh = $cn->getConexion();
            $sql = "UPDATE rubro SET NombreRubro=:nombres, Descripcion=:descripcion ";
            $sql .= "WHERE CodRubro=:cod";

            try{
                $stmt = $dbh->prepare($sql);
                $stmt->bindParam(':nombres',$rubro->NombreRubro);
                $stmt->bindParam(':descripcion',$rubro->Descripcion);
                $stmt->bindParam(':cod',$rubro->CodRubro);
                $stmt->execute();
            }catch(PDOException $e){
                echo "Error: " . $e->getMessage();
            }

        }

        public function eliminar($id){
            $cn = new Conexion();        
            $dbh = $cn->getConexion();
            $sql = "DELETE FROM rubro WHERE CodRubro=:cod";
            try{
                $stmt = $dbh->prepare($sql);
                $stmt->bindParam(':cod',$id);
                $stmt->execute();
            }catch(PDOException $e){
                echo "Lo sentimos, no se puede borrar un rubro que ya es usado en empresas";
            }
        }

        public function listaRubro(){
            $sql = "SELECT * FROM rubro ORDER BY CodRubro";
            $cn = new Conexion();
            $dbh = $cn->getConexion();
            $stmt = $dbh->prepare($sql);
            $stmt->execute();
            $rubros = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $rubros;
        }

        public function mostrarRubro($id){
            $sql = "SELECT * FROM rubro WHERE CodRubro=:id";
            $cn = new Conexion();
            $dbh = $cn->getConexion();
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':id',$id);
            $stmt->execute();
            $usuario = $stmt->fetch();
            return $usuario;
        }
    }
?>