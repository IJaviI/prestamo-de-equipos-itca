<?php 
// Carga de clases
require_once("cn.php");
require_once("cls_departamentos.php");

class cls_equipos extends cn {
    public function consult() {
        $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
        equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
        INNER JOIN depto ON equipo.id_depto = depto.id_depto"; 
        return $this -> cn() -> query($sql);
    }

    public function consultEquipo($id) {
        $sql = "SELECT * FROM equipo WHERE id_equipo = $id"; 
        return $this -> cn() -> query($sql);
    } 

    // Sabemos si un equipo ya fue registrado a algun prestamo
    public function equipoAfiliatedOrNot($id) {
        $sql = "SELECT * FROM det_prestamo WHERE id_equipo = $id";
        return $this -> cn() -> query($sql);
    }
    
    public function consultEquipoPorDeptoDeUsuario($id_depto) {
        $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
        equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
        INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE depto.id_depto = $id_depto"; 
        return $this -> cn() -> query($sql);
    }
    
    public function exists($equipo_name, $num_serie) {
        $sql = "SELECT equipo FROM equipo WHERE equipo = '$equipo_name' AND n_serie = '$num_serie'"; 
        return $this -> cn() -> query($sql);
    }

    public function existsForUpdate($equipo, $num_serie) {
        $sql = "SELECT * FROM equipo WHERE equipo = '$equipo' AND n_serie = '$num_serie'"; 
        return $this -> cn() -> query($sql);
    }

    public function delete($id) {
        $result = $this -> consultEquipo($id);
        $equipo = $result -> fetch_assoc();
        // verificamos si el equipo ya fue registrado en algun prestamo (solo si no lo esta, es posible borrar)
        if(mysqli_num_rows($this -> equipoAfiliatedOrNot($id)) >= 1) {
            return "
            <script>
                hideAlert();
                alertRed();
                setTimeout( function() { alerts('No es posible eliminar, este equipo ya fue registrado en un prestamo.'); }, 10);
            </script>";
        } else {
            $sql = "DELETE FROM equipo WHERE id_equipo = $id"; 
            $this -> cn() -> query($sql);
            return "
            <script>
                hideAlert();
                alertGreen();
                setTimeout( function() { alerts('El equipo ($equipo[equipo]) fue eliminada con exito.'); }, 1);
            </script>"; 
        }
    }
    
    public function addEquipo($name, $num_serie, $description, $model, $stock, $marca, $depto){
        $equipo_exists = $this -> exists($name, $num_serie, $model);

        if(mysqli_num_rows($equipo_exists) == 0) {
            $sql = "INSERT INTO equipo(equipo, n_serie, fecha_ingreso, estado, descripcion, modelo, stock, id_marca, id_depto) VALUES('$name', '$num_serie', CURRENT_DATE(), 'Disponible', '$description', '$model', $stock, $marca, $depto)"; 
            $this -> cn() -> query($sql);

            return "
            <script>
                btnStateChange();

                alertGreen();
                setTimeout( function() { alerts('El equipo ($name) fue creado con exito.'); }, 10);
            </script>
            ";
        } else {
            return "
            <script>
            $('#equipo_name').css('borderColor', 'var(--color-wrong)');
            $('#equipo_serie').css('borderColor', 'var(--color-wrong)');
            $('#equipo_modelo').css('borderColor', 'var(--color-wrong)');
            btnInitialState();

            hideAlert();
            alertRed();
            setTimeout( function() { alerts('Ups, ya existe un equipo ($name) con el mismo modelo y con el mismo numero de serie.'); }, 1);
            </script>";
        }
    }

