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

    <title>Ejercicio 6</title>

    <link rel="stylesheet" type="text/css" href="Ejercicio6.css" />
</head>

<body>

    <?php 
        session_Start();
        class BaseDatos {
            
            public $personaEncontrada;
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
                $this->database = "ejer6";
            }

            public function crearBD() {
                $db = new mysqli($this->servername, $this->username, $this->password, $this->database);
                $cadenaSQL = "CREATE DATABASE IF NOT EXISTS ejer6 COLLATE utf8mb4_general_ci";
                $db->query($cadenaSQL);
                $db->close();
            }

            public function crearTabla() {
                $db = new mysqli($this->servername, $this->username, $this->password, $this->database);

                $cadenaSQL = "CREATE TABLE `pruebasusabilidad` (
                    `id` varchar(5) COLLATE utf8mb4_spanish_ci NOT NULL,
                    `name` varchar(20) COLLATE utf8mb4_spanish_ci NOT NULL,
                    `surname` varchar(30) COLLATE utf8mb4_spanish_ci NOT NULL,
                    `mail` varchar(50) COLLATE utf8mb4_spanish_ci NOT NULL,
                    `phoneNumber` varchar(9) COLLATE utf8mb4_spanish_ci NOT NULL,
                    `age` int(3) NOT NULL,
                    `gender` varchar(6) COLLATE utf8mb4_spanish_ci NOT NULL,
                    `pericia` int(2) NOT NULL,
                    `time` int(4) NOT NULL,
                    `correct` varchar(2) COLLATE utf8mb4_spanish_ci NOT NULL,
                    `comments` text COLLATE utf8mb4_spanish_ci NOT NULL,
                    `mejoras` text COLLATE utf8mb4_spanish_ci NOT NULL,
                    `valoracion` int(2) NOT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
                    ALTER TABLE `pruebasusabilidad`
                    ADD PRIMARY KEY (`id`);
                    COMMIT;";
                $db->query($cadenaSQL);

                $db->close();
            }

            public function insertarPersona() {
                $db = new mysqli($this->servername, $this->username, $this->password, $this->database);
                $consultaPre = mysqli_prepare($db, "INSERT INTO pruebasusabilidad VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $id = $_POST["idInsertar"];
                $name = $_POST["nameInsertar"];
                $surname = $_POST["apellidosInsertar"];
                $mail = $_POST["emailInsertar"];
                $telefono = $_POST["telefonoInsertar"];
                $edad = $_POST["edadInsertar"];
                $sexo = $_POST["sexoInsertar"];
                $pericia = $_POST["periciaInsertar"];
                $tiempo = $_POST["tiempoInsertar"];
                $correcta = $_POST["correctaInsertar"];
                $comment = $_POST["commentInsertar"];
                $propuesta = $_POST["propuestaInsertar"];
                $valoracion = $_POST["valoracionInsertar"];
                mysqli_stmt_bind_param($consultaPre, 'sssssisiisssi', $id, $name, $surname, $mail, $telefono, $edad, $sexo, $pericia, $tiempo, $correcta, $comment, $propuesta, $valoracion);
                mysqli_stmt_execute($consultaPre);
                $db->close();
            }

            public function buscarPorId() {
                $db = new mysqli($this->servername, $this->username, $this->password, $this->database);
                $consultaPre = $db->prepare("SELECT * FROM pruebasusabilidad WHERE id = ?");
                $consultaPre->bind_param('s', $_POST["idBusc"]);
                $consultaPre->execute();
                $resultado = $consultaPre->get_result();
                $this->personaEncontrada = "No existe una persona con ID: " . $_POST["idBusc"];

                if ($resultado->fetch_assoc() != NULL) {
                    $resultado->data_seek(0);
                    $fila = $resultado->fetch_assoc();
                    $this->personaEncontrada = "ID: " . $fila["id"] . "   Nombre: " . $fila["name"] . "   Apellidos: " . $fila["surname"] . "   Email: " . $fila["mail"];
                }
                $db->close();
            }

            public function modificarEmail() {
                $db = new mysqli($this->servername, $this->username, $this->password, $this->database);
                $consultaPre = mysqli_prepare($db, "UPDATE pruebasusabilidad SET mail = ? WHERE id = ?");
                $email = $_POST["emailMod"];
                $id = $_POST["idMod"];
                mysqli_stmt_bind_param($consultaPre, 'ss', $email, $id);
                mysqli_stmt_execute($consultaPre);
                $db->close();
            }

            public function eliminarPersona() {
                $db = new mysqli($this->servername, $this->username, $this->password, $this->database);
                $consultaPre = $db->prepare("DELETE FROM pruebasusabilidad WHERE id = ?");
                $consultaPre->bind_param('s', $_POST["idElim"]);
                $consultaPre->execute();
                $db->close();
            }

            public function generarInforme() {
                $db = new mysqli($this->servername, $this->username, $this->password, $this->database);
                $resultado = $db->query("SELECT * FROM pruebasusabilidad");
                $this->informe = "";

                //Variables para las medidas
                $totalPruebas = 0;
                $totalEdad = 0;
                $totalHombres = 0;
                $totalAptos = 0;
                $totalTiempo = 0;
                $totalPericia = 0;
                $totalValoracion = 0;

                if ($resultado->fetch_assoc() != NULL) {
                    $resultado->data_seek(0);
                    while ($row = $resultado->fetch_assoc()) {
                        $totalPruebas++;
                        $totalEdad += $row['age'];
                        $totalTiempo += $row['time'];
                        $totalPericia += $row['pericia'];
                        $totalValoracion += $row['valoracion'];

                        if ($row['gender'] === "male") {
                            $totalHombres++;
                        }
                        if ($row['correct'] === "si") {
                            $totalAptos++;
                        }
                    }

                    $mediaEdad = $totalEdad / $totalPruebas;
                    $mediaTiempo = $totalTiempo / $totalPruebas;
                    $mediaPericia = $totalPericia / $totalPruebas;
                    $mediaValoracion = $totalValoracion / $totalPruebas;
                    $mediaHombres = $totalHombres / $totalPruebas;
                    $mediaAptos = $totalAptos / $totalPruebas;

                    //Formamos el string de descripcion
                    $this->informe .= "La prueba ha sido realizada por ";
                    $this->informe .= strval($totalPruebas);
                    $this->informe .= " personas";
                    $this->informe .= "\n";
                    $this->informe .= "La media de edad de la gente es ";
                    $this->informe .= strval($mediaEdad);
                    $this->informe .= " años";
                    $this->informe .= "\n";
                    $this->informe .= "La media de tiempo gastado en completar la prueba son ";
                    $this->informe .= strval($mediaTiempo);
                    $this->informe .= " segundos";
                    $this->informe .= "\n";
                    $this->informe .= "La media de pericia informática es de ";
                    $this->informe .= strval($mediaPericia);
                    $this->informe .= " sobre 10";
                    $this->informe .= "\n";
                    $this->informe .= "La media de valoración de la aplicación es de ";
                    $this->informe .= strval($mediaValoracion);
                    $this->informe .= " sobre 10";
                    $this->informe .= "\n";
                    $this->informe .= "El porcentaje de hombres es del ";
                    $this->informe .= strval($mediaHombres * 100);
                    $this->informe .= "%";
                    $this->informe .= "\n";
                    $this->informe .= "El porcentaje de mujeres es del ";
                    $this->informe .= strval((1 - $mediaHombres) * 100);
                    $this->informe .= "%";
                    $this->informe .= "\n";
                    $this->informe .= "El porcentaje de gente que supera la prueba es del ";
                    $this->informe .= strval($mediaAptos * 100);
                    $this->informe .= "%";

                }
                $db->close();
            }

            public function cargarCSV() {
                $db = new mysqli($this->servername, $this->username, $this->password, $this->database);
                $archivo = $_FILES["fileCargar"]["name"];
                $file = fopen($archivo, "r");
                while (($row = fgetcsv($file)) !== FALSE) {
                    $consultaPre = mysqli_prepare($db, "INSERT INTO pruebasusabilidad VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $id = $row[0];
                    $name = $row[1];
                    $surname = $row[2];
                    $mail = $row[3];
                    $telefono = $row[4];
                    $edad = $row[5];
                    $sexo = $row[6];
                    $pericia = $row[7];
                    $tiempo = $row[8];
                    $correcta = $row[9];
                    $comment = $row[10];
                    $propuesta = $row[11];
                    $valoracion = $row[12];
                    mysqli_stmt_bind_param($consultaPre, 'sssssisiisssi', $id, $name, $surname, $mail, $telefono, $edad, $sexo, $pericia, $tiempo, $correcta, $comment, $propuesta, $valoracion);
                    mysqli_stmt_execute($consultaPre);
                }
                $db->close();
            }

            public function exportarCSV() {
                $db = new mysqli($this->servername, $this->username, $this->password, $this->database);
                $resultado = $db->query("SELECT * FROM pruebasusabilidad");

                $file = fopen("pruebasUsabilidad.csv", "w+");

                if ($resultado->fetch_assoc() != NULL) {
                    $resultado->data_seek(0);
                    while ($row = $resultado->fetch_assoc()) {
                        $linea = "";
                        $linea .= $row["id"];
                        $linea .= ",";
                        $linea .= $row["name"];
                        $linea .= ",";
                        $linea .= $row["surname"];
                        $linea .= ",";
                        $linea .= $row["mail"];
                        $linea .= ",";
                        $linea .= $row["phoneNumber"];
                        $linea .= ",";
                        $linea .= $row["age"];
                        $linea .= ",";
                        $linea .= $row["gender"];
                        $linea .= ",";
                        $linea .= $row["pericia"];
                        $linea .= ",";
                        $linea .= $row["time"];
                        $linea .= ",";
                        $linea .= $row["correct"];
                        $linea .= ",";
                        $linea .= $row["comments"];
                        $linea .= ",";
                        $linea .= $row["mejoras"];
                        $linea .= ",";
                        $linea .= $row["valoracion"];
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

        if (isset($_POST['crearBaseDatos'])) {
            $baseDatos->crearBD();
        } else if (isset($_POST['crearTabla'])) {
            $baseDatos->crearTabla();
        } else if (isset($_POST['insertar'])) {
            $baseDatos->insertarPersona();
        } else if (isset($_POST['buscar'])) {
            $baseDatos->buscarPorId();
        } else if (isset($_POST['modificar'])) {
            $baseDatos->modificarEmail();
        } else if (isset($_POST['eliminar'])) {
            $baseDatos->eliminarPersona();
        } else if (isset($_POST['informe'])) {
            $baseDatos->generarInforme();
        } else if (isset($_POST['cargar'])) {
            $baseDatos->cargarCSV();
        } else if (isset($_POST['exportar'])) {
            $baseDatos->exportarCSV();
        }
    
    ?>

    <h1>Ejercicio 6: MySQL</h1>  
    <form action="#" method="post" name="crearBase">

        <h2> Crear Base de Datos </h2>

        <input type="submit" value="Crear base de datos(En caso de no crearse externamente)" name="crearBaseDatos">
        
    </form> 

    <form action="#" method="post" name="crearTabla">

        <h2> Crear tabla </h2>

        <input type="submit" value="Crear tabla de pruebasUsabilidad(En caso de no crearse externamente)" name="crearTabla">
        
    </form>

    <form action="#" method="post" name="insertar">

        <h2> Insertar persona </h2>

        <label for="idInsertar"> Id de la persona a insertar </label>
        <input type="text" value="" id="idInsertar" name="idInsertar" >

        <label for="nameInsertar"> Nombre de la persona a insertar </label>
        <input type="text" value="" id="nameInsertar" name="nameInsertar" >

        <label for="apellidosInsertar"> Apellidos de la persona a insertar </label>
        <input type="text" value="" id="apellidosInsertar" name="apellidosInsertar" >

        <label for="emailInsertar"> Email de la persona a insertar </label>
        <input type="text" value="" id="emailInsertar" name="emailInsertar" >

        <label for="telefonoInsertar"> Telefono sin prefijo de pais de la persona a insertar </label>
        <input type="text" value="" id="telefonoInsertar" name="telefonoInsertar" >

        <label for="edadInsertar"> Edad de la persona a insertar </label>
        <input type="text" value="" id="edadInsertar" name="edadInsertar" >

        <label for="sexoInsertar"> Sexo de la persona a insertar(male, female) </label>
        <input type="text" value="" id="sexoInsertar" name="sexoInsertar" >

        <label for="periciaInsertar"> Pericia de la persona a insertar </label>
        <input type="text" value="" id="periciaInsertar" name="periciaInsertar" >

        <label for="tiempoInsertar"> Tiempo de tarea de la persona a insertar </label>
        <input type="text" value="" id="tiempoInsertar" name="tiempoInsertar" >

        <label for="correctaInsertar"> Tarea corracta (si, no) </label>
        <input type="text" value="" id="correctaInsertar" name="correctaInsertar" >

        <label for="commentInsertar"> Pequeño comentario </label>
        <input type="text" value="" id="commentInsertar" name="commentInsertar" >

        <label for="propuestaInsertar"> Pequeña propuesta para mejorar </label>
        <input type="text" value="" id="propuestaInsertar" name="propuestaInsertar" >

        <label for="valoracionInsertar"> Valoración de la aplicación </label>
        <input type="text" value="" id="valoracionInsertar" name="valoracionInsertar" >


        <input type="submit" value="Insertar una persona" name="insertar">
        
    </form>

    <form action="#" method="post" name="buscar">
        
        <h2> Buscar persona </h2>

        <label for="idBusc"> Id de la persona a buscar </label>
        <input type="text" value="" id="idBusc" name="idBusc" >

        <input type="submit" value="Buscar en la bd por id" name="buscar">

        <label for="personaEncontrada"> Persona encontrada </label>
        <input type="text" value="<?php echo $baseDatos->personaEncontrada;?>" id="personaEncontrada" name="personaEncontrada" disabled>
        
    </form>

    <form action="#" method="post" name="modificar">
        
        <h2> Modificar persona </h2>

        <label for="idMod"> Id de la persona a modificar </label>
        <input type="text" value="" id="idMod" name="idMod" >

        <label for="emailMod"> Nuevo email </label>
        <input type="text" value="" id="emailMod" name="emailMod" >

        <input type="submit" value="Cambiar el email" name="modificar">
        
    </form>

    <form action="#" method="post" name="eliminar">
        
        <h2> Eliminar persona </h2>

        <label for="idElim"> Id de la persona a eliminar </label>
        <input type="text" value="" id="idElim" name="idElim" >

        <input type="submit" value="Eliminar una persona" name="eliminar">
        
    </form>

    <form action="#" method="post" name="generar">
        
        <h2> Generar Informe </h2>

        <input type="submit" value="Generar Informe" name="informe">

        <label for="pantalla"> Informe </label>
        <textarea id="pantalla" name="pantalla">"<?php echo $baseDatos->informe;?>"</textarea>
        
    </form>

    <form enctype="multipart/form-data" action="#" method="post" name="cargar">
        
        <h2> Cargar CSV </h2>

        <label for="fileCargar"> Introduce un archivo csv para cargar una persona </label>
        <input type="file" id="fileCargar" name="fileCargar" >

        <input type="submit" value="Cargar de csv" name="cargar">
        
    </form>

    <form action="#" method="post" name="generar">
        
        <h2> Generar CSV </h2>

        <input type="submit" value="Exportar a csv" name="exportar">
        
    </form>
</body>
</html>