<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
                    

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"> <i class="fa fa-warehouse"></i> Gudang</h1>
</div>
<div class="row row_angket">
	<div class="col mb-4">
		<div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary"><?= ucfirst($ket); ?> Gudang</h6>
                <div class="dropdown no-arrow">
                    <?= isset($add)?$add:''; ?>
                </div>
            </div>
            <div class="card-body">
                <?= isset($table)?$table:''; ?>
                <?php if(isset($form)):
                    $hidden = array(
                                        'id_gudang' => isset($id_gudang)?$id_gudang:''
                                    );
                    echo form_open($form, '', $hidden);
                ?>
                    <div class="form-group row">
                        <label for="nama_gudang" class="col-sm-2 col-form-label">Nama Gudang</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="nama_gudang" id="nama_gudang" placeholder="Nama Gudang" <?= isset($nama_gudang)?"value='$nama_gudang'":''; ?> required >
                        </div>
                    </div>
                    <?= isset($cb_provinsi)?$cb_provinsi:''; ?>
                    <div class="load_kota"></div>
                    <div class="load_kecamatan"></div>
                    <div class="form-group row">
                        <label for="alamat" class="col-sm-2 col-form-label">Alamat Gudang</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="alamat" id="alamat" placeholder="Alamat Gudang" rows="5" required><?= isset($alamat)?$alamat:''; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-10 offset-md-2">
                            <input type="submit" class="btn btn-primary" name="btnSimpan" value="Simpan">
                        </div>
                    </div>
                </form>
                <?php endif; ?>
                <?php if(isset($detail)):
                ?>
                <table class="table">
                    <tbody>
                        <tr>
                            <th width="20%">Kode Barang</th>
                            <td width="1%">:</td>
                            <td><?= $kode_barang; ?></td>
                        </tr>
                        <tr>
                            <th>Nama Barang</th>
                            <td>:</td>
                            <td><?= $nama_barang; ?></td>
                        </tr>
                        <tr>
                            <th>Harga Jual</th>
                            <td>:</td>
                            <td>Rp. <?= number_format($harga_jual); ?></td>
                        </tr>
                        <tr>
                            <th>Beban</th>
                            <td>:</td>
                            <td style="padding:0;">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Nama Beban</th>
                                            <th>Nominal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php $tot=0; foreach ($beban as $k => $v): $tot+=$v['nominal']; ?>
                                        <tr>
                                            <td><?= $v['nama_beban']; ?></td>
                                            <td class="text-right">Rp. <?= number_format($v['nominal']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Total</th>
                                            <th class="text-right">Rp. <?= number_format($tot); ?></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <th>Laba Barang</th>
                            <td>:</td>
                            <td>Rp. <?= number_format($laba_barang); ?></td>
                        </tr>
                    </tbody>
                </table>
                <a href="<?= base_url('barang'); ?>" class="btn btn-success">Kembali</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('.cb_provinsi').select2();

        $('.cb_provinsi').change(function(){
            var val = $(this).val();
            $(".load_kota").load('<?= base_url("gudang/cb_kota/"); ?>'+val, function(){
                $('.cb_kota').select2();
                $('.cb_kota').change(function(){
                    var v = $(this).val();
                    $(".load_kecamatan").load('<?= base_url("gudang/cb_kecamatan/"); ?>'+v, function(){
                        $('.cb_kecamatan').select2();
                    });
                });
            });
        });

        <?php if(isset($id_provinsi)): ?>
            var url_kot = "<?= base_url("gudang/cb_kota/"); ?>"; 
            var url_kec = "<?= base_url("gudang/cb_kecamatan/"); ?>"; 
            var val = "<?= $id_provinsi; ?>"; 
            var kot = "<?= $id_kota; ?>"; 
            var kec = "<?= $id_kecamatan; ?>"; 
            $(".load_kota").load(url_kot+'/'+val+'/'+kot, function(){
                $('.cb_kota').select2();
            });
            $(".load_kecamatan").load(url_kec+'/'+kot+'/'+kec, function(){
                $('.cb_kecamatan').select2();
            });
        <?php endif; ?>
    });

</script>

<!--  -->