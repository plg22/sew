<!DOCTYPE HTML>

<html lang="es">
<head>
    <!-- Datos que describen el documento -->
    <meta charset="UTF-8" />
	
    <!--Metadatos de los documentos HTML5-->
    <meta name ="author" content ="Pedro Limeres" />
    <meta name ="description" content ="CalculadoraBasica" />
    <meta name ="keywords" content ="calculadora" />

    <!--Definición de la ventana gráfica-->
    <meta name ="viewport" content ="width=device-width, initial scale=1.0" />

    <title>CalculadoraBasica</title>

    <link rel="stylesheet" type="text/css" href="CalculadoraMilan.css" />
</head>

<body>
    <?php
        session_start();
        class CalculadoraMilan {

            protected $pantalla;
            protected $memoria;
            protected $operando1;
            protected $anteriorSigno;

            public function __construct() {
                $this->pantalla = "0";
                $this->memoria = 0;
                $this->operando1 = 0;
                $this->anteriorSigno = "";
            }

            public function actualizarPantalla() {
                if ($this->operando1 == 0) {
                    return $this->pantalla;
                } else {
                    $res = "";
                    $res .= strval($this->operando1);
                    $res .= $this->anteriorSigno;
                    $res .= $this->pantalla;
                    return $res;
                }
            }

            public function digito($x) {
                if ($this->pantalla == "0") {
                    $this->pantalla = strval($x);
                } else {
                    $this->pantalla .= strval($x);
                }
            }

            public function punto() {
                if ($this->pantalla == "0") {
                    $this->pantalla = ".";
                } else {
                    $this->pantalla .= ".";
                }
            }

            public function suma() {
                $evaluable = "";
                if ($this->operando1 != 0) {
                    $evaluable .= strval($this->operando1);
                    $evaluable .= $this->anteriorSigno;
                    $evaluable .= $this->pantalla;
                } else {
                    $evaluable .= $this->pantalla;
                }
                $this->operando1 = eval("return $evaluable ;");
                $this->pantalla = "";
                $this->anteriorSigno = "+";
            }

            public function resta() {
                $evaluable = "";
                if ($this->operando1 != 0) {
                    $evaluable .= strval($this->operando1);
                    $evaluable .= $this->anteriorSigno;
                    $evaluable .= $this->pantalla;
                } else {
                    $evaluable .= $this->pantalla;
                }
                $this->operando1 = eval("return $evaluable ;");
                $this->pantalla = "";
                $this->anteriorSigno = "-";
            }

            public function multiplicacion() {
                $evaluable = "";
                if ($this->operando1 != 0) {
                    $evaluable .= strval($this->operando1);
                    $evaluable .= $this->anteriorSigno;
                    $evaluable .= $this->pantalla;
                } else {
                    $evaluable .= $this->pantalla;
                }
                $this->operando1 = eval("return $evaluable ;");
                $this->pantalla = "";
                $this->anteriorSigno = "*";
            }

            public function division() {
                $evaluable = "";
                if ($this->operando1 != 0) {
                    $evaluable .= strval($this->operando1);
                    $evaluable .= $this->anteriorSigno;
                    $evaluable .= $this->pantalla;
                } else {
                    $evaluable .= $this->pantalla;
                }
                $this->operando1 = eval("return $evaluable ;");
                $this->pantalla = "";
                $this->anteriorSigno = "/";
            }

            public function mrc() {
                $this->pantalla = "0";
                $this->operando1 = 0;
                $this->anteriorSigno = "";
                $this->pantalla = strval($this->memoria);
            }

            public function mMas() {
                $evaluable = "";
                if ($this->operando1 != 0) {
                    $evaluable .= strval($this->operando1);
                    $evaluable .= $this->anteriorSigno;
                    $evaluable .= $this->pantalla;
                } else {
                    $evaluable .= $this->pantalla;
                }
                $this->memoria += eval("return $evaluable ;");
            }

            public function mMenos() {
                $evaluable = "";
                if ($this->operando1 != 0) {
                    $evaluable .= strval($this->operando1);
                    $evaluable .= $this->anteriorSigno;
                    $evaluable .= $this->pantalla;
                } else {
                    $evaluable .= $this->pantalla;
                }
                $this->memoria -= eval("return $evaluable ;");
            }

            public function borrar() {
                if (strlen($this->pantalla) == 1) {
                    $this->pantalla = "0";
                } else {
                    $this->pantalla = substr($this->pantalla, 0, -1);
                }
            }

            public function aCero() {
                $this->pantalla = "0";
                $this->operando1 = 0;
                $this->anteriorSigno = "";
            }

            public function masMenos() {
                $evaluable = "";
                if ($this->operando1 != 0) {
                    $evaluable .= strval($this->operando1);
                    $evaluable .= $this->anteriorSigno;
                    $evaluable .= $this->pantalla;
                } else {
                    $evaluable .= $this->pantalla;
                }
                $this->pantalla = strval( eval("return $evaluable ;") );
                $this->operando1 = 0;
                $this->anteriorSigno = "";
                if(substr($this->pantalla, 0, 1) == "-") {
                    $this->pantalla = substr($this->pantalla, 1);
                } else {
                    $res = "-";
                    $res .= $this->pantalla;
                    $this->pantalla = $res;
                }
            }

            public function raiz() {
                $evaluable = "";
                if ($this->operando1 != 0) {
                    $evaluable .= strval($this->operando1);
                    $evaluable .= $this->anteriorSigno;
                    $evaluable .= $this->pantalla;
                } else {
                    $evaluable .= $this->pantalla;
                }
                $this->pantalla = strval( sqrt( eval("return $evaluable ;") ) );
                $this->operando1 = 0;
                $this->anteriorSigno = "";
            }

            public function porcentaje() {
                if ($this->pantalla == "0") {
                    $this->pantalla = "%";
                } else {
                    $this->pantalla .= "%";
                }
            }

            public function igual() {
                if ($this->operando1 == 0 && $this->anteriorSigno == ""){
                    $this->pantalla = strval(eval("return $this->pantalla ;"));
                } else {
                    $cambioP = "/100*";
                    $cambioP .= strval($this->operando1);
                    $this->pantalla = str_replace("%", $cambioP, $this->pantalla);
                    $evaluable = "";
                    $evaluable .= strval($this->operando1);
                    $evaluable .= $this->anteriorSigno;
                    $evaluable .= $this->pantalla;
                    $this->pantalla = eval("return $evaluable ;");
                }
        
                $this->operando1 = 0;
                $this->anteriorSigno = "";
            }
        }

        $calculadora;
        //Manejo session
        if (isset($_SESSION['miCalculadora'])){
            $calculadora = $_SESSION['miCalculadora'];
        } else {
            $calculadora = new CalculadoraMilan();
            $_SESSION['miCalculadora'] = $calculadora;
        }

        //Manejo pulsaciones
        if (isset($_POST['0'])) {
            $calculadora->digito("0");
        } else if (isset($_POST['1'])) {
            $calculadora->digito("1");
        } else if (isset($_POST['2'])) {
            $calculadora->digito("2");
        } else if (isset($_POST['3'])) {
            $calculadora->digito("3");
        } else if (isset($_POST['4'])) {
            $calculadora->digito("4");
        } else if (isset($_POST['5'])) {
            $calculadora->digito("5");
        } else if (isset($_POST['6'])) {
            $calculadora->digito("6");
        } else if (isset($_POST['7'])) {
            $calculadora->digito("7");
        } else if (isset($_POST['8'])) {
            $calculadora->digito("8");
        } else if (isset($_POST['9'])) {
            $calculadora->digito("9");
        } else if (isset($_POST['+'])) {
            $calculadora->suma();
        } else if (isset($_POST['-'])) {
            $calculadora->resta();
        } else if (isset($_POST['x'])) {
            $calculadora->multiplicacion();
        } else if (isset($_POST['/'])) {
            $calculadora->division();
        } else if (isset($_POST['ON/C'])) {
            $calculadora->aCero();
        } else if (isset($_POST['CE'])) {
            $calculadora->borrar();
        } else if (isset($_POST['+/-'])) {
            $calculadora->masMenos();
        } else if (isset($_POST['√'])) {
            $calculadora->raiz();
        } else if (isset($_POST['%'])) {
            $calculadora->porcentaje();
        } else if (isset($_POST['.'])) {
            $calculadora->punto();
        } else if (isset($_POST['Mrc'])) {
            $calculadora->mrc();
        } else if (isset($_POST['M-'])) {
            $calculadora->mMenos();
        } else if (isset($_POST['M+'])) {
            $calculadora->mMas();
        } else if (isset($_POST['='])) {
            $calculadora->igual();
        } 
    ?>

    <form action="#" method="post">
        <label for="pantalla"> Calculadora Basica </label>
        <input type="text" value="<?php echo $calculadora->actualizarPantalla();?>" name="pantalla" id="pantalla" disabled>
        
        <input type="submit" value="ON/C" name="ON/C">
        <input type="submit" value="CE" name="CE">
        <input type="submit" value="+/-" name="+/-">
        <input type="submit" value="√" name="√">
        <input type="submit" value="%" name="%">

        <input type="submit" value="7" name="7">
        <input type="submit" value="8" name="8">
        <input type="submit" value="9" name="9">
        <input type="submit" value="x" name="x">
        <input type="submit" value="/" name="/">

        <input type="submit" value="4" name="4">
        <input type="submit" value="5" name="5">
        <input type="submit" value="6" name="6">
        <input type="submit" value="-" name="-">
        <input type="submit" value="Mrc" name="Mrc">

        <input type="submit" value="1" name="1">
        <input type="submit" value="2" name="2">
        <input type="submit" value="3" name="3">
        <input type="submit" value="+" name="+">
        <input type="submit" value="M-" name="M-">

        <input type="submit" value="0" name="0">
        <input type="submit" value="." name=".">
        <input type="submit" value="=" name="=">
        <input type="submit" value="M+" name="M+">
    </form>
</body>
</html>