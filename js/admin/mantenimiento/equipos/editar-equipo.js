// When clicking the button "Guardar"
$("#guardar").click(function () {
    validate();
});

$("#page").focus();
$("#page").keydown(function (e) {
    if (e.key == "Enter") {
        validate();
    }
});

// Calling the function inputChanges on the inputs
$('#equipo_name').each(function () {
    inputChanges(this);
});

$('#equipo_serie').each(function () {
    inputChanges(this);
});

$('#equipo_description').each(function () {
    inputChanges(this);
});

$('#equipo_modelo').each(function () {
    inputChanges(this);
});

$('equipo_stock').each(function () {
    inputChanges(this);
});

$('#equipo_marca').each(function () {
    inputChanges(this);
});

$('#equipo_depto').each(function () {
    inputChanges(this);
});

// Doing something with events on the input like changing the color of the border
function inputChanges($this) {
    // Save current value of element
    $($this).data('oldVal', $($this));

    // Look for changes in the value
    $($this).bind("propertychange keyup keydown input paste", function (event) {
        // If value has changed...
        if ($($this).data('oldVal') != $($this).val()) {
            // Updated stored value
            $($this).data('oldVal', $($this).val());

            btnInitialState();
            $($this).css('borderColor', 'var(--color-purple)');

            if ($($this).attr('id') == "equipo_name") {
                $('#equipo_serie').css("borderColor", "var(--color-light)");
                $('#equipo_description').css("borderColor", "var(--color-light)");
                $('#equipo_modelo').css("borderColor", "var(--color-light)");
                $('#equipo_stock').css("borderColor", "var(--color-light)");
                $('#equipo_marca').css("borderColor", "var(--color-light)");
                $('#equipo_depto').css("borderColor", "var(--color-light)");
            } else if ($($this).attr('id') == "equipo_serie") {
                $('#equipo_name').css("borderColor", "var(--color-light)");
                $('#equipo_description').css("borderColor", "var(--color-light)");
                $('#equipo_modelo').css("borderColor", "var(--color-light)");
                $('#equipo_stock').css("borderColor", "var(--color-light)");
                $('#equipo_marca').css("borderColor", "var(--color-light)");
                $('#equipo_depto').css("borderColor", "var(--color-light)");
            } else if ($($this).attr('id') == "equipo_description") {
                $('#equipo_name').css("borderColor", "var(--color-light)");
                $('#equipo_serie').css("borderColor", "var(--color-light)");
                $('#equipo_modelo').css("borderColor", "var(--color-light)");
                $('#equipo_stock').css("borderColor", "var(--color-light)");
                $('#equipo_marca').css("borderColor", "var(--color-light)");
                $('#equipo_depto').css("borderColor", "var(--color-light)");
            } else if ($($this).attr('id') == "equipo_modelo") {
                $('#equipo_name').css("borderColor", "var(--color-light)");
                $('#equipo_serie').css("borderColor", "var(--color-light)");
                $('#equipo_description').css("borderColor", "var(--color-light)");
                $('#equipo_stock').css("borderColor", "var(--color-light)");
                $('#equipo_marca').css("borderColor", "var(--color-light)");
                $('#equipo_depto').css("borderColor", "var(--color-light)");
            } else if ($(this).attr('id') == "equipo_stock") {
                $('#equipo_name').css("borderColor", "var(--color-light)");
                $('#equipo_serie').css("borderColor", "var(--color-light)");
                $('#equipo_description').css("borderColor", "var(--color-light)");
                $('#equipo_modelo').css("borderColor", "var(--color-light)");
                $('#equipo_marca').css("borderColor", "var(--color-light)");
                $('#equipo_depto').css("borderColor", "var(--color-light)");
            } else if ($(this).attr('id') == "equipo_marca") {
                $('#equipo_name').css("borderColor", "var(--color-light)");
                $('#equipo_serie').css("borderColor", "var(--color-light)");
                $('#equipo_description').css("borderColor", "var(--color-light)");
                $('#equipo_modelo').css("borderColor", "var(--color-light)");
                $('#equipo_stock').css("borderColor", "var(--color-light)");
                $('#equipo_depto').css("borderColor", "var(--color-light)");
            }
            else if ($(this).attr('id') == "equipo_depto") {
                $('#equipo_name').css("borderColor", "var(--color-light)");
                $('#equipo_serie').css("borderColor", "var(--color-light)");
                $('#equipo_description').css("borderColor", "var(--color-light)");
                $('#equipo_modelo').css("borderColor", "var(--color-light)");
                $('#equipo_stock').css("borderColor", "var(--color-light)");
                $('#equipo_marca').css("borderColor", "var(--color-light)");
            }

            hideAlert();
        }
    });

    $($this).on("focusout", function (event) {
        // changing the color of the border
        $($this).css("borderColor", "var(--color-light)");
    });

    $($this).on("focus", function (event) {
        // changing the color of the border
        $($this).css("borderColor", "var(--color-purple)");
    });
}

