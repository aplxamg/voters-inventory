var memberCount = 0;
$('#autocomplete').autocomplete({
    serviceUrl: '/leadersmgmt/manage/getlist',
    onSelect: function (suggestion) {
        $('#leader').attr('value', suggestion.data);
    }
});

$(document).on('keypress', '.membersAutoComplete', function() {
    $(this).autocomplete({
        serviceUrl: '/leadersmgmt/manage/getlist',
        onSelect: function (suggestion) {

        }
    });
});

//$(document).on('blur', '.membersAutoComplete', function() {
//    $(this).dispose();
//});

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
            '<input type="text" class="form-control dtInput membersAutoComplete" data-class="member_' + memberCount + '">',
            '<div class="text-center"><button class="btn btn-default deleteMember-btn" id="member_' + memberCount + '" type="button"><span class="glyphicon glyphicon-trash"></span></button></div>'
    ] ).draw( false );
    $('form').append('<input type="hidden" name="members[]" class="member_' + memberCount + '"');
    memberCount++;
});

$('#members_list tbody').on('click', 'tr .deleteMember-btn', function() {
    $('')
    table
        .row( $(this).parents('tr') )
        .remove()
        .draw();
});

$(document).ready(function() {
    if($('#addLeaderContainer #members_list tbody tr.odd td').hasClass('dataTables_empty')) {
        $('#addLeaderContainer #addMember-btn').click();
    }
});
