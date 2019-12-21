$(document).ready(function() {

    if($("#emsb_service_full_day_reservation").is(":checked")) {
        $(".emsb-time-slot-container").hide();
    }
    $("#emsb_service_full_day_reservation").click(function() {
        if($(this).is(":checked")) {
            $(".emsb-time-slot-container").slideUp();
        } else {
            $(".emsb-time-slot-container").slideDown();
        }
    });

    if($("#emsbtexteditor_check").is(":checked")) {
        $("#emsb-texteditor-container").show();
    } else {
        $("#emsb-texteditor-container").hide();
    }
    $("#emsbtexteditor_check").click(function() {
        if($(this).is(":checked")) {
            $("#emsb-texteditor-container").slideDown();
        } else {
            $("#emsb-texteditor-container").slideUp();
        }
    });

      
});