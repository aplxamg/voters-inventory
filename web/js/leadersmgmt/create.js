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


$('#addLeaderContainer #addMember-btn').click(function() {
    var action = '<div class="text-center">'
               + '<ul class="list-inline">'
               + '<li><button class="btn btn-default addVoter-btn" id="member_' + memberCount + '_btn" type="button"><span class="glyphicon glyphicon-list"></span></button></li>'
               + '<li><button class="btn btn-default deleteMember-btn" id="member_' + memberCount + '" type="button"><span class="glyphicon glyphicon-trash"></span></button></li>'
               + '</ul></div>';

   table.row.add( [
            '<input type="text" class="form-control toUpper dtInput membersAutoComplete membersInput" data-class="member_' + memberCount + '" disabled>',
            action
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