    // Actualizando todo en el equipo
    public function updateAll($id, $equipo_name, $num_serie, $equipo_description, $equipo_modelo, $equipo_stock, $equipo_marca, $equipo_depto) {
        // Llamamos la funcion exists para comprobrar si se cambio algun valor de esta aula o todo sigue siendo lo mismo 
        $result = $this -> existsForUpdate($equipo_name, $num_serie);
        
        if(mysqli_num_rows($result) == 1) {            
            return "
            <script>
            btnInitialState();

            alertRed();
            alerts('Ups, ya existe un equipo con el mismo nombre ($equipo_name) y  numero de serie ($num_serie).');
            </script>
            ";
        } else if(mysqli_num_rows($result) == 0) {
            $sql = "UPDATE equipo SET equipo = '$equipo_name', n_serie = '$num_serie', descripcion = '$equipo_description', modelo = '$equipo_modelo', stock = $equipo_stock, id_marca = $equipo_marca, id_depto = $equipo_depto WHERE id_equipo = $id";
            $this -> cn() -> query($sql);

            $_SESSION["equipo"]["equipo_name"] = $equipo_name;
            $_SESSION["equipo"]["equipo_serie"] = $num_serie;
            $_SESSION["equipo"]["equipo_description"] = $equipo_description;
            $_SESSION["equipo"]["equipo_modelo"] = $equipo_modelo;
            $_SESSION["equipo"]["equipo_stock"] = $equipo_stock;
            $_SESSION["equipo"]["equipo_marca"] = $equipo_marca;
            $_SESSION["equipo"]["equipo_depto"] = $equipo_depto;

            // Cambiamos el estado del boton
            return "
                <script>
                    btnStateChange();

                    // Updating the values on the inicialValue data (to know if inputs's values changed from the actual values of the fields updating)
                    $('#equipo_name').data('inicialValue', $('#equipo_name').val());
                    $('#equipo_serie').data('inicialValue', $('#equipo_serie').val());
                    $('#equipo_description').data('inicialValue', $('#equipo_description').val());
                    $('#equipo_modelo').data('inicialValue', $('#equipo_modelo').val());
                    $('#equipo_stock').data('inicialValue', $('#equipo_stock').val());
                    $('#equipo_marca').data('inicialValue', $('#equipo_marca').val());
                    $('#equipo_depto').data('inicialValue', $('#equipo_depto').val());

                    alertGreen();
                    alerts('Los cambios han sido guardados con exito.');
                </script>
            ";
        } 
    }

    public function updateBasedOnNameAndSerie($id, $equipo_name, $num_serie, $equipo_description, $equipo_modelo, $equipo_stock, $equipo_marca, $equipo_depto) {
        // Llamamos la funcion exists para comprobrar si se cambio algun valor de esta aula o todo sigue siendo lo mismo 
        $result = $this -> existsForUpdate($equipo_name, $num_serie);
        
        if(mysqli_num_rows($result) == 1) {
            return "
            <script>
            btnInitialState();

            hideAlert();
            alertRed();
            alerts('Ups, ya existe un equipo con el mismo nombre ($equipo_name) y  numero de serie ($num_serie).');
            </script>
            ";
        } else if(mysqli_num_rows($result) == 0) {
            $sql = "UPDATE equipo SET equipo = '$equipo_name', n_serie = '$num_serie', descripcion = '$equipo_description', modelo = '$equipo_modelo', stock = $equipo_stock, id_marca = $equipo_marca, id_depto = $equipo_depto WHERE id_equipo = $id";
            $this -> cn() -> query($sql);

            $_SESSION["equipo"]["equipo_name"] = $equipo_name;
            $_SESSION["equipo"]["equipo_serie"] = $num_serie;
            $_SESSION["equipo"]["equipo_description"] = $equipo_description;
            $_SESSION["equipo"]["equipo_modelo"] = $equipo_modelo;
            $_SESSION["equipo"]["equipo_stock"] = $equipo_stock;
            $_SESSION["equipo"]["equipo_marca"] = $equipo_marca;
            $_SESSION["equipo"]["equipo_depto"] = $equipo_depto;

            // Cambiamos el estado del boton
            return "
                <script>
                    btnStateChange();

                    // Updating the values on the inicialValue data (to know if inputs's values changed from the actual values of the fields updating)
                    $('#equipo_name').data('inicialValue', $('#equipo_name').val());
                    $('#equipo_serie').data('inicialValue', $('#equipo_serie').val());
                    $('#equipo_description').data('inicialValue', $('#equipo_description').val());
                    $('#equipo_modelo').data('inicialValue', $('#equipo_modelo').val());
                    $('#equipo_stock').data('inicialValue', $('#equipo_stock').val());
                    $('#equipo_marca').data('inicialValue', $('#equipo_marca').val());
                    $('#equipo_depto').data('inicialValue', $('#equipo_depto').val());

                    hideAlert();
                    alertGreen();
                    setTimeout(function () { alerts('Los cambios han sido guardados con exito.'); }, 10);
                </script>
            ";
        } 
    }

