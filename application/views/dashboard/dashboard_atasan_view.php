<!-- Content Row -->
<div class="row">
    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Order</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                        Rp. <?= number_format($semua); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Order Bulan ini</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                        Rp. <?= number_format($bulan_ini); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Jumlah Order Hari ini</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?= number_format($hari_ini); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Jumlah Barang</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?= number_format($barang); ?>   
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-box-open fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-12 col-lg-12">
        <script src="https://code.highcharts.com/maps/highmaps.js"></script>
        <script src="https://code.highcharts.com/maps/modules/exporting.js"></script>
        <script src="<?= base_url("assets/js/id_map.js"); ?>"></script>
        <!-- <script src="https://code.highcharts.com/mapdata/countries/id/id-all.js"></script> -->
        <div id="container"></div>
        <?php
                $sql = "SELECT  
                            SUBSTR(b.alamat, 1, 2) AS prov,
                            COUNT(*) AS jml
                        FROM sales_order a
                        JOIN pelanggan b ON a.no_pelanggan = b.no_pelanggan
                        GROUP BY SUBSTR(b.alamat, 1, 2)";
                $q = $this->db->query($sql);
                $res = $q->result();
                $data = [];
                $map = json_decode(file_get_contents(base_url('assets/mapping.json')), true);
                foreach ($res as $row) {
                    $data[$map[$row->prov]] = $row->jml;
                }
            ?>
        <script type="text/javascript">
            //console.log(Highcharts.maps["countries/id/id-all"].features);
            /*var f = Highcharts.maps["countries/id/id-all"].features;
            var pro = [{"id":11,"nama":"Aceh"},{"id":12,"nama":"Sumatera Utara"},{"id":13,"nama":"Sumatera Barat"},{"id":14,"nama":"Riau"},{"id":15,"nama":"Jambi"},{"id":16,"nama":"Sumatera Selatan"},{"id":17,"nama":"Bengkulu"},{"id":18,"nama":"Lampung"},{"id":19,"nama":"Kepulauan Bangka Belitung"},{"id":21,"nama":"Kepulauan Riau"},{"id":31,"nama":"Dki Jakarta"},{"id":32,"nama":"Jawa Barat"},{"id":33,"nama":"Jawa Tengah"},{"id":34,"nama":"Di Yogyakarta"},{"id":35,"nama":"Jawa Timur"},{"id":36,"nama":"Banten"},{"id":51,"nama":"Bali"},{"id":52,"nama":"Nusa Tenggara Barat"},{"id":53,"nama":"Nusa Tenggara Timur"},{"id":61,"nama":"Kalimantan Barat"},{"id":62,"nama":"Kalimantan Tengah"},{"id":63,"nama":"Kalimantan Selatan"},{"id":64,"nama":"Kalimantan Timur"},{"id":65,"nama":"Kalimantan Utara"},{"id":71,"nama":"Sulawesi Utara"},{"id":72,"nama":"Sulawesi Tengah"},{"id":73,"nama":"Sulawesi Selatan"},{"id":74,"nama":"Sulawesi Tenggara"},{"id":75,"nama":"Gorontalo"},{"id":76,"nama":"Sulawesi Barat"},{"id":81,"nama":"Maluku"},{"id":82,"nama":"Maluku Utara"},{"id":91,"nama":"Papua Barat"},{"id":94,"nama":"Papua"}];
            
            f.forEach(function(item, index){
                $("#pre").append(item.properties.name);
                $("#pre").append("<br>");
            });*/
            // Prepare demo data
            // Data is joined to map using value of 'hc-key' property by default.
            // See API docs for 'joinBy' for more info on linking data and map.

            var data = [
                ['id-3700', 0],
                <?php foreach ($data as $k => $v) {
                    echo "['$k', $v],";
                }?>
            ];

            // Create the chart
            Highcharts.mapChart('container', {
                chart: {
                    map: 'countries/id/id-all'
                },

                title: {
                    text: 'Demograpi Penjualan'
                },

                subtitle: {
                    text: 'Source map: <a href="http://code.highcharts.com/mapdata/countries/id/id-all.js">Indonesia</a>'
                },

                mapNavigation: {
                    enabled: true,
                    buttonOptions: {
                        verticalAlign: 'bottom'
                    }
                },

                colorAxis: {
                    min: 0
                },

                series: [{
                    data: data,
                    name: 'Total Penjualan',
                    states: {
                        hover: {
                            color: '#BADA55'
                        }
                    },
                    dataLabels: {
                        enabled: false,
                        format: '{point.name}'
                    }
                }]
            });

        </script>
    </div>
    <div class="col-xl-12 col-lg-12">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Profit History</h6>
                <div class="dropdown no-arrow">
                    
                </div>
            </div>
                <!-- Card Body -->
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="myAreaChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-12 col-lg-12">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Omset History</h6>
                <div class="dropdown no-arrow">
                    
                </div>
            </div>
                <!-- Card Body -->
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="myAreaChart_omset"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    
    

?>

<script type="text/javascript">
    var ctx = document.getElementById("myAreaChart");
    var myLineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ["<?= join('", "', $chart['label']) ?>"],
            datasets: [{
                label:"Laba",
                backgroundColor:window.chartColors.red,
                borderColor:window.chartColors.red,
                data:[<?= join(", ", $chart['data']); ?>],
                fill:false,
            }],
        },
        options: {
            maintainAspectRatio: false,
            layout: {
                padding: {
                    left: 10,
                    right: 25,
                    top: 25,
                    bottom: 0
                }
            },
            scales: {
                xAxes: [{
                    time: {
                        unit: 'date'
                    },
                    gridLines: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        maxTicksLimit: 7
                    }
                }],
                yAxes: [{
                    ticks: {
                        maxTicksLimit: 5,
                        padding: 10,
                        callback: function(value, index, values) {
                            return 'Rp. ' + number_format(value);
                        }
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
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                titleMarginBottom: 10,
                titleFontColor: '#6e707e',
                titleFontSize: 14,
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                intersect: false,
                mode: 'index',
                caretPadding: 10,
                callbacks: {
                    label: function(tooltipItem, chart) {
                        var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                        return datasetLabel + ': Rp. ' + number_format(tooltipItem.yLabel);
                    }
                }
            }
        }
    });
</script>


<script type="text/javascript">
    var ctx = document.getElementById("myAreaChart_omset");
    var myLineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ["<?= join('", "', $omset['label']) ?>"],
            datasets: [{
                label:"Laba",
                backgroundColor:window.chartColors.green,
                borderColor:window.chartColors.green,
                data:[<?= join(", ", $omset['data']); ?>],
                fill:false,
            }],
        },
        options: {
            maintainAspectRatio: false,
            layout: {
                padding: {
                    left: 10,
                    right: 25,
                    top: 25,
                    bottom: 0
                }
            },
            scales: {
                xAxes: [{
                    time: {
                        unit: 'date'
                    },
                    gridLines: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        maxTicksLimit: 7
                    }
                }],
                yAxes: [{
                    ticks: {
                        maxTicksLimit: 5,
                        padding: 10,
                        callback: function(value, index, values) {
                            return 'Rp. ' + number_format(value);
                        }
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
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                titleMarginBottom: 10,
                titleFontColor: '#6e707e',
                titleFontSize: 14,
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                intersect: false,
                mode: 'index',
                caretPadding: 10,
                callbacks: {
                    label: function(tooltipItem, chart) {
                        var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                        return datasetLabel + ': Rp. ' + number_format(tooltipItem.yLabel);
                    }
                }
            }
        }
    });
</script>