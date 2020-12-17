<?php

if($this->session->userdata('user')->level==1 /*|| $this->session->userdata('user')->level==3*/):
    $judul = "Notifikasi Pengiriman :";
    $nr = 100;
    $res = [];
    if($this->session->userdata('user')->level==1){
        $judul = "Notifikasi Pengiriman :";
        $q = $this->Sales_order_model->get_where2(['status_order' => 0]);
        $res = $q->result();
        $nr = $q->num_rows();
    }
?>
<li class="nav-item dropdown no-arrow mx-1">
    <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-bell fa-fw"></i>
        <!-- Counter - Alerts -->
        <?php if($nr!=0): ?>
        <span class="badge badge-danger badge-counter"><?= $nr; ?></span>
        <?php endif; ?>
    </a>
    <!-- Dropdown - Alerts -->
    <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
        <h6 class="dropdown-header">
            <?= $judul; ?>
        </h6>
        <div class="scroll_notification">
            <?php foreach($res as $row): ?>
            <a class="dropdown-item d-flex align-items-center" href="<?= base_url("pengiriman/kirimkan/").e_url($row->id_transaksi); ?>">
                <div class="mr-3">
                    <div class="icon-circle bg-primary">
                        <i class="fas fa-dollar-sign text-white"></i>
                    </div>
                </div>
                <div>
                    <span class="font-weight-bold">
                        <strong><?= $row->nama_pelanggan; ?></strong>
                        <br><?= '';//$row->nama_barang; ?>
                    </span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <a class="dropdown-item text-center small text-gray-500" href="<?= base_url("pengiriman/belum/") ?>">Tampilkan Semua</a>
        <!-- <div class="dropdown-item text-center small text-gray-500"></div> -->
    </div>
</li>

<?php endif; ?>