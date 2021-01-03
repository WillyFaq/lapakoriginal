<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
                    
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"> <i class="fas fa-shopping-cart"></i> Sales Order</h1>
</div>
<div class="row row_angket">
	<div class="col mb-4">
		<div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary"><?= ucfirst($ket); ?> Sales Order</h6>
                <div class="dropdown no-arrow">
                    <?= isset($add)?$add:''; ?>
                </div>
            </div>
            <div class="card-body">
                <?= isset($table)?$table:''; ?>
                <?php if(isset($form)):
                    $hidden = array(
                                        'id_transaksi' => isset($id_transaksi)?$id_transaksi:'', 
                                        'no_pelanggan' => isset($no_pelanggan)?$no_pelanggan:'',
                                    );
                    $class = array("class" => 'row');
                    echo form_open($form, "", $hidden);
                ?>
                    <fieldset>
                        <legend>Data Pelanggan</legend>
                        <div class="form-group row">
                            <label for="nama_pelanggan" class="col-sm-2 col-form-label">Nama Pelanggan</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="nama_pelanggan" id="nama_pelanggan" placeholder="Nama Pelanggan" <?= isset($nama_pelanggan)?"value='$nama_pelanggan'":''; ?> required >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="notelp" class="col-sm-2 col-form-label">No Tlp</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="notelp" id="notelp" placeholder="No Tlp" <?= isset($notelp)?"value='$notelp'":''; ?> required >
                            </div>
                        </div>
                        <?= isset($cb_provinsi)?$cb_provinsi:''; ?>
                        
                        <div class="load_kota"></div>
                        <div class="load_kecamatan"></div>
                        <div class="form-group row">
                            <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="alamat" id="alamat" placeholder="Alamat Pengiriman"rows="5" required=""><?= isset($alamat)?"$alamat":''; ?></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="jasa_pengiriman" class="col-sm-2 col-form-label">Jasa Pengiriman</label>
                            <div class="col-sm-10">
                                <input type="text" list="jasa_pengiriman_list" class="form-control" name="jasa_pengiriman" id="jasa_pengiriman" placeholder="Jasa Pengiriman" <?= isset($jasa_pengiriman)?"value='$jasa_pengiriman'":''; ?> required >
                                <datalist id="jasa_pengiriman_list">
                                    <?php
                                        $sql = "SELECT DISTINCT jasa_pengiriman FROM sales_order";
                                        $q = $this->db->query($sql);
                                        $res = $q->result();
                                        foreach ($res as $row) {
                                            echo "<option>$row->jasa_pengiriman</option>";
                                        }
                                    ?>
                                </datalist>
                            </div>
                        </div> 
                        <!-- <div class="form-group row">
                            <div class="col-sm-10 offset-md-2">
                                <input type="button" class="btn btn-warning" name="btnCekOngkir" value="Cek Ongkir">
                            </div>
                        </div> -->
                    </fieldset>
                    <fieldset class="field_brg" data-show="0" style="display: none;">
                        <legend>Tambah Barang</legend>
                        <?= $cb_barang; ?>
                        <div class="load_ext_barang" >
                            <div class="form-group row" id="warna_barang_box" style="display: none;">
                                <label for="warna_barang" class="col-sm-2 col-form-label">Warna Barang</label>
                                <div class="col-sm-10">
                                    <input type="text" list="warna_barang_list" class="form-control" name="warna_barang" id="warna_barang" placeholder="Warna Barang" <?= isset($warna_barang)?"value='$warna_barang'":''; ?> >
                                    <datalist id="warna_barang_list">
                                    </datalist>
                                </div>
                            </div> 
                            <div class="form-group row" id="ukuran_barang_box" style="display: none;">
                                <label for="ukuran_barang" class="col-sm-2 col-form-label">Ukuran Barang</label>
                                <div class="col-sm-10">
                                    <input type="text" list="ukuran_barang_list" class="form-control" name="ukuran_barang" id="ukuran_barang" placeholder="Ukuran Barang" <?= isset($ukuran_barang)?"value='$ukuran_barang'":''; ?> >
                                    <datalist id="ukuran_barang_list">
                                    </datalist>
                                </div>
                            </div> 
                        </div>
                        <div class="form-group row">
                            <label for="jumlah_beli" class="col-sm-2 col-form-label">Jumlah</label>
                            <div class="col-sm-10">
                                <input type="number" min="1" class="form-control" name="jumlah_beli" id="jumlah_beli" placeholder="Jumlah" <?= isset($jumlah_beli)?"value='$jumlah_beli'":'value="1"'; ?> >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="harga_barang" class="col-sm-2 col-form-label">Harga Barang</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="addon-rp">Rp.</span>
                                    </div>
                                    <input type="number" min="0" class="form-control" name="harga_barang" id="harga_barang" placeholder="Harga Barang" <?= isset($harga_barang)?"value='$harga_barang'":''; ?> aria-describedby="addon-rp" >
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="potongan" class="col-sm-2 col-form-label">Potongan</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="addon-rp">Rp.</span>
                                    </div>
                                    <input type="number" min="0" class="form-control" name="potongan" id="potongan" placeholder="Potongan" <?= isset($potongan)?"value='$potongan'":'value="0"'; ?> aria-describedby="addon-rp" >
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="total" class="col-sm-2 col-form-label">Total</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="addon-rp-total">Rp.</span>
                                    </div>
                                    <input type="number" min="0" class="form-control" name="total" id="total" placeholder="Total" <?= isset($total)?"value='$total'":''; ?> aria-describedby="addon-rp-total" >
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-10 offset-md-2">
                                <input type="button" class="btn btn-warning btnAddBarang" name="btnAddBarang" value="Tambah Barang">
                                <input type="button" class="btn btn-danger btnBatal" name="btnBatal" value="Batal">
                            </div>
                        </div>
                    </fieldset>
                    
                    <fieldset>
                        <legend>Data Barang</legend>
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <input type="button" class="btn btn-success btnTambahBarang" name="btnAddBarang" value="Tambah Barang">
                            </div>
                        </div>
                        <div class="table_detail_box">
                        <table class="table">
                            <thead>
                                <tr>   
                                    <th>Barang</th>
                                    <th>Jumlah</th>
                                    <th>Harga</th>
                                    <th>Potongan</th>
                                    <th>Total</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="tr_barang">
                                <!-- <tr id="tr_brg_1">
                                    <td>HB<br>(Merah - XXL)</td>
                                    <td>1</td>
                                    <td>Rp. 200000</td>
                                    <td>Rp. 200000</td>
                                    <td>Rp. 200000</td>
                                    <td>
                                        <button type="button" onclick="ubah_brg('tr_brg_1')" class="btn btn-xs btn-info" ><i class="fa fa-pencil-alt"></i></button>
                                        <button type="button" onclick="hapus_brg('tr_brg_1')" class="btn btn-xs btn-danger" ><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr> -->
                            </tbody>
                        </table>
                        </div>
                    </fieldset>
                    <div class="form-group row">
                        <div class="col-sm-2 offset-md-10 text-right">
                            <input type="submit" class="btn btn-primary" name="btnSimpan" value="Simpan">
                        </div>
                    </div>
                </form>
                <?php elseif(isset($detail)): ?>
                <table class="table">
                    <tbody>
                    <?php
                        foreach ($detail as $key => $value) {
                            if(!is_array($value) && $key!="total_order"){
                    ?>
                        <tr>
                            <th style="width:20%;"><?= $key; ?></th>
                            <td style="width:1%;">:</td>
                            <td><?= $value; ?></td>
                        </tr>
                    <?php
                            }
                        }
                    ?>
                    </tbody>
                </table>
                <table class="table dataTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Barang</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Potongan</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $i=0;
                            foreach($detail['order'] as $k => $v):
                        ?>
                        <tr>
                            <td><?= ++$i; ?></td>
                            <td><?= $v['barang']; ?></td>
                            <td><?= $v['jumlah_order']; ?></td>
                            <td><?= $v['harga_barang']; ?></td>
                            <td><?= $v['potongan']; ?></td>
                            <td><?= $v['subtotal']; ?></td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5">Total</th>
                            <th><?= $detail['total_order']; ?></th>
                        </tr>
                    </tfoot>
                </table>
                <?php if(isset($pengiriman)): ?>
                <h3>Pengiriman</h3>
                <table class="table">
                    <tbody>
                    <?php
                        foreach ($pengiriman as $key => $value) {
                    ?>
                        <tr>
                            <th style="width:20%;"><?= $key; ?></th>
                            <td style="width:1%;">:</td>
                            <td><?= $value; ?></td>
                        </tr>
                    <?php
                        }
                    ?>
                    </tbody>
                </table>
                <?php endif; ?>
                <?= isset($feedback)?$feedback:''; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">

    $(document).ajaxStart(function(){
        $(".ajax_loading_box").show();
    });
    $(document).ajaxComplete(function(){
        $(".ajax_loading_box").hide();
    });
