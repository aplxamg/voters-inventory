$(window).load(function(){
    if($('#voters_list').length != 0) {
        $('#voters_list').DataTable({
            "order" : [[0, 'asc']]
        });
    }
});
