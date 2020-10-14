<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"> <i class="fa fa-file"></i> Laporan Barang</h1>
</div>
<div class="row row_angket">
	<div class="col mb-4">
		<div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary"><?= ucfirst($ket); ?> Barang
                </h6>
                <div class="dropdown no-arrow">
                    <?= isset($add)?$add:''; ?>
                </div>
            </div>
            <div class="card-body">

                <?php if(!isset($detail)):?>
                <div class="container filter">
                    
                    <div class="form-group row cb_tgl_box">
                        <label for="filter" class="col-sm-2 col-form-label">Tanggal</label>
                        <div class="col-sm-5">
                            <input type="date" class="form-control cb_tgl" id="tgl1" format="Y-m-d">
                        </div>
                        <div class="col-sm-5">
                            <input type="date" class="form-control cb_tgl" id="tgl2" format="Y-m-d">
                        </div>
                    </div>
                    <div class="form-group row cb_tgl_box">
                        <label for="filter" class="col-sm-2 col-form-label">Gudang</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="cb_gudang" id="cb_gudang" >
                                <option value="">[Semua Gudang]</option>
                                <?php
                                    $q = $this->Gudang_model->get_all();
                                    $res = $q->result();
                                    foreach ($res as $row) {
                                        echo "<option value='$row->nama_gudang'>$row->nama_gudang</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row cb_tgl_box">
                        <label for="filter" class="col-sm-2 col-form-label">Keterangan</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="cb_keterangan" id="cb_keterangan" >
                                <option value="">[Semua Keterangan]</option>
                                <option value="Restok">Restok</option>
                                <option value="Keluar">Keluar</option>
                                <option value="Ditolak">Ditolak</option>
                            </select>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="load_table">
                    <?= isset($table)?$table:''; ?>
                </div>
                <?php else:?>
                    <table class="table">
                        <tbody>
                        <?php foreach ($detail as $k => $v): ?>
                            <tr>
                                <th width="25%"><?= $k; ?></th>
                                <td width="1%">:</td>
                                <td><?= $v; ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <th>Detail Penjualan</th>
                            <td></td>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                    <table class="table dataTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Barang</th>
                                <th>Jumlah</th>
                                <th>Ketrangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $date_arr = [];
                                $d = date("Y")."-".$bln."-01";
                                $today = date('d-m-Y', strtotime($d)); 
                                $date_arr[$today] = 0;
                                $d=cal_days_in_month(CAL_GREGORIAN,$bln,date("Y"));
                                for($i=1; $i<$d; $i++){
                                    $repeat = strtotime("+1 day",strtotime($today));
                                    $today = date('d-m-Y',$repeat);
                                    $date_arr[$today] = 0;
                                }


                                $res = $penjualan->result();
                                $nama_barang = '';
                                foreach ($res as $row) {
                                    $date_arr[date("d-m-Y", strtotime($row->tgl_order))] = array(
                                                        "nama_barang" => $row->nama_barang,
                                                        "jumlah_order" => $row->jumlah_order,
                                                        );
                                    
                                    $nama_barang = $row->nama_barang;
                                }
                                foreach ($date_arr as $k => $row){
                                    if(!isset($row['nama_barang'])){
                                        $date_arr[$k] = array(
                                                        "nama_barang" => $nama_barang,
                                                        "jumlah_order" => 0,
                                                        );
                                    }
                                }
                                //print_pre($date_arr);

                                $i=0;
                                $jml = 0;
                                foreach ($date_arr as $k => $row):
                                   
                                $jml += $row['jumlah_order'];
                            ?>
                            <tr>
                                <td><?= ++$i; ?></td>
                                <td><?= $k; ?></td>
                                <td><?= $row['nama_barang']; ?></td>
                                <td><?= $row['jumlah_order']; ?></td>
                                <td>
                                    <?php
                                        if($row['jumlah_order']>= $detail['target']){
                                            echo '<span class="badge badge-success">Target</span>';
                                        }else{
                                            echo '<span class="badge badge-danger">Tidak Target</span>';
                                        }
                                    ?>        
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>    
                        <tfoot>
                            <tr>
                                <th colspan="3">Total</th>
                                <th><?= $jml; ?></th>
                            </tr>
                        </tfoot>
                    </table>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        if ($(window).width() < 768) {
            var table2 = $(".dataTable2").DataTable({
                "scrollX": true
            });
        }else{
            var table2 = $(".dataTable2").DataTable();
        }


        $('#cb_gudang, #cb_keterangan, #tgl1, #tgl2').change( function() {
            table2.draw();
        });


        $.fn.dataTable.ext.search.push(
            function( settings, data, dataIndex ) {
                var tgl1 = $("#tgl1").val();
                var tgl2 = $("#tgl2").val();
                var g = $("#cb_gudang").val();
                var k = $("#cb_keterangan").val();

                var gdg = data[1];
                var t = data[4].split("-");
                var tgl = t[2]+"-"+t[1]+"-"+t[0];
                var ket = data[5];

                tgl1 = (tgl1=='')?'':Date.parse(tgl1);
                tgl2 = (tgl2=='')?'':Date.parse(tgl2);
                tgl = (tgl=='')?'':Date.parse(tgl);

                /*
                console.log(gdg);
                console.log(tgl1);
                console.log(tgl2);
                console.log(tgl);
                */

                if( tgl1 == '' && tgl2 == '' && g == '' && k == '' ){
                    return true;
                }else{
                    if(tgl1 >= tgl && tgl2 <= tgl && g == gdg && k == ket ){
                        return true;
                    }else if(tgl1 != '' && tgl2 == '' && g == '' && k == '' ){ //---- 1
                        if(tgl>=tgl1){return true;}
                    }else if(tgl1 == '' && tgl2 != '' && g == '' && k == '' ){
                        if(tgl<=tgl2){return true;}
                    }else if(tgl1 == '' && tgl2 == '' && g != '' && k == '' ){
                        if(g==gdg){return true;}
                    }else if(tgl1 == '' && tgl2 == '' && g == '' && k != '' ){ 
                        if(k==ket){return true;}
                    }else if(tgl1 != '' && tgl2 != '' && g == '' && k == '' ){ //--- 2.1
                        if(tgl>=tgl1 && tgl<=tgl1){return true;}
                    }else if(tgl1 != '' && tgl2 == '' && g != '' && k == '' ){
                        if(tgl>=tgl1 && g==gdg){return true;}
                    }else if(tgl1 != '' && tgl2 == '' && g == '' && k != '' ){ 
                        if(tgl>=tgl1 && k==ket){return true;}
                    }else if(tgl1 == '' && tgl2 != '' && g != '' && k == '' ){ //--- 2.2
                        if(tgl<=tgl2 && g==gdg){return true;}
                    }else if(tgl1 == '' && tgl2 != '' && g == '' && k != '' ){ 
                        if(tgl<=tgl2 && k==ket){return true;}
                    }else if(tgl1 == '' && tgl2 == '' && g != '' && k != '' ){ //--- 2.3
                        if(g==gdg && k==ket){return true;}
                    }else if(tgl1 != '' && tgl2 != '' && g != '' && k == '' ){ //--- 3.1
                        if(tgl>=tgl1 && tgl<=tgl1 && g==gdg){return true;}
                    }else if(tgl1 != '' && tgl2 != '' && g == '' && k != '' ){ //--- 3.2
                        if(tgl>=tgl1 && tgl<=tgl1 && k==ket){return true;}
                    }else if(tgl1 != '' && tgl2 == '' && g != '' && k != '' ){ //--- 3.3
                        if(tgl>=tgl1 && g==gdg && k==ket){return true;}
                    }else if(tgl1 == '' && tgl2 != '' && g != '' && k != '' ){ //--- 3.4
                        if(tgl<=tgl2 && g==gdg && k==ket){return true;}
                    }
                    

                    /*if(tgl1 != '' && tgl2 == '' && g == '' && k == '' ){ //---- 1
                    }else if(tgl1 == '' && tgl2 != '' && g == '' && k == '' ){
                    }else if(tgl1 == '' && tgl2 == '' && g != '' && k == '' ){
                    }else if(tgl1 == '' && tgl2 == '' && g == '' && k != '' ){ 
                    }else if(tgl1 != '' && tgl2 != '' && g == '' && k == '' ){ //--- 2.1
                    }else if(tgl1 != '' && tgl2 == '' && g != '' && k == '' ){
                    }else if(tgl1 != '' && tgl2 == '' && g == '' && k != '' ){ 
                    }else if(tgl1 == '' && tgl2 != '' && g != '' && k == '' ){ //--- 2.2
                    }else if(tgl1 == '' && tgl2 != '' && g == '' && k != '' ){ 
                    }else if(tgl1 == '' && tgl2 == '' && g != '' && k != '' ){ //--- 2.3
                    }else if(tgl1 != '' && tgl2 != '' && g != '' && k == '' ){ //--- 3.1
                    }else if(tgl1 != '' && tgl2 != '' && g == '' && k != '' ){ //--- 3.2
                    }else if(tgl1 != '' && tgl2 == '' && g != '' && k != '' ){ //--- 3.3
                    }else if(tgl1 == '' && tgl2 != '' && g != '' && k != '' ){ //--- 3.4
                    }*/
                }
                return false;

                /*if ( ( isNaN( min ) && isNaN( max ) ) ||
                     ( isNaN( min ) && age <= max ) ||
                     ( min <= age   && isNaN( max ) ) ||
                     ( min <= age   && age <= max ) )
                {
                    return true;
                }
                return false;*/
            }
        );


    });


</script>

<!--  -->