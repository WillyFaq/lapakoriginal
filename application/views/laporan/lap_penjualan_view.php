<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"> <i class="fa fa-file-alt"></i> Laporan Penjualan</h1>
</div>
<div class="row row_angket">
	<div class="col mb-4">
		<div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary"><?= ucfirst($ket); ?> Penjualan
                </h6>
                <div class="dropdown no-arrow">
                    <?= isset($add)?$add:''; ?>
                </div>
            </div>
            <div class="card-body">
                <div class="container filter">
                    <div class="form-group row">
                        <label for="filter" class="col-sm-2 col-form-label">Filter</label>
                        <div class="col-sm-10">
                            <select name="filter" id="cb_filter" class="form-control">
                                <option value="1">Bulanan</option>
                                <option value="2">Harian</option>
                            </select>
                            <hr class="mb-0">
                        </div>
                    </div>
                    <div class="form-group row cb_bulan_box">
                        <div class="offset-md-2 col-sm-10">
                            <select name="filter" id="cb_bulan" class="form-control">
                                <option value="1">Semua Bulan</option>
                                <?php
                                    foreach (get_bulan() as $k => $v) {
                                        echo '<option value="'.$k.'">'.$v.'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row cb_tgl_box" style="display:none">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-5">
                            <input type="date" class="form-control cb_tgl" id="tgl1" format="Y-m-d">
                        </div>
                        <div class="col-sm-5">
                            <input type="date" class="form-control cb_tgl" id="tgl2" format="Y-m-d">
                        </div>
                    </div>
                </div>
                <hr>
                <div class="load_table"></div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){

        load_bulanan("");

        $("#cb_filter").change(function(){
            var v = $(this).val();
            if(v==1){
                $(".cb_bulan_box").show();
                $(".cb_tgl_box").hide();
                load_bulanan("");
            }else if (v==2){
                $(".cb_tgl_box").show();
                $(".cb_bulan_box").hide();
                load_harian("", "");
            }
        });
        $("#cb_bulan").change(function(){
            var v = $(this).val(); 
            load_bulanan(v);
        });

        $(".cb_tgl").change(function(){
            var tgl1 = $("#tgl1").val();
            var tgl2 = $("#tgl2").val();
            load_harian(tgl1, tgl2);
        });

    });

    function load_bulanan(v){
        $(".load_table").load('<?= base_url("laporan_penjualan/gen_table_bulanan/"); ?>'+v, function(){
            init_datatable();
        });
    }

    function load_harian(tgl1, tgl2) {
        console.log(tgl1);
        console.log(tgl2);
        $(".load_table").load('<?= base_url("laporan_penjualan/gen_table_harian/"); ?>'+tgl1+"/"+tgl2, function(){
            init_datatable();
        });
    }
</script>

<!--  -->