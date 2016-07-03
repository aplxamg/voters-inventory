$(window).load(function() {
    var backendResult = $('#addVoterCnt').data('errorValue');
    if(backendResult === 0) {
        $('#createVoterAlert').remove();
    }
});

$('#addVoter-form .save-btn').click(function(e) {
    var flag = 0;
    /* Voters No Validation */
    var vinPatt = /^\d{4}-\d{4}[a-zA-Z]{1}-\w*$/;
    var vin = $('#votersdbvoters-voters_no');
    if($(vin).val().length != 0 && !vinPatt.test($(vin).val())) {
        $(vin).parents('.form-group').removeClass('has-success');
        $(vin).parents('.form-group').addClass('has-error');
        $(vin).parents('.form-group').find('.help-block-error').text('Wrong VIN Format');
        flag++;
    } else {
        $(vin).parents('.form-group').addClass('has-success');
        $(vin).parents('.form-group').removeClass('has-error');
        $(vin).parents('.form-group').find('.help-block-error').text('');
    }
    /* Birthdate Validation */
    var bdatePatt = /^(\d{2}\/){2}\d{4}$/;
    var bdate = $('#votersdbvoters-birthdate');
    if($(bdate).val().length !=0 && !bdatePatt.test($(bdate).val())) {
        $(bdate).parents('.form-group').removeClass('has-success');
        $(bdate).parents('.form-group').addClass('has-error');
        $(bdate).parents('.form-group').find('.help-block-error').text('Wrong Birthdate Format');
        flag++;
    } else {
        $(bdate).parents('.form-group').addClass('has-success');
        $(bdate).parents('.form-group').removeClass('has-error');
        $(bdate).parents('.form-group').find('.help-block-error').text('');
    }
    /* Precinct No Validation */
    var precinctPatt = /^\d{4}[a-zA-Z]$/;
    var precinct = $('#votersdbvoters-precinct_no');
    if($(precinct).val().length !=0 && !precinctPatt.test($(precinct).val())) {
        $(precinct).parents('.form-group').removeClass('has-success');
        $(precinct).parents('.form-group').addClass('has-error');
        $(precinct).parents('.form-group').find('.help-block-error').text('Wrong Precinct Number Format');
        flag++;
    } else {
        $(precinct).parents('.form-group').addClass('has-success');
        $(precinct).parents('.form-group').removeClass('has-error');
        $(precinct).parents('.form-group').find('.help-block-error').text('');
    }

    if(flag != 0 ) {
        e.preventDefault(e);
    } else {
        $('#addVoter-form').submit();
    }

});