    public function updateRestOfFields($id, $equipo_description, $equipo_modelo, $equipo_stock, $equipo_marca, $equipo_depto) {
            $sql = "UPDATE equipo SET descripcion = '$equipo_description', modelo = '$equipo_modelo', stock = $equipo_stock, id_marca = $equipo_marca, id_depto = $equipo_depto WHERE id_equipo = $id";
            $this -> cn() -> query($sql);

            $_SESSION["equipo"]["equipo_description"] = $equipo_description;
            $_SESSION["equipo"]["equipo_modelo"] = $equipo_modelo;
            $_SESSION["equipo"]["equipo_stock"] = $equipo_stock;
            $_SESSION["equipo"]["equipo_marca"] = $equipo_marca;
            $_SESSION["equipo"]["equipo_depto"] = $equipo_depto;

            // Cambiamos el estado del boton
            return "
                <script>
                    btnStateChange();

                    // Updating the values on the inicialValue data (to know if inputs's values changed from the actual values of the fields updating)
                    $('#equipo_description').data('inicialValue', $('#equipo_description').val());
                    $('#equipo_modelo').data('inicialValue', $('#equipo_modelo').val());
                    $('#equipo_stock').data('inicialValue', $('#equipo_stock').val());
                    $('#equipo_marca').data('inicialValue', $('#equipo_marca').val());
                    $('#equipo_depto').data('inicialValue', $('#equipo_depto').val());

                    hideAlert();
                    alertGreen();
                    setTimeout(function () { alerts('Los cambios han sido guardados con exito.'); }, 10);
                </script>
            ";
    }



    public function filterEquipoByName($equipo_name) {
        $obj_departamentos = new cls_departamentos;

        $depto_user_name = $_SESSION["userProfile"]["depto"];        
        $depto = $obj_departamentos -> consultDeptoByName($depto_user_name);
        $result = $depto -> fetch_assoc();

        $id_depto_usuario = $result["id_depto"];

        $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
        equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
        INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE equipo.equipo LIKE '%$equipo_name%' AND depto.id_depto = $id_depto_usuario"; 
        return $this -> cn() -> query($sql);
    }

    public function filterEquipoByMarca($equipo_marca) {
        $obj_departamentos = new cls_departamentos;

        $depto_user_name = $_SESSION["userProfile"]["depto"];        
        $depto = $obj_departamentos -> consultDeptoByName($depto_user_name);
        $result = $depto -> fetch_assoc();

        $id_depto_usuario = $result["id_depto"];

        if($equipo_marca == 'Todas'){
            $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
            equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
            INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE depto.id_depto = $id_depto_usuario";
            return $this -> cn() -> query($sql);
        } else {
            $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
            equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
            INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE marca.id_marca = $equipo_marca AND depto.id_depto = $id_depto_usuario"; 
            return $this -> cn() -> query($sql);
        }
    } 

    public function filterEquipoByModelo($equipo_modelo) {
        $obj_departamentos = new cls_departamentos;

        $depto_user_name = $_SESSION["userProfile"]["depto"];        
        $depto = $obj_departamentos -> consultDeptoByName($depto_user_name);
        $result = $depto -> fetch_assoc();

        $id_depto_usuario = $result["id_depto"];

        $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
        equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
        INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE equipo.modelo like '%$equipo_modelo%' AND depto.id_depto = $id_depto_usuario"; 
        return $this -> cn() -> query($sql);
    } 

    public function filterEquipoByNameMarca($equipo_name, $equipo_marca) {
        $obj_departamentos = new cls_departamentos;

        $depto_user_name = $_SESSION["userProfile"]["depto"];        
        $depto = $obj_departamentos -> consultDeptoByName($depto_user_name);
        $result = $depto -> fetch_assoc();

        $id_depto_usuario = $result["id_depto"];

        if($equipo_marca == 'Todas') {
            $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
            equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
            INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE equipo.equipo like '%$equipo_name%' AND depto.id_depto = $id_depto_usuario"; 
            return $this -> cn() -> query($sql);
        } else {
            $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
            equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
            INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE equipo.equipo like '%$equipo_name%' AND marca.id_marca = $equipo_marca AND depto.id_depto = $id_depto_usuario"; 
            return $this -> cn() -> query($sql);
        }
    } 

