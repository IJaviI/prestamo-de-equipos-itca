<?php
session_start();

require_once("../controladores/cls_equipos.php");
require_once("../controladores/cls_marcas.php");
require_once("../controladores/cls_departamentos.php");

$obj_equipos = new cls_equipos;

// Mostrar tabla
if(isset($_GET["tabla"])) {
    $result = $obj_equipos -> consult();
    getData($result);
}

// Agregar equipo
$addingEquipo = "";
if(isset($_GET["addEquipo"]) && isset($_GET["equipo_name"]) && isset($_GET["equipo_serie"]) && isset($_GET["equipo_description"]) && isset($_GET["equipo_modelo"]) && isset($_GET["equipo_stock"]) && isset($_GET["equipo_depto"]) && isset($_GET["equipo_marca"])) {
    $equipo_name = $_GET["equipo_name"];
    $equipo_serie = $_GET["equipo_serie"];
    $equipo_description = $_GET["equipo_description"];
    $equipo_modelo = $_GET["equipo_modelo"];
    $equipo_stock = $_GET["equipo_stock"];
    $equipo_depto = $_GET["equipo_depto"];
    $equipo_marca = $_GET["equipo_marca"];

    $addingEquipo.= $obj_equipos -> addEquipo($equipo_name, $equipo_serie, $equipo_description, $equipo_modelo, $equipo_stock, $equipo_marca, $equipo_depto);
}
echo $addingEquipo;

// Agregando registros de equipos atraves de un csv
$csv_equipos="";
if(array_key_exists('csv_equipos', $_FILES)){
   $file_name = $_FILES["csv_equipos"]["name"];
   $file_type = $_FILES["csv_equipos"]["type"];
   $tmp_name = $_FILES["csv_equipos"]["tmp_name"];
   $file_size = $_FILES["csv_equipos"]["size"];
   $folder = "../../recursos/csv-files/". $file_name;
   
   $file["name"] = $file_name;
   $file["type"] = $file_type;
   $file["tmp_name"] = $tmp_name;
   $file["size"] = $file_size;
   $file["folder"] = $folder;

   $csv_equipos.= $obj_equipos -> csvEquipos($file);
}
echo $csv_equipos;

// Eliminar equipo
$deleteEquipo = "";
if(isset($_GET["equipo_idRemove"])) {
    $deleteEquipo.= $obj_equipos -> delete($_GET["equipo_idRemove"]);
}
echo $deleteEquipo;

// Si el boton edit es clickeado
$sendToEdit = "";
if(isset($_GET["equipo_sendToEdit"])) {
    $_SESSION["equipo"]["id"] = $_GET["equipo_sendToEdit"];

    if(isset($_SESSION["equipo"])){
        $equipo = $obj_equipos -> consultEquipo($_SESSION["equipo"]["id"]);
        $equipo_data = $equipo -> fetch_assoc();

        $_SESSION["equipo"]["equipo_name"] = $equipo_data["equipo"];
        $_SESSION["equipo"]["equipo_serie"] = $equipo_data["n_serie"];
        $_SESSION["equipo"]["equipo_description"] = $equipo_data["descripcion"];
        $_SESSION["equipo"]["equipo_modelo"] = $equipo_data["modelo"];
        $_SESSION["equipo"]["equipo_stock"] = $equipo_data["stock"];
        $_SESSION["equipo"]["equipo_marca"] = $equipo_data["modelo"];
        $_SESSION["equipo"]["equipo_depto"] = $equipo_data["modelo"];
    }
    
    $sendToEdit.= "<script>window.location.href='editar-equipo'</script>";
}

echo $sendToEdit;

