<?php
    
    $q = $this->Menu_model->get_where(["level" => $this->session->userdata('user')->level, "sts_menu" => 1]);
    $res = $q->result_array();
    //echo $this->db->last_query();
    $menu = [];
    foreach ($res as $row) {
        $menu[$row['parent_menu']][] = $row;
    }

    foreach ($menu as $key => $value) {
        if($key!="MAIN"){
            echo '<hr class="sidebar-divider">';
            echo '<div class="sidebar-heading">'.$key.'</div>';
        }
        foreach ($value as $a => $b) {
            echo '<li class="nav-item" id="'.$b['nama_menu'].'">';
            echo '<a class="nav-link" href="'.base_url($b['link_menu']).'">';
            echo '<i class="fas fa-fw '.$b['icon_menu'].'"></i>';
            echo '<span>'.$b['nama_menu'].'</span>';
            echo '</a></li>';
        }
    }
?>