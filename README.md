SistemaAvanzado-PHP

Repositorio: https://github.com/UNCOMMONFITZGERALD7/SistemaAvanzado-PHP

Presentado por Jesus Daniel Pérez Berrocal


AUTOR

Cedula: 1137524660


ARCHIVO DE BASE DE DATOS

El entregable incluye el archivo tienda_mi_papeleria_BD.sql, que contiene la estructura de las tablas (productos, categoria, proveedores, ventas) necesarias para que el proyecto funcione. Antes de correr el sistema hay que importar ese archivo en PostgreSQL.

Para importarlo desde la terminal:
psql -U postgres -h 127.0.0.1 -p 5432 -d tienda_mi_papeleria -f tienda_mi_papeleria_BD.sql

O desde pgAdmin: click derecho sobre la base de datos, Restore, y seleccionar el archivo tienda_mi_papeleria_BD.sql.

Si la base de datos tienda_mi_papeleria todavia no existe, hay que crearla primero antes de importar el archivo.


DATOS DE CONEXION PARA PRUEBAS

Base de datos: tienda_mi_papeleria
Usuario: postgres
Contraseña: hola2121
Host: 127.0.0.1
Puerto: 5432

Estos datos ya estan puestos en el archivo conexion.php. Si tu Postgres local tiene otro usuario o contraseña, cambialos ahi antes de correr el proyecto.


REQUISITOS

- XAMPP (Apache + PHP)
- PostgreSQL instalado y corriendo (esto no viene incluido en XAMPP, se instala aparte)
- Extension pdo_pgsql habilitada en PHP


CONFIGURACION EN XAMPP

1. Poner la carpeta del proyecto dentro de C:\xampp\htdocs\, por ejemplo C:\xampp\htdocs\examen\

2. Revisar que la constante URL_BASE en globals.php coincida con el nombre de esa carpeta:
   define('URL_BASE', 'http://localhost/examen/');

3. Iniciar Apache desde el panel de XAMPP.

4. Instalar y dejar corriendo PostgreSQL (se instala aparte, no viene con XAMPP).

5. Crear la base de datos tienda_mi_papeleria e importar el archivo tienda_mi_papeleria_BD.sql antes de usar el sistema.


COMO HABILITAR POSTGRES EN PHP (pdo_pgsql)

Por defecto XAMPP no trae habilitada la extension para conectarse a PostgreSQL.

1. Abrir el archivo php.ini, normalmente esta en:
   C:\xampp\php\php.ini

2. Buscar estas lineas:
   ;extension=pdo_pgsql
   ;extension=pgsql

3. Quitarles el punto y coma de adelante para que queden asi:
   extension=pdo_pgsql
   extension=pgsql

4. Guardar el archivo.

5. Reiniciar Apache desde el panel de XAMPP para que tome los cambios.

6. Para confirmar que quedo activa, se puede crear un archivo con <?php phpinfo(); ?> y buscar "pdo_pgsql" en la pagina que se genera.


TRATAMIENTO DE ERRORES DE POSTGRESQL

El proyecto usa PDO con modo de excepciones activado, asi que los errores se pueden atrapar con try/catch en vez de que la pagina se rompa sin explicacion.

Recomendaciones:

- No mostrarle al usuario el mensaje crudo del error de PDO, mejor traducirlo a algo entendible. En el proyecto ya existe interpretarError() en globals.php para esto.

- Codigos de error (SQLSTATE) mas comunes que pueden salir:
  23505 - llave duplicada / dato que ya existe
  23502 - un campo obligatorio quedo vacio
  23503 - violacion de llave foranea, por ejemplo tratar de borrar algo que esta en uso
  22001 - el valor es muy largo para el campo
  08006 - no se pudo conectar a la base de datos
  42703 - la columna no existe
  42P01 - la tabla no existe

- Guardar el error real con error_log() para revisarlo despues, pero mostrarle al usuario un mensaje simple.

- Si sale el error 08006, revisar primero que el servicio de PostgreSQL este corriendo antes de pensar que es un bug del codigo.