<?php
class InventarioControlador {
    public $archivo;

    public function __construct() {
        $this->archivo = dirname(__DIR__)."\db\db.txt";
    }

    public function obtenerProductos() {
        if (file_exists($this->archivo)) {
            $contenido = file_get_contents($this->archivo);
            $lineas = explode("\n", $contenido);
            $productos = [];

            foreach ($lineas as $linea) {
                $datos = explode(',', $linea);

                if (count($datos) === 6) {
                    list($codigo, $nombre, $cantidad, $tipo, $fecha, $saldo) = $datos;

                    $producto = [
                        'codigo' => $codigo,
                        'nombre' => $nombre,
                        'cantidad' => $cantidad,
                        'tipo' => $tipo,
                        'fecha' => $fecha,
                        'saldo' => $saldo
                    ];

                    $productos[] = $producto;
                }
            }

            return $productos;
        }

        return [];
    }

    public function buscarProductoPorCodigo($productos, $codigo) {
        foreach ($productos as $producto) {
            if ($producto['codigo'] == $codigo) {
                return $producto;
            }
        }

        return null;
    }

    public function guardarProductos($productos) {
        $contenido = '';
        foreach ($productos as $producto) {
            $contenido .= implode(',', $producto) . "\n";
        }

        file_put_contents($this->archivo, $contenido);
    }

    public function guardarDatos($datos) {
        $productos = $this->obtenerProductos();
        list($codigo, $nombre, $cantidad, $tipo, $fecha) = explode(',', $datos);

        $productoExistente = $this->buscarProductoPorCodigo($productos, $codigo);

        if ($productoExistente) {
            $saldo = 0;

            foreach ($productos as &$producto) {
                if ($producto['codigo'] == $codigo) {
                    if ($producto['tipo'] == 'entra') {
                        $saldo += $producto['cantidad'];
                    } elseif ($producto['tipo'] == 'sale') {
                        $saldo -= $producto['cantidad'];
                    }
                }
            }

            if ($tipo == 'entra') {
                $saldo += $cantidad;
            } else {
                $saldo -= $cantidad;
            }
        } else {
            $saldo = $cantidad;
        }

        $nuevoRegistro = array(
            'codigo' => $codigo,
            'nombre' => $nombre,
            'cantidad' => $cantidad,
            'tipo' => $tipo,
            'fecha' => $fecha,
            'saldo' => $saldo
        );

        $productos[] = $nuevoRegistro;

        $this->guardarProductos($productos);
    }

    public function eliminarDatos($indice) {
        $productos = $this->obtenerProductos();

        if (isset($productos[$indice])) {
            unset($productos[$indice]);
            $productos = array_values($productos);
            $this->guardarProductos($productos);
        }
    }

    // public function actualizarDatos($datos, $indice) {
    //     $productos = $this->obtenerProductos();
    //     list($codigo, $nombre, $cantidad, $tipo, $fecha) = explode(',', $datos);

    //     if (isset($productos[$indice])) {
    //         $producto = $productos[$indice];

    //         if ($codigo != $producto['codigo']) {
    //             unset($productos[$indice]);

    //             $saldo = 0;
    //             foreach ($productos as &$productoExistente) {
    //                 if ($productoExistente['codigo'] == $codigo) {
    //                     if ($productoExistente['tipo'] == 'entra') {
    //                         $saldo += $productoExistente['cantidad'];
    //                     } elseif ($productoExistente['tipo'] == 'sale') {
    //                         $saldo -= $productoExistente['cantidad'];
    //                     }
    //                 }
    //             }
    //             $saldo += $cantidad;
    //         } else {
    //             $saldo = $producto['saldo'];
    //             if ($producto['tipo'] == 'entra') {
    //                 $saldo -= $producto['cantidad'];
    //             } elseif ($producto['tipo'] == 'sale') {
    //                 $saldo += $producto['cantidad'];
    //             }
    //             $saldo += $cantidad;
    //         }

    //         $productos[$indice]['codigo'] = $codigo;
    //         $productos[$indice]['nombre'] = $nombre;
    //         $productos[$indice]['cantidad'] = $cantidad;
    //         $productos[$indice]['tipo'] = $tipo;
    //         $productos[$indice]['fecha'] = $fecha;
    //         $productos[$indice]['saldo'] = $saldo;

    //         $this->guardarProductos($productos);
    //     }
    // }
}

?>