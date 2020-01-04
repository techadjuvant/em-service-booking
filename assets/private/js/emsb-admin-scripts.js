$(document).ready(function() {

    
    var pluginsUrl = backend_ajax_object.pluginsUrl;
    var emsb_pluginUrl = pluginsUrl +"/em-service-booking";
    var emsb_icon_url = emsb_pluginUrl +"/assets/img/service-booking.png";
    var emsb_loading_icon_url = emsb_pluginUrl +"/assets/img/loading.gif";

    $("li#toplevel_page_emsb_admin_page .dashicons-admin-generic").append("<span class='emsb-icon-wrapper'><img src='"+emsb_icon_url+"' alt='EMSB'></span>");

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
        $("tbody#emsbPendingBookings").css("opacity","0.3");
        var emsb_booking_approval_nonce = $("#emsb_booking_approval_nonce").val();
        var emsb_booking_approval_action_value = $(this).children('.emsb-booking-action-value').val();
        var emsb_booking_update_availability = $(this).children('.emsb-booking-update-avalability').val();
        var booked_slot_id = $(this).children('.emsb-booked-slot-id').val();
        var emsb_customer_email_address = $(this).children('.emsb-customer-email-address').val();
        var emsb_booking_approval_id = $(this).children('.emsb-booking-approval-id').val();
        var emsb_booking_approval_action_data = {
            'action': 'emsb_booking_approval',
            'security': emsb_booking_approval_nonce,
            'emsb_booking_approval_action_value': emsb_booking_approval_action_value,
            'emsb_booking_update_availability': emsb_booking_update_availability,
            'booked_slot_id': booked_slot_id,
            'emsb_customer_email_address': emsb_customer_email_address,
            'emsb_booking_approval_id': emsb_booking_approval_id
        };
        $.ajax({
            type: 'POST',
            url: backend_ajax_object.ajaxurl,
            data: emsb_booking_approval_action_data,
            dataType:"json",
            success: function(response) {
                fetchPendingBookings();
            }

        });

    });
    

    var timeIntervalForCounting = 1000*60*2;

    jQuery("#toplevel_page_emsb_admin_page .wp-menu-name").append("<span class='emsb-pending-bookings-count'></span>");

    setInterval(function () {
        fetchPendingBookingsCounts();
        fetchPendingBookings();
    }, timeIntervalForCounting);

    fetchPendingBookings();

    function fetchPendingBookings(){
        var emsb_fetch_pending_bookings_data = {
            'action': 'emsb_fetch_pending_bookings'
        };
        $.ajax({
            type: 'POST',
            url: backend_ajax_object.ajaxurl,
            data: emsb_fetch_pending_bookings_data,
            dataType:"json",
            success: function(data) {
                preparePendingBookingTable(data);
                $(".emsb-admin-loading-gif").css("display","none");
                $("tbody#emsbPendingBookings").css("opacity","1");
                fetchPendingBookingsCounts();
            }

        });

    }

    function preparePendingBookingTable(data) {
        $('#emsbPendingBookings').empty();
        $.each(data, function(index, booking) {
            index = index + 1;
            var availability_per_slot = booking.available_orders;
            var availability_per_slot_to_int = parseInt(availability_per_slot);
            var update_availability = availability_per_slot_to_int - 1;
            var do_not_update_availability = availability_per_slot_to_int;
            if(availability_per_slot_to_int > 0){
                $('#emsbPendingBookings').append("\
                <tr> \
                    <td class='emsb-pending-order-checkbox'><input type='checkbox'/>&nbsp;</td>\
                    <td>"+ booking.id +"</td>\
                    <td> "+ booking.service_name +" </td>\
                    <td>"+ booking.customer_name +"</td>\
                    <td>"+ booking.customer_phone +"</td>\
                    <td>"+ booking.customer_email +"</td>\
                    <td>"+ booking.booked_date +"</td> \
                    <td>  "+ booking.booked_time_slot +"  </td>\
                    <td> \
                        <div class='emsb-booking-status-wrapper'> \
                            <span> Pending </span> <br>\
                            <span> Available: "+ availability_per_slot_to_int +" </span>\
                            <div class='emsb-approval-actions-wrapper'>\
                                <a class='emsb-approval-action emsb-approval-approve'>\
                                    <input class='emsb-booking-approval-id' type='hidden' value='"+ booking.id +"' name='emsb_booking_approval_id' >\
                                    <input class='emsb-booking-action-value' type='hidden' value='1' name='emsb_booking_approved' > Confirm \
                                    <input class='emsb-booking-update-avalability' type='hidden' value='"+ update_availability +"' >\
                                    <input class='emsb-booked-slot-id' type='hidden' value='"+ booking.booked_slot_id +"' >\
                                    <input class='emsb-customer-email-address' type='hidden' value='"+ booking.customer_email +"' >\
                                </a>\
                                <a class='emsb-approval-action emsb-approval-action-trush'>\
                                    <input class='emsb-booking-approval-id' type='hidden' value='"+ booking.id +"' name='emsb_booking_approval_id' >\
                                    <input class='emsb-booking-action-value' type='hidden' value='trush' name='emsb_booking_trush' > Cancel \
                                    <input class='emsb-booking-update-avalability' type='hidden' value='"+ do_not_update_availability +"' >\
                                    <input class='emsb-customer-email-address' type='hidden' value='"+ booking.customer_email +"' >\
                                </a>\
                            </div> \
                        </div>\
                    </td>\
                </tr>"); 
            } else {
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
                        <span> Slot filled up </span>\
                        <div class='emsb-approval-actions-wrapper'>\
                            <a class='emsb-approval-action emsb-approval-action-trush'>\
                                <input class='emsb-booking-approval-id' type='hidden' value='"+ booking.id +"' name='emsb_booking_approval_id' >\
                                <input class='emsb-booking-action-value' type='hidden' value='trush' name='emsb_booking_trush' > Cancel \
                                <input class='emsb-booking-update-avalability' type='hidden' value='"+ do_not_update_availability +"' >\
                                <input class='emsb-customer-email-address' type='hidden' value='"+ booking.customer_email +"' >\
                            </a>\
                        </div>\
                    </td>\
                </tr>"); 
            }

        });

    }

    fetchPendingBookingsCounts();

    function fetchPendingBookingsCounts(){
        var emsb_fetch_pending_bookings_counts_data = {
            'action': 'emsb_fetch_pending_bookings_counts'
        };
        $.ajax({
            type: 'POST',
            url: backend_ajax_object.ajaxurl,
            data: emsb_fetch_pending_bookings_counts_data,
            success: function(result) {
                jQuery("#toplevel_page_emsb_admin_page .wp-menu-name .emsb-pending-bookings-count").text(result);
            }

        });

    }

    

    
      
});