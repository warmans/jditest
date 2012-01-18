$(document).ready(function(){
    
    /*validation*/
    $(".content-panel form").validate();
    
    /*ui widgets*/
    $(".datepicker").datepicker({ dateFormat: 'yy-mm-dd 00:00:00' });
});