<?php

use Vendor\VentasPhp\Config\Database;

/**
 * Funciones de ayuda para la aplicación de ventas
 */

// Definición de constantes
define("PASSWORD_PREDETERMINADA", "Admin1");
define("HOY", date("Y-m-d"));

/**
 * Conexión a la base de datos (compatibilidad con código antiguo)
 * 
 * @return PDO
 */
function conectarBaseDatos() {
    return Database::getInstance();
}

/**
 * Obtiene registros de la base de datos
 * 
 * @param string $sentencia Consulta SQL
 * @param array $parametros Parámetros para la consulta
 * @return array
 */
function select($sql, $params = []) {
    $stmt = Database::getInstance()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}


/**
 * Inserta un nuevo registro
 * 
 * @param string $sentencia Consulta SQL
 * @param array $parametros Parámetros para la consulta
 * @return bool
 */
function insertar($sql, $params) {
    $stmt = Database::getInstance()->prepare($sql);
    return $stmt->execute($params);
}

/**
 * Elimina un registro
 * 
 * @param string $sentencia Consulta SQL
 * @param mixed $id ID del registro a eliminar
 * @return bool
 */
function eliminar($sentencia, $id) {
    $bd = Database::getInstance();
    $respuesta = $bd->prepare($sentencia);
    return $respuesta->execute([$id]);
}

/**
 * Actualiza un registro
 * 
 * @param string $sentencia Consulta SQL
 * @param array $parametros Parámetros para la consulta
 * @return bool
 */
function editar($sentencia, $parametros) {
    $bd = Database::getInstance();
    $respuesta = $bd->prepare($sentencia);
    return $respuesta->execute($parametros);
}

/**
 * Inicia sesión de usuario
 * 
 * @param string $usuario Nombre de usuario
 * @param string $password Contraseña
 * @return object|false
 */
function iniciarSesion($usuario, $password) {
    $sentencia = "SELECT id, usuario FROM usuarios WHERE usuario = ?";
    $resultado = select($sentencia, [$usuario]);
    if($resultado) {
        $usuario = $resultado[0];
        if(verificarPassword($usuario->id, $password)) {
            return $usuario;
        }
    }
    return false;
}

/**
 * Verifica la contraseña del usuario
 * 
 * @param int $idUsuario ID del usuario
 * @param string $password Contraseña a verificar
 * @return bool
 */
function verificarPassword($idUsuario, $password) {
    $sentencia = "SELECT password FROM usuarios WHERE id = ?";
    $contrasenia = select($sentencia, [$idUsuario])[0]->password;
    return password_verify($password, $contrasenia);
}

// ... [Todas tus otras funciones existentes] ...

/**
 * Obtiene el último ID de venta insertado
 * 
 * @return int
 */
function obtenerUltimoIdVenta() {
    $sentencia = "SELECT id FROM ventas ORDER BY id DESC LIMIT 1";
    return select($sentencia)[0]->id;
}

/**
 * Calcula el total de una lista de productos
 * 
 * @param array $lista Lista de productos
 * @return float
 */
function calcularTotalLista($lista) {
    $total = 0;
    foreach($lista as $producto) {
        $total += floatval($producto->venta * $producto->cantidad);
    }
    return $total;
}