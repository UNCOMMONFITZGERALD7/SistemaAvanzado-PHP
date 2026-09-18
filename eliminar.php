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
    } elseif ($_POST['id-venta']) {

        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare('SELECT producto_id, cantidad FROM ventas WHERE id = :id');
            $stmt->execute(['id' => $_POST['id-venta']]);
            $venta = $stmt->fetch();

            if (!$venta) {
                $pdo->rollBack();
                header('Location: ' . URL_BASE . 'modulos/modulos.php?stlabel=vnts&estado=error&mensaje=' . urlencode('La venta no existe.'));
                exit();
            }

            $stmt = $pdo->prepare('DELETE FROM ventas WHERE id = :id');
            $stmt->execute([
                'id' => $_POST['id-venta'],
            ]);

            $stmt = $pdo->prepare('UPDATE productos SET stock = stock + :cantidad WHERE id = :id');
            $stmt->execute([
                'cantidad' => $venta['cantidad'],
                'id' => $venta['producto_id'],
            ]);

            $pdo->commit();

            header('Location: ' . URL_BASE . 'modulos/modulos.php?stlabel=vnts&estado=eliminado');
            exit();
        } catch (PDOException $e) {
            $pdo->rollBack();
            error_log('Error al eliminar el elemento: ' . $e->getMessage());
            $mensajeError = interpretarError($e);
            header('Location: ' . URL_BASE . 'modulos/modulos.php?stlabel=vnts&estado=error&mensaje=' . urlencode($mensajeError));
            exit();
        }
    }
}

