<?php
require_once(dirname(__FILE__) . '/globals.php');
require_once ROOT_PATH . 'conexion.php';


$stmt = $pdo->prepare("SELECT * FROM categoria ORDER BY id DESC");
$stmt->execute();
$categorias = $stmt->fetchAll();


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    try {

        $stmt = $pdo->prepare(
            "INSERT INTO productos (nombre, precio, stock, categoria)
            VALUES (:nombre, :precio, :stock, :categoria)"
        );

        $stmt->execute([
            'nombre' => $_POST['nombre-producto'],
            'precio' => $_POST['precio-producto'],
            'stock' => $_POST['stock-producto'],
            'categoria' => (int)$_POST['categoria-producto'],
        ]);

        header('Location: index.php?estado=ingresado');
        exit();
    } catch (PDOException $e) {
        error_log('Error al insertar producto: ' . $e->getMessage());

        $mensajeError = interpretarError($e);
        header('Location: index.php?estado=error&mensaje=' . urlencode($mensajeError));
        exit;
    }
}

function interpretarError(PDOException $e): string
{
    $codigoError = $e->getCode();

    switch ($codigoError) {
        case '23505':
            return 'Ya existe un producto registrado con esa identificación';
        case '23502':
            return 'Los campos son obligatorios';
        case '22001':
            return 'Uno de los campos es demasiado largo';
        case '08006':
            return 'No se pudo conectar a la base de datos';
        case '42703':
            return error_log('La columna no existe en la base de datos');
        case '42P01':
            return error_log('La tabla no existe');
        default:
            return error_log('Error desconocido');
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= URL_BASE . 'css/styles.css' ?>">
    <title>Tienda Mi Papeleria</title>
</head>

<body>
    <?php include('./plantillas/header.php') ?>
    <?php include('./plantillas/formAgregar.php') ?>
    <dialog id="modal">
        <?php include('./plantillas/plantillas-modales/agregarCategorias.php') ?>
    </dialog>
    <script src="index.js"></script>
</body>

</html>