$(document).ready(function(){
    $('.datetime').datetimepicker({
        viewMode: 'years',
        format: 'MMMM YYYY',
        locale: 'es',
        icons: icons
    }).parent().css('position: relative');
});

var icons = {
    time: 'fa fa-time',
    date: 'fa fa-calendar',
    up: 'fa fa-chevron-up',
    down: 'fa fa-chevron-down',
    previous: 'fa fa-chevron-left',
    next: 'fa fa-chevron-right',
    today: 'fa fa-screenshot',
    clear: 'fa fa-trash',
    close: 'fa fa-remove'
}
