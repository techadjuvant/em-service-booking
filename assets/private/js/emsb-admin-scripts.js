$(document).ready(function() {
    if($("#emsb_service_for_full_day").is(":checked")) {
        $(".emsb-time-slot-container").hide();
    }
    $("#emsb_service_for_full_day").click(function() {
        if($(this).is(":checked")) {
            $(".emsb-time-slot-container").slideUp();
        } else {
            $(".emsb-time-slot-container").slideDown();
        }
    });
});