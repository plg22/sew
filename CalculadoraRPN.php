<!DOCTYPE HTML>

<html lang="es">
<head>
    <!-- Datos que describen el documento -->
    <meta charset="UTF-8" />
	
    <!--Metadatos de los documentos HTML5-->
    <meta name ="author" content ="Pedro Limeres" />
    <meta name ="description" content ="CalculadoraRPN" />
    <meta name ="keywords" content ="calculadora" />

    <!--Definición de la ventana gráfica-->
    <meta name ="viewport" content ="width=device-width, initial scale=1.0" />

    <title>CalculadoraRPN</title>

    <link rel="stylesheet" type="text/css" href="CalculadoraRPN.css" />
</head>

<body>

    <?php
        session_start();
        class CalculadoraRPN {

            protected $pantalla;
            protected $auxiliar;
            protected $shift;
            protected $stack;

            public function __construct() {
                $this->pantalla = "";
                $this->auxiliar = "";
                $this->shift = 0;
                $this->stack = array();
            }

            public function elementosEnPila() {
                return count($this->stack);
            }

            public function actualizarPantalla() {
                $this->pantalla = "";
        
                for ($i = 0; $i <= $this->elementosEnPila() - 1; $i++){
                    $this->pantalla .= strval($this->elementosEnPila() - $i);
                    $this->pantalla .= ":\t";
                    $this->pantalla .= strval($this->stack[$i]);
                    if ($i != $this->elementosEnPila() - 1){
                        $this->pantalla .= "\n";
                    }
                }
        
                return strval($this->pantalla);
            }

            public function actualizarAuxiliar() {
                return $this->auxiliar;
            }

            public function digito($x) {
                $this->auxiliar .= strval($x);
            }

            public function cambio() {
                if ($this->shift == 0){
                    $this->shift = 1;
                } else {
                    $this->shift = 0;
                }
            }

            public function aCero() {
                $this->pantalla = "";
                $this->auxiliar = "";
                $this->shift = 0;
                $this->stack = array();
            }

            public function ce(){
                $this->auxiliar = "";
            }

            public function masMenos() {
                if(substr($this->auxiliar, 0, 1) == "-") {
                    $this->auxiliar = substr($this->auxiliar, 1);
                } else {
                    $res = "-";
                    $res .= $this->auxiliar;
                    $this->auxiliar = $res;
                }
            }

            public function punto() {
                if ($this->auxiliar == "0") {
                    $this->auxiliar = ".";
                } else {
                    $this->auxiliar .= ".";
                }
            }

            public function enter() {
                array_unshift($this->stack, floatval($this->auxiliar)) ;
                $this->auxiliar = "";
            }

            public function suma() {
                if ($this->elementosEnPila() < 2){
                    return;
                }
                $operando2 = array_shift($this->stack);
                $operando1 = array_shift($this->stack);
                array_unshift($this->stack, ($operando1 + $operando2));
            }

            public function resta() {
                if ($this->elementosEnPila() < 2){
                    return;
                }
                $operando2 = array_shift($this->stack);
                $operando1 = array_shift($this->stack);
                array_unshift($this->stack, ($operando1 - $operando2));
            }

            public function multiplicacion() {
                if ($this->elementosEnPila() < 2){
                    return;
                }
                $operando2 = array_shift($this->stack);
                $operando1 = array_shift($this->stack);
                array_unshift($this->stack, ($operando1 * $operando2));
            }

            public function division() {
                if ($this->elementosEnPila() < 2){
                    return;
                }
                $operando2 = array_shift($this->stack);
                $operando1 = array_shift($this->stack);
                array_unshift($this->stack, ($operando1 / $operando2));
            }

            public function sin() {
                if (empty($this->stack)){
                    return;
                }
                $operando = array_shift($this->stack);
                if ($this->shift == 0){
                    array_unshift($this->stack, sin($operando));
                } else {
                    array_unshift($this->stack, asin($operando));
                }
            }

            public function cos() {
                if (empty($this->stack)){
                    return;
                }
                $operando = array_shift($this->stack);
                if ($this->shift == 0){
                    array_unshift($this->stack, cos($operando));
                } else {
                    array_unshift($this->stack, acos($operando));
                }
            }

            public function tan() {
                if (empty($this->stack)){
                    return;
                }
                $operando = array_shift($this->stack);
                if ($this->shift == 0){
                    array_unshift($this->stack, tan($operando));
                } else {
                    array_unshift($this->stack, atan($operando));
                }
            }

        }

        $calculadora;
        //Manejo session
        if (isset($_SESSION['miCalculadora'])){
            $calculadora = $_SESSION['miCalculadora'];
        } else {
            $calculadora = new CalculadoraRPN();
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
        } else if (isset($_POST['±'])) {
            $calculadora->masMenos();
        } else if (isset($_POST['.'])) {
            $calculadora->punto();
        } else if (isset($_POST['Enter'])) {
            $calculadora->enter();
        } else if (isset($_POST['sin'])) {
            $calculadora->sin();
        } else if (isset($_POST['cos'])) {
            $calculadora->cos();
        } else if (isset($_POST['tan'])) {
            $calculadora->tan();
        } else if (isset($_POST['↥'])) {
            $calculadora->cambio();
        } 
    ?>

    <form action="#" method="post">
        <label for="pantalla"> Calculadora RPN </label>
        <textarea id="pantalla" name="pantalla">"<?php echo $calculadora->actualizarPantalla();?>"</textarea>

        <label for="auxiliar"> Actual: </label>
        <input type="text" name="auxiliar" id="auxiliar" value="<?php echo $calculadora->actualizarAuxiliar();?>" disabled>

        <input type="submit" value="↥" name="↥">
        <input type="submit" value="sin" name="sin">
        <input type="submit" value="cos" name="cos">
        <input type="submit" value="tan" name="tan">
        
        <input type="submit" value="ON/C" name="ON/C">
        <input type="submit" value="CE" name="CE">
        <input type="submit" value="±" name="±">
        <input type="submit" value="/" name="/">
        
        <input type="submit" value="7" name="7">
        <input type="submit" value="8" name="8">
        <input type="submit" value="9" name="9">
        <input type="submit" value="x" name="x">

        <input type="submit" value="4" name="4">
        <input type="submit" value="5" name="5">
        <input type="submit" value="6" name="6">
        <input type="submit" value="-" name="-">

        <input type="submit" value="1" name="1">
        <input type="submit" value="2" name="2">
        <input type="submit" value="3" name="3">
        <input type="submit" value="+" name="+">

        <input type="submit" value="0" name="0">
        <input type="submit" value="." name=".">
        <input type="submit" value="Enter" name="Enter">

    </form>
</body>
</html>