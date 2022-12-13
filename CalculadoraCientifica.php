<!DOCTYPE HTML>

<html lang="es">
<head>
    <!-- Datos que describen el documento -->
    <meta charset="UTF-8" />
	
    <!--Metadatos de los documentos HTML5-->
    <meta name ="author" content ="Pedro Limeres" />
    <meta name ="description" content ="CalculadoraCientifica" />
    <meta name ="keywords" content ="calculadora" />

    <!--Definición de la ventana gráfica-->
    <meta name ="viewport" content ="width=device-width, initial scale=1.0" />

    <title>CalculadoraBasica</title>

    <link rel="stylesheet" type="text/css" href="CalculadoraCientifica.css" />
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

        class CalculadoraCientifica extends CalculadoraMilan {

            protected $shift;
            protected $grados;

            public function __construct() {
                parent::__construct();
                $this->shift = 0;
                $this->grados = "DEG";
            }

            public function suma(){
                if ($this->pantalla == "0") {
                    $this->pantalla = "+";
                } else {
                    $this->pantalla .= "+";
                }
            }
            public function resta(){
                if ($this->pantalla == "0") {
                    $this->pantalla = "-";
                } else {
                    $this->pantalla .= "-";
                }
            }
            public function multiplicacion(){
                if ($this->pantalla == "0") {
                    $this->pantalla = "*";
                } else {
                    $this->pantalla .= "*";
                }
            }
            public function division(){
                if ($this->pantalla == "0") {
                    $this->pantalla = "/";
                } else {
                    $this->pantalla .= "/";
                }
            }

            public function cambio() {
                if ($this->shift == 0){
                    $this->shift = 1;
                } else {
                    $this->shift = 0;
                }
            }

            public function actualizarPantalla() {
                return $this->pantalla;
            }

            public function sin() {
                if ($this->shift == 0){
                    $this->pantalla = sin( eval("return $this->pantalla ;") );
                } else {
                    $this->pantalla = asin( eval("return $this->pantalla ;") );
                }
            }

            public function cos() {
                if ($this->shift == 0){
                    $this->pantalla = cos( eval("return $this->pantalla ;") );
                } else {
                    $this->pantalla = acos( eval("return $this->pantalla ;") );
                }
            }

            public function tan() {
                if ($this->shift == 0){
                    $this->pantalla = tan( eval("return $this->pantalla ;") );
                } else {
                    $this->pantalla = atan( eval("return $this->pantalla ;") );
                }
            }

            public function ce() {
                $this->pantalla = "0";
            }

            public function openParentesis() {
                if ($this->pantalla == "0") {
                    $this->pantalla = "(";
                } else {
                    $this->pantalla .= "(";
                }
            }

            public function closeParentesis() {
                if ($this->pantalla == "0") {
                    $this->pantalla = ")";
                } else {
                    $this->pantalla .= ")";
                }
            }

            public function square() {
                if ($this->pantalla == "0") {
                    $this->pantalla = "^2";
                } else {
                    $this->pantalla .= "^2";
                }
            }

            public function elevate() {
                if ($this->pantalla == "0") {
                    $this->pantalla = "^";
                } else {
                    $this->pantalla .= "^";
                }
            }

            public function tenElevatedTo() {
                if ($this->pantalla == "0") {
                    $this->pantalla = "10^";
                } else {
                    $this->pantalla .= "10^";
                }
            }

            public function numeroPi() {
                if ($this->pantalla == "0") {
                    $this->pantalla = "π";
                } else {
                    $this->pantalla += "π";
                }
            }

            public function mc() {
                $this->memoria = 0;
            }

            public function ms() {
                $this->memoria = eval("return $this->pantalla ;");
            }

            public function igual() {
                $this->pantalla = str_replace("π", strval(M_PI), $this->pantalla);
                $this->pantalla = str_replace("^", "**", $this->pantalla);
                $this->pantalla = str_replace("%", "/100", $this->pantalla);
                $this->pantalla = str_replace("mod", "%", $this->pantalla);
                $this->pantalla = strval( eval("return $this->pantalla ;") );
            }

            public function factorial() {
                $entrada = eval("return $this->pantalla ;");
                $factorial = 1;
                for ($i = $entrada; $i>0; $i--){
                    $factorial *= $i;
                }
                $this->pantalla = strval($factorial);
        
                $this->operando1 = Number(0);
                $this->anteriorSigno = "";
            }

            public function logar() {
                $this->pantalla = log(eval("return $this->pantalla ;"));
            }

            public function expo() {
                $this->pantalla = strval(exp(intval(eval("return $this->pantalla ;"))) );
            }

            public function mod() {
                if ($this->pantalla == "0") {
                    $this->pantalla = "mod";
                } else {
                    $this->pantalla .= "mod";
                }
            }

            public function deg() {
                if ($this->grados == "DEG"){
                    $this->grados = "RAD";
                    $this->pantalla = strval(intval($this->pantalla) * M_PI / 180);
                } else {
                    $this->grados = "DEG";
                    $this->pantalla = strval(intval($this->pantalla) / M_PI * 180);
                }
            }
        }
    
        $calculadora;
        //Manejo session
        if (isset($_SESSION['miCalculadora'])){
            $calculadora = $_SESSION['miCalculadora'];
        } else {
            $calculadora = new CalculadoraCientifica();
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
        } else if (isset($_POST['C'])) {
            $calculadora->aCero();
        } else if (isset($_POST['CE'])) {
            $calculadora->borrar();
        } else if (isset($_POST['±'])) {
            $calculadora->masMenos();
        } else if (isset($_POST['√'])) {
            $calculadora->raiz();
        } else if (isset($_POST['%'])) {
            $calculadora->porcentaje();
        } else if (isset($_POST['.'])) {
            $calculadora->punto();
        } else if (isset($_POST['MR'])) {
            $calculadora->mrc();
        } else if (isset($_POST['M-'])) {
            $calculadora->mMenos();
        } else if (isset($_POST['M+'])) {
            $calculadora->mMas();
        } else if (isset($_POST['='])) {
            $calculadora->igual();
        } else if (isset($_POST['DEG'])) {
            $calculadora->deg();
        } else if (isset($_POST['MC'])) {
            $calculadora->mc();
        } else if (isset($_POST['MS'])) {
            $calculadora->ms();
        } else if (isset($_POST['x^2'])) {
            $calculadora->square();
        } else if (isset($_POST['x^y'])) {
            $calculadora->elevate();
        } else if (isset($_POST['sin'])) {
            $calculadora->sin();
        } else if (isset($_POST['cos'])) {
            $calculadora->cos();
        } else if (isset($_POST['tan'])) {
            $calculadora->tan();
        } else if (isset($_POST['10^x'])) {
            $calculadora->tenElevatedTo();
        } else if (isset($_POST['log'])) {
            $calculadora->logar();
        } else if (isset($_POST['Exp'])) {
            $calculadora->expo();
        } else if (isset($_POST['Mod'])) {
            $calculadora->mod();
        } else if (isset($_POST['π'])) {
            $calculadora->numeroPi();
        } else if (isset($_POST['⌫'])) {
            $calculadora->borrar();
        } else if (isset($_POST['↥'])) {
            $calculadora->cambio();
        } else if (isset($_POST['n!'])) {
            $calculadora->factorial();
        } else if (isset($_POST['('])) {
            $calculadora->openParentesis();
        } else if (isset($_POST[')'])) {
            $calculadora->closeParentesis();
        } 
    ?>

    <form action="#" method="post"> 
        
        <label for="pantalla"> Calculadora Cientifica </label>
        <input type="text" value="<?php echo $calculadora->actualizarPantalla();?>" name="pantalla" id="pantalla" disabled>
        
        <input type="submit" value="DEG" name="DEG">
        <input type="submit" value="HYP" name="HYP">
        <input type="submit" value="F-E" name="F-E">

        <input type="submit" value="MC" name="MC">
        <input type="submit" value="MR" name="MR">
        <input type="submit" value="M+" name="M+">
        <input type="submit" value="M-" name="M-">
        <input type="submit" value="MS" name="MS">

        <input type="submit" value="x^2" name="x^2">
        <input type="submit" value="x^y" name="x^y">
        <input type="submit" value="sin" name="sin">
        <input type="submit" value="cos" name="cos">
        <input type="submit" value="tan" name="tan">

        <input type="submit" value="√" name="√">
        <input type="submit" value="10^x" name="10^x">
        <input type="submit" value="log" name="log">
        <input type="submit" value="Exp" name="Exp">
        <input type="submit" value="Mod" name="Mod">

        <input type="submit" value="↥" name="↥">
        <input type="submit" value="CE" name="CE">
        <input type="submit" value="C" name="C">
        <input type="submit" value="⌫" name="⌫">
        <input type="submit" value="÷" name="/">

        <input type="submit" value="π" name="π">
        <input type="submit" value="7" name="7">
        <input type="submit" value="8" name="8">
        <input type="submit" value="9" name="9">
        <input type="submit" value="x" name="x">

        <input type="submit" value="n!" name="n!">
        <input type="submit" value="4" name="4">
        <input type="submit" value="5" name="5">
        <input type="submit" value="6" name="6">
        <input type="submit" value="-" name="-">

        <input type="submit" value="±" name="±">
        <input type="submit" value="1" name="1">
        <input type="submit" value="2" name="2">
        <input type="submit" value="3" name="3">
        <input type="submit" value="+" name="+">

        <input type="submit" value="(" name="(">
        <input type="submit" value=")" name=")">
        <input type="submit" value="0" name="0">
        <input type="submit" value="." name=".">
        <input type="submit" value="=" name="=">
    </form>
</body>
</html>