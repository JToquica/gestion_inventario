<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <title>Gestión Inventario</title>
</head>
<body>
    <?php 
        require_once "./controllers/inventarioController.php";
        $controlador = new InventarioControlador();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $codigo = $_POST['codigo'];
            $nombre = $_POST['nombre'];
            $cantidad = $_POST['cantidad'];
            $tipo = $_POST['tipo_movimiento'];
            $fecha = $_POST['fecha'];

            $datos = $codigo . ',' . $nombre . ',' . $cantidad . ',' . $tipo . ',' . $fecha;

            if (isset($_POST["guardar"])) {
                $controlador->guardarDatos($datos);
            } elseif (isset($_POST['eliminar'])) {
                $indice = $_POST['indice'];
                $controlador->eliminarDatos($indice);
            }

            header('Location: index.php');
        }
    ?>

    <div class="contenedor-centrado mt-5 mb-5">
        <div class="titulo">
            <h1 class="texto-header text-center uppercase">Almacén Saya S.A.S</h1>
        </div>

        <div class="main">
            <form method="post">
                <div class="contenido">
                    <div class="campo">
                        <label for="codigo">Código:</label>
                        <input id="codigo" name="codigo" type="text" required>
                    </div>

                    <div class="campo">
                        <label for="nombre">Nombre:</label>
                        <input id="nombre" name="nombre" type="text" required>
                    </div>

                    <div class="campo">
                        <label for="nombre">Cantidad:</label>
                        <input id="nombre" name="cantidad" type="number" min="1" step="1" value="1" required>
                    </div>

                    <div class="tipo-movimiento">
                        <div class="campo">
                            <label for="entra">Entra:</label>
                            <input id="entra" name="tipo_movimiento" type="radio" value="entra" checked>
                        </div>
                        
                        <div class="campo">
                            <label for="sale">Sale:</label>
                            <input id="sale" name="tipo_movimiento" type="radio" value="sale" >
                        </div>
                    </div>

                    <div class="campo">
                        <label for="fecha">Fecha:</label>
                        <input type="date" name="fecha" id="fecha" required>
                    </div>

                    <div class="botones">
                        <button class="btn-guardar" name="guardar" type="submit">Guardar</button>
                    </div>
                </div>
            </form>

            <div class="registros">
                <h2 class="texto-registro text-center uppercase">Registros</h2>

                <?php
                $registros = $controlador->obtenerProductos();

                if (!empty($registros)) {
                    echo "<table id='table-registros' class='table table-striped display responsive nowrap' style='width:100%'>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Codigo</th>
                                <th>Nombre</th>
                                <th>Fecha</th>
                                <th>Cantidad</th>
                                <th>Tipo</th>
                                <th>Saldo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                    <tbody>";

                    foreach ($registros as $indice => $registro) {
                        $codigo = $registro["codigo"];
                        $nombre = $registro["nombre"];
                        $cantidad = $registro["cantidad"];
                        $tipo = ucfirst($registro["tipo"]);
                        $fecha = $registro["fecha"];
                        $saldo = $registro["saldo"];

                        echo "<tr>
                            <td>".$indice."</td>
                            <td>".$codigo."</td>
                            <td>".$nombre."</td>
                            <td>".$fecha."</td>
                            <td>".$cantidad."</td>
                            <td>".$tipo."</td>
                            <td>".$saldo."</td>
                            <td>
                                <form method='post'>
                                    <input type='hidden' name='indice' value='$indice'>
                                    <input class='btn-eliminar' type='submit' name='eliminar' value='Eliminar'>
                                </form>
                            </td>
                        </tr>";
                    }
                    echo '</tbody>
                    </table>';
                } else {
                    echo "<p class='text-center'>No hay registros</p>";
                }
                ?>
            </div>

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#table-registros').DataTable({
                responsive: true,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json',
                },
            });
        });
    </script>
</body>
</html>