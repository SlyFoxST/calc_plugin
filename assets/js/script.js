$(document).ready(function(){
    var x = $('.days_txt').val();
    console.log(x);
    var checkHeight = function(){
        var currentHeight = $('.content-block.active').outerHeight();
        $('.content').height(currentHeight);
    };

    var loader = {
        show: function(){
            $('#loader').show();
        },
        hide: function(){
            setTimeout(function(){
                $('#loader').hide();
            }, 400);
        }
    };

    var TIForm = {
        region: '',
        date_from: '',
        date_to: '',
        days: 0,

        passenger_age: [],
        option_to_person: {}
    };


    $('[name=date_from], [name=date_to], [name=date_from_txt], [name=date_to_txt]').change(function(){

        var from_inp = $('[name=date_from]');
        var to_inp = $('[name=date_to]');
        var days_txt_inp = $('.days_txt');
        var days_inp = $('[name="days"]');


        if($(this).attr('name') == 'date_from_txt' || $(this).attr('name') == 'date_to_txt') {
            from_inp.val($('[name="date_from_txt"]').val());
            to_inp.val($('[name="date_to_txt"]').val());
        }


        if(from_inp.val() && to_inp.val()){
            var from = from_inp.val().split('/');
            var to = to_inp.val().split('/');

            from[1] = parseInt(from[1]) - 1;
            from = new Date(from[2], from[1], from[0]);
            from = from.getTime();

            to[1] = parseInt(to[1]) - 1;
            to = new Date(to[2], to[1], to[0]);
            to = to.getTime();

            var days = 0;

            if(to >= from){
                 days = (to-from)/1000/3600/24+1;
                 $('.date_from_txt').html(from_inp.val());
                 $('.date_to_txt').html(to_inp.val());

                 if($(this).attr('name') != 'date_from_txt' && $(this).attr('name') != 'date_to_txt') {
                     $('[name="date_from_txt"]').val(from_inp.val());
                     $('[name="date_to_txt"]').val(to_inp.val());
                 }
                 else{
                     from_inp.val($('[name="date_from_txt"]').val());
                     to_inp.val($('[name="date_to_txt"]').val());

                 }
            }
            else{
                to_inp.val(null);
                days = 0;
            }



            days_txt_inp.html(days);
            days_inp.val(days);



            var f = $('[name="tifrom-1"]');
            $.each(f.serializeArray(), function (i,v){
                TIForm[v.name] = v.value;
            });
            $('.region_txt').html(TIForm.region);
            $('#personal_date_from').val(TIForm.date_from);
            $('#personal_date_to').val(TIForm.date_to);
        }

    });



    $('[name="tifrom-1"]').submit(function(){
        var f = $(this);
        $.each(f.serializeArray(), function (i,v){
           TIForm[v.name] = v.value;
        });

        $('.region_txt').html(TIForm.region);

        $('#personal_date_from').val(TIForm.date_from);
        $('#personal_date_to').val(TIForm.date_to);

    });

    $('[name="tifrom-2"]').submit(function(e){


        e.preventDefault();

        var f = $(this);
        $.each(f.serializeArray(), function(i,v){
            TIForm['passenger_age'].push(v.value);
        });

        $('.passengers_txt').html(TIForm['passenger_age'].length);


        var o2p = $('#option-to-person');
        o2p.find('div.checkbox-popup-flex').html(null);
        for(var $i=0; $i < TIForm['passenger_age'].length; $i++){
            o2p.find('div.checkbox-popup-flex').append('<label class="label label_sm">'
                +'<input type="checkbox" value="'+TIForm['passenger_age'][$i]+'" class="label-input option-to-person-inp" '+(TIForm['passenger_age'].length === 1 ? 'checked' : '')+' >'
                +'<span class="label-span"></span>'
                +'<span class="label-text">'+$age_id2name[TIForm['passenger_age'][$i]]+'</span>'
                +'</label>');
        }
        if(TIForm['passenger_age'].length > 1){
            o2p.show();
        }
        else{
            o2p.hide();
        }


        $.ajax({
            type: "POST",
            url: "/wp-admin/admin-ajax.php",
            dataType: "json",
            data: {
                action: 'yf_tif_get_results',
                form: TIForm
            },
            beforeSend: function () {
                loader.show();
            },
            complete: function () {
                loader.hide();
            },
            success: function (json) {
                $.each(json['results'], function(i,v){
                    $('#company-'+v['id']+'-price').html(v['price']+v['currency']);
                });


                $('.main').scrollTop(0);
                $('.content-block_third').addClass('active').siblings().removeClass('active');
                checkHeight();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });

    });



    $('#confirm-option-btn').click(function(e){
        e.preventDefault();

        var o2p = $('#option-to-person');

        if(TIForm['passenger_age'].length > 1){
            if(o2p.find('input:checked').length > 0){
                $(this).closest('.popup').removeClass('active');
            }
        }
        else if(TIForm['passenger_age'].length === 1){
            o2p.find('input').prop('checked', true);
            $(this).closest('.popup').removeClass('active');
        }


        if(o2p.find('input:checked').length > 0) {

            var from_inp = $('#personal_date_from');
            var to_inp = $('#personal_date_to');


            var from = from_inp.val().split('/');
            var to = to_inp.val().split('/');

            from[1] = parseInt(from[1]) - 1;
            from = new Date(from[2], from[1], from[0]);
            from = from.getTime();

            to[1] = parseInt(to[1]) - 1;
            to = new Date(to[2], to[1], to[0]);
            to = to.getTime();

            var days = 0;

            if(to >= from){
                days = (to-from)/1000/3600/24+1;
            }
            else{
                to_inp.val(null);
                days = 0;
            }

            TIForm['option_to_person'][o2p.find('[name="option-to-person-id"]').val()] = {
                option_id: o2p.find('[name="option-to-person-id"]').val(),
                age: [],
                days: days,
                date_from: from_inp.val(),
                date_to: to_inp.val()
            };
            o2p.find('input:checked').each(function () {
                TIForm['option_to_person'][o2p.find('[name="option-to-person-id"]').val()]['age'].push($(this).val());
            });

            $.ajax({
                type: "POST",
                url: "/wp-admin/admin-ajax.php",
                dataType: "json",
                data: {
                    action: 'yf_tif_get_results',
                    form: TIForm
                },
                beforeSend: function () {
                    loader.show();
                },
                complete: function () {
                    loader.hide();
                },
                success: function (json) {
                    $.each(json['results'], function(i,v){
                        $('#company-'+v['id']+'-price').html(v['price']+v['currency']);
                    });
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });

        }


    });

    $('.option-input').change(function(){

        var o2p = $('#option-to-person');
        var from_inp = $('#personal_date_from');
        var to_inp = $('#personal_date_to');

        if($(this).prop('checked')){
            if(TIForm['option_to_person'][$(this).data('option_id')]){
                from_inp.val(TIForm['option_to_person'][$(this).data('option_id')]['date_from']);
                to_inp.val(TIForm['option_to_person'][$(this).data('option_id')]['date_to']);
                $.each(TIForm['option_to_person'][$(this).data('option_id')]['date_to']['age'], function(i,v){
                    o2p.find('input[value="'+v+'"]:not(:checked)').eq(0).prop('checked', true);
                });
            }
            else{
                from_inp.val(TIForm['date_from']);
                to_inp.val(TIForm['date_to']);
                o2p.find('input').prop('checked', false);
            }
            if($(this).data('hide_date_select') == 1){
                o2p.find('input').prop('checked', true);
                $('#confirm-option-btn').click();
            }
        }
        else{
            if(TIForm['option_to_person'][$(this).data('option_id')]){
                delete TIForm['option_to_person'][$(this).data('option_id')];
                $.ajax({
                    type: "POST",
                    url: "/wp-admin/admin-ajax.php",
                    dataType: "json",
                    data: {
                        action: 'yf_tif_get_results',
                        form: TIForm
                    },
                    beforeSend: function () {
                        loader.show();
                    },
                    complete: function () {
                        loader.hide();
                    },
                    success: function (json) {
                        $.each(json['results'], function(i,v){
                            $('#company-'+v['id']+'-price').html(v['price']+v['currency']);
                        });
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            }
        }
    });

    $('[name=date_from_txt], [name=date_to_txt]').change(function(){
        setTimeout(function(){
            $.ajax({
                type: "POST",
                url: "/wp-admin/admin-ajax.php",
                dataType: "json",
                data: {
                    action: 'yf_tif_get_results',
                    form: TIForm
                },
                beforeSend: function () {
                    loader.show();
                },
                complete: function () {
                    loader.hide();
                },
                success: function (json) {
                    $.each(json['results'], function(i,v){
                        $('#company-'+v['id']+'-price').html(v['price']+v['currency']);
                    });
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        }, 200);
    });



    $('#personal_date_from, #personal_date_to').change(function(){
        var from_inp = $('#personal_date_from');
        var to_inp = $('#personal_date_to');

        if(from_inp.val() && to_inp.val()){
            var from = from_inp.val().split('/');
            var to = to_inp.val().split('/');

            from[1] = parseInt(from[1]) - 1;
            from = new Date(from[2], from[1], from[0]);
            from = from.getTime();

            to[1] = parseInt(to[1]) - 1;
            to = new Date(to[2], to[1], to[0]);
            to = to.getTime();


            if(to <= from){
                var new_to_date = new Date((from+(3600*24*1000)));
                var dd = new_to_date.getDate();
                var mm = new_to_date.getMonth() + 1; //January is 0!

                var yyyy = new_to_date.getFullYear();
                if (dd < 10) {
                    dd = '0' + dd;
                }
                if (mm < 10) {
                    mm = '0' + mm;
                }
                console.log(dd + '/' + mm + '/' + yyyy);
                to_inp.val(dd + '/' + mm + '/' + yyyy);
            }

        }
    });



    $('.option-to-person-close').click(function(){
        $('input[data-option_id="'+$('#option-to-person').find('[name="option-to-person-id"]').val()+'"]').prop('checked', false);
    });


    $('.result-link__place_order').click(function(){
        $.ajax({
            type: "POST",
            url: "/wp-admin/admin-ajax.php",
            dataType: "json",
            data: {
                action: 'yf_tif_place_order',
                form: TIForm,
                company_id: $(this).data('company_id')

            },
            beforeSend: function () {
                loader.show();
            },
            complete: function () {
                loader.hide();
            },
            success: function (json) {
                //console.log(json);

            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });

    });


    $('[name="feedback-form"]').submit(function(){

        var f = $(this),
            d = {};

        d['user_name'] = f.find('[name=user_name]').val();
        d['user_phone'] = f.find('[name=user_phone]').val();
        if(f.find('[name=company]:checked').length > 0){
            d['company'] = [];
            f.find('[name=company]:checked').each(function(){
                d['company'].push($(this).val());
            });
        }



        $.ajax({
            type: "POST",
            url: "/wp-admin/admin-ajax.php",
            dataType: "json",
            data: {
                action: 'yf_tif_feedback',
                data: d

            },
            beforeSend: function () {
                loader.show();
            },
            complete: function () {
                loader.hide();
            },
            success: function (json) {
                //console.log(json);

            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });


        return false;
    });



    $('#screen-2-back-btn').click(function(){
        $('.main').scrollTop(0);
        $('.content-block_first').addClass('active').siblings().removeClass('active');
        checkHeight();
    });

    $('.content-form-btn_back').click(function(){
        TIForm['passenger_age'] = [];
        TIForm['option_to_person'] = {};
        
    });

});