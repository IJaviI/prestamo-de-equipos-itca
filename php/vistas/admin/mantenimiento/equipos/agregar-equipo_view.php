<?php
if(isset($_SESSION["equipo"])){
    unset($_SESSION["equipo"]);
}
?>

<div class="page" id="page" tabindex="3">
    <div class="page__navigation">
        <a href="<?php echo RUTA;?>agregar-equipo" class="page__card <?php if($_SESSION["equipos"] == "agregar-equipo") { echo "hide"; }?>">
            <svg class="page__card-icon page__card-icon--left">
                <use xlink:href="img/SVG/sprite.svg#person-circle"></use>
            </svg>
            <div class="page__card-block">
                Agregar equipo
                <span>Crea un nuevo equipo</span>
            </div>
            <svg class="page__card-icon page__card-icon--right">
                <use xlink:href="img/SVG/sprite.svg#plus"></use>
            </svg>
        </a>

        <a href="<?php echo RUTA;?>administrar-equipos" class="page__card">
            <svg class="page__card-icon page__card-icon--left" style='width: 2.8rem !important; height: 3.3rem !important'>
                <use xlink:href="img/SVG/sprite.svg#equipos"></use>
            </svg>
            <div class="page__card-block">
                Administrar equipos
                <span>Administra equipos existentes</span>
            </div>
            <svg class="page__card-icon page__card-icon--right">
                <use xlink:href="img/SVG/sprite.svg#edit"></use>
            </svg>
        </a>
    </div>
    <div class="form-container select-allowed">
        <form class='inside-form'>
            <div class="form__division"></div>
            <h2 class='form__heading'>Crea un equipo</h2>
            <div class="form__division"></div>
            <div class='form__row'>
                <input onkeydown="return event.key != 'Enter'" type='text' id='equipo_name' autofocus class='form__input' placeholder='Nombre del equipo'>
            </div>
            <div class='form__row'>
                <input onkeydown="return event.key != 'Enter'" type='text' id='equipo_serie' class='form__input' placeholder='Numero de serie'>
            </div>
            <div class='form__row'>
                <input onkeydown="return event.key != 'Enter'" type='text' id='equipo_description' class='form__input' placeholder='Descripcion del equipo'>
            </div>
            <div class='form__row'>
                <input onkeydown="return event.key != 'Enter'" type='text' id='equipo_modelo' class='form__input' placeholder='Modelo'>
            </div>
            <div class='form__row'>
                <input onkeydown="return event.key != 'Enter'" type='number' min="1" id='equipo_stock' class='form__input' placeholder='Stock'>
            </div>

            <?php
            $isThereDeptos = $obj_departamentos -> consult();
            $isThereMarcas = $obj_marcas -> consult();
            $print = "";
            if(mysqli_num_rows($isThereDeptos) >= 1) {
                if(mysqli_num_rows($isThereMarcas) >= 1) {
                    $print.= "
                    <div class='form__row'>
                        <select id='equipo_depto' required class='form__input form__select'>
                            <option selected value='0' class='disabled'>Departamento</option>";
                            foreach($isThereDeptos as $fila){
                                $print.= "<option value='$fila[id_depto]'>$fila[depto]</option>";
                            }
                            $print.="
                        </select>
                    </div>

                    <div class='form__row'>
                        <select id='equipo_marca' required class='form__input form__select'>
                            <option selected value='0' class='disabled'>Marca</option>";
                            foreach($isThereMarcas as $fila){
                                $print.= "<option value='$fila[id_marca]'>$fila[marca]</option>";
                            }
                            $print.="
                        </select>
                    </div>
                    
                    <div class='form__row'>
                        <button type='button' id='create-equipo' class='form__btn no-margin'>
                            <span class='create-text'>Crear equipo</span>
                            <svg class='btn-check hidden'>
                                <use xlink:href='img/SVG/sprite.svg#check'></use>
                            </svg>
                        </button>
                    </div>

                    <div class='form__row'>
                        <span style='display: block; font-size: calc(var(--font-app) + .1rem); margin: 1rem  0; margin-top: 4rem'>¿Ya tienes una coleccion de registros de equipos? Inserta tu archivo csv aqui.</span>

                        <label class='form__input form__row-label no-padding' for='csv_equipos'>
                            <span style='color: var(--color-light); background-color: var(--color-dark);'>Csv</span>
                            <div class='form__row-label-state'>Ingresa tu archivo csv (La inserción sera automatica una vez insertado)</div>
                            <input type='file' accept='text/csv' id='csv_equipos' class='hidden'>
                        </label>
                    </div>";   
                } else {
                    $print.= "
                    <div class='form__row'>
                        <div class='warning-depto'>
                            <p>No existe ninguna marca para asignar a este equipo, la creacion del equipo solo sera posible hasta que exista minimo una marca.</p>
                        </div>
                    </div>";    
                }
            } else {
                $print.= "
                <div class='form__row'>
                    <div class='warning-depto'>
                        <p>No existe ningun departamento para asignar a este equipo, la creacion del equipo solo sera posible hasta que exista minimo un departamento.</p>
                    </div>
                </div>";
            }

            echo $print;
            ?>
        </form>
    </div>
</div>

<div id="alert" class="alert hidden">
    <button type="button" id="btnCloseAlert" class="alert__btn-close">&times;</button>
    <p id="alert__message" class="alert__message"></p>
</div>

<div id="resp" ></div>

<script src="js/admin/mantenimiento/equipos/agregar-equipo.js"></script>