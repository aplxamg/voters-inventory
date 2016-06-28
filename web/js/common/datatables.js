$(window).load(function(){
    if($('#voters_list').length != 0) {
        $('#voters_list').DataTable({
            "order" : [[0, 'asc']],
            "aoColumnDefs": [
	      		{ "bSortable": false, "aTargets": [ 5 ] },
                { "bSearchable": false, "aTargets": [5] }
	    	],
        });
    }

    if($('#leaders_list').length != 0) {
        $('#leaders_list').DataTable({
            "aoColumnDefs": [
                { "bSortable": false, "aTargets": [3] }
            ],
            "order": [[ 0, "desc" ]],
        });
    }
});
