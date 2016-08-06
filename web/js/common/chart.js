var user_type = $('#dashboard').data('user');
var leader_id = $('#dashboard').data('id');

$(document).ready(function() {
    var url = '';
    if(user_type == 'leader') {
        $('#selectLeader').val(leader_id);
        url = '/votersmgmt/manage/chart/' + leader_id;
    } else {
        url = '/votersmgmt/manage/chart/null';
    }

    generateChart(url);

    $('#selectLeader').change(function() {
        console.log('asdasd');
        var value = $('#selectLeader').val();
        if(value == 0) {
            url = '/votersmgmt/manage/chart/null';
        } else {
            url = '/votersmgmt/manage/chart/' + value;
        }
        console.log(url);
        generateChart(url);
    });




});

function generateChart(url)
{
    $.ajax({
        type: "GET",
        url: url,
        success: function (data) {
            data = jQuery.parseJSON(data);
            var summary = data.datasets[0]['data'];
            $('#total-voter').html(summary[0] + summary[1]);
            $('#total-voted').html(summary[0]);
            $('#total-not-voted').html(summary[1]);

            var ctx = $('#voter-chart');
            var myDoughnutChart = new Chart(ctx, {
                type: 'doughnut',
                data: data,
                options: {

                }
            });

        }
    });
}
