
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
                    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
                    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
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
                    </fieldset>
                    <fieldset>
                        <legend>Data Barang</legend>
                        <?= $cb_barang; ?>
                        <div class="form-group row">
                            <label for="harga_barang" class="col-sm-2 col-form-label">Harga Barang</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="addon-rp">Rp.</span>
                                    </div>
                                    <input type="number" min="0" class="form-control" name="harga_barang" id="harga_barang" placeholder="Harga Barang" <?= isset($harga_barang)?"value='$harga_barang'":''; ?> required aria-describedby="addon-rp" >
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="jumlah_beli" class="col-sm-2 col-form-label">Jumlah</label>
                            <div class="col-sm-10">
                                <input type="number" min="1" class="form-control" name="jumlah_beli" id="jumlah_beli" placeholder="Jumlah" <?= isset($jumlah_beli)?"value='$jumlah_beli'":'value="1"'; ?> required >
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="total" class="col-sm-2 col-form-label">Total</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="addon-rp-total">Rp.</span>
                                    </div>
                                    <input type="number" min="0" class="form-control" name="total" id="total" placeholder="Total" <?= isset($total)?"value='$total'":''; ?> required aria-describedby="addon-rp-total" >
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="keterangan" class="col-sm-2 col-form-label">Keterangan</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="keterangan" id="keterangan" placeholder="Keterangan" rows="5"><?= isset($keterangan)?"$keterangan":''; ?></textarea>
                            </div>
                        </div>
                    </fieldset>
                    
                    <div class="form-group row">
                        <div class="col-sm-10 offset-md-2">
                            <input type="submit" class="btn btn-primary" name="btnSimpan" value="Simpan">
                        </div>
                    </div>
                </form>
                <?php elseif(isset($detail)): ?>
                <table class="table">
                    <tbody>
                    <?php
                        foreach ($detail as $key => $value) {
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
    $(document).ready(function(){
        var kode_barang = $("#kode_barang").val();
        //console.log(kode_barang);
        load_harga(kode_barang);

        $("#kode_barang").change(function(){
            load_harga($(this).val());
        });

        $("#jumlah_beli").change(function(){
            var jml = $(this).val();
            var hrg = $("#harga_barang").val();
            var tot = jml*hrg;
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

    });

    $(document).ajaxStart(function(){
        $(".ajax_loading_box").show();
    });
    $(document).ajaxComplete(function(){
        $(".ajax_loading_box").hide();
    });

    function load_harga(kode_barang) {
        $.ajax({
            type : 'POST',
            url : '<?= base_url("sales_order/get_harga");?>/'+kode_barang,
            success : function(response){
                $("#harga_barang").val(response);
                var jml = $("#jumlah_beli").val();
                var tot = jml*response;
                $("#total").val(tot);
            }
        });
    }
</script>

<!--  -->