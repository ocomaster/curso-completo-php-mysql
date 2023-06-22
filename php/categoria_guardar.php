<?php 

require_once "main.php";

# Almacenar los datos del formulario en variables

$nombre = limpiar_cadena($_POST['categoria_nombre']);
$ubicacion = limpiar_cadena($_POST['categoria_ubicacion']);

# Verificacion de campos obligatorios #

if ($nombre =="") {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            No has llenado todos los campos que son obligatorios
        </div>
    ';
    exit();
}

#Verificacion  de los inputs, integridad de datos#
if (verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{4,50}",$nombre)) {
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        El Nombre no coincide con el formato solicitado.
    </div>
';
exit();
}

if ($ubicacion!="") {
    if (verificar_datos("[[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{5,150}",$ubicacion)) {
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            La ubicacion no coincide con el formato solicitado.
        </div>
    ';
    exit();
    }
}

#Verificacion de categoria, que dicha no sea repetida#

$check_nombre= conexion();
$check_nombre= $check_nombre ->query("SELECT categoria_nombre FROM categoria WHERE categoria_nombre='$nombre'");
if ($check_nombre->rowCount()>0) {
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
       El nombre ingresado ya se encuentra registrado.
    </div>
    ';
exit();
}
$check_nombre=null;



    

    #Guardar datos categoria
    $guardar_categoria= conexion();
    $guardar_categoria = $guardar_categoria->prepare("INSERT INTO categoria(categoria_nombre,categoria_ubicacion)values(:nombre, :ubicacion)");

    $marcadores=[
        ":nombre"=> $nombre,
        ":ubicacion"=>$ubicacion
    ];
    $guardar_categoria->execute($marcadores);

    if ($guardar_categoria->rowCount()==1) {
        echo '
        <div class="notification is-info is-light">
            <strong>¡Categoria Registrada!</strong><br>
            Categoria registrada con exito.
        </div>
        ';
    } else {
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            No se pudo registrar el categotia, por favor intente nuevamente.
        </div>
        ';
    }
    $guardar_categoria = null;

    ?>