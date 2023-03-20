/* 
Author: Maël Guiraud
17/03/2023
Property of CESI Nanterre
*/


$(document).ready(() => {

    // Script that update buttons status
    $('button').click((ev) => {
        switch (ev.currentTarget.id) {
            //Confort < 3 : Not happy, we display the "reason" div
            case "mark0": case "mark1": case "mark2":
                $("#discomfort_kind").removeClass("hidden");
                $("#discomfort_kind").addClass("visible");
                break;
            //Confort > 3 : Happy, we hide all divs and reset values
            case "mark3": case "mark4":
                $("#discomfort_kind").removeClass("visible");
                $("#discomfort_kind").addClass("hidden");
                $("#reason_2").removeClass("selected");
                $("#light_0").removeClass("selected");
                $("#light_1").removeClass("selected");
                $('#light_hidden').val("-1");
                $('#reason_hidden').val("0");
                $("#reason_1").removeClass("selected");
                $("#heat_0").removeClass("selected");
                $("#heat_1").removeClass("selected");
                $('#heat_hidden').val("-1");
                $("#reason_4").removeClass("selected");
                $("#light").addClass("hidden");
                $("#light").removeClass("visible");
                $("#heat").addClass("hidden");
                $("#heat").removeClass("visible");
                break;

            //Use of a flag for the reason :
            // Heat = 1
            // Light = 2
            // Sound = 3
            // Confort reason between 1 and 7
            //Reason 1: Show/hide heat divs and reset value if hide
            case "reason_1":
                hb = document.querySelectorAll("button.hb");
                if (hb[0].classList.contains('selected')) {
                    $("#heat").addClass("hidden");
                    $("#heat").removeClass("visible");
                    $("#reason_1").removeClass("selected");
                    $("#heat_0").removeClass("selected");
                    $("#heat_1").removeClass("selected");
                    $('#heat_hidden').val("-1");
                }
                else {
                    lb = document.querySelectorAll("button.lb");
                    if (lb[0].classList.contains('selected')) {
                        $("#heat").insertAfter("#light");
                    }
                    $("#heat").removeClass("hidden");
                    $("#heat").addClass("visible");
                    $("#reason_1").addClass("selected");
                }
                break;
            //Reason 2: Show/hide light divs and reset value if hide
            case "reason_2":
                lb = document.querySelectorAll("button.lb");
                if (lb[0].classList.contains('selected')) {
                    $("#light").addClass("hidden");
                    $("#light").removeClass("visible");
                    $("#reason_2").removeClass("selected");
                    $("#light_0").removeClass("selected");
                    $("#light_1").removeClass("selected");
                    $('#light_hidden').val("-1");
                }
                else {
                    hb = document.querySelectorAll("button.hb");
                    if (hb[0].classList.contains('selected')) {
                        $("#light").insertAfter("#heat");
                    }
                    $("#light").removeClass("hidden");
                    $("#light").addClass("visible");
                    $("#reason_2").addClass("selected");
                }
                break;
            //Reason 4: Show/hide sound button select
            case "reason_4":
                sb = document.querySelectorAll("button.sb");
                if (sb[0].classList.contains('selected')) {
                    $("#reason_4").removeClass("selected");
                }
                else {
                    $("#reason_4").addClass("selected");
                }
                break;
            default:
        }
    });
});


// Change the value of hidden inputs of the form when it is submitted
function onSend() {
    //Get all selected buttons
    const selecteds = $('.selected');
    console.log(selecteds);
    if (!selecteds.length) {
        confirm('Veuillez sélectionner une réponse');
        return false;
    }

    //Check if the room value is okay or if a there is an xss injection attempt
    const val_room = parseInt($('input[name=room]').val());
    if ((val_room < 0 || val_room > 3) && val_room != 10) {
        alert('Vous essayer d\'utiliser une url modifiée. Si vous n\'êtes pas à l\'origine du problème, merci de contacter le responsable de la page.');
        return false;
    }

    let reason = 0;
    for (const sel of selecteds) {
        const score = sel.id.substring(sel.id.length - 1);
        if (sel.id.startsWith("mark")) {
            $('#level_hidden').val(score);
            if (selecteds.length == 1 && parseInt(score) <= 2) {
                alert('Veuillez sélectionner une raison');
                return false;
            }
        }
        else if (sel.id.startsWith("reason")) {
            // binary flags
            reason |= score;

        }
        else if (sel.id.startsWith("heat")) {
            $('#heat_hidden').val(score);
        }
        else if (sel.id.startsWith("light")) {
            $('#light_hidden').val(score);
        }
        else {
            console.error("Unknown selected button id : " + sel.id);
        }
    }
    if (reason) {
        $('#reason_hidden').val(reason);
    }
    if (reason == 3 || reason == 1 || reason == 7 || reason == 5) {
        const v = parseInt($('#heat_hidden').val());
        if (v == -1) {
            alert('Merci de préciser votre réponse');
            return false;
        }
    }
    if (reason == 6 || reason == 2 || reason == 7 || reason == 3) {
        const v = parseInt($('#light_hidden').val());
        if (v == -1) {
            alert('Merci de préciser votre réponse');
            return false;
        }

    }
    alert('Merci pour votre réponse');
    return true;
}

/* Update the state of a class of buttons that cannot be selected at the same time*/
function updateButtonState(buttons, selectedButton) {
    for (var i = 0; i < buttons.length; i++) {
        buttons[i].classList.remove("selected");
    }
    selectedButton.classList.add("selected");
}


/* Management of confort level buttons*/
window.addEventListener("load", function () {
    // Select all confort level buttons
    var buttons = document.querySelectorAll("button.general_confort");
    // Add event listener to each confort level buttons
    for (var i = 0; i < buttons.length; i++) {
        buttons[i].addEventListener("click", function () {
            updateButtonState(buttons, this);
        });
    }
});

/* Management of heat buttons*/
window.addEventListener("load", function () {
    // Select all heat buttons
    var buttons = document.querySelectorAll("button.temper");
    // Add event listener to each buttons
    for (var i = 0; i < buttons.length; i++) {
        buttons[i].addEventListener("click", function () {
            updateButtonState(buttons, this);
        });
    }
});

/* Management of light buttons*/
window.addEventListener("load", function () {
    // Select all light buttons
    var buttons = document.querySelectorAll("button.lumi");
    // Add event listener to each buttons
    for (var i = 0; i < buttons.length; i++) {
        buttons[i].addEventListener("click", function () {
            updateButtonState(buttons, this);
        });
    }
});
