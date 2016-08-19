var memberCount = 0;
$(document).on('keypress', '.membersAutoComplete', function() {
    $(this).autocomplete({
        serviceUrl: '/leadersmgmt/manage/getlist',
        onSelect: function (suggestion) {
            var className= $(this).data('class');
            $('.' + className).attr('value', suggestion.data);
        }
    });
});

var table = $('#addLeaderContainer #members_list').DataTable({
    "searching": true,
    columnDefs: [
        {"targets": [1], "searchable": false,"orderable": false},
        { "type": "html-input", "targets": [0] }
    ],
    "pageLength": 10,
    "lengthMenu": [[5, 10, 20], [5, 10, 20]],
});


$('#addLeaderContainer #addMember-btn').click(function() {
   table.row.add( [
            '<input type="text" class="form-control toUpper dtInput membersAutoComplete membersInput" data-class="member_' + memberCount + '">',
            '<div class="text-center"><button class="btn btn-default deleteMember-btn" id="member_' + memberCount + '" type="button"><span class="glyphicon glyphicon-trash"></span></button></div>'
    ] ).draw( false );
    $('form').append('<input type="hidden" name="members[]" class="members member_' + memberCount + '">');
    memberCount++;
});

$('#members_list tbody').on('click', 'tr .deleteMember-btn', function() {
    var id = $(this).attr('id');
    $('.' + id).remove();
    table
        .row( $(this).parents('tr') )
        .remove()
        .draw();
});

$(document).ready(function() {
    if($('#addLeaderContainer #members_list tbody tr.odd td').hasClass('dataTables_empty')) {
        $('#addLeaderContainer #addMember-btn').click();
    } else {
        memberCount = $('.membersInput').length;
    }
});

$('#addLeaderContainer #precinct').blur(function() {
     /* Precinct No Validation */
    var precinctPatt = /^\d{4}[a-zA-Z]$/;
    var precinct = $('#precinct');
    if($(precinct).val().length !=0 && !precinctPatt.test($(precinct).val())) {
        $(precinct).parents('.form-group').removeClass('has-success');
        $(precinct).parents('.form-group').addClass('has-error');
        $(precinct).parents('.form-group').find('.help-block-error').text('Wrong Precinct Number Format');
    } else {
        $(precinct).parents('.form-group').addClass('has-success');
        $(precinct).parents('.form-group').removeClass('has-error');
        $(precinct).parents('.form-group').find('.help-block-error').text('');
    }
});

$('#addLeaderContainer #save-btn').click(function(e) {
    var flag = 0;
    var msg = '';
    var flagMember = 0;
     /* Precinct No Validation */
    var precinctPatt = /^\d{4}[a-zA-Z]$/;
    var precinct = $('#precinct');
    if($(precinct).val().length !=0 && !precinctPatt.test($(precinct).val())) {
        $(precinct).parents('.form-group').removeClass('has-success');
        $(precinct).parents('.form-group').addClass('has-error');
        $(precinct).parents('.form-group').find('.help-block-error').text('Wrong Precinct Number Format');
        flag++;
        msg += 'Wrong Precinct Number Format. ';
    } else {
        $(precinct).parents('.form-group').addClass('has-success');
        $(precinct).parents('.form-group').removeClass('has-error');
        $(precinct).parents('.form-group').find('.help-block-error').text('');
    }

    $('.membersInput').each(function() {
        if($(this).val().length == 0) {
            flag++;
            flagMember++;
        }
    });
    if(flagMember != 0) {
        msg += 'Empty Input field for member. ';
    }

    if(flag != 0) {
        swal("Error!", msg, "error");
        e.preventDefault(e);
    } else {
        $('#leaderMemberSave-form').submit();
    }



});