<?php if(isset($form)): ?>
    var data_barang = [];
    $(document).ready(function(){
        var kode_barang = $("#kode_barang").val();
        load_harga(kode_barang);

        $("#kode_barang").change(function(){
            load_harga($(this).val());
        });

        $("#jumlah_beli").change(function(){
            var jml = $(this).val();
            var pot = $("#potongan").val();
            var hrg = $("#harga_barang").val();
            var tot = (jml*hrg)-pot;
            $("#total").val(tot);
        });

        $("#potongan").change(function(){
            var jml = $("#jumlah_beli").val();
            var pot = $(this).val();
            var hrg = $("#harga_barang").val();
            var tot = (jml*hrg)-pot;
            $("#total").val(tot);
        });
        $('.cb_provinsi').select2();

        $('.cb_provinsi').change(function(){
            var val = $(this).val();
            $(".load_kota").load('<?= base_url("sales_order/cb_kota/"); ?>'+val, function(){
                $('.cb_kota').select2();
                $('.cb_kota').change(function(){
                    var v = $(this).val();
                    $(".load_kecamatan").load('<?= base_url("sales_order/cb_kecamatan/"); ?>'+v, function(){
                        $('.cb_kecamatan').select2();
                    });
                });
            });
        });

        <?php if(isset($cb_kecamatan)): ?>
            var val = "<?= $id_provinsi; ?>";
            $(".load_kota").load('<?= base_url("sales_order/cb_kota/"); ?>'+val+"/<?= $cb_kota; ?>", function(){
                $('.cb_kota').select2();
                var v = "<?= $cb_kota; ?>";
                $(".load_kecamatan").load('<?= base_url("sales_order/cb_kecamatan/"); ?>'+v+"/<?= $cb_kecamatan; ?>", function(){
                    $('.cb_kecamatan').select2();
                });
            });
        <?php endif; ?>

        <?php if(isset($barang)):
            foreach($barang as $k => $v):
        ?>
            var tmp_brg = {
                            "kd_brg" : '<?= $v["kode_brg"]; ?>',
                            "kode_barang" : '<?= $v["kode_barang"]; ?>',
                            "nama_barang" : '<?= $v["barang"]; ?>',
                            "warna" : '<?= $v["warna"]; ?>',
                            "ukuran" : '<?= $v["ukuran"]; ?>',
                            "jumlah" : '<?= $v["jumlah_order"]; ?>',
                            "harga_barang" : '<?= $v["harga_barang"]; ?>',
                            "potongan" : '<?= $v["potongan"]; ?>',
                            "total" : '<?= $v["subtotal"]; ?>',
                            };
                data_barang.push(tmp_brg);
        <?php endforeach; ?>
            draw_tr(data_barang);
            console.log(data_barang);
        <?php endif; ?>

        $('.btnAddBarang').click(function(){
            var kode_barang = $("#kode_barang").val();
            var barang = $("#kode_barang").find(":selected").text();
            var warna = $("#warna_barang").val();
            var ukuran = $("#ukuran_barang").val();
            var jumlah = $("#jumlah_beli").val();
            var harga_barang = $("#harga_barang").val();
            var potongan = $("#potongan").val();
            var total = (jumlah * harga_barang) - potongan;
            var kd_brg = kode_barang+(warna!=""?"."+warna:"")+(ukuran!=""?"."+ukuran:"")
            var tmp_brg = {
                            "kd_brg" : kd_brg,
                            "kode_barang" : kode_barang,
                            "nama_barang" : barang,
                            "warna" : warna,
                            "ukuran" : ukuran,
                            "jumlah" : jumlah,
                            "harga_barang" : harga_barang,
                            "potongan" : potongan,
                            "total" : total,
                            };
            var id = cek_barang(kd_brg);
            if(id != -1){
                data_barang[id] = tmp_brg;
            }else{
                data_barang.push(tmp_brg);
            }
            draw_tr(data_barang);
            form_barang(0);
            console.log(data_barang);
        });

        $(".btnTambahBarang").click(function(){
            form_barang(1);
        });

        $(".btnBatal").click(function(){
            form_barang(0);
        });

    });


    function load_harga(kode_barang) {
        $.ajax({
            type : 'POST',
            url : '<?= base_url("sales_order/get_barang");?>/',
            data: 'kode='+kode_barang,
            success : function(response){
                var res = JSON.parse(response);
                harga_ajax(res.harga_jual);
                if(res.warna_barang!=''){
                    $("#warna_barang_box").show();
                    $("#warna_barang").attr("data-required",true);
                    $("#warna_barang_list").html("");
                    var war = res.warna_barang.split(",");
                    war.forEach(function(t, i){
                        $("#warna_barang_list").append("<option>"+t+"</option>");
                    });
                }else{
                    $("#warna_barang_box").hide();
                    $("#warna_barang").attr("data-required",false);
                }
                if(res.ukuran_barang!=''){
                    $("#ukuran_barang_box").show();
                    $("#ukuran_barang").attr("data-required",true);
                    $("#ukuran_barang_list").html("");
                    var war = res.ukuran_barang.split(",");
                    war.forEach(function(t, i){
                        $("#ukuran_barang_list").append("<option>"+t+"</option>");
                    });
                }else{
                    $("#ukuran_barang_box").hide();
                    $("#ukuran_barang").attr("data-required",false);
                }

            }
        });
    }

    function harga_ajax(hrg) {
        var jml = $("#jumlah_beli").val();
        var tot = jml*hrg;
        $("#total").val(tot);
        $("#harga_barang").val(tot);
    }

    function cek_barang(kode) {
        var ret = -1;
        data_barang.forEach(function(e, i){
            if(kode == e.kd_brg){
                ret = i;
            }
        });
        return ret;
    }

    function draw_tr(brng) {
        var ret = '';
        $(".tr_barang").html("");
        
        data_barang.forEach(function(brg, i){
            console.log(i);
            ret += '<tr id="tr_brg_'+brg.kd_brg+'">';
                ret += '<td>'+brg.nama_barang+'<br>('+brg.warna+' - '+brg.ukuran+')</td>';
                ret += '<td>'+brg.jumlah+'</td>';
                ret += '<td>Rp. '+numberWithCommas(brg.harga_barang)+'</td>';
                ret += '<td>Rp. '+numberWithCommas(brg.potongan)+'</td>';
                ret += '<td>Rp. '+numberWithCommas(brg.total)+'</td>';
                ret += '<td>';
                    ret += '<button type="button" onclick="ubah_brg(\''+brg.kd_brg+'\')" class="btn btn-xs btn-info" ><i class="fa fa-pencil-alt"></i></button>';
                    ret += '<button type="button" onclick="hapus_brg(\''+brg.kd_brg+'\')" class="btn btn-xs btn-danger" ><i class="fa fa-trash"></i></button>';
                ret += '</td>';
            ret += '</tr>';
            ret += '<input type="hidden" name="kode_barang[]" value="'+brg.kode_barang+'" />';
            ret += '<input type="hidden" name="warna_barang[]" value="'+brg.warna+'" />';
            ret += '<input type="hidden" name="ukuran_barang[]" value="'+brg.ukuran+'" />';
            ret += '<input type="hidden" name="jumlah_beli[]" value="'+brg.jumlah+'" />';
            ret += '<input type="hidden" name="harga_barang[]" value="'+brg.harga_barang+'" />';
            ret += '<input type="hidden" name="potongan[]" value="'+brg.potongan+'" />';
            ret += '<input type="hidden" name="subtotal_order[]" value="'+brg.total+'" />';
        });
        $(".tr_barang").append(ret);
    }

    function ubah_brg(kode){
        form_barang(1);
        var i = cek_barang(kode);
        var tmp_brg = data_barang[i];
        console.log(tmp_brg);
        $("#kode_barang option").each(function(index){
            if($(this).val() == tmp_brg.kode_barang){
                $(this).attr('selected', true);
                load_harga($(this).val());
                console.log($(this).val());
            }
        });
        $("#warna_barang").val(tmp_brg.warna);
        $("#ukuran_barang").val(tmp_brg.ukuran);
        $("#jumlah_beli").val(tmp_brg.jumlah);
        $("#harga_barang").val(tmp_brg.harga_barang);
        $("#potongan").val(tmp_brg.potongan);
        $("#total").val(tmp_brg.total);


    }

    function hapus_brg(kode){
        var i = cek_barang(kode);
        data_barang.splice(i, 1);
        draw_tr(data_barang);
    }

    function form_barang(idd) {
        if(idd==1){
            $(".btnTambahBarang").hide();
            $(".field_brg").show();
            $("#warna_barang").val("");
            $("#ukuran_barang").val("");
            $("#jumlah_beli").val("1");
        }else{
            $(".btnTambahBarang").show();
            $(".field_brg").hide();
        }
    }

    function numberWithCommas(x) {
        return x.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
    }
<?php endif; ?>
</script>

<!--  -->