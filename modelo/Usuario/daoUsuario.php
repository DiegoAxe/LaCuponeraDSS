<?php
require_once '../modelo/claseConexion.php';
class daoUsuario{

    public function iniciar($eMail){
        $sql = "SELECT * FROM usuario WHERE correo=:eMail";
        $cn = new Conexion();
        $dbh = $cn->getConexion();
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':eMail',$eMail);
        $stmt->execute();
        $perfil = $stmt->fetch(PDO::FETCH_ASSOC);
        if($perfil){
            return $perfil;
        }else{
            return false;
        }
    }

    public function validar($eMail){
        $sql = "SELECT count('Correo') FROM usuario WHERE Correo=:eMail";
        $cn = new Conexion();
        $dbh = $cn->getConexion();
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':eMail',$eMail);
        $stmt->execute();
        $val = $stmt->execute();
        return $val;
    }
    public function validar2($eMail){
        $sql = "SELECT count('Correo') FROM usuario WHERE Correo=:eMail";
        $cn = new Conexion();
        $dbh = $cn->getConexion();
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':eMail',$eMail);
        $stmt->execute();
        $val = $stmt->execute();
        if($stmt->rowCount()){
            return true;
        } else {
            return false;
        }
    }

    public function verifyDui($dui){
        $cn = new Conexion();
        $dbh = $cn->getConexion();
        $sql = "SELECT * FROM usuario WHERE DUI = :dui";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':dui', $dui);
        $stmt->execute();

        if($stmt->rowCount()){
            return true;
        } else {
            return false;
        }
    }

    public function insertar($usuario){
        $cn = new Conexion();
        $dbh = $cn->getConexion();
        $sql = "INSERT INTO usuario (CodUsuario, DUI, Foto, Nombre, Apellido, Direccion, Telefono, Correo, Contrasena, CodRol, CodEmpresa, codigo_verificacion, verificado)  
                VALUES (:CodUsuario,:DUI,null,:Nombre,:Apellido,:Direccion,:Telefono,:Correo,:Contrasena,2, null, null, 0)";

        $cod=$usuario->getCodUsuario();
        $dui=$usuario->getDUI();
        $foto=$usuario->getIMG();
        $nom=$usuario->getNombre();
        $ape=$usuario->getApellido();
        $dir=$usuario->getDireccion();
        $tel=$usuario->getTelefono();
        $correo=$usuario->getCorreo();
        $psswd=$usuario->getContraseña();
        $rol=$usuario->getRol();
        //$empre=$usuario->getEmpreUsuario();
        //$Cod_ver=$usuario->getCodVerUsuario();
        //$ver=$usuario->getverUsuario();

        try {
            $stmt = $dbh->prepare($sql);
            $stmt -> bindParam(':CodUsuario', $cod);
            $stmt -> bindParam(':DUI', $dui);
            $stmt -> bindParam(':Nombre', $nom);
            $stmt -> bindParam(':Apellido', $ape);
            $stmt -> bindParam(':Direccion', $dir);
            $stmt -> bindParam(':Telefono', $tel);
            $stmt -> bindParam(':Correo', $correo);
            $stmt -> bindParam(':Contrasena', $psswd);
            $stmt -> execute();
        }catch(PDOException $e){
            echo "Error: " . $e->getMessage();
        }

    }
    public function actualizar($usuario){
        $cn = new Conexion();
        $dbh = $cn->getConexion();
        $sql = "UPDATE usuario SET 
            DUI = :DUI,
            Foto = :Foto,
            Nombre = :Nombre,
            Apellido = :Apellido,
            Direccion = :Direccion,
            Telefono = :Telefono,
            Contrasena = :Contrasena,
            CodRol = :CodRol,
            CodEmpresa = :CodEmpresa,
            codigo_verificacion = :codigo_verificacion,
            verificado = :verificado
            WHERE Correo = :Correo";

        $dui = $usuario->getDUI();
        $foto = $usuario->getIMG();
        $nom = $usuario->getNombre();
        $ape = $usuario->getApellido();
        $dir = $usuario->getDireccion();
        $tel = $usuario->getTelefono();
        $correo = $usuario->getCorreo();
        $psswd = $usuario->getContraseña();
        $rol = $usuario->getRol();
        $empre = $usuario->getEmpreUsuario();
        $Cod_ver = $usuario->getCodVerUsuario();
        $ver = $usuario->getverUsuario();

        try {
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':DUI', $dui);
            $stmt->bindParam(':Foto', $foto);
            $stmt->bindParam(':Nombre', $nom);
            $stmt->bindParam(':Apellido', $ape);
            $stmt->bindParam(':Direccion', $dir);
            $stmt->bindParam(':Telefono', $tel);
            $stmt->bindParam(':Correo', $correo);
            $stmt->bindParam(':Contrasena', $psswd);
            $stmt->bindParam(':CodRol', $rol);
            $stmt->bindParam(':CodEmpresa', $empre);
            $stmt->bindParam(':codigo_verificacion', $Cod_ver);
            $stmt->bindParam(':verificado', $ver);
            $stmt->execute();
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function buscarPorCorreo($correo){
        $sql = "SELECT * FROM usuario WHERE Correo=:Correo";
        $cn = new Conexion();
        $dbh = $cn->getConexion();
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':Correo',$correo);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        if($usuario){
            return $usuario;
        }else{
            return false;
        }
    }

    public function buscarPorId($Id){
        $sql = "SELECT * FROM usuario WHERE CodUsuario=:id";
        $cn = new Conexion();
        $dbh = $cn->getConexion();
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':id',$Id);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        if($usuario){
            return $usuario;
        }else{
            return false;
        }
    }

    public function UsuarioYEmpresa($idUser, $rol){
        if($rol == "Empleado"){
            $sql = "SELECT usu.*, empre.* FROM empresa empre INNER JOIN usuario usu ON usu.CodEmpresa = empre.CodEmpresa 
        WHERE usu.CodUsuario = :idUser";
        }else{
            $sql = "SELECT * FROM usuario WHERE CodUsuario = :idUser";
        }

        $cn = new Conexion();
        $dbh = $cn->getConexion();
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':idUser',$idUser);
        $stmt->execute();
        $InfoUsuario = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $InfoUsuario;
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

    public function valNombreUsuario($name){
        $sql = "SELECT COUNT(*) FROM usuario WHERE Nombre = :nom";
        $cn = new Conexion();
        $dbh = $cn->getConexion();
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':nom',$name);
        $stmt->execute();
        $canNom = $stmt->fetch();
        return $canNom;
    }

    public function listaEmpleados($empresa){
        $sql = "SELECT * FROM usuario WHERE CodEmpresa=:empresa AND CodRol = 4";
        $cn = new Conexion();
        $dbh = $cn->getConexion();
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':empresa',$empresa);
        $stmt->execute();
        $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $empleados;
    }

}
?>