$equipoEdit = "";
// Actualizar equipo
if(isset($_GET["equipo_idEdit"]) && isset($_GET["equipo_nameEdit"]) && isset($_GET["equipo_serieEdit"]) && isset($_GET["equipo_descriptionEdit"]) && isset($_GET["equipo_modeloEdit"]) && isset($_GET["equipo_stockEdit"]) && isset($_GET["equipo_marcaEdit"]) && isset($_GET["equipo_deptoEdit"])) {
    $equipo_id = $_GET["equipo_idEdit"];
    $equipo_name = $_GET["equipo_nameEdit"];
    $equipo_serie = $_GET["equipo_serieEdit"];
    $equipo_description = $_GET["equipo_descriptionEdit"];
    $equipo_modelo = $_GET["equipo_modeloEdit"];
    $equipo_stock = $_GET["equipo_stockEdit"];
    $equipo_marca = $_GET["equipo_marcaEdit"];
    $equipo_depto = $_GET["equipo_deptoEdit"];

    $equipoEdit.= $obj_equipos -> updateAll($equipo_id, $equipo_name, $equipo_serie, $equipo_description, $equipo_modelo, $equipo_stock, $equipo_marca, $equipo_depto);
} else if(isset($_GET["equipo_nameSerie"]) && isset($_GET["equipo_idEdit"]) && isset($_GET["equipo_nameEdit"]) && isset($_GET["equipo_serieEdit"]) && isset($_GET["equipo_descriptionEdit"]) && isset($_GET["equipo_modeloEdit"]) && isset($_GET["equipo_stockEdit"]) && isset($_GET["equipo_marcaEdit"]) && isset($_GET["equipo_deptoEdit"])) {
    $equipo_id = $_GET["equipo_idEdit"];
    $equipo_name = $_GET["equipo_nameEdit"];
    $equipo_serie = $_GET["equipo_serieEdit"];
    $equipo_description = $_GET["equipo_descriptionEdit"];
    $equipo_modelo = $_GET["equipo_modeloEdit"];
    $equipo_stock = $_GET["equipo_stockEdit"];
    $equipo_marca = $_GET["equipo_marcaEdit"];
    $equipo_depto = $_GET["equipo_deptoEdit"];

    $equipoEdit.= $obj_equipos -> updateBasedOnNameAndSerie($equipo_id, $equipo_name, $equipo_serie, $equipo_description, $equipo_modelo, $equipo_stock, $equipo_marca, $equipo_depto);
} else if(isset($_GET["equipo_RestOfFields"]) && isset($_GET["equipo_idEdit"]) && isset($_GET["equipo_descriptionEdit"]) && isset($_GET["equipo_modeloEdit"]) && isset($_GET["equipo_stockEdit"]) && isset($_GET["equipo_marcaEdit"]) && isset($_GET["equipo_deptoEdit"])) {
    $equipo_id = $_GET["equipo_idEdit"];
    $equipo_description = $_GET["equipo_descriptionEdit"];
    $equipo_modelo = $_GET["equipo_modeloEdit"];
    $equipo_stock = $_GET["equipo_stockEdit"];
    $equipo_marca = $_GET["equipo_marcaEdit"];
    $equipo_depto = $_GET["equipo_deptoEdit"];

    $equipoEdit.= $obj_equipos -> updateRestOfFields($equipo_id, $equipo_description, $equipo_modelo, $equipo_stock, $equipo_marca, $equipo_depto);
}
echo $equipoEdit;

// Filtros a equipos
if(isset($_GET["filterEquipoName"]) && isset($_GET["filterEquipoMarca"]) && isset($_GET["filterEquipoDepto"]) && isset($_GET["filterEquipoModelo"])) {
    $name = $_GET["filterEquipoName"];
    $marca = $_GET["filterEquipoMarca"];
    $depto = $_GET["filterEquipoDepto"];
    $modelo = $_GET["filterEquipoModelo"];

    if($name != "" && $marca != "" && $depto != "" && $modelo != "") {
        // Filtrar equipos por nombre, estado, marca y depto
        $result = $obj_equipos -> filterEquipoByAll($name, $marca, $depto, $modelo);
        getData($result);
    } else if($name != "" && $marca != "" && $modelo != "") {
        $result = $obj_equipos->filterEquipoByNameMarcaModelo($name, $marca, $modelo);
        getData($result);
    } else if($name != "" && $depto != "" && $modelo != "") {
        $result = $obj_equipos->filterEquipoByNameDeptoModelo($name, $depto, $modelo);
        getData($result);
    } else if($name != "" && $depto != "" && $marca != "") {
        $result = $obj_equipos->filterEquipoByNameDeptoMarca($name, $depto, $marca);
        getData($result);
    } else if($marca != "" && $modelo != "" && $depto != "") {
        $result = $obj_equipos->filterEquipoByMarcaModeloDepto($marca, $modelo, $depto);
        getData($result);
    } else if($name != "" && $modelo != "") {
        $result = $obj_equipos->filterEquipoByNameModeloAdmin($name, $modelo);
        getData($result);
    } else if($name != "" && $marca != "") {
        $result = $obj_equipos->filterEquipoByNameMarcaAdmin($name, $marca);
        getData($result);
    } else if($marca != "" && $modelo != "") {
        $result = $obj_equipos->filterEquipoByMarcaModeloAdmin($marca, $modelo);
        getData($result);
    } else if($depto != "" && $name != "") {
        $result = $obj_equipos->filterEquipoByDeptoNameAdmin($depto, $name);
        getData($result);
    } else if($depto != "" && $marca != "") {
        $result = $obj_equipos->filterEquipoByDeptoMarcaAdmin($depto, $marca);
        getData($result);
    } else if($depto != "" && $modelo != "") {
        $result = $obj_equipos->filterEquipoByDeptoModeloAdmin($depto, $modelo);
        getData($result);
    } else if($depto != "" && $modelo != "") {
        $result = $obj_equipos->filterEquipoByDeptoModeloAdmin($depto, $modelo);
        getData($result);
    } else if($name != "") {
        $result = $obj_equipos->filterEquipoByNameAdmin($name);
        getData($result);
    } else if($modelo != "") {
        $result = $obj_equipos->filterEquipoByModeloAdmin($modelo);
        getData($result);
    } else if($marca != "") {
        $result = $obj_equipos->filterEquipoByMarcaAdmin($marca);
        getData($result);
    } else if($depto != "") {
        $result = $obj_equipos->filterEquipoByDeptoAdmin($depto);
        getData($result);
    } else if($name == "" && $modelo == "") {
        $result = $obj_equipos->consult();
        getData($result);
    }
}


