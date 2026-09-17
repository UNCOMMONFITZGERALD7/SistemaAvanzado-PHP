<?php
require_once(dirname(__FILE__) . '/globals.php');
require_once ROOT_PATH . 'conexion.php';



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['id-proveedor']) {
        try {
            $stmt = $pdo->prepare('DELETE FROM proveedores WHERE id = :id');
            $stmt->execute([
                'id' => $_POST['id-proveedor'],
            ]);

            header('Location: ' . URL_BASE . 'modulos/modulos.php?stlabel=prvd&estado=eliminado');
            exit();
        } catch (PDOException $e) {
            error_log('Error al eliminar el elemento: ' . $e->getMessage());

            $mensajeError = interpretarError($e);
            header('Location: ' . URL_BASE . 'modulos/modulos.php?stlabel=prvd&estado=error&mensaje=' . urlencode($mensajeError));
            exit();
        }
    } elseif ($_POST['id-producto']) {

        try {
            $stmtCheck = $pdo->prepare('SELECT COUNT(*) FROM ventas WHERE producto_id = :id');
            $stmtCheck->execute(['id' => $_POST['id-producto']]);
            $enUso = (int) $stmtCheck->fetchColumn();

            if ($enUso > 0) {
                header('Location: ' . URL_BASE . 'modulos/modulos.php?stlabel=prdc&estado=error&mensaje=' . urlencode("No se puede eliminar: hay $enUso venta(s) asociada(s)."));
                exit();
            }

            $stmt = $pdo->prepare('DELETE FROM productos WHERE id = :id');
            $stmt->execute([
                'id' => $_POST['id-producto'],
            ]);
            header('Location: ' . URL_BASE . 'modulos/modulos.php?stlabel=prdc&estado=eliminado');
            exit();
        } catch (PDOException $e) {
            error_log('Error al eliminar el elemento: ' . $e->getMessage());
            $mensajeError = interpretarError($e);
            header('Location: ' . URL_BASE . 'modulos/modulos.php?stlabel=prdc&estado=error&mensaje=' . urlencode($mensajeError));
            exit();
        }
    }
}

