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

    <title>Ejercicio 7</title>

    <link rel="stylesheet" type="text/css" href="Ejercicio7.css" />
</head>

<body>

    <?php 
        session_Start();
        class BaseDatos {
            
            public $found;
            public $informe;
            protected $servername;
            protected $username;
            protected $password;
            protected $database;

            public function __construct() {
                $this->servername = "localhost";
                $this->username = "DBUSER2022";
                $this->password = "DBPSWD2022";
                $this->personaEncontrada = "";
                $this->informe = "";
                $this->database = "ejer7";
            }

            public function insertar() {
                $db = new mysqli($this->servername, $this->username, $this->password, $this->database);
                $query = "INSERT INTO " . $_POST["tablaInsertar"] . " VALUES (?, ?, ?)";
                $consultaPre = mysqli_prepare($db, $query);
                $id = $_POST["idInsertar"];
                $name = $_POST["nameInsertar"];
                $extra = $_POST["extraInsertar"];
                mysqli_stmt_bind_param($consultaPre, 'sss', $id, $name, $extra);
                mysqli_stmt_execute($consultaPre);
                $db->close();
            }

            public function buscar() {
                $db = new mysqli($this->servername, $this->username, $this->password, $this->database);
                $query = "SELECT * FROM " . $_POST["tablaBusc"];
                $consultaPre = $db->prepare("SELECT * FROM ?");
                $resultado = $db->query($query);
                $this->found = "La tabla " . $_POST["tablaBusc"] . " no tiene filas";

                if ($resultado->fetch_assoc() != NULL) {
                    $this->found = "";
                    $resultado->data_seek(0);
                    while ($row = $resultado->fetch_assoc()) {
                        $this->found .= "Tabla: " . $_POST["tablaBusc"] . "   ID: " . $row["id"] . "   Nombre: " . $row["name"] . "\n";
                    }
                }
                $db->close();
            }

            public function modificar() {
                $db = new mysqli($this->servername, $this->username, $this->password, $this->database);
                $query = "UPDATE " . $_POST["tablaMod"] . " SET `name` = ? WHERE id = ?";
                $consultaPre = mysqli_prepare($db, $query);
                $name = $_POST["nameMod"];
                $id = $_POST["idMod"];
                mysqli_stmt_bind_param($consultaPre, 'ss', $name, $id);
                mysqli_stmt_execute($consultaPre);
                $db->close();
            }

            public function eliminar() {
                $db = new mysqli($this->servername, $this->username, $this->password, $this->database);
                $query = "DELETE FROM " . $_POST["tablaElim"] . "  WHERE id = ?";
                $consultaPre = mysqli_prepare($db, $query);
                $id = $_POST["idElim"];
                mysqli_stmt_bind_param($consultaPre, 's', $id);
                mysqli_stmt_execute($consultaPre);
                $db->close();
            }

            public function generarInforme() {
                $db = new mysqli($this->servername, $this->username, $this->password, $this->database);
                $this->informe = "";

                //Variables para las medidas
                $totalJefes = 0;
                $totalEquipos = 0;
                $totalPilotos = 0;
                $totalIngenieros = 0;
                $totalMonoplazas = 0;

                $resultado = $db->query("SELECT * FROM jefe");
                if ($resultado->fetch_assoc() != NULL) {
                    $resultado->data_seek(0);
                    while ($row = $resultado->fetch_assoc()) {
                        $totalJefes++;
                    }
                }

                $resultado = $db->query("SELECT * FROM team");
                if ($resultado->fetch_assoc() != NULL) {
                    $resultado->data_seek(0);
                    while ($row = $resultado->fetch_assoc()) {
                        $totalEquipos++;
                    }
                }

                $resultado = $db->query("SELECT * FROM piloto");
                if ($resultado->fetch_assoc() != NULL) {
                    $resultado->data_seek(0);
                    while ($row = $resultado->fetch_assoc()) {
                        $totalPilotos++;
                    }
                }

                $resultado = $db->query("SELECT * FROM ingeniero");
                if ($resultado->fetch_assoc() != NULL) {
                    $resultado->data_seek(0);
                    while ($row = $resultado->fetch_assoc()) {
                        $totalIngenieros++;
                    }
                }

                $resultado = $db->query("SELECT * FROM monoplaza");
                if ($resultado->fetch_assoc() != NULL) {
                    $resultado->data_seek(0);
                    while ($row = $resultado->fetch_assoc()) {
                        $totalMonoplazas++;
                    }
                }

                $this->informe .= "En la tabla jefe hay ";
                $this->informe .= strval($totalJefes);
                $this->informe .= " filas\n";

                $this->informe .= "En la tabla team(equipo) hay ";
                $this->informe .= strval($totalEquipos);
                $this->informe .= " filas\n";

                $this->informe .= "En la tabla piloto hay ";
                $this->informe .= strval($totalPilotos);
                $this->informe .= " filas\n";

                $this->informe .= "En la tabla ingeniero hay ";
                $this->informe .= strval($totalIngenieros);
                $this->informe .= " filas\n";

                $this->informe .= "En la tabla monoplaza hay ";
                $this->informe .= strval($totalMonoplazas);
                $this->informe .= " filas\n";

                $db->close();
            }

            public function cargarCSV() {
                $db = new mysqli($this->servername, $this->username, $this->password, $this->database);
                $archivo = $_FILES["fileCargar"]["name"];
                $file = fopen($archivo, "r");
                while (($row = fgetcsv($file)) !== FALSE) {
                    $query = "INSERT INTO " . $row[0] . " VALUES (?, ?, ?)";
                    $consultaPre = mysqli_prepare($db, $query);
                    $id = $row[1];
                    $name = $row[2];
                    $extra = $row[3];
                    mysqli_stmt_bind_param($consultaPre, 'ssss', $tabla, $id, $name, $extra);
                    mysqli_stmt_execute($consultaPre);
                }
                $db->close();
            }

            public function exportarCSV() {
                $db = new mysqli($this->servername, $this->username, $this->password, $this->database);

                $file = fopen("formula1.csv", "w+");

                $resultado = $db->query("SELECT * FROM jefe");
                if ($resultado->fetch_assoc() != NULL) {
                    $resultado->data_seek(0);
                    while ($row = $resultado->fetch_assoc()) {
                        $linea = "jefe,";
                        $linea .= $row["id"];
                        $linea .= ",";
                        $linea .= $row["name"];
                        $linea .= ",";
                        $linea .= $row["wifeName"];
                        $linea .= "\n";

                        fwrite($file, $linea);
                    }
                }

                $resultado = $db->query("SELECT * FROM team");
                if ($resultado->fetch_assoc() != NULL) {
                    $resultado->data_seek(0);
                    while ($row = $resultado->fetch_assoc()) {
                        $linea = "team,";
                        $linea .= $row["id"];
                        $linea .= ",";
                        $linea .= $row["name"];
                        $linea .= ",";
                        $linea .= $row["jefeId"];
                        $linea .= "\n";
                        
                        fwrite($file, $linea);
                    }
                }

                $resultado = $db->query("SELECT * FROM piloto");
                if ($resultado->fetch_assoc() != NULL) {
                    $resultado->data_seek(0);
                    while ($row = $resultado->fetch_assoc()) {
                        $linea = "piloto,";
                        $linea .= $row["id"];
                        $linea .= ",";
                        $linea .= $row["name"];
                        $linea .= ",";
                        $linea .= $row["teamId"];
                        $linea .= "\n";
                        
                        fwrite($file, $linea);
                    }
                }

                $resultado = $db->query("SELECT * FROM ingeniero");
                if ($resultado->fetch_assoc() != NULL) {
                    $resultado->data_seek(0);
                    while ($row = $resultado->fetch_assoc()) {
                        $linea = "ingeniero,";
                        $linea .= $row["id"];
                        $linea .= ",";
                        $linea .= $row["name"];
                        $linea .= ",";
                        $linea .= $row["pilotoId"];
                        $linea .= "\n";
                        
                        fwrite($file, $linea);
                    }
                }

                $resultado = $db->query("SELECT * FROM monoplaza");
                if ($resultado->fetch_assoc() != NULL) {
                    $resultado->data_seek(0);
                    while ($row = $resultado->fetch_assoc()) {
                        $linea = "monoplaza,";
                        $linea .= $row["id"];
                        $linea .= ",";
                        $linea .= $row["name"];
                        $linea .= ",";
                        $linea .= $row["ingenieroJefeId"];
                        $linea .= "\n";
                        
                        fwrite($file, $linea);
                    }
                }
                
                fclose($file);
                $db->close();
            }

        }

        $baseDatos;
        //Manejo session
        if (isset($_SESSION['miBase'])){
            $baseDatos = $_SESSION['miBase'];
        } else {
            $baseDatos = new BaseDatos();
            $_SESSION['miBase'] = $baseDatos;
        }

        if (isset($_POST['insertar'])) {
            $baseDatos->insertar();
        } else if (isset($_POST['buscar'])) {
            $baseDatos->buscar();
        } else if (isset($_POST['modificar'])) {
            $baseDatos->modificar();
        } else if (isset($_POST['eliminar'])) {
            $baseDatos->eliminar();
        } else if (isset($_POST['informe'])) {
            $baseDatos->generarInforme();
        } else if (isset($_POST['cargar'])) {
            $baseDatos->cargarCSV();
        } else if (isset($_POST['exportar'])) {
            $baseDatos->exportarCSV();
        }
    
    ?>

    <h1>Ejercicio 7: BD Formula 1</h1>  

    <nav>
        <a accesskey="I" tabindex="3" href = "#insertar">Insertar prueba</a>
            
        <a accesskey="B" tabindex="4" href = "#buscar">Buscar prueba</a>

        <a accesskey="M" tabindex="5" href = "#modificar">Modificar prueba</a>

        <a accesskey="E" tabindex="6" href = "#eliminar">Eliminar prueba</a>

        <a accesskey="G" tabindex="7" href = "#informe">Generar Informe</a>

        <a accesskey="S" tabindex="8" href = "#cargar">Cargar CSV</a>

        <a accesskey="V" tabindex="9" href = "#generar">Generar CSV</a>
    </nav>

    <form action="#" method="post" name="insertar" id="insertar">

        <h2> Insertar en tabla </h2>

        <label for="tablaInsertar"> Tabla donde insertar insertar (monoplaza, ingeniero, piloto, team, jefe)</label>
        <input type="text" value="" id="tablaInsertar" name="tablaInsertar" >

        <label for="idInsertar"> Id a insertar </label>
        <input type="text" value="" id="idInsertar" name="idInsertar" >

        <label for="nameInsertar"> Nombre a insertar </label>
        <input type="text" value="" id="nameInsertar" name="nameInsertar" >

        <label for="extraInsertar"> Id de la relación, o Nombre de la mujer para "jefe" </label>
        <input type="text" value="" id="extraInsertar" name="extraInsertar" >


        <input type="submit" value="Insertar una persona" name="insertar">
        
    </form>

    <form action="#" method="post" name="buscar" id="buscar">
        
        <h2> Buscar en una tabla </h2>

        <label for="tablaBusc"> Nombre de la tabla en la que buscar </label>
        <input type="text" value="" id="tablaBusc" name="tablaBusc" >

        <input type="submit" value="Buscar en la bd por tabla" name="buscar">

        <label for="found"> Valores encontrados </label>
        <textarea id="found" name="found">"<?php echo $baseDatos->found;?>"</textarea>        
    </form>

    <form action="#" method="post" name="modificar" id="modificar">
        
        <h2> Modificar nombre </h2>

        <label for="tablaMod"> Tabla en la que modificar </label>
        <input type="text" value="" id="tablaMod" name="tablaMod" >

        <label for="idMod"> Id de la persona a modificar </label>
        <input type="text" value="" id="idMod" name="idMod" >

        <label for="nameMod"> Nuevo nombre </label>
        <input type="text" value="" id="nameMod" name="nameMod" >

        <input type="submit" value="Cambiar el nombre" name="modificar">
        
    </form>

    <form action="#" method="post" name="eliminar" id="eliminar">
        
        <h2> Eliminar fila </h2>

        <label for="tablaElim"> Tabla de la que eliminar </label>
        <input type="text" value="" id="tablaElim" name="tablaElim" >

        <label for="idElim"> Id de la instancia a eliminar </label>
        <input type="text" value="" id="idElim" name="idElim" >

        <input type="submit" value="Eliminar una persona" name="eliminar">
        
    </form>

    <form action="#" method="post" name="informe" id="informe">
        
        <h2> Generar Informe </h2>

        <input type="submit" value="Generar Informe" name="informe">

        <label for="pantalla"> Informe </label>
        <textarea id="pantalla" name="pantalla">"<?php echo $baseDatos->informe;?>"</textarea>
        
    </form>

    <form action="#" method="post" name="cargar" id="cargar">
        
        <h2> Cargar filas en CSV </h2>

        <label for="fileCargar"> Introduce un archivo csv para cargar filas </label>
        <input type="file" id="fileCargar" name="fileCargar" >

        <input type="submit" value="Cargar de csv" name="cargar">
        
    </form>

    <form action="#" method="post" name="generar" id="generar">
        
        <h2> Generar CSV </h2>

        <input type="submit" value="Exportar a csv" name="exportar">
        
    </form>
</body>
</html>