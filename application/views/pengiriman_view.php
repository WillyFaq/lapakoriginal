
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"> <i class="fas fa-box-open"></i> Pengiriman</h1>
</div>
<div class="row">
	<div class="col mb-4">
		<div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary"><?= ucfirst($ket); ?> Pengiriman</h6>
                <div class="dropdown no-arrow">
                    <?= isset($add)?$add:''; ?>
                </div>
            </div>
            <div class="card-body">
                <?= isset($table)?$table:''; ?>
                <?php if(isset($detail)): 
                        //print_pre($detail);
                        echo '<table class="table"><tbody>';
                        foreach ($detail as $k => $v):
                            echo "<tr><td></td><td></td><th>$k</th></tr>";
                            foreach ($v as $a => $b):
                ?>
                            <tr>
                                <th style="width:20%;"><?= $a; ?></th>
                                <td style="width:1%;">:</td>
                                <td><?= $b; ?></td>
                            </tr>
                <?php 
                            endforeach; 
                        endforeach; 
                        echo '</tbody></table>';
                    endif; 
                ?>
                <?php if(isset($form)):
                
                    $hidden = array('id_pengiriman' => isset($id_pengiriman)?$id_pengiriman:'');
                    echo form_open($form, '', $hidden);
                ?>
                    <fieldset>
                        <legend>Data Transaksi</legend>
                        <table class="table">
                            <tbody>
                            <?php
                                foreach ($transaksi as $key => $value) {
                                    if($key=="Id Transaksi"){
                                        echo "<input type=\"hidden\" name=\"id_transaksi\" value=\"$value\">";
                                    }
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
                    </fieldset>
                    <?php
                    if($form == "pengiriman/acc_add"):
                    ?>

                    <fieldset>
                        <legend>Form Acc Pengiriman</legend>
                        <div class="form-group row">
                            <label for="gudang" class="col-sm-2 col-form-label">Gudang</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="nama_gudang" id="nama_gudang" placeholder="Nama Gudang" <?= isset($nama_gudang)?"value='$nama_gudang' readonly":'required'; ?>  >
                                <input type="hidden" id="id_gudang" name="id_gudang" <?= isset($id_gudang)?"value='$id_gudang'":'required'; ?>>
                            </div>
                        </div>
                    </fieldset>

                    <?php
                    else:
                    ?>
                    <fieldset>
                        <legend>Form Pengiriman</legend>
                        <div class="form-group row">
                            <label for="jasa_pengiriman" class="col-sm-2 col-form-label">Jasa Pengiriman</label>
                            <div class="col-sm-10">
                                <input type="text" list="jasa" class="form-control" name="jasa_pengiriman" id="jasa_pengiriman" placeholder="Jasa Pengiriman" <?= isset($jasa_pengiriman)?"value='$jasa_pengiriman' readonly":'required'; ?>  >
                                <datalist id="jasa">
                                    <option>JNE</option>
                                    <option>JNT</option>
                                </datalist>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="no_resi" class="col-sm-2 col-form-label">No Resi</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="no_resi" id="no_resi" placeholder="No Resi" <?= isset($no_resi)?"value='$no_resi' readonly":'required'; ?>  >
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="tgl_kirim" class="col-sm-2 col-form-label">Tgl Kirim </label>
                            <div class="col-sm-10">
                                <input type="date" class="form-control" name="tgl_kirim" id="tgl_kirim" placeholder="Tgl Kirim" <?= isset($tgl_kirim)?"value='".date("Y-m-d", strtotime($tgl_kirim))."' readonly":'required'; ?>  >
                            </div>
                        </div>
                        <?php if(isset($status_pengiriman)): ?>
                        <div class="form-group row">
                            <label for="status_pengiriman" class="col-sm-2 col-form-label">Status Pengiriman</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="status_pengiriman" id="status_pengiriman" required>
                                    <option value="0">Terkirim</option>
                                    <option value="1">Diterima</option>
                                </select>
                            </div>
                        </div>
                        <?php endif; ?>
                    </fieldset>
                    <?php endif; ?>
                    <div class="form-group row">
                        <div class="col-sm-10 offset-md-2">
                            <input type="submit" class="btn btn-primary" name="btnSimpan" value="Simpan">
                        </div>
                    </div>
                </form>
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

<div class="modal fade" id="promtModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Anda yakin?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="ket_gudang">-</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                <button class="btn btn-success btn_iya" type="button" data-dismiss="modal">Iya</button>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function(){

        $("#nama_gudang").focus(function(){
            var kode = '<?= isset($kode_barang)?$kode_barang:''; ?>';
            var bth = '<?= isset($jumlah)?$jumlah:''; ?>';
            $(".load-modal").load('<?= base_url("pengiriman/gen_table_gudang/"); ?>'+kode+"/"+bth);
            $('#modalGudang').modal('show');
        });

    });

    function pilih_gudang(kode_barang, nama_barang, kt) {
        if(kt==0){
            $(".btn_iya").attr("onclick", "pilih_gudang('"+kode_barang+"', '"+nama_barang+"', 1)");
            $("#ket_gudang").html(nama_barang+" tidak mempunyai cukup stok!");
            $('#promtModal').modal('show');
        }else{
            $('#modalGudang').modal('hide');
            $("#id_gudang").val(kode_barang);
            $("#nama_gudang").val(nama_barang);
        }
    }

</script>

<!--  -->