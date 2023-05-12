<?php
    //Esta direccion, el directiorio superior se cambiará segun la carpeta final
    require_once '../modelo/claseConexion.php';

    class daoEmpresa{
        public function insertarEmpresa($empresa){
            $cn = new Conexion();        
            $dbh = $cn->getConexion();
            $sql = "INSERT INTO empresa (CodEmpresa, NombreEmpresa, PorcentajeComision, CodRubro) ";
            $sql .= " VALUES (:CodEmpresa, :NombreEmpresa, :PorcentajeComision, :CodRubro)";

            //variables a ingresar
            $cod=$empresa->getCodEmpresa();
            $nom=$empresa->getNombreEmpresa();
            $prt=$empresa->getPorcentajeComision();
            $rub=$empresa->getRubro();
            try{
                $stmt = $dbh->prepare($sql);
                $stmt->bindParam(':CodEmpresa',$cod);
                $stmt->bindParam(':NombreEmpresa',$nom);
                $stmt->bindParam(':PorcentajeComision',$prt);
                $stmt->bindParam(':CodRubro',$rub);
                $stmt->execute();
            }catch(PDOException $e){
                echo "Error: " . $e->getMessage();
            }
        }  
/*
        public function notificar(){
            
        }
*/
        public function insertarJefe($empresa){
            $cn = new Conexion();        
            $dbh = $cn->getConexion();
            $sql = "INSERT INTO usuario (DUI, Foto, Nombre, Apellido, Direccion, Telefono, Correo, Contrasena, CodRol, CodEmpresa, Verificado) ";
            $sql .= " VALUES (:DUI, :Foto, :Nombre, :Apellido, :Direccion, :Telefono, :Correo, :Contrasena, :CodRol, :CodEmpresa, :Verificado)";
            //variables a ingresar
            $du = null;
            $imag = $empresa->getImg();
            $nomUsu = $empresa->getNombre();
            $apeUsu = $empresa->getApellido();
            $dire = $empresa->getDireccion();
            $tel = $empresa->getTelefono();
            $email = $empresa->getCorreo();
            $contra = $empresa->getContraseña();
            $contra = hash('sha512', $contra);
            $role = $empresa->getRol();
            $codEmpre = $empresa->getCodEmpresa();
            $verify = $empresa->getVerificado();
            
            try{
                $stmt = $dbh->prepare($sql);
                $stmt->bindParam(':DUI',$du);
                $stmt->bindParam(':Foto',$imag);
                $stmt->bindParam(':Nombre',$nomUsu);
                $stmt->bindParam(':Apellido',$apeUsu);
                $stmt->bindParam(':Direccion',$dire);
                $stmt->bindParam(':Telefono',$tel);
                $stmt->bindParam(':Correo',$email);
                $stmt->bindParam(':Contrasena',$contra);
                $stmt->bindParam(':CodRol',$role);
                $stmt->bindParam(':CodEmpresa',$codEmpre);
                $stmt->bindParam(':Verificado',$verify);
                $stmt->execute();
            }catch(PDOException $e){
                echo "Error: " . $e->getMessage();
            }
        }  

        function insertarIMG($cod){
            $target_dir = "../vista/img/"; //directorio en el que se subira
            $uploadOk = 1;//se añade un valor determinado en 1
            $imageFileType = strtolower(pathinfo(basename($_FILES["image"]["name"]), PATHINFO_EXTENSION));
            $target_file = $target_dir . $cod . "." . $imageFileType;
            // Permitir ciertos formatos de archivo
            if($imageFileType != "jpg" && $imageFileType != "png") {
                echo "Perdon solo, JPG y PNG";
                $uploadOk = 0;
            }
            //Comprueba si $ uploadOk se establece en 0 por un error
            if ($uploadOk == 0) {
                echo "Perdon, pero el archivo no se subio";
            // si todo está bien, intenta subir el archivo
            } else {
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    //echo "El archivo ". basename( $_FILES["file1"]["name"]). " Se subio correctamente";
                    return true;
                } else {
                    //echo "Error al cargar el archivo";
                    return false;
                }
            }
        }

        public function modificarEmpresa($empresa){
            $cn = new Conexion();
            $dbh = $cn->getConexion();
            $sql = "UPDATE empresa SET NombreEmpresa=:NombreEmpresa, PorcentajeComision=:PorcentajeComision, CodRubro=:CodRubro ";
            $sql .= "WHERE CodEmpresa=:CodEmpresa";

            $cod=$empresa->getCodEmpresa();
            $nom=$empresa->getNombreEmpresa();
            $prt=$empresa->getPorcentajeComision();
            $rub=$empresa->getRubro();

            try{
                $stmt = $dbh->prepare($sql);
                $stmt->bindParam(':NombreEmpresa',$nom);
                $stmt->bindParam(':PorcentajeComision',$prt);
                $stmt->bindParam(':CodRubro',$rub);
                $stmt->bindParam(':CodEmpresa',$cod);
                $stmt->execute();
            }catch(PDOException $e){
                echo "Error: " . $e->getMessage();
            }

        }

        public function modificarJefe($empresa){
            $cn = new Conexion();        
            $dbh = $cn->getConexion();
            $sql = "UPDATE usuario SET Nombre=:Nombre, Apellido=:Apellido, Direccion=:Direccion, Telefono=:Telefono, Correo=:Correo";
            $sql .= " WHERE CodUsuario=:CodUsuario AND CodEmpresa=:CodEmpresa";
            //variables a ingresar
            $codUsu = $empresa->getCodUsuario();
            $nomUsu = $empresa->getNombre();
            $apeUsu = $empresa->getApellido();
            $dire = $empresa->getDireccion();
            $tel = $empresa->getTelefono();
            $email = $empresa->getCorreo();
            $codEmpre = $empresa->getCodEmpresa();
            
            try{
                $stmt = $dbh->prepare($sql);
                $stmt->bindParam(':Nombre',$nomUsu);
                $stmt->bindParam(':Apellido',$apeUsu);
                $stmt->bindParam(':Direccion',$dire);
                $stmt->bindParam(':Telefono',$tel);
                $stmt->bindParam(':Correo',$email);
                $stmt->bindParam(':CodUsuario',$codUsu);
                $stmt->bindParam(':CodEmpresa',$codEmpre);
                $stmt->execute();
            }catch(PDOException $e){
                echo "Error: " . $e->getMessage();
            }
        } 
        
        public function modificarJefeFoto($empresa){
            $cn = new Conexion();        
            $dbh = $cn->getConexion();
            $sql = "UPDATE usuario SET Foto=:Foto, Nombre=:Nombre, Apellido=:Apellido, Direccion=:Direccion, Telefono=:Telefono, Correo=:Correo";
            $sql .= " WHERE CodUsuario=:CodUsuario AND CodEmpresa=:CodEmpresa";
            //variables a ingresar
            $codUsu = $empresa->getCodUsuario();
            $img = $empresa->getImg();
            $nomUsu = $empresa->getNombre();
            $apeUsu = $empresa->getApellido();
            $dire = $empresa->getDireccion();
            $tel = $empresa->getTelefono();
            $email = $empresa->getCorreo();
            $codEmpre = $empresa->getCodEmpresa();
            
            try{
                $stmt = $dbh->prepare($sql);
                $stmt->bindParam(':Foto', $img);
                $stmt->bindParam(':Nombre',$nomUsu);
                $stmt->bindParam(':Apellido',$apeUsu);
                $stmt->bindParam(':Direccion',$dire);
                $stmt->bindParam(':Telefono',$tel);
                $stmt->bindParam(':Correo',$email);
                $stmt->bindParam(':CodUsuario',$codUsu);
                $stmt->bindParam(':CodEmpresa',$codEmpre);
                $stmt->execute();
            }catch(PDOException $e){
                echo "Error: " . $e->getMessage();
            }
        }  

        public function eliminar($id){
            $cn = new Conexion();        
            $dbh = $cn->getConexion();
            $sql = "DELETE FROM empresa WHERE CodEmpresa=:cod";
            try{
                $stmt = $dbh->prepare($sql);
                $stmt->bindParam(':cod',$id);
                $stmt->execute();
            }catch(PDOException $e){
                echo "Lo sentimos, no se puede borrar una empresa que ya está operacional";
            }
        }

        public function eliminarJefe($id){
            $cn = new Conexion();        
            $dbh = $cn->getConexion();
            $sql = "DELETE FROM usuario WHERE CodUsuario=:cod";
            try{
                $stmt = $dbh->prepare($sql);
                $stmt->bindParam(':cod',$id);
                $stmt->execute();
            }catch(PDOException $e){
                echo "No se puede eliminar al Administrador de la Empresa:";
            }
        }

        public function listaEmpresa(){
            /*
            $sql = "SELECT empresa.CodEmpresa, empresa.NombreEmpresa, empresa.PorcentajeComision, rubro.NombreRubro 
            FROM `empresa` INNER JOIN rubro ON empresa.CodRubro = rubro.CodRubro";
            */
            $sql = "SELECT empresa.CodEmpresa, empresa.NombreEmpresa, 
            empresa.PorcentajeComision, rubro.NombreRubro, usuario.CodUsuario, 
            usuario.Nombre, usuario.Apellido, usuario.Foto, usuario.Direccion, 
            usuario.Telefono, usuario.Correo, usuario.CodRol, usuario.Verificado FROM ((Empresa `empresa` INNER JOIN rubro ON empresa.CodRubro = rubro.CodRubro) INNER JOIN `usuario` ON empresa.CodEmpresa = usuario.CodEmpresa) WHERE usuario.CodRol = 3";
            $cn = new Conexion();
            $dbh = $cn->getConexion();
            $stmt = $dbh->prepare($sql);
            $stmt->execute();
            $rubros = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $rubros;
        }

        public function mostrarEmpresa($id){
            $sql = "SELECT empresa.CodEmpresa, empresa.NombreEmpresa, 
            empresa.PorcentajeComision, rubro.NombreRubro, usuario.CodUsuario, 
            usuario.Nombre, usuario.Apellido, usuario.Foto, usuario.Direccion, 
            usuario.Telefono, usuario.Correo, usuario.CodRol, usuario.Verificado FROM ((Empresa `empresa` INNER JOIN rubro ON empresa.CodRubro = rubro.CodRubro) INNER JOIN `usuario` ON empresa.CodEmpresa = usuario.CodEmpresa) WHERE usuario.CodRol = 3 
            AND empresa.CodEmpresa = :id";
            $cn = new Conexion();
            $dbh = $cn->getConexion();
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':id',$id);
            $stmt->execute();
            $usuario = $stmt->fetch();
            return $usuario;
        }

        public function valEmail($correo){
            $sql = "SELECT COUNT(*) FROM usuario WHERE Correo = :correo";
            $cn = new Conexion();
            $dbh = $cn->getConexion();
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':correo',$correo);
            $stmt->execute();
            $cantMail = $stmt->fetch();
            return $cantMail;
        }

        public function valTelefono($tel){
            $sql = "SELECT COUNT(*) FROM usuario WHERE Telefono = :tel";
            $cn = new Conexion();
            $dbh = $cn->getConexion();
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':tel',$tel);
            $stmt->execute();
            $cantTelefono = $stmt->fetch();
            return $cantTelefono;
        }

        public function valNombreEmpresa($name){
            $sql = "SELECT COUNT(*) FROM empresa WHERE NombreEmpresa = :nom";
            $cn = new Conexion();
            $dbh = $cn->getConexion();
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':nom',$name);
            $stmt->execute();
            $canNomEmpre = $stmt->fetch();
            return $canNomEmpre;
        }
    }
?>