    public function filterEquipoByMarcaModelo($equipo_marca, $equipo_modelo) {
        $obj_departamentos = new cls_departamentos;

        $depto_user_name = $_SESSION["userProfile"]["depto"];        
        $depto = $obj_departamentos -> consultDeptoByName($depto_user_name);
        $result = $depto -> fetch_assoc();

        $id_depto_usuario = $result["id_depto"];

        if($equipo_marca == 'Todas') {
            $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
            equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
            INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE equipo.modelo like '%$equipo_modelo%' AND depto.id_depto = $id_depto_usuario"; 
            return $this -> cn() -> query($sql);
        } else {
            $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
            equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
            INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE marca.id_marca = $equipo_marca AND equipo.modelo like '%$equipo_modelo%' AND depto.id_depto = $id_depto_usuario"; 
            return $this -> cn() -> query($sql);
        }
    } 

    public function filterEquipoByNameModelo($equipo_name, $equipo_modelo) {
        $obj_departamentos = new cls_departamentos;

        $depto_user_name = $_SESSION["userProfile"]["depto"];        
        $depto = $obj_departamentos -> consultDeptoByName($depto_user_name);
        $result = $depto -> fetch_assoc();

        $id_depto_usuario = $result["id_depto"];

        $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
        equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
        INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE equipo.equipo like '%$equipo_name%' AND equipo.modelo like '%$equipo_modelo%' AND depto.id_depto = $id_depto_usuario"; 
        return $this -> cn() -> query($sql);
    } 

    public function filterEquipoBy3($equipo_name, $equipo_marca, $equipo_modelo) {
        $obj_departamentos = new cls_departamentos;

        $depto_user_name = $_SESSION["userProfile"]["depto"];        
        $depto = $obj_departamentos -> consultDeptoByName($depto_user_name);
        $result = $depto -> fetch_assoc();

        $id_depto_usuario = $result["id_depto"];

        if($equipo_marca == "Todas") {
            $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
            equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
            INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE equipo.equipo like '%$equipo_name%' AND equipo.modelo like '%$equipo_modelo%' AND depto.id_depto = $id_depto_usuario";
            return $this -> cn() -> query($sql);
        } else {
            $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
            equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
            INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE marca.id_marca = $equipo_marca AND equipo.equipo like '%$equipo_name%' AND equipo.modelo like '%$equipo_modelo%' AND depto.id_depto = $id_depto_usuario";
            return $this -> cn() -> query($sql);
        }
    }

    public function filterEquipoByNameAdmin($equipo_name) {
        $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
        equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
        INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE equipo.equipo like '%$equipo_name%'"; 
        return $this -> cn() -> query($sql);
    }

    public function filterEquipoByMarcaAdmin($equipo_marca) {
        if($equipo_marca == 'Todas'){
            $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
            equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
            INNER JOIN depto ON equipo.id_depto = depto.id_depto";
            return $this -> cn() -> query($sql);
        } else {
            $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
            equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
            INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE marca.id_marca = $equipo_marca"; 
            return $this -> cn() -> query($sql);
        }
    }

    public function filterEquipoByDeptoAdmin($equipo_depto) {
        if($equipo_depto == 'Todos'){
            $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
            equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
            INNER JOIN depto ON equipo.id_depto = depto.id_depto";
            return $this -> cn() -> query($sql);
        } else {
            $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
            equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
            INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE depto.id_depto = $equipo_depto"; 
            return $this -> cn() -> query($sql);
        }
    }

    public function filterEquipoByModeloAdmin($equipo_modelo) {
        $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
        equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
        INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE equipo.modelo like '%$equipo_modelo%'"; 
        return $this -> cn() -> query($sql);
    } 

    public function filterEquipoByNameMarcaAdmin($equipo_name, $equipo_marca) {
        if($equipo_marca == 'Todas') {
            $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
            equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
            INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE equipo.equipo like '%$equipo_name%'"; 
            return $this -> cn() -> query($sql);
        } else {
            $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
            equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
            INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE equipo.equipo like '%$equipo_name%' AND marca.id_marca = $equipo_marca"; 
            return $this -> cn() -> query($sql);
        }
    } 

