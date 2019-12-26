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

    $(document).on('click','.emsb-table-wrapper table tr', function(){
        $(this).addClass("emsb-active-pending-order");
        $(this).siblings().removeClass("emsb-active-pending-order");
        $(this).children(".emsb-pending-order-checkbox").children("input").prop("checked", true);
        $(this).siblings().children(".emsb-pending-order-checkbox").children("input").prop("checked", false);

    });


    
    $(document).on('click','.emsb-active-pending-order .emsb-approval-action', function(){
        $(".emsb-admin-loading-gif").css("display","block");
        var emsb_booking_approval_nonce = $("#emsb_booking_approval_nonce").val();
        var emsb_booking_approval_action_value = $(this).children('.emsb-booking-action-value').val();
        var emsb_booking_approval_id = $(this).children('.emsb-booking-approval-id').val();
        // console.log(emsb_booking_approval_nonce +'Value: '+emsb_booking_approval_id);
        var emsb_booking_approval_action_data = {
            'action': 'emsb_booking_approval',
            'security': emsb_booking_approval_nonce,
            'emsb_booking_approval_action_value': emsb_booking_approval_action_value,
            'emsb_booking_approval_id': emsb_booking_approval_id
        };
        $.ajax({
            type: 'POST',
            url: backend_ajax_object.ajaxurl,
            data: emsb_booking_approval_action_data,
            dataType:"json",
            success: function(response) {
                console.log("Successfully approved");
                fetchBookings();
            }

        });

    });

    fetchBookings();

    function fetchBookings(){
        var emsb_booking_approval_nonce = $("#emsb_booking_approval_nonce").val();
        var emsb_fetch_bookings_data = {
            'action': 'emsb_fetch_bookings',
            'security': emsb_booking_approval_nonce
        };
        $.ajax({
            type: 'POST',
            url: backend_ajax_object.ajaxurl,
            data: emsb_fetch_bookings_data,
            dataType:"json",
            success: function(data) {
                console.log(data);
                preparePendingBookingTable(data);
                $(".emsb-admin-loading-gif").css("display","none");
            }

        });

    }

    function preparePendingBookingTable(data) {
        $('#emsbPendingBookings').empty();
        $.each(data, function(index, booking) {
            index = index + 1;
            $('#emsbPendingBookings').append("\
            <tr>\
                <td class='emsb-pending-order-checkbox'><input type='checkbox'/>&nbsp;</td>\
                <td>"+ booking.id +"</td>\
                <td> "+ booking.service_name +" </td>\
                <td>"+ booking.customer_name +"</td>\
                <td>"+ booking.customer_phone +"</td>\
                <td>"+ booking.customer_email +"</td>\
                <td>"+ booking.booked_date +"</td> \
                <td>  "+ booking.booked_time_slot +"  </td>\
                <td> \
                    <span> Pending </span>\
                    <div class='emsb-approval-actions-wrapper'>\
                        <a class='emsb-approval-action emsb-approval-approve'>\
                            <input class='emsb-booking-approval-id' type='hidden' value='"+ booking.id +"' name='emsb_booking_approval_id' >\
                            <input class='emsb-booking-action-value' type='hidden' value='1' name='emsb_booking_approved' > Confirm \
                        </a>\
                        <a class='emsb-approval-action emsb-approval-action-trush'>\
                            <input class='emsb-booking-approval-id' type='hidden' value='"+ booking.id +"' name='emsb_booking_approval_id' >\
                            <input class='emsb-booking-action-value' type='hidden' value='trush' name='emsb_booking_trush' > Trush \
                        </a>\
                    </div>\
                </td>\
            </tr>"); 

        });

    }

      
});