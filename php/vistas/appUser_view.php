<?php
require_once("php/librerias/cls_contenido.php");
require_once("php/controladores/cls_usuarios.php");
require_once("php/controladores/cls_departamentos.php");

// Recibiendo datos para perfil de usuario
$obj_usuarios = new cls_usuarios;
$obj_departamentos = new cls_departamentos;

$usuarioData = $obj_usuarios -> consultDocente($_SESSION["userProfile"]["id"]);
$usuario = $usuarioData -> fetch_assoc();
$deptoData = $obj_departamentos -> consultDepto($usuario["id_depto"]);
$depto = $deptoData -> fetch_assoc(); 



$obj_contenido = new cls_contenido;
$pagina = $obj_contenido -> ver();

// Definiendo en que pagina nos encontramos (session currebt-rute sirve para mostrar donde nos encontramos)
if($pagina == "php/vistas/default_view.php") {
    $_SESSION["current-rute"] = "";
} 

// Si nos encontramos en paginas relacionadas a DEPARTAMENTOS
else if($pagina == "php/vistas/admin/mantenimiento/departamentos/agregar-departamento_view.php") {
    $_SESSION["current-rute"] = "Mantenimiento / Agregar departamento";
    $_SESSION["departamentos"] = "agregar-departamento";
} else if($pagina == "php/vistas/admin/mantenimiento/departamentos/administrar-departamentos_view.php") {
    $_SESSION["current-rute"] = "Mantenimiento / Administrar departamentos";
    $_SESSION["departamentos"] = "administrar-departamentos";
} else if($pagina == "php/vistas/admin/mantenimiento/departamentos/editar-departamento_view.php") {
    $_SESSION["current-rute"] = "Mantenimiento / Editar departamento";
}

// Si nos encontramos en paginas relacionadas a USUARIOS
else if($pagina == "php/vistas/admin/mantenimiento/usuarios/agregar-usuario_view.php") {
    $_SESSION["current-rute"] = "Mantenimiento / Agregar usuario";
    $_SESSION["usuarios"] = "agregar-usuario";
} else if($pagina == "php/vistas/admin/mantenimiento/usuarios/administrar-usuarios_view.php") {
    $_SESSION["current-rute"] = "Mantenimiento / Administrar usuarios";
    $_SESSION["usuarios"] = "administrar-usuarios";
} 

// Si nos encontramos en paginas relacionadas a MARCAS
else if($pagina == "php/vistas/admin/mantenimiento/marcas/agregar-marca_view.php") {
    $_SESSION["current-rute"] = "Mantenimiento / Agregar marca";
    $_SESSION["marcas"] = "agregar-marca";
} else if($pagina == "php/vistas/admin/mantenimiento/marcas/administrar-marcas_view.php") {
    $_SESSION["current-rute"] = "Mantenimiento / Administrar marcas";
    $_SESSION["marcas"] = "administrar-marcas";
} else if($pagina == "php/vistas/admin/mantenimiento/marcas/editar-marca_view.php") {
    $_SESSION["current-rute"] = "Mantenimiento / Editar marca";
} 

// Si nos encontramos en paginas relacionadas a AULAS
else if($pagina == "php/vistas/admin/mantenimiento/aulas/agregar-aula_view.php") {
    $_SESSION["current-rute"] = "Mantenimiento / Agregar aula";
    $_SESSION["aulas"] = "agregar-aula";
} else if($pagina == "php/vistas/admin/mantenimiento/aulas/administrar-aulas_view.php") {
    $_SESSION["current-rute"] = "Mantenimiento / Administrar aulas";
    $_SESSION["aulas"] = "administrar-aulas";
} else if($pagina == "php/vistas/admin/mantenimiento/aulas/editar-aula_view.php") {
    $_SESSION["current-rute"] = "Mantenimiento / Editar aula";
}

// Si nos encontramos en paginas relacionadas a PRESTAMOS(usuario y admin)
else if($pagina == "php/vistas/admin_user/realizar-prestamo_view.php") {
    $_SESSION["current-rute"] = "Registro / Realizar prestamo";
    $_SESSION["prestamos"] = "realizar-prestamo";
} else if($pagina == "php/vistas/admin_user/historial-prestamos_view.php") {
    $_SESSION["current-rute"] = "Registro / Historial de prestamos";
    $_SESSION["prestamos"] = "historial-prestamos";
}
?>

