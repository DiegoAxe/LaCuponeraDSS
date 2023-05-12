<?php
    class Usuario{
        protected $CodUsuario;
        protected $img;
        protected $nombre;
        protected $apellido;
        protected $telefono;
        protected $correo;
        protected $contraseña;
        protected $rol;
        protected $verificado;

        function __construct($nom, $ape, $tel, $email, $role, $verify){
                $this->nombre = $nom;
                $this->apellido = $ape;
                $this->telefono = $tel;
                $this->correo = $email;
                $this->rol = $role;
                $this->verificado = $verify;
        }

        public function setCodUsuario($cod){
            $this->CodUsuario = $cod;
        }
        public function setIMG($imga){
            $this->img = $imga;
        }
        public function setContraseña($contra){
            $this->contraseña = $contra;
        }

        //getters
        public function getCodUsuario(){
            return $this->CodUsuario;
        }

        public function getImg(){
            return $this->img;
        }

        public function getNombre(){
            return $this->nombre;
        }

        public function getApellido(){
            return $this->apellido;
        }

        public function getTelefono(){
            return $this->telefono;
        }

        public function getCorreo(){
            return $this->correo;
        }

        public function getContraseña(){
            return $this->contraseña;
        }

        public function getRol(){
            return $this->rol;
        }

        public function getVerificado(){
            return $this->verificado;
        }
        
        public function valIMG($cod){
            $imagen = explode(".", $this->img);
            if(count($imagen)>=2){
                $nombre = $imagen[0];
                $extension = $imagen[1];
                if(preg_match("/(jpg|png)/", $extension) != 0){
                    $this->setIMG($cod . "." . $extension);
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }

        public function valNombre(){
            if(preg_match("/^(?=.{3,25}$)[A-ZÁÉÍÓÚ][a-zñáéíóú]+(?: [A-ZÁÉÍÓÚ][a-zñáéíóú]+)?$/", $this->nombre) != 0){
                return true;
            }else{
                return false;
            }
        }
        
        public function valApellido(){
            if(preg_match("/^(?=.{3,25}$)[A-ZÁÉÍÓÚ][a-zñáéíóú]+(?: [A-ZÁÉÍÓÚ][a-zñáéíóú]+)?$/", $this->apellido) != 0){
                return true;
            }else{
                return false;
            }
        }

        public function valTelefono(){
            if(preg_match("/^[762][0-9]{3}-[0-9]{4}$/", $this->telefono) != 0){
                return true;
            }else{
                return false;
            }
        }

        public function valCorreo(){
            if(preg_match("/^[^@]+@[^@]+\.[a-zA-Z]{2,}$/", $this->correo) != 0){
                return true;
            }else{
                return false;
            }
        }

        public function valUsuario($cod){
            $errores = array();
            if($this->valIMG($cod) == false){
                array_push($errores, "<h4>Solo se aceptan imágenes: archivos <b>.png</b> o <b>.jpg</b></h4>");
            }else{
                array_push($errores, "");
            }
            if($this->valNombre() == false){
                array_push($errores, "<h4>Solo se aceptan nombres en el formato: Nombre Nombre</h4>");
            }else{
                array_push($errores, "");
            }
            
            if($this->valApellido() == false){
                array_push($errores, "<h4>Solo se aceptan apellidos en el formato: Apellido Apellido</h4>");
            }else{
                array_push($errores, "");
            }
            if($this->valTelefono() == false){
                array_push($errores, "<h4>Solo se acepta un número de telefono (iniciando con 7, 6 o 2) en el formato: XXXX-XXXX</h4>");
            }else{
                array_push($errores, "");
            }

            if($this->valCorreo() == false){
                array_push($errores, "<h4>Solo se acepta un correo electrónico en el formato: direcion@email.com</h4>");
            }else{
                array_push($errores, "");
            }
            return $errores;
        }

        public function valUsuarioAlter(){
            $errores = array();

            array_push($errores, "");

            if($this->valNombre() == false){
                array_push($errores, "<h4>Solo se aceptan nombres en el formato: Nombre Nombre</h4>");
            }else{
                array_push($errores, "");
            }
            
            if($this->valApellido() == false){
                array_push($errores, "<h4>Solo se aceptan apellidos en el formato: Apellido Apellido</h4>");
            }else{
                array_push($errores, "");
            }
            if($this->valTelefono() == false){
                array_push($errores, "<h4>Solo se acepta un número de telefono (iniciando con 7, 6 o 2) en el formato: XXXX-XXXX</h4>");
            }else{
                array_push($errores, "");
            }

            if($this->valCorreo() == false){
                array_push($errores, "<h4>Solo se acepta un correo electrónico en el formato: direcion@email.com</h4>");
            }else{
                array_push($errores, "");
            }
            return $errores;
        }

        public function generarContraseña($strength = 8) {
            $caracteres = 'abcdefghijklmnopqrstuvwyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $anchoCadena = strlen($caracteres);
            $contraRandom = '';
            for($i = 0; $i < $strength; $i++) {
                $caracterAleatorio = $caracteres[mt_rand(0, $anchoCadena - 1)];
                $contraRandom .= $caracterAleatorio;
            }
            return $contraRandom;
        }

        public function seguroInsertarUsuario($cod){
            if($this->valIMG($cod) == true && $this->valNombre() == true && $this->valApellido() == true && 
                $this->valTelefono() == true && $this->valCorreo()== true){
                return true;
            }else{
                return false;
            }
        }

        public function seguroInsertarUsuarioAlter(){
            if($this->valNombre() == true && $this->valApellido() == true && 
                $this->valTelefono() == true && $this->valCorreo()== true){
                return true;
            }else{
                return false;
            }
        }
    }


?>