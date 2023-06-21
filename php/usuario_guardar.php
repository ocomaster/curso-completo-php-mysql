<?php

require_once "main.php";

#Almacenar datos de las variables

$nombre = limpiar_cadena($_POST['usuario_nombre']);
$apellido = limpiar_cadena($_POST['usuario_apellido']);

$usuario = limpiar_cadena($_POST['usuario_usuario']);
$email = limpiar_cadena($_POST['usuario_email']);

$clave_1 = limpiar_cadena($_POST['usuario_clave_1']);
$clave_2 = limpiar_cadena($_POST['usuario_clave_2']);

# Verificacion de campos obligatorios #

if ($nombre =="" || $apellido=="" || $usuario=="" || $clave_1=="" || $clave_2=="") {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            No has llenado todos los campos que son obligatorios
        </div>
    ';
    exit();
}

#Verificacion  de los inputs, integridad de datos#
if (verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$nombre)) {
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        El Nombre no coincide con el formato solicitado.
    </div>
';
exit();
}

if (verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$apellido)) {
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        El Apellidus no coincide con el formato solicitado.
    </div>
';
exit();
}

if (verificar_datos("[a-zA-Z0-9]{4,20}",$usuario)) {
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        El Usuario no coincide con el formato solicitado.
    </div>
';
exit();
}


if (verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave_1) || verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave_2) ) {
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        Las Claves no coincide con el formato solicitado.
    </div>
';
exit();
}

#Verificacion campo Email,Campo no obligatorio #

if ($email!= "") {
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
       $check_email= conexion();
       $check_email= $check_email ->query("SELECT usuario_email FROM usuario WHERE usuario_email='$email' ");
       if ($check_email->rowCount()>0) {
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            El correo ingresado ya esta en uso.
        </div>
    ';
    exit();
       }
        $check_email=null; // Se cuerra la conexion a la base de datos
    } else {
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            El Correo ingresado no es valido.
        </div>
    ';
    exit();
    }
}

#Verificacion de Usuario#

        $check_usuario= conexion();
        $check_usuario= $check_usuario ->query("SELECT usuario_usuario FROM usuario WHERE usuario_usuario='$usuario' ");
        if ($check_usuario->rowCount()>0) {
            echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El Usuario ingresado ya esta en uso.
            </div>
            ';
        exit();
        }
        $check_usuario=null;

# Verificacion misma Clave  #
if ($clave_1 != $clave_2) {
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        Las Claves no coinciden.
    </div>
    ';
exit();
} else {
    # Encriptar clave
    $clave=password_hash($clave_2,PASSWORD_BCRYPT,["cost"=>10]);
}

    #Guardar datos
    //Forma 1 de insertar datos
    // $guardar_usuario= conexion();
    // $guardar_usuario = $guardar_usuario->query("INSERT INTO usuario(usuario_nombre,usuario_apellido,usuario_usuario,usuario_clave,usuario_email)
    //  VALUES('$nombre','$apellido','$usuario','$clave','$email')");

        //Forma 2
        $guardar_usuario= conexion();
        $guardar_usuario = $guardar_usuario->prepare("INSERT INTO usuario(usuario_nombre,usuario_apellido,usuario_usuario,usuario_clave,usuario_email)
        VALUES(:nombre,:apellido,:usuario,:clave,:email)");
        $marcadores=[
            ":nombre"=>$nombre,
            ":apellido"=>$apellido,
            ":usuario"=>$usuario,
            ":clave"=>$clave,
            ":email"=>$email
        ];
        $guardar_usuario->execute($marcadores);

        if ($guardar_usuario->rowCount()==1) {
            echo '
            <div class="notification is-info is-light">
                <strong>¡Usuario Registrado!</strong><br>
                Usuario registrado con exito.
            </div>
            ';
        } else {
            echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No se pudo registrar el usuario,por favor intente nuevamente.
            </div>
            ';
        }
        $guardar_usuario = null;

        ?>
        