// Changing the btn "Guardar" text and showing the check icon
function btnStateChange() {
    const btnText = document.querySelector('.guardar-text');
    const checkIcon = document.querySelector('.btn-check');

    btnText.textContent = "Guardado";
    checkIcon.classList.remove("hidden");
}

// Returning the btn "Guardar" to its initial values
function btnInitialState() {
    const btnText = document.querySelector('.guardar-text');
    const checkIcon = document.querySelector('.btn-check');

    btnText.textContent = "Guardar";
    checkIcon.classList.add("hidden");
}

// Validating that the inputs are not empty
$("#equipo_name").data('inicialValue', $("#equipo_name").val());
$("#equipo_serie").data('inicialValue', $("#equipo_serie").val());
$("#equipo_description").data('inicialValue', $("#equipo_description").val());
$("#equipo_modelo").data('inicialValue', $("#equipo_modelo").val());
$("#equipo_stock").data('inicialValue', $("#equipo_stock").val());
$("#equipo_marca").data('inicialValue', $("#equipo_marca").val());
$("#equipo_depto").data('inicialValue', $("#equipo_depto").val());

function validate() {
    if ($("#equipo_name").data('inicialValue') == $("#equipo_name").val() && $("#equipo_serie").data('inicialValue') == $("#equipo_serie").val() && $("#equipo_description").data('inicialValue') == $("#equipo_description").val() && $("#equipo_modelo").data('inicialValue') == $("#equipo_modelo").val() && $("#equipo_stock").data('inicialValue') == $("#equipo_stock").val() && $("#equipo_marca").data('inicialValue') == $("#equipo_marca").val() && $("#equipo_depto").data('inicialValue') == $("#equipo_depto").val()) {
        hideAlert();
        alertRed();
        setTimeout(function () { alerts("Huh?, no se ha detectado un cambio en alguno de los campos a editar, para editar asigne un nuevo valor en uno de los campos de este equipo."); }, 10);

        $('#equipo_name').css("borderColor", "var(--color-wrong)");
        $('#equipo_serie').css("borderColor", "var(--color-wrong)");
        $('#equipo_description').css("borderColor", "var(--color-wrong)");
        $('#equipo_modelo').css("borderColor", "var(--color-wrong)");
        $('#equipo_stock').css("borderColor", "var(--color-wrong)");
        $('#equipo_marca').css("borderColor", "var(--color-wrong)");
        $('#equipo_depto').css("borderColor", "var(--color-wrong)");
    } else if ($("#equipo_name").val() == "") {
        $("#equipo_name").css("borderColor", "var(--color-wrong)");

        hideAlert();
        alertRed();
        setTimeout(function () { alerts("Error, debe asignar un nombre para editar esta equipo."); }, 10);
    } else if ($("#equipo_serie").val() == "") {
        $("#equipo_serie").css("borderColor", "var(--color-wrong)");

        hideAlert();
        alertRed();
        setTimeout(function () { alerts("Error, debe asignar un numero de serie para editar esta marca."); }, 10);
    } else if ($("#equipo_description").val() == "") {
        $("#equipo_description").css("borderColor", "var(--color-wrong)");

        hideAlert();
        alertRed();
        setTimeout(function () { alerts("Error, debe asignar una descripcion para editar esta equipo."); }, 10);
    } else if ($("#equipo_modelo").val() == "") {
        $("#equipo_modelo").css("borderColor", "var(--color-wrong)");

        hideAlert();
        alertRed();
        setTimeout(function () { alerts("Error, debe asignar un modelo para editar esta equipo."); }, 10);
    } else if ($("#equipo_stock").val() == "") {
        $("#equipo_stock").css("borderColor", "var(--color-wrong)");

        hideAlert();
        alertRed();
        setTimeout(function () { alerts("Error, el stock no puede ser menor a 1."); }, 10);
    } else if (containsSpecialChars($("#aula_name").val())) {
        $("#aula_name").css("borderColor", "var(--color-wrong)");

        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, asegurese que el nombre del equipo no contenga ningun caracter especial.'); }, 10);
    } else if (containsSpecialCharsGuion($("#equipo_serie").val())) {
        $("#equipo_serie").css("borderColor", "var(--color-wrong)");

        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, asegurese que el numero de serie no contenga ningun caracter especial a exepcion del guion (-).'); }, 10);
    } else if (containsSpecialCharsDescripcion($("#equipo_description").val())) {
        $("#equipo_description").css("borderColor", "var(--color-wrong)");

        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, a exepcion de los caracteres !;:.,"() asegurese que la descripcion del aula no contenga ningun otro caracter especial.'); }, 10);
    } else if (containsSpecialCharsGuion($("#equipo_modelo").val())) {
        $("#equipo_modelo").css("borderColor", "var(--color-wrong)");

        hideAlert();
        alertRed();
        setTimeout(function () { alerts('Error, asegurese que el modelo del equipo no contenga ningun caracter especial a exepcion del guion (-).'); }, 10);
    } else {
        if ($("#equipo_name").data('inicialValue') != $("#equipo_name").val() && $("#equipo_serie").data('inicialValue') != $("#equipo_serie").val() && $("#equipo_description").data('inicialValue') != $("#equipo_description").val() && $("#equipo_modelo").data('inicialValue') != $("#equipo_modelo").val() && $("#equipo_stock").data('inicialValue') != $("#equipo_stock").val() && $("#equipo_marca").data('inicialValue') != $("#equipo_marca").val() && $("#equipo_depto").data('inicialValue') != $("#equipo_depto").val()) {
            const Obj = {};
            Obj.equipo_idEdit = $("#equipo_id").val();
            Obj.equipo_nameEdit = $("#equipo_name").val();
            Obj.equipo_serieEdit = $("#equipo_serie").val();
            Obj.equipo_descriptionEdit = $("#equipo_description").val();
            Obj.equipo_modeloEdit = $("#equipo_modelo").val();
            Obj.equipo_stockEdit = $("#equipo_stock").val();
            Obj.equipo_marcaEdit = $("#equipo_marca").val();
            Obj.equipo_deptoEdit = $("#equipo_depto").val();

            $.ajax({
                url: "php/ajax/ajax_equipos.php?" + $.param(Obj), success: function (respuesta) {
                    $("#resp").html(respuesta);
                }
            });
            return true;
        } else if ($("#equipo_name").data('inicialValue') != $("#equipo_name").val() || $("#equipo_serie").data('inicialValue') != $("#equipo_serie").val()) {
            const Obj = {};
            Obj.equipo_nameSerie = "";
            Obj.equipo_idEdit = $("#equipo_id").val();
            Obj.equipo_nameEdit = $("#equipo_name").val();
            Obj.equipo_serieEdit = $("#equipo_serie").val();
            Obj.equipo_descriptionEdit = $("#equipo_description").val();
            Obj.equipo_modeloEdit = $("#equipo_modelo").val();
            Obj.equipo_stockEdit = $("#equipo_stock").val();
            Obj.equipo_marcaEdit = $("#equipo_marca").val();
            Obj.equipo_deptoEdit = $("#equipo_depto").val();

            $.ajax({
                url: "php/ajax/ajax_equipos.php?" + $.param(Obj), success: function (respuesta) {
                    $("#resp").html(respuesta);
                }
            });
            return true;
        } else if ($("#equipo_description").data('inicialValue') != $("#equipo_description").val() || $("#equipo_modelo").data('inicialValue') != $("#equipo_modelo").val() || $("#equipo_stock").data('inicialValue') != $("#equipo_stock").val() || $("#equipo_marca").data('inicialValue') != $("#equipo_marca").val() || $("#equipo_depto").data('inicialValue') != $("#equipo_depto").val()) {
            const Obj = {};
            Obj.equipo_RestOfFields = "";
            Obj.equipo_idEdit = $("#equipo_id").val();
            Obj.equipo_descriptionEdit = $("#equipo_description").val();
            Obj.equipo_modeloEdit = $("#equipo_modelo").val();
            Obj.equipo_stockEdit = $("#equipo_stock").val();
            Obj.equipo_marcaEdit = $("#equipo_marca").val();
            Obj.equipo_deptoEdit = $("#equipo_depto").val();

            $.ajax({
                url: "php/ajax/ajax_equipos.php?" + $.param(Obj), success: function (respuesta) {
                    $("#resp").html(respuesta);
                }
            });
            return true;
        }
    }
}