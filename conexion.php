<?php

$host = '127.0.0.1';
$port = '5432';
$dbname = 'tienda_mi_papeleria';
$user = 'postgres';
$password = 'hola2121';
$dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $infoconn = 'Estado: Conexion exitosa.';

} catch (PDOException $e) {
    error_log('Estado de la conexion: ' . $e->getMessage());
    $infoconn = 'Estado: Desconectado.';
}