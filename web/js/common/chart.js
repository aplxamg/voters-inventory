$(document).ready(function() {

    $.ajax({
        type: "GET",
        url: '/votersmgmt/manage/chart',
        success: function (data) {
            data = jQuery.parseJSON(data);
            console.log(data);
            var ctx = $('#voter-chart');
            var myBarChart = new Chart(ctx, {
                type: 'bar',
                data: data,
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero:true
                            }
                        }]
                    }
                }
            });

//            var myBarChart = new Chart(ctx, {
//                type: 'bar',
//                data: {
//                    label: 'Count',
//                    data: data,
//                    backgroundColor: [
//                        'rgba(54, 162, 235, 0.2)',
//                        'rgba(255, 99, 132, 0.2)'
//                    ],
//                    borderColor: [
//                        'rgba(54, 162, 235, 1)',
//                        'rgba(255,99,132,1)'
//                    ],
//                    borderWidth: 1
//                },
//                options: {
//                    scales: {
//                        yAxes: [{
//                            ticks: {
//                                beginAtZero:true
//                            }
//                        }]
//                    }
//                }
//            });
        }
    });

});
