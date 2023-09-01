<div class="page" id="page" tabindex="6"  >
    <div class="page__navigation">
        <a href="<?php echo RUTA;?>agregar-equipo" class="page__card">
            <svg class="page__card-icon page__card-icon--left" style='width: 2.8rem !important; height: 3.3rem !important'>
                <use xlink:href="img/SVG/sprite.svg#equipo"></use>
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
    <div class="table-container select-allowed">
        <div class="page__division"></div>
        <h2 class='page__heading'>Edita esta equipo</h2>
        <div class="page__division"></div>
        <div class="table-responsive">
            <table class="page__table">
                <thead class="page__table-head">    
                    <tr class='page__table-row'>    
                        <th>Equipo</th>
                        <th>Num de serie</th>
                        <th>Descripcion</th>
                        <th>Modelo</th>
                        <th>Stock</th>
                        <th>Marca</th>
                        <th>Departamento</th>
                        <th style='text-align: center'>Acciones</th>
                    </tr>
                </thead>
                
                <form>
                    <tbody class="page__table-body">
                        <?php
                        if(!isset($_SESSION["equipo"])){
                            echo "
                            <tr class='page__table-row'>
                                <td colspan='8' style='text-align: center; background-color: var(--color-wrong); color: var(--color-light)'>No se ha seleccionado un equipo a editar.</td>
                            </tr>
                            ";    
                        }
                        ?>
                        <tr class='page__table-row' <?php if(!isset($_SESSION["equipo"])){echo "style='display: none'";}?>>
                            <td class="no-padding" style="min-width: 200px">
                                <input type="hidden" id="equipo_id" value="<?php if(isset($_SESSION["equipo"])){ echo $_SESSION["equipo"]["id"];}?>">
                                <input onkeydown="return event.key != 'Enter'" type="text" value="<?php if(isset($_SESSION["equipo"])){ echo $_SESSION["equipo"]["equipo_name"];}?>" class="page__table-row-input" id="equipo_name" style='padding-top: .9rem; padding-bottom: .9rem' placeholder="Nombre del equipo">
                            </td>
                            <td class="no-padding" style="min-width: 140px">
                                <input onkeydown="return event.key != 'Enter'" type="text" value="<?php if(isset($_SESSION["equipo"])){ echo $_SESSION["equipo"]["equipo_serie"];}?>" class="page__table-row-input" id="equipo_serie" style='padding-top: .9rem; padding-bottom: .9rem' placeholder="Numero de serie">
                            </td>
                            <td class="no-padding" style="min-width: 250px">
                                <input onkeydown="return event.key != 'Enter'" type="text" value="<?php if(isset($_SESSION["equipo"])){ echo $_SESSION["equipo"]["equipo_description"];}?>" class="page__table-row-input" id="equipo_description" style='padding-top: .9rem; padding-bottom: .9rem' placeholder="Descripcion">
                            </td>
                            <td class="no-padding" style="min-width: 150px">
                                <input onkeydown="return event.key != 'Enter'" type="text" value="<?php if(isset($_SESSION["equipo"])){ echo $_SESSION["equipo"]["equipo_modelo"];}?>" class="page__table-row-input" id="equipo_modelo" style='padding-top: .9rem; padding-bottom: .9rem' placeholder="Modelo">
                            </td>
                            <td class="no-padding" style="min-width: 80px">
                                <input onkeydown="return event.key != 'Enter'" type="number" min='1' value="<?php if(isset($_SESSION["equipo"])){ echo $_SESSION["equipo"]["equipo_stock"];}?>" class="page__table-row-input" id="equipo_stock" style='padding-top: .9rem; padding-bottom: .9rem' placeholder="Stock">
                            </td>
                            <td class="no-padding" style="min-width: 150px">
                                <select id='equipo_marca' required class='form__input form__select' style="border-color: #fff; padding-top: 0.9rem; padding-bottom: 0.9rem">
                                    <option disabled value='0' class='disabled'>Marca</option>";
                                    <?php
                                        $obj_marcas = new cls_marcas;
                                        $marcas = $obj_marcas -> consult();

                                        $print = "";
                                        foreach($marcas as $marca){
                                            $print.="<option "; if($_SESSION["equipo"]["equipo_marca"] == $depto["id_marca"]){ $print.="selected "; } $print.="value='$marca[id_marca]'>$marca[marca]</option>";
                                        }
                                        echo $print;
                                    ?>
                                </select>
                            </td>
                            <td class="no-padding" style="min-width: 150px">
                                <select id='equipo_depto' required class='form__input form__select' style="border-color: #fff; padding-top: 0.9rem; padding-bottom: 0.9rem">
                                    <option value='0' disabled class='disabled'>Departamento</option>";
                                    <?php
                                        $obj_departamentos = new cls_departamentos;
                                        $deptos = $obj_departamentos -> consult();

                                        $print = "";
                                        foreach($deptos as $depto){
                                            $print.="<option "; if($_SESSION["equipo"]["equipo_depto"] == $depto["id_depto"]){ $print.="selected "; } $print.="value='$depto[id_depto]'>$depto[depto]</option>";
                                        }
                                        echo $print;
                                    ?>
                                </select>
                            </td>
                            <td class="no-padding">
                                <button type="button" id="guardar">
                                    <span class="guardar-text">Guardar</span>
                                    <svg class="btn-check hidden">
                                        <use xlink:href="img/SVG/sprite.svg#check"></use>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </form>
            </table>
        </div>
    </div>
</div>




<div id="alert" class="alert hidden">
    <button type="button" id="btnCloseAlert" class="alert__btn-close">&times;</button>
    <p id="alert__message" class="alert__message"></p>
</div>

<div id="resp" ></div>

<script src="js/admin/mantenimiento/equipos/editar-equipo.js"></script>