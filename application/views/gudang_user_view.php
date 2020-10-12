<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
                    

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"> <i class="fa fa-warehouse"></i> Bagian Gudang</h1>
</div>
<div class="row row_angket">
	<div class="col mb-4">
		<div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary"><?= ucfirst($ket); ?> Bagian Gudang</h6>
                <div class="dropdown no-arrow">
                    <?= isset($add)?$add:''; ?>
                </div>
            </div>
            <div class="card-body">
                <?= isset($table)?$table:''; ?>
                <?php if(isset($form)):
                    $hidden = array(
                                        'id_gudang_user' => isset($id_gudang_user)?$id_gudang_user:'',
                                        'id_jabatan' => isset($id_jabatan)?$id_jabatan:'',
                                    );
                    echo form_open($form, '', $hidden);
                ?>
                    <div class="form-group row">
                        <label for="nama" class="col-sm-2 col-form-label">Nama User</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="nama" id="nama" placeholder="Nama Bagian User" <?= isset($nama)?"value='$nama'":''; ?> required >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="username" class="col-sm-2 col-form-label">Username</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="username" id="username" placeholder="Username" <?= isset($username)?"value='$username' readonly":''; ?> required >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password" class="col-sm-2 col-form-label">Password</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="password" id="password" placeholder="Password" <?= isset($password)?"value='$password'":''; ?> required >
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="gudang" class="col-sm-2 col-form-label">Gudang</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="gudang" id="gudang" placeholder="Gudang" <?= isset($nama_gudang)?"value='$nama_gudang'":''; ?> required >
                            <input type="hidden" name="id_gudang" id="id_gudang" <?= isset($id_gudang)?"value='$id_gudang'":''; ?> required >
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


<div class="modal fade" id="modalGudang" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Data Gudang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body load-modal">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
        </div>
    </div>
</div>



<script type="text/javascript">
    $(document).ready(function(){
        $("#gudang").focus(function(){
            $(".load-modal").load('<?= base_url("gudang_user/gen_table_gudang"); ?>');
            $('#modalGudang').modal('show');
        });
    });

    function pilih_gudang(id_gudang, nama_gudang) {
        $('#modalGudang').modal('hide');
        $("#id_gudang").val(id_gudang);
        $("#gudang").val(nama_gudang);
    }

</script>

<!--  -->