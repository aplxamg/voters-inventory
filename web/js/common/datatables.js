$(window).load(function(){
    if($('#voters_list').length != 0) {
        $('#voters_list').DataTable({
            "order" : [[0, 'asc']],
            "aoColumnDefs": [
	      		{ "bSortable": false, "aTargets": [ 5 ] },
                { "bSearchable": false, "aTargets": [5] },
                { "sWidth": "15%", "aTargets": [5] }
	    	],
        });
    }

    if($('#leaders_list').length != 0) {
        $('#leaders_list').DataTable({
            "aoColumnDefs": [
                { "bSortable": false, "aTargets": [3] },
                { "sWidth": "20%", "aTargets": [0,3] },
                { "sWidth": "10%", "aTargets": [2] },
            ],
            "order": [[ 0, "desc" ]],
        });
    }
});
