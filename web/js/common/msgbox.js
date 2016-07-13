$(document).on('click', '.delete-button', function(){
    var id = parseInt($(this).val());
    var title = null;
    var msg = null;
    var url = null;

    var a = $(this).hasClass('delete-member');

    if(a) {
        if (a) {
            url = '/leadersmgmt/manage/deletemember' + id;
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
        function(){
          swal("Deleted!", "Your imaginary file has been deleted.", "success");
        });
    }

});
