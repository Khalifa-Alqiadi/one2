(function ($) {
  "use strict";

    $(document).on('click', '#dayview', function() {
        calendar.changeView('agendaDay')
    });

    $(document).on('click', '#weekview', function() {
        calendar.changeView( 'agendaWeek')
    });

    $(document).on('click', '#monthview', function() {
        calendar.changeView( 'month')
    });

    $(document).on('click', '#todayview', function() {
        calendar.changeView('today')
    });

})(jQuery);