<div class="wrapper">
    <nav class="sidebar">
        <ul class="sidebar__list">
            <li class="sidebar__item">
                <a href="<?php echo RUTA;?>"class="sidebar__logo">ITCA</a>
            </li>

            <li class="sidebar__item">
                <div class="sidebar__link btn-reset flex-link">
                    Prestamos
                    <span>
                        <svg class="sidebar__icon">
                            <use xlink:href="img/SVG/sprite.svg#caret-right-fill"></use>
                        </svg>
                    </span>


                    <div class="sidebar__submenu">
                        <div class="sidebar__item">
                            <a href="<?php echo RUTA;?>realizar-prestamo" class="sidebar__link  sidebar__link-submenu">
                                Realizar prestamo
                                <span>
                                    <svg class="sidebar__icon" style='width: 1.1rem !important; height: 1.1rem !important'>
                                        <use xlink:href="img/SVG/sprite.svg#plus"></use>
                                    </svg>
                                </span>
                            </a>
                            <a href="<?php echo RUTA;?>historial-prestamos" class="sidebar__link  sidebar__link-submenu">
                                Historial de prestamos
                                <span>
                                    <svg class="sidebar__icon" style='transform: rotate(0); width: 1.3rem !important; height: 1.3rem !important'>
                                        <use xlink:href="img/SVG/sprite.svg#historial-2"></use>
                                    </svg>
                                </span>
                            </a>
                        </div>                     
                    </div>
                </div>
            </li>
        </ul>
        <div class="time__wrapper" style="position: relative;">
            <div class="time-app" style="width: min(10rem, 100%); padding: 1.5rem 0; position: absolute; bottom: 0; left: 50%; transform: translateX(-50%)">
                <p id="time-app-user" style="position: absolute; top: 0; left: 0; margin-left: .4rem">
                </p>
                <span id="pm-am-time-user" style="position: absolute; top: 0; right: 0; margin-right: 1.2rem"></span>
            </div>
            <!-- <p class="copyright">&copy; Copyright 2023 ITCA-FEPADE</p> -->
        </div>
    </nav>

    <div id="user__overlay" class="user__overlay hidden"></div>
    <div class="content">
        <header class="content__header">
            <a href="<?php echo RUTA;?>"class="sidebar__logo">ITCA</a>
            <button type="button">
                <svg class="content__user-icon" style="width: 2.5rem; height: 2.5rem; color: var(--color-purple)">
                    <use xlink:href="img/SVG/sprite.svg#open-menu"></use>
                </svg>
            </button>
            <p class="current-rute"><?php if(isset($_SESSION["current-rute"])) {echo $_SESSION["current-rute"];}?></p>
            <p class="welcome-message">Bienvenido/a <span id="userWelcome"><?php if(isset($_SESSION["userProfile"])) {echo $_SESSION["userProfile"]["names"];}?></span></p>
            <div class="content__user" tabIndex="1">
                <div class="pointer">
                    <img id='imgWelcome' src="img/app-photos/usuarios/<?php if(isset($_SESSION["userProfile"]["photo"])) { echo $_SESSION["userProfile"]["photo"]; }?>" alt="foto del usuario" class="content__user-photo <?php if(!isset($_SESSION["userProfile"]["photo"])){echo "hidden";}?>">
                    <div id='icon_welcome' class="content__user-icon__wrapper <?php if(isset($_SESSION["userProfile"]["photo"])){echo "hidden";}?>">
                        <svg class="content__user-icon people">
                            <use xlink:href="img/SVG/sprite.svg#user"></use>
                        </svg>
                    </div>
                    <svg class="content__user-arrow">
                        <use xlink:href="img/SVG/sprite.svg#caret-down"></use>
                    </svg>
                </div>

                <div class="content__user-menu user-menu hidden">
                    <div class="content__user-list">
                        <div class="content__user-item">
                            <img id='image_menu' src="img/app-photos/usuarios/<?php if(isset($_SESSION["userProfile"]["photo"])) { echo $_SESSION["userProfile"]["photo"]; }?>" alt="foto del usuario" class="content__user-photo <?php if(!isset($_SESSION["userProfile"]["photo"])){echo "hidden";}?>">
                            <div id='icon_menu' class="content__user-icon__wrapper <?php if(isset($_SESSION["userProfile"]["photo"])){echo "hidden";}?>">
                                <svg class="content__user-icon people">
                                    <use xlink:href="img/SVG/sprite.svg#user"></use>
                                </svg>
                            </div>
                            <p id="emailUser"><?php if(isset($_SESSION["userProfile"])) {echo $_SESSION["userProfile"]["email"];}?></p>
                        </div>
                        <div class="content__user-item">
                            <div class="content__user-link tema" tabindex="1">
                                <span>
                                    <svg class="content__user-icon">
                                        <use xlink:href="img/SVG/sprite.svg#theme"></use>
                                    </svg>
                                </span>
                                Tema

                                <form class="content__user-submenu">
                                    <div class="content__user-item no-padding no-margin">
                                        <button id="btnDark" type="button" <?php if(isset($_SESSION["theme-dark"])){echo "style='cursor: default;'";} if(isset($_SESSION["theme-light"])){echo "onmousedown='darkTheme()'";}?> class="content__user-link content__user-theme--dark">
                                            <span>
                                                <svg class='content__user-icon' id="iconCheckDark" 
                                                <?php
                                                if(isset($_SESSION["theme-light"]) && !isset($_SESSION["theme-dark"])){
                                                    echo "style='visibility: hidden;'";
                                                }
                                                ?>
                                                >
                                                    <use xlink:href='img/SVG/sprite.svg#check'></use>
                                                </svg>
                                            </span>
                                            <div>Oscuro</div>
                                        </button>
                                    </div>
                                    
                                    <div class="content__user-item">
                                        <button id="btnLight" type="button" <?php if(isset($_SESSION["theme-light"])){echo "style='cursor: default;'";} if(isset($_SESSION["theme-dark"])){echo "onmousedown='lightTheme()''";}?> class="content__user-link content__user-theme--light">
                                            <span>
                                                <svg class="content__user-icon" id="iconCheckLight"
                                                <?php
                                                if(isset($_SESSION["theme-dark"]) && !isset($_SESSION["theme-light"])){
                                                    echo "style='visibility: hidden;'";
                                                }
                                                ?>
                                                >
                                                    <use xlink:href="img/SVG/sprite.svg#check"></use>
                                                </svg>
                                            </span>
                                            <div>Claro</div>
                                        </button>
                                    </div>
                            </div>
                        </div>
                        <div class="content__user-item">
                            <button type="button" id="opneUserMenu" class="content__user-link settings">
                                <span>
                                    <svg class="content__user-icon">
                                        <use xlink:href="img/SVG/sprite.svg#settings"></use>
                                    </svg>
                                </span>
                                <div>configuracion</div>
                            </button>
                        </div>
                        <div class="content__user-divisor"></div>
                        <div class="content__user-item">
                            <button id="btnLogOut" type="button" onclick='logOut()' class="content__user-link log-out">
                                <span>
                                    <svg class="content__user-icon">
                                        <use xlink:href="img/SVG/sprite.svg#log-out"></use>
                                    </svg>
                                </span>
                                <div>Cerrar sesión</div>
                            </button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </header>        
        
        <main>
            <?php
            require_once($pagina);
            ?>
            <div id="res"></div>
        </main>
    </div>

    <div class="user__profile hidden" id="modal-background"></div>
    <div class="user__modal user__modal-general hidden" id="user-modal">
        <button type="button" id="btnCloseProfile" class="alert__btn-close closing">&times;</button>
        <form class="user__photo">
            <svg id='icon_modal' class="<?php if(isset($_SESSION["userProfile"]["photo"])){echo "hidden";}?>">
                <use xlink:href="img/SVG/sprite.svg#person-circle"></use>
            </svg>
            <img id='imgProfile' src="img/app-photos/usuarios/<?php if(isset($_SESSION["userProfile"]["photo"])) { echo $_SESSION["userProfile"]["photo"]; }?>" alt="foto del usuario" class="<?php if(!isset($_SESSION["userProfile"]["photo"])){echo "hidden";}?>">
            <label for="newPhotoProfile" id="photoProfile">Editar imagen</label>
            <input type="file" class="hidden" id="newPhotoProfile">
        </form>
        <form class="user__info">
            <h2 class="user__info-title">Carnet</h2>
            <span id="carnet"><?php echo $usuario["carnet"]; ?></span>
            <button type="button" id="carnetProfile">Editar carnet</button>
            <h2 class="user__info-title">Nombres</h2>
            <span id="nombres"><?php echo $usuario["nom_docente"]; ?></span>
            <button type="button" id="namesProfile">Editar nombres</button>
            <h2 class="user__info-title">Apellidos</h2>
            <span id="apellidos"><?php echo $usuario["ape_docente"]; ?></span>
            <button type="button" id="lastnamesProfile">Editar apellidos</button>
            <h2 class="user__info-title">Email</h2>
            <span id="email"><?php echo $usuario["email"]; ?></span>
            <button type="button" id="emailProfile">Editar email</button>
            <h2 class="user__info-title">Telefono de casa</h2>
            <span id="telefonoCasa"><?php echo $usuario["telcasa"]; ?></span>
            <button type="button" id="housePhoneProfile">Editar telefono de casa</button>
            <h2 class="user__info-title">Celular</h2>
            <span id="telefono"><?php echo $usuario["celular"]; ?></span>
            <button type="button" id="phoneProfile">Editar celular</button>
            <h2 class="user__info-title">Cargo</h2>
            <span id="tipo"><?php echo $usuario["tipo"]; ?></span>
            <button type="button" id="typeProfile">Editar cargo</button>

            <h2 class="user__info-title">Departamento</h2>
            <span id="depto"><?php if(isset($_SESSION["userProfile"]["depto"])) {echo $depto["depto"];} else {echo "<p style='color: var(--color-wrong)'>Debe ingresar a que departamento pertenece</p>";}?></span>
            <button type="button" id="deptoProfile">Editar departamento</button>
            <div class="content__user-divisor"></div>
            <h2 class="user__info-title">Contraseña</h2>
            <button type="button" id="passwordProfile">Editar contraseña</button>
        </form>
    </div>

    <div id="details__overlay" class="details__overlay hidden"></div>

    <form class="user__modal user__modal-detail hidden" id="carnet-modal">
        <button type="button" id="btnCloseCarnet" class="alert__btn-close closing">&times;</button>
        <div class="user__detail">  
            <h2 class="user__info-title">Edita tu carnet</h2>
            <input type='text' required maxlength='12' id='usuario_carnet' autocomplete="on" class='form__input' placeholder='Nuevo carnet'>
            <button type="button" id="editCarnet">Guardar nuevo carnet</button>
        </div>
    </form>

    <form class="user__modal user__modal-detail hidden" id="names-modal">
        <button type="button" id="btnCloseNames" class="alert__btn-close closing">&times;</button>
        <div class="user__detail">  
            <h2 class="user__info-title">Edita tus nombres</h2>
            <input type='text' required pattern='^\S+\s\S+$' id='usuario_names' autocomplete="on" class='form__input' placeholder='Nuevos nombres'>
            <button type="button" id="editNames">Guardar nuevos nombres</button>
        </div>
    </form>

    <form class="user__modal user__modal-detail hidden" id="lastnames-modal">
        <button type="button" id="btnCloseLastNames" class="alert__btn-close closing">&times;</button>
        <div class="user__detail">  
            <h2 class="user__info-title">Edita tus Apellidos</h2>
            <input type='text' required pattern='^\S+\s\S+$' id='usuario_lastnames' autocomplete="on" class='form__input' placeholder='Nuevos apellidos'>
            <button type="button" id="editLastNames">Guardar nuevos apellidos</button>
        </div>
    </form>

    <form class="user__modal user__modal-detail hidden" id="email-modal">
        <button type="button" id="btnCloseEmail" class="alert__btn-close closing">&times;</button>
        <div class="user__detail">  
            <h2 class="user__info-title">Edita tu email</h2>
            <input type='email' required id='usuario_email' class='form__input' autocomplete="on" placeholder='Nuevo email'>
            <button type="button" id="editEmail">Guardar nuevo email</button>
        </div>
    </form>

    <form class="user__modal user__modal-detail hidden" id="housePhone-modal">
        <button type="button" id="btnCloseHousePhone" class="alert__btn-close closing">&times;</button>
        <div class="user__detail">  
            <h2 class="user__info-title">Edita tu telefono de casa</h2>
            <input type='text' required pattern='[0-9]{4}-[0-9]{4}' autocomplete="on" id='usuario_house-phone' class='form__input' placeholder='Nuevo telefono de casa'>
            <button type="button" id="editHousePhone">Guardar nuevo telefono de casa</button>
        </div>
    </form>

    <form class="user__modal user__modal-detail hidden" id="phone-modal">
        <button type="button" id="btnClosePhone" class="alert__btn-close closing">&times;</button>
        <div class="user__detail">  
            <h2 class="user__info-title">Edita tu celular</h2>
            <input type='text' required pattern='[0-9]{4}-[0-9]{4}' autocomplete="on" id='usuario_phone' class='form__input' placeholder='Nuevo celular'>
            <button type="button" id="editPhone">Guardar nuevo celular</button>
        </div>
    </form>

    <form class="user__modal user__modal-detail hidden" id="type-modal">
        <button type="button" id="btnCloseType" class="alert__btn-close closing">&times;</button>
        <div class="user__detail">  
            <h2 class="user__info-title">Edita tu cargo</h2>
            <select id='usuario_type' required class='form__input form__select'>
                <option disabled selected value='0' class='disabled'>Cargo</option>
                <option value='Ingeniero'>Ingeniero</option>
                <option value='Licenciado'>Licenciado/a</option>
                <option value='Tecnico'>Tecnico</option>
            </select>
            <button type="button" id="editType">Guardar nuevo cargo</button>
        </div>
    </form>

    <form class="user__modal user__modal-detail hidden" id="depto-modal">
        <button type="button" id="btnCloseDepto" class="alert__btn-close closing">&times;</button>
        <div class="user__detail">  
            <h2 class="user__info-title">Edita el departamento al que perteneces</h2>

            <select id='usuarioDepto' required class='form__input form__select'>
                <option selected disabled value="0">Departamentos</option>
            </select>
            <div id="requestingDeptos"></div>

            <button type="button" id="editDepto">Guardar nuevo departamento</button>
        </div>
    </form>

    <form class="user__modal user__modal-detail hidden" id="password-modal">
        <button type="button" id="btnClosePassword" class="alert__btn-close closing">&times;</button>
        <div class="user__detail">  
            <h2 class="user__info-title">Edita tu contraseña</h2>
            <input type='password' required pattern='^\S{6,}$' autocomplete="off" id='usuario_passwordLast' class='form__input' placeholder='Contraseña actual'>
            <input type='password' required pattern='^\S{6,}$' autocomplete="off" id='usuario_password' class='form__input' placeholder='Nueva contraseña'>
            <input type='password' required pattern='^\S{6,}$' autocomplete="off" id='usuario_passwordRe' class='form__input' placeholder='Confirma tu nueva contraseña'>
            <button type="button" id="editPassword">Guardar nueva contraseña</button>
        </div>
    </form>
</div>


<div id="alert2" class="alert hidden" style="left: 50%; top: 1rem; z-index: 9;">
    <button type="button" id="btnCloseAlert2" class="alert__btn-close">&times;</button>
    <p id="alert__message2" class="alert__message"></p>
</div>

<script src="js/appAdminUser.js"></script>
<script>
    // Cerrando sesion
    function logOut() {
        Obj = new Object();
        Obj.logOut = "";

        $.ajax({
            url: "php/ajax/ajax_app.php?" + $.param(Obj), success: function () {
                window.location.href = '<?php echo $_SESSION["RUTA"]?>';
            }
        });
    }
</script>