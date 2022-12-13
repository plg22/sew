<!DOCTYPE HTML>

<html lang="es">
<head>
    <!-- Datos que describen el documento -->
    <meta charset="UTF-8" />
	
    <!--Metadatos de los documentos HTML5-->
    <meta name ="author" content ="Pedro Limeres" />
    <meta name ="description" content ="aquí cada documento debe tener la descripción del contenido concreto del mismo" />
    <meta name ="keywords" content ="aquí cada documento debe tener la lista de las palabras clave del mismo separadas por comas" />

    <!--Definición de la ventana gráfica-->
    <meta name ="viewport" content ="width=device-width, initial scale=1.0" />

    <title>Ejercicio 4</title>

    <link rel="stylesheet" type="text/css" href="Ejercicio4.css" />
</head>

<body>

    <?php 
        session_Start();
        class CobrePrecio {
            protected $url1;
            protected $url2;
            protected $latest;
            protected $result;
            protected $url;

            public function __construct() {
                $this->url1 = "https://metals-api.com/api/";
                $this->url2 = "?access_key=a487v40l3fyi9oqv23r17ollww3k0qii4sm65l2i7227yup25fzfihknzg70&base=EUR&symbols=XCU";
                $this->latest = "latest";
                $this->result = "";
            }

            public function cargarDatos($mode) {
                $this->url = "";
                if ($mode === "0"){
                    $this->url = $this->url1 . $this->latest . $this->url2;
                } else {
                    $fecha = $_POST['fecha'];
                    $this->url = $this->url1 . $fecha . $this->url2;
                }
                $datos = file_get_contents($this->url);

                $json = json_decode($datos);

                //Obtencion de datos
                $dia = $json->date;
                $moneda = $json->base;
                $cobreNormal = $json->rates->XCU;

                //Pasarlo al string
                $this->result = "";
                $this->result = "En el día " . $dia . " el cobre vale " . $cobreNormal . " " . $moneda;
            }

            public function actualizarPantalla() {
                return $this->result;
            }
        }

        $cobrepre;
        //Manejo session
        if (isset($_SESSION['miPC'])){
            $cobrepre = $_SESSION['miPC'];
        } else {
            $cobrepre = new CobrePrecio();
            $_SESSION['miPC'] = $cobrepre;
        }

        if (isset($_POST['actual'])) {
            $cobrepre->cargarDatos("0");
        } else if (isset($_POST['pasado'])) {
            $cobrepre->cargarDatos("1");
        }
    
    ?>

    <h1>Ejercicio 4: Precio del cobre con metals-api</h1>  
    <form action="#" method="post">
        <input type="submit" value="Precio actual" name="actual">
        <label for="fecha"> Introduzca una fecha posterior a 2020-03-31 (YYYY-MM-DD) </label><input type="text" value="" id="fecha" name="fecha" >
        <input type="submit" value="Precio en una fecha" name="pasado">

        <label for="pantalla"> Resultado </label>
        <textarea id="pantalla" name="pantalla">"<?php echo $cobrepre->actualizarPantalla();?>"</textarea>
    </form> 
    
    <footer>
        <section>Precio del cobre</section>
    </footer>
</body>
</html>