<?php
    require '../modelo/Usuario/usuario.class.php';
    class Empresa extends Usuario{
        //Propiedades
        private $CodEmpresa;
        private $NombreEmpresa;
        private $PorcentajeComision;
        private $Rubro;

        private $Direccion;
        
        //Método constructor
        //$c es un correlativo
        function __construct($nomEmpre, $porcentaje, $Rubro, $dir, $nom, $ape, $tel, $email, $role, $verify){
            parent::__construct($nom, $ape, $tel, $email, $role, $dir,$verify);
            $this->NombreEmpresa = $nomEmpre;
            $this->PorcentajeComision = $porcentaje;
            $this->Rubro = $Rubro;
            $this->Direccion = $dir;
        }

        //Setters
        public function valNombreEmpresa(){
            if(preg_match('/^(?=.{3,25}$)[A-ZÁÉÍÓÚ][a-zñáéíóú]+(?: [A-ZÁÉÍÓÚ][a-zñáéíóú]+)?$/', $this->NombreEmpresa)){
                return true;
            }else{
                return false;
            }
        }

        public function valPorcentajeComision(){
            $exist = intval($this->PorcentajeComision);
            $this->PorcentajeComision = $exist;
            if(is_int($this->PorcentajeComision) && $this->PorcentajeComision>0 &&  $this->PorcentajeComision < 40){
                return true;
            }else{
                return false;
            }
        }
        
        public function valDireccion(){
            if(preg_match('/[A-Z][a-zA-ZáéíóúÁÉÍÓÚñÑ\s\d\.\,\:\/\%\#\"\']+/', $this->Direccion)){
                return true;
            }else{
                return false;
            }
        }
    
        public function setCodEmpresa($id){
            $this->CodEmpresa = $id;
        }

        public function generarCodEmpresa(){
            $cod = "EMP";
            $cod.= mt_rand(0,999);
            return $cod;
        }
        
        
        //Getters
        public function getCodEmpresa(){
            return $this->CodEmpresa;
        }
        public function getNombreEmpresa(){
            return $this->NombreEmpresa;
        }
        public function getPorcentajeComision(){
            return $this->PorcentajeComision;
        }
        public function getRubro(){
            return $this->Rubro;
        }
        public function getDireccion(){
            return $this->Direccion;
        }

        public function valEmpresa(){
            $errores = array();
            if($this->valNombreEmpresa() == false){
                array_push($errores, "<h4>Solo se aceptan nombres con inicial mayúscula en el formato: Nombre Nombre</h4>");
            }else{
                array_push($errores, "");
            }
            if($this->valDireccion() == false){
                array_push($errores, "<h4>Sólo se aceptan direcciones que cada ubicación inicie con mayúscula en el formato: Direccion...</h4>");
            }else{
                array_push($errores, "");
            }
            if($this->valPorcentajeComision() == false){
                array_push($errores, "<h4>Solo se aceptan números enteros</h4>");
            }else{
                array_push($errores, "");
            }
            
            
            return $errores;
        }

        public function seguroInsertarEmpresa(){
            if($this->valNombreEmpresa() == true && $this->valPorcentajeComision() == true && $this->valDireccion() == true){
                return true;
            }else{
                return false;
            }
        }
}//Fin clase
?>