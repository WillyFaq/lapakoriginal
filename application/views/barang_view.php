<link rel="stylesheet" href="<?= base_url('assets/vendor/tagsinput/dist/tagsinput.css'); ?>">
<script type="text/javascript" src="<?= base_url('assets/vendor/tagsinput/dist/tagsinput.js'); ?>"></script>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"> <i class="fa fa-box-open"></i> Barang</h1>
</div>
<div class="row row_angket">
	<div class="col mb-4">
		<div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary"><?= ucfirst($ket); ?> Barang</h6>
                <div class="dropdown no-arrow">
                    <?= isset($add)?$add:''; ?>
                </div>
            </div>
            <div class="card-body">
                <?= isset($table)?$table:''; ?>
                <?php if(isset($form)):
                    echo form_open($form);
                ?>
                    <div class="form-group row">
                        <label for="kode_barang" class="col-sm-2 col-form-label">Kode Bahan</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="kode_barang" id="kode_barang" placeholder="Kode Bahan" <?= isset($kode_barang)?"value='$kode_barang' readonly":'required'; ?> maxlength="50">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="nama_barang" class="col-sm-2 col-form-label">Nama Barang</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="nama_barang" id="nama_barang" placeholder="Nama Barang" <?= isset($nama_barang)?"value='$nama_barang'":''; ?> required >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="warna_barang" class="col-sm-2 col-form-label">Warna</label>
                        <div class="col-sm-10">
                            <input type="text" data-role="tagsinput" class="form-control" name="warna_barang" id="warna_barang" placeholder="Warna (kosongkan jika tidak ada warna)" <?= isset($warna_barang)?"value='$warna_barang'":''; ?> >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="ukuran_barang" class="col-sm-2 col-form-label">Ukuran</label>
                        <div class="col-sm-10">
                            <input type="text" data-role="tagsinput" class="form-control" name="ukuran_barang" id="ukuran_barang" placeholder="Ukuran (kosongkan jika tidak ada warna)" <?= isset($ukuran_barang)?"value='$ukuran_barang'":''; ?>>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="harga_jual" class="col-sm-2 col-form-label">Harga Jual</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="addon-rp">Rp.</span>
                                </div>
                                <input type="number" min="0" class="form-control" name="harga_jual" id="harga_jual" placeholder="Harga Jual" <?= isset($harga_jual)?"value='$harga_jual'":''; ?> required aria-describedby="addon-rp" >
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="setting_harga" class="col-sm-2 col-form-label">Setting Harga</label>
                        <div class="col-sm-10">
                            <?php
                                $rb_fix_harga = ' checked="true" ';
                                $rb_nfix_harga = ' ';
                                if(isset($setting_harga)){
                                    if($setting_harga==0){
                                        $rb_nfix_harga = ' checked="true" ';
                                    }
                                }
                            ?>
                            <input type="radio" name="setting_harga" value="1" id="rb_fix_harga" <?= $rb_fix_harga; ?> > <label for="rb_fix_harga"> Fix </label> &nbsp;&nbsp;&nbsp;
                            <input type="radio" name="setting_harga" value="0" id="rb_nfix_harga" <?= $rb_nfix_harga; ?> > <label for="rb_nfix_harga"> Mengikuti Ukuran </label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="beban" class="col-sm-2 col-form-label">Beban</label>
                        <div class="col-sm-10">
                            <button type="button" class="btn btn-success btn-sm btn_add_beban" title="Tambah Beban" data-toggle="tooltip" data-placement="right"><i class="fa fa-plus"></i></button>
                            <br>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Nama Beban</th>
                                        <th>Nominal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="load_tbody">
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="laba_barang" class="col-sm-2 col-form-label">Laba Barang</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="addon-rp2">Rp.</span>
                                </div>
                                <input type="number" min="0" class="form-control" name="laba_barang" id="laba_barang" placeholder="Laba Barang" <?= isset($laba_barang)?"value='$laba_barang'":''; ?> readonly aria-describedby="addon-rp2" >
                            </div>
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
                            <th>Warna Barang</th>
                            <td>:</td>
                            <td><?= $warna_barang; ?></td>
                        </tr>
                        <tr>
                            <th>Ukuran Barang</th>
                            <td>:</td>
                            <td><?= $ukuran_barang; ?></td>
                        </tr>
                        <tr>
                            <th>Harga Jual</th>
                            <td>:</td>
                            <td>Rp. <?= number_format($harga_jual); ?></td>
                        </tr>
                        <tr>
                            <th>Setting Harga</th>
                            <td>:</td>
                            <td><?= $setting_harga==1?'Fix':'Mengikuti Ukuran'; ?></td>
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
        $(".btn_add_beban").click(function(){
            var len = $(".load_tbody>tr").length;
            len = parseInt(len);
            $(".load_tbody").append(add_beban(len));
        });

        $("#harga_jual").change(function(){
            hitung_laba();
        });
    });

    <?php
        if(isset($beban)){
            $i = 0;
            foreach ($beban as $k => $v) {
                echo "$(\".load_tbody\").append(update_beban($i, '$v[id]', '$v[nama_beban]', '$v[nominal]'));";
                echo "hitung_laba();";
                $i++;
            }
        }
    ?>

    function hitung_laba() {
        var jual = parseInt($("#harga_jual").val());
        var beban = hitung_nominal();
        var laba = 0;
        if(jual == 0 || beban == 0){

        }else{
            laba = jual-beban;
        }
        $("#laba_barang").val(laba);
    }

    function hitung_nominal() {
        var ret = 0;
        $(".load_tbody>tr").each(function(i, elm){
            var nom = parseInt($("#nominal_"+i).val());
            ret += nom;
        });
        return ret;
    }

    function add_beban(i){
        var html = "";
        html += '<tr id="beban_'+i+'">';
            html += '<td>';
                html += '<input type="text" class="form-control" name="nama_beban[]" id="nama_beban_'+i+'" placeholder="Nama Beban" required >';
            html += '</td>';
            html += '<td>';
                html += '<div class="input-group">';
                    html += '<div class="input-group-prepend">';
                        html += '<span class="input-group-text" id="addon-rp">Rp.</span>';
                    html += '</div>';
                    html += '<input type="number" min="0" class="form-control" onchange="hitung_laba()" name="nominal[]" id="nominal_'+i+'" placeholder="Nominal" required >';
                html += '</div>';
            html += '</td>';
            html += '<td>';
                html += '<button type="button" class="btn btn-sm btn-danger" onclick="hapus_beban('+i+')" title="Hapus Beban" data-toggle="tooltip"><i class="fa fa-trash"></i></button>';
            html += '</td>';
        html += '</tr>';
        return html;
    }

    function update_beban(i, id, nama, nominal){
        var html = "";
        html += '<tr id="beban_'+i+'">';
            html += '<td>';
                html += '<input type="hidden" name="id_beban[]" id="id_beban_'+i+'" value="'+id+'" >';
                html += '<input type="text" class="form-control" name="nama_beban[]" id="nama_beban_'+i+'" placeholder="Nama Beban" value="'+nama+'" required >';
            html += '</td>';
            html += '<td>';
                html += '<div class="input-group">';
                    html += '<div class="input-group-prepend">';
                        html += '<span class="input-group-text" id="addon-rp">Rp.</span>';
                    html += '</div>';
                    html += '<input type="number" min="0" class="form-control" onchange="hitung_laba()" name="nominal[]" id="nominal_'+i+'" placeholder="Nominal" value="'+nominal+'" required >';
                html += '</div>';
            html += '</td>';
            html += '<td>';
                html += '<button type="button" class="btn btn-sm btn-danger" onclick="hapus_beban('+i+')" title="Hapus Beban" data-toggle="tooltip"><i class="fa fa-trash"></i></button>';
            html += '</td>';
        html += '</tr>';
        return html;
    }

    function hapus_beban(i) {
        $("#beban_"+i).remove();
        hitung_laba();
    }
</script>

<!--  -->