    public function filterEquipoByMarcaModeloAdmin($equipo_marca, $equipo_modelo) {
        if($equipo_marca == 'Todas') {
            $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
            equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
            INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE equipo.modelo = '%$equipo_modelo%'"; 
            return $this -> cn() -> query($sql);
        } else {
            $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
            equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
            INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE marca.id_marca = $equipo_marca AND equipo.modelo like '%$equipo_modelo%'"; 
            return $this -> cn() -> query($sql);
        }
    } 

    public function filterEquipoByNameModeloAdmin($equipo_name, $equipo_modelo) {
        $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
        equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
        INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE equipo.equipo like '%$equipo_name%' AND equipo.modelo like '%$equipo_modelo%'"; 
        return $this -> cn() -> query($sql);
    }

    public function filterEquipoByDeptoNameAdmin($equipo_depto, $equipo_name) {
        if($equipo_depto == 'Todos') {
            $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
            equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
            INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE equipo.equipo = '%$equipo_name%'"; 
            return $this -> cn() -> query($sql);
        } else {
            $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
            equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
            INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE equipo.equipo like '%$equipo_name%' AND depto.id_depto = $equipo_depto"; 
            return $this -> cn() -> query($sql);
        }
    } 

    public function filterEquipoByDeptoMarcaAdmin($equipo_depto, $equipo_marca) {
        if($equipo_depto == "Todos" && $equipo_marca == "Todas") {
            $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
            equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
            INNER JOIN depto ON equipo.id_depto = depto.id_depto"; 
            return $this -> cn() -> query($sql);
        } else if($equipo_depto == "Todos") {
            $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
            equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
            INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE marca.id_marca = $equipo_marca"; 
            return $this -> cn() -> query($sql);
        } else if($equipo_marca == "Todas") {
            $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
            equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
            INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE depto.id_depto = $equipo_depto"; 
            return $this -> cn() -> query($sql);
        } else {
            $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
            equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
            INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE depto.id_depto = $equipo_depto AND marca.id_marca = $equipo_marca";
            return $this -> cn() -> query($sql);
        }
    } 

    public function filterEquipoByDeptoModeloAdmin($equipo_depto, $equipo_modelo) {
        if($equipo_depto == "Todos") {
            $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
            equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
            INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE equipo.modelo like '%$equipo_modelo%'"; 
            return $this -> cn() -> query($sql);
        }  else {
            $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
            equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
            INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE depto.id_depto = $equipo_depto AND equipo.modelo like '%$equipo_modelo%'";
            return $this -> cn() -> query($sql);
        }
    }

    public function filterEquipoByNameMarcaModelo($equipo_name, $equipo_marca, $equipo_modelo) {
        if($equipo_marca == "Todas") {
            $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
            equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
            INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE equipo.equipo like '%$equipo_name%' AND equipo.modelo like '%$equipo_modelo%'";
            return $this -> cn() -> query($sql);
        } else {
            $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
            equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
            INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE marca.id_marca = $equipo_marca AND equipo.equipo like '%$equipo_name%' AND equipo.modelo like '%$equipo_modelo%' ";
            return $this -> cn() -> query($sql);
        }
    }

    public function filterEquipoByNameDeptoModelo($equipo_name, $equipo_depto, $equipo_modelo) {
        if($equipo_depto == "Todos") {
            $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
            equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
            INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE equipo.equipo like '%$equipo_name%' AND equipo.modelo like '%$equipo_modelo%'";
            return $this -> cn() -> query($sql);
        } else {
            $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
            equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
            INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE depto.id_depto = $equipo_depto AND equipo.equipo like '%$equipo_name%' AND equipo.modelo like '%$equipo_modelo%' ";
            return $this -> cn() -> query($sql);
        }
    }

    public function filterEquipoByNameDeptoMarca($equipo_name, $equipo_depto, $equipo_marca) {
        if($equipo_depto == "Todos" && $equipo_marca == "Todas") {
            $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
            equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
            INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE equipo.equipo like '%$equipo_name%'"; 
            return $this -> cn() -> query($sql);
        } else if($equipo_depto == "Todos") {
            $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
            equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
            INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE equipo.equipo like '%$equipo_name%' AND marca.id_marca = $equipo_marca"; 
            return $this -> cn() -> query($sql);
        } else if($equipo_marca == "Todas") {
            $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
            equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
            INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE equipo.equipo like '%$equipo_name%' AND depto.id_depto = $equipo_depto"; 
            return $this -> cn() -> query($sql);
        } else {
            $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
            equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
            INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE equipo.equipo like '%$equipo_name%' AND depto.id_depto = $equipo_depto AND marca.id_marca = $equipo_marca";
            return $this -> cn() -> query($sql);
        }
    } 

