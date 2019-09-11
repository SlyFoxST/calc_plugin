
$(document).ready(function () {
    
    $(".content-form-select_first").select2({
        placeholder : "יעד עיקרי"
    });
    $(".content-form-select_second").select2({
        placeholder : "בחר גיל מבוטח"
    });
    var dateToday = new Date();
    ( function( factory ) {
        if ( typeof define === "function" && define.amd ) {

            // AMD. Register as an anonymous module.
            define( [ "../widgets/datepicker" ], factory );
        } else {

            // Browser globals
            factory( jQuery.datepicker );
        }
    }( function( datepicker ) {

        datepicker.regional.he = {
            closeText: "סגור",
            prevText: "&#x3C;הקודם",
            nextText: "הבא&#x3E;",
            currentText: "היום",
            monthNames: [ "ינואר","פברואר","מרץ","אפריל","מאי","יוני",
                "יולי","אוגוסט","ספטמבר","אוקטובר","נובמבר","דצמבר" ],
            monthNamesShort: [ "ינו","פבר","מרץ","אפר","מאי","יוני",
                "יולי","אוג","ספט","אוק","נוב","דצמ" ],
            dayNames: [ "ראשון","שני","שלישי","רביעי","חמישי","שישי","שבת" ],
            dayNamesShort: [ "א'","ב'","ג'","ד'","ה'","ו'","שבת" ],
            dayNamesMin: [ "א'","ב'","ג'","ד'","ה'","ו'","שבת" ],
            weekHeader: "Wk",
            dateFormat: "dd/mm/yy",
            firstDay: 0,
            isRTL: true,
            showMonthAfterYear: false,
            minDate: dateToday,
            yearSuffix: "" };
        datepicker.setDefaults( datepicker.regional.he );

        return datepicker.regional.he;

    } ) );
    var date_input = $('.content-form-date, .checkbox-popup-date');
    var options = {
        format: 'dd/mm/yyyy',
        todayHighlight: true,
        autoclose: true,
        language: 'he'
    };
    date_input.datepicker(options);
    $('.content-block_first').submit(function(e){
        e.preventDefault();
        $('.main').scrollTop(0);
        $('.content-block_second').addClass('active').siblings().removeClass('active');
        checkHeight();
    });
    /*$('.content-block_second').submit(function(e){
        e.preventDefault();
        $('.main').scrollTop(0);
        $('.content-block_third').addClass('active').siblings().removeClass('active');
        checkHeight();
    });*/

    function checkHeight(){
        var currentHeight = $('.content-block.active').outerHeight();
        $('.content').height(currentHeight);
    }
    checkHeight();
    $('.content-form-edit-add').click(function(e){
        e.preventDefault();
        if($('.content-form-add select:valid').length == $('.content-form-add select').length) {
            $('.hide .content-form-row').eq(0).clone().appendTo('.content-form-add');
            $(".content-form-add .content-form-select_third").select2({
                placeholder : "בחר גיל מבוטח"
            });
            checkHeight();
        }
    });
    $('.content-form-edit-delete').click(function(e){
        e.preventDefault();
        var totalLength = $('.content-form-add .content-form-row').length;
        if(totalLength > 1) {
            $('.content-form-add .content-form-row').eq(totalLength - 1).remove();
        }

    });
    /*$('.content-form-flex .label').click(function(e){
        e.stopPropagation();
       $('.checkbox-popup').addClass('active');
        checkFlex();
    });*/

    $('.option-input').change(function(e){
        if($(this).prop('checked')) {
            e.stopPropagation();
            $('.checkbox-popup').addClass('active');
            $('#option-to-person').find('[name="option-to-person-id"]').val($(this).data('option_id'));
            checkFlex();
        }
    });

    $('.checkbox-popup-close').click(function(e){
        e.preventDefault();
        $(this).closest('.popup').removeClass('active');
    });
    $('.js-popup').click(function(e){
        e.preventDefault();
        var currentImg = $(this).data('src');
        $('.popup-img-img').attr('src', currentImg);
        $('.popup-img').addClass('active');
        setTimeout(function(){
            checkFlex();
        })

    });
    $('.js-text').click(function(e){
        e.preventDefault();
        e.stopPropagation();
        var currentText = $(this).data('text');
        $('.popup-img-text').html(currentText);
        $('.popup-text').addClass('active');
        setTimeout(function(){
            checkFlex();
        })
    });
    $('.page-right-form').submit(function(e){
        e.preventDefault();
        $('.thank-text').show();
        checkHeight();
    });
    $('.content-form-btn_back').click(function(){
        $('.content-block_second').addClass('active').siblings().removeClass('active');
        checkHeight();
    });
});
function checkFlex() {
    var flexHeight = $('.popup.active').find('.flex-height').outerHeight() + 40;
    console.log(flexHeight);
    console.log($(window).height());
    if (flexHeight > $(window).height()) {
        $('.popup.active').addClass('flex-start')
    }
    else {
        $('.popup.active').removeClass('flex-start')
    }
}
$(window).resize(function(){
    checkFlex()
});