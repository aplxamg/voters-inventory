/* Datatables and Cloning */
var memberCount = 0;
var table = $('#addLeaderContainer #members_list').DataTable({
    "searching": true,
    columnDefs: [
        {"targets": [1], "searchable": false,"orderable": false},
        { "type": "html-input", "targets": [0] }
    ],
    "pageLength": 10,
    "lengthMenu": [[5, 10, 20], [5, 10, 20]],
});

// Add new member row
$('#addLeaderContainer #addMember-btn').click(function() {
    var action = '<div class="text-center">'
               + '<ul class="list-inline">'
               + '<li><button class="btn btn-default addVoter-btn" id="member_' + memberCount + '" type="button"><span class="glyphicon glyphicon-list"></span></button></li>'
               + '<li><button class="btn btn-default deleteMember-btn" id="' + memberCount + '" type="button"><span class="glyphicon glyphicon-trash"></span></button></li>'
               + '</ul></div>';

   table.row.add( [
            '<input type="text" class="form-control toUpper dtInput membersInput" disabled>',
            action
    ] ).draw( false );
    $('form').append('<input type="hidden" name="members[]" class="members member_' + memberCount + '">');
    memberCount++;
});
// Delete member row
$('#members_list tbody').on('click', 'tr .deleteMember-btn', function() {
    var id = $(this).attr('id');
    $('.member_' + id).remove(); // deletes hidden input
    table
        .row( $(this).parents('tr') )
        .remove()
        .draw();
});

/* Datatables Modal */
var current_id, table2, voter_name, voter_id = null;
// Opens modal to select voter name
$(document).on('click', '.addVoter-btn', function() {
    current_id = $(this).attr('id');
    // Default Settings
    $('#selectVoterModal').modal('show');
    $('#loader').show();
    $('#selectVoterModal #selected-voter-btn').prop('disabled', true);
    $('#select-voter-div').hide();

    table2 = $('#select-voter-table').DataTable({
        "destroy": true,
        "responsive": true,
        "lengthMenu": [[5, 10, 20], [5, 10, 20]],
        "order": [[ 1, "desc" ]],
        "ajax": {
            type: 'GET',
            dataType: 'json',
            cache: false,
            url: '/leadersmgmt/manage/getlists',
        },
        "columns": [
            { "data": "vin" },
            { "data": "name" },
            { "data": "precinct" },
        ],
         "columnDefs": [
            {
                "targets": 1,
                "data": "name",
                "render": function ( data, type, full, meta ) {
                    var id = full.id;
                    return '<span class="voterInfo" data-id="' + id +'">' + data  + '</span>';
                }
            },
        ],
        "initComplete": function(settings, json) {
            $('#loader').hide();
            $('#select-voter-div').show();
        }
    });
});
// Select row in datatable modal
$(document).on( 'click', '#selectVoterModal tr', function () {
    if ( $(this).hasClass('selected') ) {
        $(this).removeClass('info');
        $(this).removeClass('selected');
        $('#selectVoterModal #selected-voter-btn').prop('disabled', true);
    }
    else {
        table2.$('tr.selected').removeClass('info');
        table2.$('tr.selected').removeClass('selected');
        $(this).addClass('selected');
        $(this).addClass('info');
        $('#selectVoterModal #selected-voter-btn').prop('disabled', false);
        voter_name = $(this).find('.voterInfo').text();
        voter_id = $(this).find('.voterInfo').data('id');
    }
});
// select voter in datatable modal, selected voter will reflect on selected row
$('#selectVoterModal #selected-voter-btn').click(function() {
   if(current_id !== null) {
       $('#' + current_id).parents('tr').find('.membersInput').val(voter_name);
       $('.' + current_id).val(voter_id);
       $('#selectVoterModal').modal('hide');
   }
});
// Reset to default settings when modal is closed
$('#selectVoterModal').on('hidden.bs.modal', function (e) {
    current_id = null;
    voter_name = null;
    voter_id   = null;

});
// Validates precinct no
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
// Validate input on save
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

$(document).ready(function() {
    // Instantiate plugin for datatable
    if($('#addLeaderContainer #members_list tbody tr.odd td').hasClass('dataTables_empty')) {
        $('#addLeaderContainer #addMember-btn').click();
    } else {
        memberCount = $('.membersInput').length;
    }
});