    public function filterEquipoByMarcaModeloDepto($equipo_marca, $equipo_modelo, $equipo_depto) {
        if($equipo_depto == "Todos" && $equipo_marca == "Todas") {
            $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
            equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
            INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE equipo.modelo like '%$equipo_modelo%'"; 
            return $this -> cn() -> query($sql);
        } else if($equipo_depto == "Todos") {
            $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
            equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
            INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE equipo.modelo like '%$equipo_modelo%' AND marca.id_marca = $equipo_marca"; 
            return $this -> cn() -> query($sql);
        } else if($equipo_marca == "Todas") {
            $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
            equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
            INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE equipo.modelo like '%$equipo_modelo%' AND depto.id_depto = $equipo_depto"; 
            return $this -> cn() -> query($sql);
        } else {
            $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
            equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
            INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE equipo.modelo like '%$equipo_modelo%' AND depto.id_depto = $equipo_depto AND marca.id_marca = $equipo_marca";
            return $this -> cn() -> query($sql);
        }
    }

    public function filterEquipoByAll($equipo_name, $equipo_marca, $equipo_depto, $equipo_modelo) {
        if($equipo_marca == "Todas" && $equipo_depto == "Todos") {
            $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
            equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
            INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE equipo.equipo like '%$equipo_name%' AND equipo.modelo like '%$equipo_modelo%'";
            return $this -> cn() -> query($sql);
        } else if($equipo_marca == "Todas") {
            $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
            equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
            INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE equipo.equipo like '%$equipo_name%' AND equipo.modelo like '%$equipo_modelo%' AND depto.id_depto = $equipo_depto";
            return $this -> cn() -> query($sql);
        } else if($equipo_depto == "Todos") {
            $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
            equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
            INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE equipo.equipo like '%$equipo_name%' AND equipo.modelo like '%$equipo_modelo%' AND marca.id_marca = $equipo_marca";
            return $this -> cn() -> query($sql);
        } else {
            $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
            equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
            INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE equipo.equipo like '%$equipo_name%' AND equipo.modelo like '%$equipo_modelo%' AND marca.id_marca = $equipo_marca AND depto.id_depto = $equipo_depto";
            return $this -> cn() -> query($sql);
        }
    }

    // Filtros a equipos agregados para ASIGNAR PRESTAMO
    public function filterEquipoByNameAsignar($equipo_name) {
        $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
        equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
        INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE equipo.equipo LIKE '%$equipo_name%'"; 
        return $this -> cn() -> query($sql);
    }

    public function filterEquipoByMarcaAsignar($equipo_marca) {
        if($equipo_marca == 'Todas'){
            $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
            equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
            INNER JOIN depto ON equipo.id_depto = depto.id_depto";
            return $this -> cn() -> query($sql);
        } else {
            $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
            equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
            INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE marca.id_marca = $equipo_marca"; 
            return $this -> cn() -> query($sql);
        }
    } 

    public function filterEquipoByModeloAsignar($equipo_modelo) {
        $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
        equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
        INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE equipo.modelo like '%$equipo_modelo%'"; 
        return $this -> cn() -> query($sql);
    } 

    public function filterEquipoByNameMarcaAsignar($equipo_name, $equipo_marca) {
        if($equipo_marca == 'Todas') {
            $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
            equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
            INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE equipo.equipo like '%$equipo_name%'"; 
            return $this -> cn() -> query($sql);
        } else {
            $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
            equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
            INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE equipo.equipo like '%$equipo_name%' AND marca.id_marca = $equipo_marca"; 
            return $this -> cn() -> query($sql);
        }
    } 

    public function filterEquipoByMarcaModeloAsignar($equipo_marca, $equipo_modelo) {
        if($equipo_marca == 'Todas') {
            $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
            equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
            INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE equipo.modelo like '%$equipo_modelo%'"; 
            return $this -> cn() -> query($sql);
        } else {
            $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
            equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
            INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE marca.id_marca = $equipo_marca AND equipo.modelo like '%$equipo_modelo%'"; 
            return $this -> cn() -> query($sql);
        }
    } 

