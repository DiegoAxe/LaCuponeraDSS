<?php
    class Rubro{
        //Propiedades
        public $CodRubro; //Sólo ingreso en el constructor
        public $NombreRubro;    //Sólo ingreso en el constructor
        public $Descripcion; //Sólo ingreso en el constructor
        //Método constructor
        //$c es un correlativo
        public function __construct($nombre,$desc){
            if(!empty($nombre))
                $this->NombreRubro = $nombre;
            else
                throw new Exception('Error. Nombre incorrecto');
            if(!empty($desc))
                $this->Descripcion = $desc;
            else
                throw new Exception('Error. Descripción vacía');
        }

        //Setters
        public function valNombre($nomRubro){
            if(preg_match('/^[A-Z][a-z-áéíóúÁÉÍÓÚñÑ]+$/', $nomRubro)){
                return true;
            }else{
                return false;
            }
        }

        public function valDescripcion($desc){
            if(preg_match('/^[A-Z\¿\!][a-zA-ZáéíóúÁÉÍÓÚñÑ\s\d\.\,\:\%\/\+\-\*\&\$\"\'\?\¡\¿\!]+$/',$desc))
                return true;
            else
                return false;
        }
    
        public function setCodRubro($id){
            $this->CodRubro = $id;
        }
        
        //Getters
        public function getCodRubro(){
            return $this->CodRubro;
        }
        public function getNombre(){
            return $this->NombreRubro;
        }
        public function getDescripcion(){
            return $this->Descripcion;
        }
        public function toString(){
            return $this->CodRubro.  " - " . $this->NombreRubro . " " . $this->Descripcion;
        }

        public function validarRubro(){
            if($this->valNombre($this->NombreRubro) == true
                && $this->valDescripcion($this->Descripcion) == true){
                $val = true;
            }else{
                $val = false;
            }
            return $val;

        }
}//Fin clase
?>