function getData($result) {
    $tabla= "
    <table class='page__table' id='table-equipos'>
        <thead class='page__table-head'>
            <tr class='page__table-row'>
                <th>Equipo</th>
                <th>Num de serie</th>
                <th>Fecha de ingreso</th>
                <th>Estado</th>
                <th>Descripcion</th>
                <th>Modelo</th>
                <th>Stock</th>
                <th>Marca</th>
                <th>Departamento</th>
                <th style='text-align: center'>Acciones</th>
            </tr>
        </thead>

        <tbody class='page__table-body'>";

if(mysqli_num_rows($result) >= 1) {
    foreach($result as $fila) {

    $tabla.= "
            <tr class='page__table-row'>
                <td style='min-width: 160px; max-width: 160px; overflow-x: scroll'>$fila[equipo]</td>
                <td style='min-width: 100px; max-width: 100px; overflow-x: scroll'>$fila[n_serie]</td>
                <td style='min-width: 120px; max-width: 120px; overflow-x: scroll'>$fila[fecha_ingreso]</td>
                <td style='min-width: 100px; max-width: 100px; overflow-x: scroll'>$fila[estado]</td>
                <td style='min-width: 250px; max-width: 250px; overflow-x: scroll'>$fila[descripcion]</td>
                <td style='min-width: 120px; max-width: 120px; overflow-x: scroll'>$fila[modelo]</td>
                <td style='min-width: 50px; max-width: 50px; overflow-x: scroll'>$fila[stock]</td>
                <td style='min-width: 110px; max-width: 110px; overflow-x: scroll'>$fila[marca]</td>
                <td style='min-width: 100px; max-width: 100px; overflow-x: scroll'>$fila[depto]</td>
                <td class='no-padding'>
                    <div>
                        <button id='eliminar' class='accion' onClick='btnDeleteClicked($fila[id_equipo])' style='margin-right: .2rem'>Eliminar</button>
                        <button id='editar' class='accion' onClick='btnEditClicked($fila[id_equipo])'>Editar</button>
                    </div>
                </td>
            </tr>
            ";
    }
} else {
    $tabla.= "
        <tr class='page__table-row'>
            <th class='hidden'>1</th>
            <th class='hidden'>2</th>
            <th class='hidden'>3</th>
            <th class='hidden'>4</th>
            <th class='hidden'>5</th>
            <th class='hidden'>6</th>
            <th class='hidden'>7</th>
            <th class='hidden'>8</th>
            <th class='hidden'>9</th>
            <td colspan='10' style='text-align: center; background-color: var(--color-wrong); color: var(--color-light)'>No se ha encontrado ningun equipo.</td>
        </tr>
        ";
}

    $tabla.= "
            </tbody>
        </table>
        
        <script>
            $('#table-equipos').DataTable({
                'searching': false,
                'pageLength': 5,
                'lengthMenu': [[5, 10, 25, 50, -1], [5, 10, 25, 50, 'Todos las']],
                'language': {
                    'lengthMenu': 'Mostrar _MENU_ equipos',
                    'info': 'Mostrando _START_ a _END_ de _TOTAL_ equipos',
                    'paginate': {
                        'first':      'Primero',
                        'last':       'Ultimo',
                        'next':       'Siguiente',
                        'previous':   'Anterior'
                    },
                }
            })
        </script>
        ";

    echo $tabla;
}
?>