$('#create-account #save-button').click(function() {
    var type        = $('#users-user_type');
    var username    = $('#users-username');
    var password    = $('#users-password');
    var flag        = 0;
    var msg         = '';

    // Type cannot be blank
    if($(type).val().length == 0) {
        flag++;
        msg += '* Type cannot be blank. <br>';
        $(type).parents('.form-group').removeClass('has-success');
        $(type).parents('.form-group').addClass('has-error');
    }

    // Username cannot be blank
    if($(username).val().length == 0) {
        flag++;
        msg += '* Username cannot be blank. <br>';
        $(username).parents('.form-group').removeClass('has-success');
        $(username).parents('.form-group').addClass('has-error');
    } else {
        var doAjax = false;
        if($('#create-account').data('id') == 0) {
            doAjax = true;
        } else {
            console.log('Prop = ' + $(username).prop('defaultValue') + " " + typeof $(username).prop('defaultValue') );
            console.log('value = ' + $(username).val() + " " + typeof $(username).val());
            if($(username).prop('defaultValue') === $(username).val()) {

            } else {
                doAjax = true;
            }
        }

        if(doAjax) {
            $.ajax({
                type: 'GET',
                dataType: 'json',
                cache: false,
                url: '/account/manage/check/' + $(username).val().trim(),
                async: false
            }).done(function(data) {
               if(data != 0) {
                   flag++;
                   msg += '* Username is already taken. <br>';
                   $(username).parents('.form-group').removeClass('has-success');
                   $(username).parents('.form-group').addClass('has-error');
               } else {
                   $(username).parents('.form-group').addClass('has-success');
                   $(username).parents('.form-group').removeClass('has-error');
               }
            });
        }

    }

    // Password cannot be blank
    if($(password).val().length == 0) {
        flag++;
        msg += '* Password cannot be blank. <br>';
        $(password).parents('.form-group').removeClass('has-success');
        $(password).parents('.form-group').addClass('has-error');
    }

    if(flag != 0) {
        swal({
            html: true,
            title: "Error",
            text: msg,
            type: "error"
        });
    } else {
        $('#addVoter-form').submit();
    }


});

