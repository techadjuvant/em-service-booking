
$(document).ready(function() {
    'use strict';

    var offDay;

    $("#emShowPM").css("display","none");
    $(".em-timer").css("display","none");

    var serviceAndFormContainer = $(".emsb-booking-ticket-container");
    if(serviceAndFormContainer){
        $(".emsb-booking-ticket-container").parent().children(".emsb-services-and-form-container").css("display","none");;
    }

    // Enable BS tooltip feature
    $("body").tooltip({ selector: '[data-toggle=tooltip]' });


    // Select a service
    $('.em-select-service-button').on('click', function() {
        scrollToTopDiv();
        
        $(this).closest(".em-service").addClass('selected');
        
        $(this).closest(".em-service").siblings().removeClass('selected');
        $(".em-services-container").css({
            'transition': 'all .4s ease-in-out',
            'transform' : 'scale(0.8)',
            'opacity' : '0',
            'height': '0'
         });
         $(".em-reservation-process-container").css({
            'transition': 'all .8s ease-in-out',
            'transform' : 'scale(1)',
            'opacity' : '1',
            'height': '100%'
         });

        var selectedService = $(this).closest(".em-service").children(".em-service-excerpt").html();
        $('.em-get-selected-service').html(selectedService);
        
        addServiceDateId();
        disablePassedDays();
        setTimeout(disableOffDays,500);
        enableAvailableDates();
        // Only for the full day reservation services
        var checkSlotEnabled = $("article.em-service.selected #emsb_fullDayReserve").is(":checked");
        if(checkSlotEnabled){
            disableAleadyBookedDates();
        }

        

    });

    function scrollToTopDiv(){
        var scrollToEmsbContainer = $('.emsb-plugin-container').offset();
        window.scroll({
            top: scrollToEmsbContainer.top - 100,
            left: scrollToEmsbContainer.left
          });
    }

    $('.em-change-service-btn').on('click', function() {
        window.location.reload(false); 
    });


    // Calendar
    var selectedYear = "";
    var months = "";
    var selectedMonth = "";
    var selectedMonthNumber = "";
    var selectedDate = "";
    var days = "";
    var selectedDay = "";
    var selectedDateMonthYear = "";
    var emsb_selected_service_date_id_for_db = "";
    var selectedDateMonthYearTimestamp = "";

    $('.em-reservation-calendar').calendar({
        date: new Date(),
        autoSelect: false, // false by default
        select: function(date) {
            selectedYear = date.getFullYear();
            months = ["Jan","Feb","March","April","May","June","July","Aug","Sept","Oct","Nov","Dec"];
            selectedMonth = months[date.getMonth()];
            selectedMonthNumber = date.getMonth();
            selectedDate = date.getDate();
            days = ["Sun","Mon","Tue","Wed","Thu","Fri","Sat"];
            selectedDay = days[date.getDay()];
            selectedDateMonthYear = selectedDate + selectedMonthNumber + selectedYear;

            // For creating slot starting time
            var selectedMonthNumberIncr = selectedMonthNumber + 1;
            selectedDateMonthYearTimestamp = selectedYear +'-'+ selectedMonthNumberIncr +'-'+ selectedDate;

        }
    });


    $(document).on('click','.em-reservation-calendar button.ic', function(){
            addServiceDateId();  
            onIcClickdisableOffDays();   
            onIcClickdisablePassedDays(); 
            var checkSlotEnabled = $("article.em-service.selected #emsb_fullDayReserve").is(":checked");
            if(checkSlotEnabled){
                disableAleadyBookedDates();
            }
            

    });



    var monthYearOnCalendar = "";
    var emsbServiceStartingDateGetDateTime = "";

    // Disable passed dates
    function addServiceDateId(){
        
        // Return today's date and time
        var currentTime = new Date();
        // returns the day of the month (from 1 to 31)
        var currentDate = currentTime.getDate();
        // returns the month (from 0 to 11)
        var currentMonth = currentTime.getMonth() + 1;
        // returns the year (four digits)
        var currentYear = currentTime.getFullYear();
        var activeYearValue = $(".em-reservation-calendar .year-month .month-head div").text();
        activeYearValue = parseInt(activeYearValue, 10);
        var eachActiveYearString = activeYearValue.toString();
    
        var activeMonthValue = $(".em-reservation-calendar .year-body tr td.active").data("month");
        activeMonthValue = parseInt(activeMonthValue, 10);
        var eachActiveMonthString = activeMonthValue.toString();

        // Date Id
        var emsb_selected_service_id = $("article.em-service.selected #emsb-service-id input").val();
        var emsb_selected_service_each_date_id = emsb_selected_service_id +'-'+ eachActiveYearString +'-'+ eachActiveMonthString;
        var i = 1;
        for(i=1;i<=31; i++){
            $(".em-reservation-calendar tbody.month-days tr td[data-date='" + i +"']").attr('data-servicedateid', emsb_selected_service_each_date_id +'-'+ i).attr('title', 'Unvailable').addClass("unavailable emsb-service-date-unavailable").attr('disabled', true).attr('data-toggle', 'tooltip');
            
        }


    }


    
    var monthYearOnCalendar = "";
    var emsbServiceStartingDateGetDateTime = "";

    // Disable Off days
    function disableOffDays(){
    
        var offDays;
        offDays = $("article.em-service.selected .emOffDays").map(function() {
            return this.value;
        }).get();
        
        jQuery.each( offDays, function( i, val ) {
            offDay = $(".em-reservation-calendar tbody.month-days tr").children('td:nth-child('+ val +')').addClass("unavailable off-day").attr('disabled', true).attr('title', 'Weekly Off Day');
            // Will stop running after "7"
            return ( val !== 7 );

        });

    }

    function onIcClickdisableOffDays(){
        var offDays;
        offDays = $("article.em-service.selected .emOffDays").map(function() {
            return this.value;
        }).get();

        jQuery.each( offDays, function( i, val ) {
            offDay = $(".em-reservation-calendar tbody.month-days tr").children('td:nth-child('+ val +')').addClass("unavailable off-day").attr('disabled', true).attr('title', 'Weekly Off Day');
            // Will stop running after "7"
            return ( val !== 7 );
        });
    
        $(".em-reservation-calendar td.ripple-element").on("click", function(){

            var isDisabled = $(this).hasClass("unavailable"); 
            if(!isDisabled){
                $('.date').html(selectedDay + ',  ' + selectedDate + ' ' + selectedMonth + ' ' + selectedYear);
                $(".em-calendar-wrapper").css("display","none");
                $(".em-change-date-btn").slideDown("slow");
                $(".em-selected-date-wrapper").slideDown("slow");

                emsb_selected_service_date_id_for_db = $(this).data("servicedateid");

                var checkSlotEnabled = $("article.em-service.selected #emsb_fullDayReserve").is(":checked");
                if(checkSlotEnabled){
                    fullDayReservationFunc();
                } else {
                    accordionAmPm();
                    amSlotCalculate();
                    pmSlotCalculate();
                }
                

            };
        });
    }

    // Disable passed dates
    function disablePassedDays(){
        // Return today's date and time
        var currentTime = new Date();
        // returns the day of the month (from 1 to 31)
        var currentDate = currentTime.getDate();
        // returns the month (from 0 to 11)
        var currentMonth = currentTime.getMonth() + 1;
        // returns the year (four digits)
        var currentYear = currentTime.getFullYear();
        var activeYearValue = $(".em-reservation-calendar .year-month .month-head div").text();
        activeYearValue = parseInt(activeYearValue, 10);
        var eachActiveYearString = activeYearValue.toString();
    
        var activeMonthValue = $(".em-reservation-calendar .year-body tr td.active").data("month");
        activeMonthValue = parseInt(activeMonthValue, 10);
        var eachActiveMonthString = activeMonthValue.toString();

        var todayDateValue = $(".em-reservation-calendar tbody.month-days tr td.today").data("date");
        var eachDateValue = $(".em-reservation-calendar tbody.month-days tr td").data("date");

        if(currentYear === activeYearValue && currentMonth === activeMonthValue){
            var i = 1;
            for(i=1;i<todayDateValue; i++){
                $(".em-reservation-calendar tbody.month-days tr td[data-date='" + i +"']").addClass("unavailable passed-day").attr('disabled', true).attr('title', 'Date Passed');
            }
        } 


    }

    // Onclick on arrow button
    function onIcClickdisablePassedDays(){
        // Return today's date and time
        var currentTime = new Date();
        // returns the day of the month (from 1 to 31)
        var currentDate = currentTime.getDate();
        // returns the month (from 0 to 11)
        var currentMonth = currentTime.getMonth() + 1;
        // returns the year (four digits)
        var currentYear = currentTime.getFullYear();

        var activeYearValue = $(".em-reservation-calendar .year-month .month-head div").text();
        activeYearValue = parseInt(activeYearValue, 10);
        var eachActiveYearString = activeYearValue.toString();

        var activeMonthValue = $(".em-reservation-calendar .year-body tr td.active").data("month");
        activeMonthValue = parseInt(activeMonthValue, 10);
        var eachActiveMonthString = activeMonthValue.toString();

        var todayDateValue = $(".em-reservation-calendar tbody.month-days tr td.today").data("date");
        var eachDateValue = $(".em-reservation-calendar tbody.month-days tr td").data("date");


        var i = 0;
        if(currentYear == activeYearValue && currentMonth == activeMonthValue){
            for(i=1;i<todayDateValue; i++){
                $(".em-reservation-calendar tbody.month-days tr td[data-date='" + i +"']").addClass("unavailable passed-day").removeClass("available emsb-service-date-available").attr('disabled', true).attr('title', 'Date Passed');
            }
            
        } else if(currentYear == activeYearValue  && currentMonth > activeMonthValue){
            todayDateValue = 31;
            for(i;i<todayDateValue; i++){
                $(".em-reservation-calendar tbody.month-days tr td").addClass("unavailable passed-day").removeClass("available emsb-service-date-available").attr('disabled', true).attr('title', 'Date Passed');
            }
            
        } else if(currentYear > activeYearValue){
            todayDateValue = 31;
            for(i;i<todayDateValue; i++){
                $(".em-reservation-calendar tbody.month-days tr td").addClass("unavailable passed-day").removeClass("available emsb-service-date-available").attr('disabled', true).attr('title', 'Date Passed');
            }
            
        }



        // Availability starts from
        var emsbServiceStartingDate = $("article.em-service.selected .emsb_service_availability_starts_at").val();
        var emsbServiceStartingDateGetDate = new Date(emsbServiceStartingDate);
        var emsbServiceStartingDateGetDateTime = emsbServiceStartingDateGetDate.getTime();
        var makeSmallerStartingDate = emsbServiceStartingDateGetDateTime / 10000;
        // Availability ends at
        var emsbServiceEndingDate = $("article.em-service.selected .emsb_service_availability_ends_at").val();
        var emsbServiceEndingDateGetDate = new Date(emsbServiceEndingDate);
        var emsbServiceEndingDateGetDateTime = emsbServiceEndingDateGetDate.getTime();
        var makeSmallerEndingDate = emsbServiceEndingDateGetDateTime / 10000;

        monthYearOnCalendar = eachActiveYearString +'-'+ eachActiveMonthString;

        for(var i=1;i<=31; i++){
            var datesOnCalendar = monthYearOnCalendar +'-'+ i;
            var datesOnCalendarGetDate = new Date(datesOnCalendar);
            var datesOnCalendarGetDateTime = datesOnCalendarGetDate.getTime();
            var makeSmallerCDate = datesOnCalendarGetDateTime / 10000;
            if(makeSmallerStartingDate <= makeSmallerCDate && makeSmallerCDate <= makeSmallerEndingDate){
                $(".em-reservation-calendar tbody.month-days tr td[data-date='" + i +"']").attr('title', 'Available').removeClass("unavailable emsb-service-date-unavailable").addClass("emsb-service-date-available");
            } 
        }


        


    };

    function enableAvailableDates(){
        var activeYearValue = $(".em-reservation-calendar .year-month .month-head div").text();
        activeYearValue = parseInt(activeYearValue, 10);
        var eachActiveYearString = activeYearValue.toString();

        var activeMonthValue = $(".em-reservation-calendar .year-body tr td.active").data("month");
        activeMonthValue = parseInt(activeMonthValue, 10);
        var eachActiveMonthString = activeMonthValue.toString();
        // Availability starts from
        var emsbServiceStartingDate = $("article.em-service.selected .emsb_service_availability_starts_at").val();
        var emsbServiceStartingDateGetDate = new Date(emsbServiceStartingDate);
        emsbServiceStartingDateGetDateTime = emsbServiceStartingDateGetDate.getTime();
        var makeSmallerStartingDate = emsbServiceStartingDateGetDateTime / 10000;
        // Availability ends at
        var emsbServiceEndingDate = $("article.em-service.selected .emsb_service_availability_ends_at").val();
        var emsbServiceEndingDateGetDate = new Date(emsbServiceEndingDate);
        var emsbServiceEndingDateGetDateTime = emsbServiceEndingDateGetDate.getTime();
        var makeSmallerEndingDate = emsbServiceEndingDateGetDateTime / 10000;

        monthYearOnCalendar = eachActiveYearString +'-'+ eachActiveMonthString;
        for(var i=1;i<=31; i++){
            var datesOnCalendar = monthYearOnCalendar +'-'+ i;
            var datesOnCalendarGetDate = new Date(datesOnCalendar);
            var datesOnCalendarGetDateTime = datesOnCalendarGetDate.getTime();
            var makeSmallerCDate = datesOnCalendarGetDateTime / 10000;
            if(makeSmallerStartingDate <= makeSmallerCDate && makeSmallerCDate <= makeSmallerEndingDate){
                $(".em-reservation-calendar tbody.month-days tr td[data-date='" + i +"']").attr('title', 'Available').removeClass("unavailable emsb-service-date-unavailable").addClass("emsb-service-date-available"); 
            }    
        }
    }


    // Disable Off days Ends

    // Disable Booked dates (Only for the full day reservation services)
    function disableAleadyBookedDates(){
        
        $(".emsb-calender-loading-gif").css("display","flex");
        
        // check date availabilty with ajax
        var emsb_create_nonce = $("#emsb-create-nonce").val();
        var emsb_selected_service_id = $("article.em-service.selected #emsb-service-id input").val();
        var data = {
            'action': 'emsb_booked_dates',
            'security': emsb_create_nonce,
            'check_availability_of_date': emsb_selected_service_id
        };
       // Disable the already booked dates with ajax
        var bookedDates;
        $.ajax({
            type: 'POST',
            url: frontend_ajax_object.ajaxurl,
            data: data,
            dataType:"json",
            success: function(response) {
                bookedDates = response;
                $.each(response, function(index, slot) {
                    var isOffDay = $(".em-reservation-calendar tbody.month-days tr td[data-servicedateid='" + slot.booked_date_id +"']").hasClass("off-day"); 
                    var isDatePassed = $(".em-reservation-calendar tbody.month-days tr td[data-servicedateid='" + slot.booked_date_id +"']").hasClass("passed-day"); 
                    var isunavailable = $(".em-reservation-calendar tbody.month-days tr td[data-servicedateid='" + slot.booked_date_id +"']").hasClass("unavailable"); 
                    
                    if(!isOffDay && !isDatePassed && !isunavailable){ 
                        if(slot.available_orders == 0){
                            $(".em-reservation-calendar tbody.month-days tr td[data-servicedateid='" + slot.booked_date_id +"']").addClass("unavailable already-booked").attr('title', 'Already Booked');
                        } else {                      
                            $(".em-reservation-calendar tbody.month-days tr td[data-servicedateid='" + slot.booked_date_id +"']").attr('title', 'Available '+ slot.available_orders);
                        }
                    }
                    
                });
                
                $(".emsb-calender-loading-gif").fadeOut(1000);

                
            }

        });

    }

    // Ends Disable Already booked date slots //
    

    // Date selection
    $(".em-reservation-calendar td.ripple-element").on("click", function(){
        
        var isDisabled = $(this).hasClass("unavailable"); 

        if(!isDisabled){

            emsb_selected_service_date_id_for_db = $(this).data("servicedateid");

            $('.date').html(selectedDay + ',  ' + selectedDate + ' ' + selectedMonth + ' ' + selectedYear);
            $(".em-calendar-wrapper").css("display","none");
            $(".em-change-date-btn").slideDown("slow");
            $(".em-selected-date-wrapper").slideDown("slow");

            var checkSlotEnabled = $("article.em-service.selected #emsb_fullDayReserve").is(":checked");
            if(checkSlotEnabled){
                fullDayReservationFunc();
            } else {
                accordionAmPm();
                amSlotCalculate();
                pmSlotCalculate();
            }
               
        };

    });

    



    // Change Date
    $('.em-change-date-btn').on('click', function(){
        $(".em-timer").css("display","none");
        $(".em-booking-form-container").slideUp("slow");
        $(".em-calendar-wrapper").slideDown(800);
    });


    //***********************************************/
    // Create dynamic time slot for AM
    //***********************************************/

    // Slide show the Slot accordion
    function accordionAmPm(){
        
        $(".em-timer").slideDown(800);
        $('#amButton').on('click', function(){
            $(this).addClass('active');
            $('#pmButton').removeClass('active');
            $('#emShowPM').slideUp();
            $('#emShowAM').slideDown(); 
        });
    
        $('#pmButton').on('click', function(){
            $(this).addClass('active');
            $('#amButton').removeClass('active');
            $('#emShowAM').slideUp();
            $('#emShowPM').slideDown();
        });
    }
    

    function amSlotCalculate() {
        
        // Get Selected Service Slot Duration
        var amTimeSlotSelected = parseInt($("article.em-service.selected .am-time-slot .amSlotDuration").val(), 10);
        var amTimeSlot = amTimeSlotSelected;
        // Get Selected Service Start and End Time (AM)
        var amTimeOne = $("article.em-service.selected .amSlotStarts").val().split(':'), amTimeTwo = $("article.em-service.selected .amSlotEnds").val().split(':');
        var amHoursOne = parseInt(amTimeOne[0], 10), 
            amHoursTwo = parseInt(amTimeTwo[0], 10),
            amMinsOne = parseInt(amTimeOne[1], 10),
            amMinsTwo = parseInt(amTimeTwo[1], 10);

        var amTotalHours = amHoursTwo - amHoursOne, amMinsDiffers = 0;

        // get hours
        if(amTotalHours < 0) amTotalHours = 24 + amTotalHours;
        // get minutes
        if(amMinsTwo >= amMinsOne) {
            amMinsDiffers = amMinsTwo - amMinsOne;

        } else {
            amMinsDiffers = (amMinsTwo + 60) - amMinsOne;
            amTotalHours--;
        }
        // Convert Available Hours to mins
        var amHoursToMins = amTotalHours*60;
        var totalMinsOfAM = amHoursToMins+amMinsDiffers;
        // Count Number of Slots from available hours
        var amTimeSlotNumber = totalMinsOfAM / amTimeSlot;
        amTimeSlotNumber = Math.floor(amTimeSlotNumber);

        // Create starting Total Mins from provided starting time by the admin ( amHoursOne, amMinsDiffers )
        var amStartingHourToMins = amHoursOne*60 + amMinsOne - amTimeSlot;
        // Create Ending Total Mins from provided starting time by the admin ( amHoursOne, amMinsDiffers )
        var amEndingHourToMins = amHoursOne*60 + amMinsOne;
        // Create variable for dynamically creating the Slots
        var amStartingHour;
        var amStartingMins;
        var amEndingHour;
        var amEndingMins;
        // Make empty before appending the slots
        var amSlot = $('#emShowAM').empty();
        
        // Start for loop for SlotNumber times
        for(var i=1; i<=amTimeSlotNumber; ++i){
            amStartingHourToMins += amTimeSlot;
            amStartingHour = Math.floor(amStartingHourToMins / 60);
            amStartingMins = amStartingHourToMins % 60;
            if(amStartingMins > 60 ) {
                amStartingMins = amStartingMins - 60;
                amStartingHour = amStartingHour + 1;
            }
            var amShowStartingHour = amStartingHour;
            if(amShowStartingHour == "0" ) {
                amShowStartingHour = amShowStartingHour + 12;
            }

            // Format the time lass than 9 ( Make 1 to 01, 9 to 09)
            var amShowStartingMins = ("0" + amStartingMins).slice(-2);
            amEndingHourToMins += amTimeSlot;
            amEndingHour = Math.floor(amEndingHourToMins / 60);
            amEndingMins = amEndingHourToMins % 60;
            if(amEndingMins > 60 ) {
                amEndingMins = amEndingMins - 60;
                amEndingHour = amEndingHour + 1;
            }
            var showAmEndingHour = amEndingHour;
            // Format the time lass than 9 ( Make 1 to 01, 9 to 09)
            var showAmEndingMins = ("0" + amEndingMins).slice(-2);
            if(showAmEndingMins == "00" ) {
                showAmEndingMins = 59;
                showAmEndingHour = showAmEndingHour - 1;
            }

            if(showAmEndingHour == "0" ) {
                showAmEndingHour = showAmEndingHour + 12;
            }

            var emsb_orders_per_slot = $("article.em-service.selected .emsb-service-booking-orders-per-slot input").val();

            // Slot Id
            var emsb_selected_service_date_id_for_creating_slot_id = emsb_selected_service_date_id_for_db;
            var amSlotId = i + "-AM-"+ emsb_selected_service_date_id_for_creating_slot_id;
            amSlot = $('#emShowAM').append('<li class="list-group-item" data-slotId="'+ amSlotId +'"> <label> <input class="d-none emsb-slotId" type="text" value="'+ amSlotId +'"> <input class="d-none emsb-slot-starts-at" type="text" value="'+ amShowStartingHour +':'+ amShowStartingMins +'"> '+ amShowStartingHour +':'+ amShowStartingMins +' AM - '+ showAmEndingHour +':'+ showAmEndingMins +' AM  </label> <button type="button" class="btn btn-light em-select-slot-button available" data-toggle="tooltip" title="Available '+ emsb_orders_per_slot +'"> Available </button>  </li>');
            
        };


    }
    // Create dynamic time slot for AM Ends 

    //***********************************************/ 
    // Create dynamic time slot for PM starts
    //***********************************************/ 

    function pmSlotCalculate() {
        
            // Get Selected Service Slot Duration
        var pmTimeSlotSelected = parseInt($("article.em-service.selected .pm-time-slot .pmSlotDuration").val(), 10);
        var pmTimeSlot = pmTimeSlotSelected;

        // Get Selected Service Start and End Time (AM)
        var pmTimeOne = $("article.em-service.selected .pmSlotStarts").val().split(':'), pmTimeTwo = $("article.em-service.selected .pmSlotEnds").val().split(':');
        var pmHoursOne = parseInt(pmTimeOne[0], 10), 
            pmHoursTwo = parseInt(pmTimeTwo[0], 10),
            pmMinsOne = parseInt(pmTimeOne[1], 10),
            pmMinsTwo = parseInt(pmTimeTwo[1], 10);

        var pmTotalHours = pmHoursTwo - pmHoursOne, pmMinsDiffers = 0;
        // get hours
        if(pmTotalHours < 0) pmTotalHours = 24 + pmTotalHours;
        // get minutes
        if(pmMinsTwo >= pmMinsOne) {
            pmMinsDiffers = pmMinsTwo - pmMinsOne;
        } else {
            pmMinsDiffers = (pmMinsTwo + 60) - pmMinsOne;
            pmTotalHours--;
        }
        // Convert Available Hours to mins
        var pmHoursToMins = pmTotalHours*60;
        var totalMinsOfPM = pmHoursToMins+pmMinsDiffers;
        // Count Number of Slots from available hours
        var pmTimeSlotNumber = totalMinsOfPM / pmTimeSlot;
        pmTimeSlotNumber = Math.floor(pmTimeSlotNumber);
        // Create starting Total Mins from provided starting time by the admin ( pmHoursOne, pmMinsOne )
        var pmStartingHourToMins = pmHoursOne*60 + pmMinsOne - pmTimeSlot;
        // Create Ending Total Mins from provided starting time by the admin ( pmHoursOne, pmMinsOne )
        var pmEndingHourToMins = pmHoursOne*60 + pmMinsOne;
        // Create variable for dynamically creating the Slots
        var pmStartingHour;
        var pmStartingMins;
        var pmEndingHour;
        var pmEndingMins;
        // Make empty before appending the slots
        var pmSlot = $('#emShowPM').empty();

        // Start for loop for SlotNumber times
        for(var i=1; i<=pmTimeSlotNumber; ++i){

            pmStartingHourToMins += pmTimeSlot;
            pmStartingHour = Math.floor(pmStartingHourToMins / 60);
            pmStartingMins = pmStartingHourToMins % 60;
            if(pmStartingMins > 60 ) {
                pmStartingMins = pmStartingMins - 60;
                pmStartingHour = pmStartingHour + 1;
            }

            var pmShowStartingHour = pmStartingHour;

            if(pmShowStartingHour == "0" ) {
                pmShowStartingHour = pmShowStartingHour + 12;
            }

            if(pmShowStartingHour > 12 ) {
                pmShowStartingHour = pmShowStartingHour - 12;
            }

            // Format the time lass than 9 ( Make 1 to 01, 9 to 09)
            var pmShowStartingMins = ("0" + pmStartingMins).slice(-2);
            pmEndingHourToMins += pmTimeSlot;
            pmEndingHour = Math.floor(pmEndingHourToMins / 60);
            pmEndingMins = pmEndingHourToMins % 60;
            if(pmEndingMins > 60 ) {
                pmEndingMins = pmEndingMins - 60;
                pmEndingHour = pmEndingHour + 1;
            }


            var showPmEndingHour = pmEndingHour;

            // Format the time lass than 9 ( Make 1 to 01, 9 to 09)
            var showPmEndingMins = ("0" + pmEndingMins).slice(-2);

            if(showPmEndingMins == "00" ) {
                showPmEndingMins = 59;
                showPmEndingHour = showPmEndingHour - 1;
            }

            if(showPmEndingHour == "0" ) {
                showPmEndingHour = showPmEndingHour + 12;
            }

            if(showPmEndingHour > 12 ) {
                showPmEndingHour = showPmEndingHour - 12;
            }

            var emsb_orders_per_slot = $("article.em-service.selected .emsb-service-booking-orders-per-slot input").val();

            // Slot Id ( emsb_selected_service_date_id_for_db is a global variable )
            var emsb_selected_service_date_id_for_creating_slot_id = emsb_selected_service_date_id_for_db;
            var pmSlotId = i + "-PM-"+ emsb_selected_service_date_id_for_creating_slot_id;
            var pmSlotStartingTimeInt = parseInt(pmShowStartingHour);
            var pmSlotStartingTimeHour = 12 + pmSlotStartingTimeInt;
            pmSlot = $('#emShowPM').append('<li class="list-group-item" data-slotId="'+ pmSlotId +'"> <label> <input class="d-none emsb-slotId" type="text" value="'+ pmSlotId +'"> <input class="d-none emsb-slot-starts-at" type="text" value="'+ pmSlotStartingTimeHour +':'+ pmShowStartingMins +'">  '+ pmShowStartingHour +':'+ pmShowStartingMins +' PM - '+ showPmEndingHour +':'+ showPmEndingMins +' PM </label> <button type="button" class="btn btn-light em-select-slot-button available" data-toggle="tooltip" title="Available '+ emsb_orders_per_slot +'"> Available </button>  </li>'); 
            
        };

        check_availability_of_Slot();

    }

    function check_availability_of_Slot(){
        var emsb_orders_per_slot = $("article.em-service.selected .emsb-service-booking-orders-per-slot input").val();
        var emsb_orders_per_slot_to_int = parseInt(emsb_orders_per_slot);
        /// check slot availabilty with ajax
        $(".emsb-loading-gif").css("display","flex");
        var emsb_create_nonce = $("#emsb-create-nonce").val();
        var emsb_selected_date_id_for_slot_checking_from_db = emsb_selected_service_date_id_for_db;
        var emsb_slot_action_data = {
            'action': 'emsb_booked_slot',
            'security': emsb_create_nonce,
            'check_slots_availability': emsb_selected_date_id_for_slot_checking_from_db
        };
        $.ajax({
            type: 'POST',
            url: frontend_ajax_object.ajaxurl,
            data: emsb_slot_action_data,
            dataType:"json",
            success: function(emsb_slot_response) {

                $.each(emsb_slot_response, function(index, slot) {

                    var availableOrdersInString = slot.available_orders;
                    var availableOrdersInInt = parseInt(availableOrdersInString);
                    
                    if(availableOrdersInInt <= 0){
                        $(".slots li[data-slotid='" + slot.booked_slot_id +"'] button").addClass("booked").removeClass("available").attr('title', 'Already Booked').text("Booked");
                    } else {
                        $(".slots li[data-slotid='" + slot.booked_slot_id +"'] button").text("Availabel").attr('title', "Availabel: "+ slot.available_orders);
                    }
                    
                });

                $(".emsb-loading-gif").fadeOut(1000);

                SlotSelection();
                
            }

          });

    }

    // Create dynamic time slot for PM Ends

    // After selecting the full day reservation run this function
    function fullDayReservationFunc() { 
            

            $(".em-timer").slideUp(800);
            $(".em-booking-form-container").slideDown("slow");
            $("#emsb_user_fullName").focus();

            // Cookie Decoded by getCookie function written below
            var emsb_user_cookie_FullName = getCookie("emsb_user_fullName");
            var emsb_user_cookie_Email = getCookie("emsb_user_email");
            var emsb_user_cookie_telephone = getCookie("emsb_user_telephone");


            // Fill the form fields with cookies
            $("#emsb_user_fullName").val(emsb_user_cookie_FullName);
            $("#emsb_user_email").val(emsb_user_cookie_Email);
            $("#emsb_user_telephone").val(emsb_user_cookie_telephone);


            // Hidden Form Fields
            var emsb_selected_service_id = $("article.em-service.selected #emsb-service-id input").val();
            var emsb_selected_service = $(".em-selected-service #emsb-service-name").text();
            var emsb_selected_service_title = $(".em-selected-service .emsb-service-title").text();
            var emsb_selected_service_location = $(".em-selected-service .emsb-service-location").text();
            var emsb_admin_email = $("article.em-service.selected .emsb-service-provider-email input").val();
            var emsb_booked_date = $(".em-selected-date label").text();
            var emsb_booked_service_price = $(".em-selected-service #emsb-service-price").text();
            var emsb_selected_slot_id = emsb_selected_service_date_id_for_db + "-Full-Day";
            var emsb_booked_time_slot = "Day Reservation";

            var emsb_slot_starts_at_for_db = selectedDateMonthYearTimestamp;
            var emsb_slot_starts_at_for_db_GetDate = new Date(emsb_slot_starts_at_for_db);
            var emsb_slot_starts_at_for_db_GetDateTime = emsb_slot_starts_at_for_db_GetDate.getTime();

            var emsb_orders_per_slot = $("article.em-service.selected .emsb-service-booking-orders-per-slot input").val();

            $("#emsb_selected_service_id").val(emsb_selected_service_id);
            $("#emsb_selected_service").val(emsb_selected_service);
            $("#emsb_selected_service_title").val(emsb_selected_service_title);
            $("#emsb_selected_service_location").val(emsb_selected_service_location);
            $("#emsb_selected_service_provider_email").val(emsb_admin_email);
            $("#emsb_selected_service_date").val(emsb_booked_date);
            $("#emsb_selected_service_price").val(emsb_booked_service_price);
            $("#emsb_selected_service_date_id").val(emsb_selected_service_date_id_for_db);
            $("#emsb_selected_slot_id").val(emsb_selected_slot_id);
            $("#emsb_selected_time_slot").val(emsb_booked_time_slot);
            $("#emsb_booking_slot_starts_at").val(emsb_slot_starts_at_for_db_GetDateTime);
            $("#emsb_service_orders_per_slot").val(emsb_orders_per_slot);


    }


    // Select Time slot
    function SlotSelection() { 
        
        $(".em-select-slot-button.available").on("click", function(){
            
            var selectedSlot = $(this).siblings().html();
            $('.time-slot').html(selectedSlot);
            $(".em-change-time-slot-btn").slideDown("slow");
            $(".em-timer").slideUp(800);
            $(".em-booking-form-container").slideDown("slow");
            $(".em-selected-time-slot-wrapper").slideDown("slow");

            $("#emsb_user_fullName").focus();

            // Cookie Decoded by getCookie function written below
            var emsb_user_cookie_FullName = getCookie("emsb_user_fullName");
            var emsb_user_cookie_Email = getCookie("emsb_user_email");
            var emsb_user_cookie_telephone = getCookie("emsb_user_telephone");

            // Fill the form fields with cookies
            $("#emsb_user_fullName").val(emsb_user_cookie_FullName);
            $("#emsb_user_email").val(emsb_user_cookie_Email);
            $("#emsb_user_telephone").val(emsb_user_cookie_telephone);

            // Hidden Form Fields
            var emsb_selected_service_id = $("article.em-service.selected #emsb-service-id input").val();
            var emsb_selected_service = $(".em-selected-service #emsb-service-name").text();
            var emsb_selected_service_title = $(".em-selected-service .emsb-service-title").text();
            var emsb_selected_service_location = $(".em-selected-service .emsb-service-location").text();
            var emsb_admin_email = $("article.em-service.selected .emsb-service-provider-email input").val();
            var emsb_booked_date = $(".em-selected-date label").text();
            var emsb_booked_time_slot = $(".em-selected-time-slot label").text();
            var emsb_booked_service_price = $(".em-selected-service #emsb-service-price").text();
            var emsb_selected_slot_id = $(".em-selected-time-slot label.time-slot input.emsb-slotId").val();

            var emsb_slot_starts_at = $(".em-selected-time-slot label.time-slot input.emsb-slot-starts-at").val();
            var emsb_slot_starts_at_for_db = selectedDateMonthYearTimestamp +' '+ emsb_slot_starts_at;
            var emsb_slot_starts_at_for_db_GetDate = new Date(emsb_slot_starts_at_for_db);
            var emsb_slot_starts_at_for_db_GetDateTime = emsb_slot_starts_at_for_db_GetDate.getTime();

            var emsb_orders_per_slot = $("article.em-service.selected .emsb-service-booking-orders-per-slot input").val();

            $("#emsb_selected_service_id").val(emsb_selected_service_id);
            $("#emsb_selected_service").val(emsb_selected_service);
            $("#emsb_selected_service_title").val(emsb_selected_service_title);
            $("#emsb_selected_service_location").val(emsb_selected_service_location);
            $("#emsb_selected_service_provider_email").val(emsb_admin_email);
            $("#emsb_selected_service_date").val(emsb_booked_date);
            $("#emsb_selected_time_slot").val(emsb_booked_time_slot);
            $("#emsb_selected_service_price").val(emsb_booked_service_price);
            $("#emsb_selected_slot_id").val(emsb_selected_slot_id);
            $("#emsb_selected_service_date_id").val(emsb_selected_service_date_id_for_db);
            $("#emsb_booking_slot_starts_at").val(emsb_slot_starts_at_for_db_GetDateTime);
            $("#emsb_service_orders_per_slot").val(emsb_orders_per_slot);

            changeTimeSlot();

            

        });


    }


    // Change Time slot
    function changeTimeSlot(){

        $(".em-change-time-slot-btn").on("click", function(){
            $(".em-booking-form-container").slideUp("slow");
            $(".em-calendar-wrapper").slideUp(800);
            $(".em-timer").slideDown(800);
        });
    }

    // Browser Cookie ( On form submission set the browser cookies )
    $("#submitForm").on("click", function(){
        checkCookie();
    });

    function setCookie(cname,cvalue,exdays) {    
        var now = new Date();
        var time = now.getTime();
        var expireTime = time + 1000*3600*24*exdays;
        now.setTime(expireTime);
        var expires = "expires=" + now.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    function getCookie(cname) {
        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
        }
        return "";
    }
        

    function checkCookie() {
        var emsb_user_fullName= $("#emsb_user_fullName").val();
        var emsb_user_email = $("#emsb_user_email").val();
        var emsb_user_telephone = $("#emsb_user_telephone").val();
        var emsbCookieDuration = $("#emsb_cookie_duration").val();

        if (emsb_user_fullName!= "" && emsb_user_fullName!= null) {
            setCookie("emsb_user_fullName", emsb_user_fullName, emsbCookieDuration);
        }
        if (emsb_user_email != "" && emsb_user_email != null) {
            setCookie("emsb_user_email", emsb_user_email, emsbCookieDuration);
        }
        if (emsb_user_telephone != "" && emsb_user_telephone != null) {
            setCookie("emsb_user_telephone", emsb_user_telephone, emsbCookieDuration);
        }
        
    }
    // Browser Cookie ( On form submission set the browser cookies ) Ends

    (function() {
        
        // Disabling form submissions if there are invalid fields
        window.addEventListener('load', function() {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName('needs-validation');
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
            });
        }, false);
  
    })();


    // Create PDF of Booking Ticket
    $("#createPDF").on("click", function(){
        
        var pdf = new jsPDF('p', 'pt', 'letter');
        var source = $("#emsb_booking_ticket")[0];
        pdf.addHTML(
            source, 
            function (dispose) {
                pdf.save('Ticket.pdf');
            }
        );

    });

    $("#goBackButton").on("click", function(){
        history.back();
    });


    $(function () {
		"use strict";
		var configChosen = {
		  '.chosen-select'           : {},
		  '.chosen-select-deselect'  : {allow_single_deselect:true},
		  '.chosen-select-no-single' : {disable_search_threshold:10},
		  '.chosen-select-no-results': {no_results_text:'Nothing Found'},
		  '.chosen-select-width'     : {width:"100%"}
		}
		for (var selector in configChosen) {
		  $(selector).chosen(configChosen[selector]);
		}
	});
    
    
});

// Ends document.ready function