    public function filterEquipoByNameModeloAsignar($equipo_name, $equipo_modelo) {
        $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
        equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
        INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE equipo.equipo like '%$equipo_name%' AND equipo.modelo like '%$equipo_modelo%'"; 
        return $this -> cn() -> query($sql);
    } 

    public function filterEquipoBy3Asignar($equipo_name, $equipo_marca, $equipo_modelo) {
        if($equipo_marca == "Todas") {
            $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
            equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
            INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE equipo.equipo like '%$equipo_name%' AND equipo.modelo like '%$equipo_modelo%'";
            return $this -> cn() -> query($sql);
        } else {
            $sql = "SELECT equipo.id_equipo, equipo.equipo, equipo.n_serie, equipo.fecha_ingreso, equipo.estado, equipo.descripcion, equipo.modelo, 
            equipo.stock , marca.marca, depto.depto FROM equipo INNER JOIN marca ON equipo.id_marca = marca.id_marca 
            INNER JOIN depto ON equipo.id_depto = depto.id_depto WHERE marca.id_marca = $equipo_marca AND equipo.equipo like '%$equipo_name%' AND equipo.modelo like '%$equipo_modelo%'";
            return $this -> cn() -> query($sql);
        }
    }


    public function csvEquipos($file) {
        if($file["type"] == "text/csv") {

            move_uploaded_file($file["tmp_name"], $file["folder"]);

            $equipos = array();
            // Ver si el csv esta vacio
            if($file["size"] !== 0) {
                // Abre el archivo en modo lectura
                if(($csv = fopen($file["folder"], "r")) !== FALSE){
                    // Lee cada linea del archivo
                    while(($row = fgetcsv($csv, 1000, ",")) !== FALSE) {
                        $encoded_row = array_map('utf8_encode', $row);
                        $equipos[$encoded_row[0]] = $encoded_row;

                        // Another way of inserting the csv rows
                        // $inserting = $this -> cn() -> prepare("INSERT INTO marca (marca) VALUES (?)");
                        // $inserting -> bind_param("s", $row[1]);
                        // $inserting -> execute();
                    }

                    $data_repits = 0; 
                    foreach($equipos as $equipo){
                        if(mysqli_num_rows($this -> exists($equipo[1], $equipo[2])) == 0) {
                            $sql = "INSERT INTO equipo (id_equipo, equipo, n_serie, fecha_ingreso, estado, descripcion, modelo, stock, id_marca, id_depto) VALUES ($equipo[0], '$equipo[1]', '$equipo[2]', '$equipo[3]', '$equipo[4]', '$equipo[5]', '$equipo[6]', $equipo[7], $equipo[8], $equipo[9])";
                            $this -> cn() -> query($sql);
                        } else {
                            $data_repits++;        
                        }
                    }

                    if($data_repits == 0) {
                        return "
                        <script>
                            $('#csv_equipos').val(null);
                            hideAlert();
                            alertGreen();
                            setTimeout( function() { alerts('El archivo csv ha sido leido, la coleccion de registros de equipos fue insertada con exito.'); }, 1);
                        </script>
                        "; 
                    } else {
                        return "
                        <script>
                            $('#csv_equipos').val(null);
                            hideAlert();
                            $('#alert').css('background-color', '#ffe979');
                            $('#alert').css('color', 'var(--color-dark)');
                            setTimeout( function() { alerts('El archivo csv ha sido leido, algunos equipos se repiten por lo que solo se han agregado los que no se repiten (si todos se repiten la insercion no se llevara acabo).'); }, 1);
                        </script>
                        "; 
                    }
                }    
            } else {
                return "
                <script>
                    $('#csv_equipos').val(null);
                    hideAlert();
                    alertRed();
                    setTimeout( function() { alerts('El archivo csv esta vacio, porfavor verifique los datos del archivo.'); }, 1);
                </script>
                ";    
            }
        } else {
         return "
         <script>
            $('#csv_equiipos').val(null);
            hideAlert();
            alertRed();
            setTimeout( function() { alerts('Error, el archivo ingresado debe ser de tipo csv.'); }, 1);
         </script>
         ";    
        }
    }
}

?>