$(document).on('click', '.msgbox-button', function(){
    var id = parseInt($(this).val());
    var title = null;
    var msg = null;
    var url = null;
    var leader = 0;

    var a = $(this).hasClass('delete-voter');
    var b = $(this).hasClass('approve-leader');
    var c = $(this).hasClass('remove-leader');
    var d = $(this).hasClass('set-vote');
    var e = $(this).hasClass('reset-vote');
    var f = $(this).hasClass('member-set-vote');
    var g = $(this).hasClass('member-reset-vote');
    var h = $(this).hasClass('delete-member');
    if(a || b || c || d || e || f || g || h) {
        if (a) {
            url = '/votersmgmt/manage/delete/' + id;
            title = 'Delete Voter';
            msg = 'Are you sure you want to delete voter?';
        } else if (b) {
            url = '/leadersmgmt/manage/leader/appoint/' + id;
            title = 'Appoint Leader';
            msg = 'Are you sure you want to appoint voter as leader?';
        } else if (c) {
            url = '/leadersmgmt/manage/leader/remove/' + id;
            title = 'Remove Leader';
            msg = 'Are you sure you want to remove voter as leader?';
        } else if (d) {
            url = '/votersmgmt/manage/vote/set/' + id + '/voter/' + leader;
            title = 'Set as voted';
            msg = 'Are you sure you want to set vote?';
        } else if (e) {
            url = '/votersmgmt/manage/vote/reset/' + id + '/voter/' + leader;
            title = 'Reset Vote';
            msg = 'Are you sure you want to reset vote?';
        } else if (f) {
            leader = $(this).data('leader');
            url = '/votersmgmt/manage/vote/set/' + id + '/leader/' + leader;
            title = 'Set as voted';
            msg = 'Are you sure you want to set vote?';
        } else if (g) {
            leader = $(this).data('leader');
            url = '/votersmgmt/manage/vote/reset/' + id + '/leader/' + leader;
            title = 'Reset Vote';
            msg = 'Are you sure you want to reset vote?';
        } else if (h) {
            leader = $(this).data('leader');
            url = '/leadersmgmt/manage/deletemember/' + id + '/' + leader;
            title = 'Delete Member';
            msg = 'Are you sure you want to delete member?';
        }

        swal({
          title: title,
          text: msg,
          type: "warning",
          showCancelButton: true,
          confirmButtonClass: "btn-danger",
          confirmButtonText: "Yes",
          closeOnConfirm: false
        },
        function(isConfirm) {
            if(isConfirm) {
                $.ajax({
                    type: 'POST',
                    url: url,
                    success: function (data) {
                        var data = jQuery.parseJSON(data);
                        if(data.error == 0 ) {
                            window.location = data.url;
                        } else {
                            swal("Error!", data.msg, "error");
                        }
                    },
                });
            }
        });
    }
});

$(document).on('click', '.delete-button', function(){

});
