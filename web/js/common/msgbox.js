$(document).on('click', '.msgbox-button', function(){
    var id = parseInt($(this).val());
    var title = null;
    var msg = null;
    var url = null;

    var a = $(this).hasClass('delete-voter');
    var b = $(this).hasClass('approve-leader');
    var c = $(this).hasClass('remove-leader');
    var d = $(this).hasClass('set-vote');
    var e = $(this).hasClass('reset-vote');
    if(a || b || c || d || e) {
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
            url = '/votersmgmt/manage/vote/set/' + id;
            title = 'Set as voted';
            msg = 'Are you sure you want to set vote?';
        } else if (e) {
            url = '/votersmgmt/manage/vote/reset/' + id;
            title = 'Reset Vote';
            msg = 'Are you sure you want to reset vote?';
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
