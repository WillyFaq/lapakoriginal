<canvas id="detail_demo_bar"></canvas>

<script type="text/javascript">
function render_chart(){
    var ctx = document.getElementById("detail_demo_bar");
    ctx.height = 900;
    var myLineChart = new Chart(ctx, {
        type: 'horizontalBar',
        data: {
            labels: ["<?= join('", "', $detail['label']) ?>"],
            datasets: [{
                label:"Jumlah",
                backgroundColor:window.chartColors.green,
                borderColor:window.chartColors.green,
                data:[<?= join(", ", $detail['data']); ?>],
                fill:false,
            }],
        },
        options: {
            maintainAspectRatio: false,
            layout: {
                padding: {
                    left: 5,
                    right: 5,
                    top: 0,
                    bottom: 0
                }
            },
            scales: {
                yAxes: [{
                    gridLines: {
                        display: false,
                        drawBorder: false
                    },
                }],
                xAxes: [{
                    ticks: {
                        maxTicksLimit: 50,
                        padding: 5
                    },
                    gridLines: {
                        color: "rgb(234, 236, 244)",
                        zeroLineColor: "rgb(234, 236, 244)",
                        drawBorder: false,
                        borderDash: [2],
                        zeroLineBorderDash: [2]
                    }
                }],
            },
            legend: {
                display: false
            },
        }
    });
}
</script>