<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends CI_Controller {

	var $icon = [
		".fa-ad", ".fa-address-book", ".fa-address-card", ".fa-adjust", ".fa-air-freshener", ".fa-align-center", ".fa-align-justify", ".fa-align-left", ".fa-align-right", ".fa-allergies", ".fa-ambulance", ".fa-american-sign-language-interpreting", ".fa-anchor", ".fa-angle-double-down", ".fa-angle-double-left", ".fa-angle-double-right", ".fa-angle-double-up", ".fa-angle-down", ".fa-angle-left", ".fa-angle-right", ".fa-angle-up", ".fa-angry", ".fa-ankh", ".fa-apple-alt", ".fa-archive", ".fa-archway", ".fa-arrow-alt-circle-down", ".fa-arrow-alt-circle-left", ".fa-arrow-alt-circle-right", ".fa-arrow-alt-circle-up", ".fa-arrow-circle-down", ".fa-arrow-circle-left", ".fa-arrow-circle-right", ".fa-arrow-circle-up", ".fa-arrow-down", ".fa-arrow-left", ".fa-arrow-right", ".fa-arrow-up", ".fa-arrows-alt", ".fa-arrows-alt-h", ".fa-arrows-alt-v", ".fa-assistive-listening-systems", ".fa-asterisk", ".fa-at", ".fa-atlas", ".fa-atom", ".fa-audio-description", ".fa-award", ".fa-baby", ".fa-baby-carriage", ".fa-backspace", ".fa-backward", ".fa-balance-scale", ".fa-ban", ".fa-band-aid", ".fa-barcode", ".fa-bars", ".fa-baseball-ball", ".fa-basketball-ball", ".fa-bath", ".fa-battery-empty", ".fa-battery-full", ".fa-battery-half", ".fa-battery-quarter", ".fa-battery-three-quarters", ".fa-bed", ".fa-beer", ".fa-bell", ".fa-bell-slash", ".fa-bezier-curve", ".fa-bible", ".fa-bicycle", ".fa-binoculars", ".fa-biohazard", ".fa-birthday-cake", ".fa-blender", ".fa-blender-phone", ".fa-blind", ".fa-blog", ".fa-bold", ".fa-bolt", ".fa-bomb", ".fa-bone", ".fa-bong", ".fa-book", ".fa-book-dead", ".fa-book-open", ".fa-book-reader", ".fa-bookmark", ".fa-bowling-ball", ".fa-box", ".fa-box-open", ".fa-boxes", ".fa-braille", ".fa-brain", ".fa-briefcase", ".fa-briefcase-medical", ".fa-broadcast-tower", ".fa-broom", ".fa-brush", ".fa-bug", ".fa-building", ".fa-bullhorn", ".fa-bullseye", ".fa-burn", ".fa-bus", ".fa-bus-alt", ".fa-business-time", ".fa-calculator", ".fa-calendar", ".fa-calendar-alt", ".fa-calendar-check", ".fa-calendar-day", ".fa-calendar-minus", ".fa-calendar-plus", ".fa-calendar-times", ".fa-calendar-week", ".fa-camera", ".fa-camera-retro", ".fa-campground", ".fa-candy-cane", ".fa-cannabis", ".fa-capsules", ".fa-car", ".fa-car-alt", ".fa-car-battery", ".fa-car-crash", ".fa-car-side", ".fa-caret-down", ".fa-caret-left", ".fa-caret-right", ".fa-caret-square-down", ".fa-caret-square-left", ".fa-caret-square-right", ".fa-caret-square-up", ".fa-caret-up", ".fa-carrot", ".fa-cart-arrow-down", ".fa-cart-plus", ".fa-cash-register", ".fa-cat", ".fa-certificate", ".fa-chair", ".fa-chalkboard", ".fa-chalkboard-teacher", ".fa-charging-station", ".fa-chart-area", ".fa-chart-bar", ".fa-chart-line", ".fa-chart-pie", ".fa-check", ".fa-check-circle", ".fa-check-double", ".fa-check-square", ".fa-chess", ".fa-chess-bishop", ".fa-chess-board", ".fa-chess-king", ".fa-chess-knight", ".fa-chess-pawn", ".fa-chess-queen", ".fa-chess-rook", ".fa-chevron-circle-down", ".fa-chevron-circle-left", ".fa-chevron-circle-right", ".fa-chevron-circle-up", ".fa-chevron-down", ".fa-chevron-left", ".fa-chevron-right", ".fa-chevron-up", ".fa-child", ".fa-church", ".fa-circle", ".fa-circle-notch", ".fa-city", ".fa-clipboard", ".fa-clipboard-check", ".fa-clipboard-list", ".fa-clock", ".fa-clone", ".fa-closed-captioning", ".fa-cloud", ".fa-cloud-download-alt", ".fa-cloud-meatball", ".fa-cloud-moon", ".fa-cloud-moon-rain", ".fa-cloud-rain", ".fa-cloud-showers-heavy", ".fa-cloud-sun", ".fa-cloud-sun-rain", ".fa-cloud-upload-alt", ".fa-cocktail", ".fa-code", ".fa-code-branch", ".fa-coffee", ".fa-cog", ".fa-cogs", ".fa-coins", ".fa-columns", ".fa-comment", ".fa-comment-alt", ".fa-comment-dollar", ".fa-comment-dots", ".fa-comment-slash", ".fa-comments", ".fa-comments-dollar", ".fa-compact-disc", ".fa-compass", ".fa-compress", ".fa-compress-arrows-alt", ".fa-concierge-bell", ".fa-cookie", ".fa-cookie-bite", ".fa-copy", ".fa-copyright", ".fa-couch", ".fa-credit-card", ".fa-crop", ".fa-crop-alt", ".fa-cross", ".fa-crosshairs", ".fa-crow", ".fa-crown", ".fa-cube", ".fa-cubes", ".fa-cut", ".fa-database", ".fa-deaf", ".fa-democrat", ".fa-desktop", ".fa-dharmachakra", ".fa-diagnoses", ".fa-dice", ".fa-dice-d20", ".fa-dice-d6", ".fa-dice-five", ".fa-dice-four", ".fa-dice-one", ".fa-dice-six", ".fa-dice-three", ".fa-dice-two", ".fa-digital-tachograph", ".fa-directions", ".fa-divide", ".fa-dizzy", ".fa-dna", ".fa-dog", ".fa-dollar-sign", ".fa-dolly", ".fa-dolly-flatbed", ".fa-donate", ".fa-door-closed", ".fa-door-open", ".fa-dot-circle", ".fa-dove", ".fa-download", ".fa-drafting-compass", ".fa-dragon", ".fa-draw-polygon", ".fa-drum", ".fa-drum-steelpan", ".fa-drumstick-bite", ".fa-dumbbell", ".fa-dumpster", ".fa-dumpster-fire", ".fa-dungeon", ".fa-edit", ".fa-eject", ".fa-ellipsis-h", ".fa-ellipsis-v", ".fa-envelope", ".fa-envelope-open", ".fa-envelope-open-text", ".fa-envelope-square", ".fa-equals", ".fa-eraser", ".fa-ethernet", ".fa-euro-sign", ".fa-exchange-alt", ".fa-exclamation", ".fa-exclamation-circle", ".fa-exclamation-triangle", ".fa-expand", ".fa-expand-arrows-alt", ".fa-external-link-alt", ".fa-external-link-square-alt", ".fa-eye", ".fa-eye-dropper", ".fa-eye-slash", ".fa-fast-backward", ".fa-fast-forward", ".fa-fax", ".fa-feather", ".fa-feather-alt", ".fa-female", ".fa-fighter-jet", ".fa-file", ".fa-file-alt", ".fa-file-archive", ".fa-file-audio", ".fa-file-code", ".fa-file-contract", ".fa-file-csv", ".fa-file-download", ".fa-file-excel", ".fa-file-export", ".fa-file-image", ".fa-file-import", ".fa-file-invoice", ".fa-file-invoice-dollar", ".fa-file-medical", ".fa-file-medical-alt", ".fa-file-pdf", ".fa-file-powerpoint", ".fa-file-prescription", ".fa-file-signature", ".fa-file-upload", ".fa-file-video", ".fa-file-word", ".fa-fill", ".fa-fill-drip", ".fa-film", ".fa-filter", ".fa-fingerprint", ".fa-fire", ".fa-fire-alt", ".fa-fire-extinguisher", ".fa-first-aid", ".fa-fish", ".fa-fist-raised", ".fa-flag", ".fa-flag-checkered", ".fa-flag-usa", ".fa-flask", ".fa-flushed", ".fa-folder", ".fa-folder-minus", ".fa-folder-open", ".fa-folder-plus", ".fa-font", ".fa-football-ball", ".fa-forward", ".fa-frog", ".fa-frown", ".fa-frown-open", ".fa-funnel-dollar", ".fa-futbol", ".fa-gamepad", ".fa-gas-pump", ".fa-gavel", ".fa-gem", ".fa-genderless", ".fa-ghost", ".fa-gift", ".fa-gifts", ".fa-glass-cheers", ".fa-glass-martini", ".fa-glass-martini-alt", ".fa-glass-whiskey", ".fa-glasses", ".fa-globe", ".fa-globe-africa", ".fa-globe-americas", ".fa-globe-asia", ".fa-globe-europe", ".fa-golf-ball", ".fa-gopuram", ".fa-graduation-cap", ".fa-greater-than", ".fa-greater-than-equal", ".fa-grimace", ".fa-grin", ".fa-grin-alt", ".fa-grin-beam", ".fa-grin-beam-sweat", ".fa-grin-hearts", ".fa-grin-squint", ".fa-grin-squint-tears", ".fa-grin-stars", ".fa-grin-tears", ".fa-grin-tongue", ".fa-grin-tongue-squint", ".fa-grin-tongue-wink", ".fa-grin-wink", ".fa-grip-horizontal", ".fa-grip-lines", ".fa-grip-lines-vertical", ".fa-grip-vertical", ".fa-guitar", ".fa-h-square", ".fa-hammer", ".fa-hamsa", ".fa-hand-holding", ".fa-hand-holding-heart", ".fa-hand-holding-usd", ".fa-hand-lizard", ".fa-hand-paper", ".fa-hand-peace", ".fa-hand-point-down", ".fa-hand-point-left", ".fa-hand-point-right", ".fa-hand-point-up", ".fa-hand-pointer", ".fa-hand-rock", ".fa-hand-scissors", ".fa-hand-spock", ".fa-hands", ".fa-hands-helping", ".fa-handshake", ".fa-hanukiah", ".fa-hashtag", ".fa-hat-wizard", ".fa-haykal", ".fa-hdd", ".fa-heading", ".fa-headphones", ".fa-headphones-alt", ".fa-headset", ".fa-heart", ".fa-heart-broken", ".fa-heartbeat", ".fa-helicopter", ".fa-highlighter", ".fa-hiking", ".fa-hippo", ".fa-history", ".fa-hockey-puck", ".fa-holly-berry", ".fa-home", ".fa-horse", ".fa-horse-head", ".fa-hospital", ".fa-hospital-alt", ".fa-hospital-symbol", ".fa-hot-tub", ".fa-hotel", ".fa-hourglass", ".fa-hourglass-end", ".fa-hourglass-half", ".fa-hourglass-start", ".fa-house-damage", ".fa-hryvnia", ".fa-i-cursor", ".fa-icicles", ".fa-id-badge", ".fa-id-card", ".fa-id-card-alt", ".fa-igloo", ".fa-image", ".fa-images", ".fa-inbox", ".fa-indent", ".fa-industry", ".fa-infinity", ".fa-info", ".fa-info-circle", ".fa-italic", ".fa-jedi", ".fa-joint", ".fa-journal-whills", ".fa-kaaba", ".fa-key", ".fa-keyboard", ".fa-khanda", ".fa-kiss", ".fa-kiss-beam", ".fa-kiss-wink-heart", ".fa-kiwi-bird", ".fa-landmark", ".fa-language", ".fa-laptop", ".fa-laptop-code", ".fa-laugh", ".fa-laugh-beam", ".fa-laugh-squint", ".fa-laugh-wink", ".fa-layer-group", ".fa-leaf", ".fa-lemon", ".fa-less-than", ".fa-less-than-equal", ".fa-level-down-alt", ".fa-level-up-alt", ".fa-life-ring", ".fa-lightbulb", ".fa-link", ".fa-lira-sign", ".fa-list", ".fa-list-alt", ".fa-list-ol", ".fa-list-ul", ".fa-location-arrow", ".fa-lock", ".fa-lock-open", ".fa-long-arrow-alt-down", ".fa-long-arrow-alt-left", ".fa-long-arrow-alt-right", ".fa-long-arrow-alt-up", ".fa-low-vision", ".fa-luggage-cart", ".fa-magic", ".fa-magnet", ".fa-mail-bulk", ".fa-male", ".fa-map", ".fa-map-marked", ".fa-map-marked-alt", ".fa-map-marker", ".fa-map-marker-alt", ".fa-map-pin", ".fa-map-signs", ".fa-marker", ".fa-mars", ".fa-mars-double", ".fa-mars-stroke", ".fa-mars-stroke-h", ".fa-mars-stroke-v", ".fa-mask", ".fa-medal", ".fa-medkit", ".fa-meh", ".fa-meh-blank", ".fa-meh-rolling-eyes", ".fa-memory", ".fa-menorah", ".fa-mercury", ".fa-meteor", ".fa-microchip", ".fa-microphone", ".fa-microphone-alt", ".fa-microphone-alt-slash", ".fa-microphone-slash", ".fa-microscope", ".fa-minus", ".fa-minus-circle", ".fa-minus-square", ".fa-mitten", ".fa-mobile", ".fa-mobile-alt", ".fa-money-bill", ".fa-money-bill-alt", ".fa-money-bill-wave", ".fa-money-bill-wave-alt", ".fa-money-check", ".fa-money-check-alt", ".fa-monument", ".fa-moon", ".fa-mortar-pestle", ".fa-mosque", ".fa-motorcycle", ".fa-mountain", ".fa-mouse-pointer", ".fa-mug-hot", ".fa-music", ".fa-network-wired", ".fa-neuter", ".fa-newspaper", ".fa-not-equal", ".fa-notes-medical", ".fa-object-group", ".fa-object-ungroup", ".fa-oil-can", ".fa-om", ".fa-otter", ".fa-outdent", ".fa-paint-brush", ".fa-paint-roller", ".fa-palette", ".fa-pallet", ".fa-paper-plane", ".fa-paperclip", ".fa-parachute-box", ".fa-paragraph", ".fa-parking", ".fa-passport", ".fa-pastafarianism", ".fa-paste", ".fa-pause", ".fa-pause-circle", ".fa-paw", ".fa-peace", ".fa-pen", ".fa-pen-alt", ".fa-pen-fancy", ".fa-pen-nib", ".fa-pen-square", ".fa-pencil-alt", ".fa-pencil-ruler", ".fa-people-carry", ".fa-percent", ".fa-percentage", ".fa-person-booth", ".fa-phone", ".fa-phone-slash", ".fa-phone-square", ".fa-phone-volume", ".fa-piggy-bank", ".fa-pills", ".fa-place-of-worship", ".fa-plane", ".fa-plane-arrival", ".fa-plane-departure", ".fa-play", ".fa-play-circle", ".fa-plug", ".fa-plus", ".fa-plus-circle", ".fa-plus-square", ".fa-podcast", ".fa-poll", ".fa-poll-h", ".fa-poo", ".fa-poo-storm", ".fa-poop", ".fa-portrait", ".fa-pound-sign", ".fa-power-off", ".fa-pray", ".fa-praying-hands", ".fa-prescription", ".fa-prescription-bottle", ".fa-prescription-bottle-alt", ".fa-print", ".fa-procedures", ".fa-project-diagram", ".fa-puzzle-piece", ".fa-qrcode", ".fa-question", ".fa-question-circle", ".fa-quidditch", ".fa-quote-left", ".fa-quote-right", ".fa-quran", ".fa-radiation", ".fa-radiation-alt", ".fa-rainbow", ".fa-random", ".fa-ravelry", ".fa-receipt", ".fa-recycle", ".fa-redo", ".fa-redo-alt", ".fa-registered", ".fa-reply", ".fa-reply-all", ".fa-republican", ".fa-restroom", ".fa-retweet", ".fa-ribbon", ".fa-ring", ".fa-road", ".fa-robot", ".fa-rocket", ".fa-route", ".fa-rss", ".fa-rss-square", ".fa-ruble-sign", ".fa-ruler", ".fa-ruler-combined", ".fa-ruler-horizontal", ".fa-ruler-vertical", ".fa-running", ".fa-rupee-sign", ".fa-sad-cry", ".fa-sad-tear", ".fa-satellite", ".fa-satellite-dish", ".fa-save", ".fa-school", ".fa-screwdriver", ".fa-scroll", ".fa-sd-card", ".fa-search", ".fa-search-dollar", ".fa-search-location", ".fa-search-minus", ".fa-search-plus", ".fa-seedling", ".fa-server", ".fa-shapes", ".fa-share", ".fa-share-alt", ".fa-share-alt-square", ".fa-share-square", ".fa-shekel-sign", ".fa-shield-alt", ".fa-ship", ".fa-shipping-fast", ".fa-shoe-prints", ".fa-shopping-bag", ".fa-shopping-basket", ".fa-shopping-cart", ".fa-shower", ".fa-shuttle-van", ".fa-sign", ".fa-sign-in-alt", ".fa-sign-language", ".fa-sign-out-alt", ".fa-signal", ".fa-signature", ".fa-sim-card", ".fa-sitemap", ".fa-skating", ".fa-skiing", ".fa-skiing-nordic", ".fa-skull", ".fa-skull-crossbones", ".fa-slash", ".fa-sleigh", ".fa-sliders-h", ".fa-smile", ".fa-smile-beam", ".fa-smile-wink", ".fa-smog", ".fa-smoking", ".fa-smoking-ban", ".fa-sms", ".fa-snowboarding", ".fa-snowflake", ".fa-snowman", ".fa-snowplow", ".fa-socks", ".fa-solar-panel", ".fa-sort", ".fa-sort-alpha-down", ".fa-sort-alpha-up", ".fa-sort-amount-down", ".fa-sort-amount-up", ".fa-sort-down", ".fa-sort-numeric-down", ".fa-sort-numeric-up", ".fa-sort-up", ".fa-spa", ".fa-space-shuttle", ".fa-spider", ".fa-spinner", ".fa-splotch", ".fa-spray-can", ".fa-square", ".fa-square-full", ".fa-square-root-alt", ".fa-stamp", ".fa-star", ".fa-star-and-crescent", ".fa-star-half", ".fa-star-half-alt", ".fa-star-of-david", ".fa-star-of-life", ".fa-step-backward", ".fa-step-forward", ".fa-stethoscope", ".fa-sticky-note", ".fa-stop", ".fa-stop-circle", ".fa-stopwatch", ".fa-store", ".fa-store-alt", ".fa-stream", ".fa-street-view", ".fa-strikethrough", ".fa-stroopwafel", ".fa-subscript", ".fa-subway", ".fa-suitcase", ".fa-suitcase-rolling", ".fa-sun", ".fa-superscript", ".fa-surprise", ".fa-swatchbook", ".fa-swimmer", ".fa-swimming-pool", ".fa-synagogue", ".fa-sync", ".fa-sync-alt", ".fa-syringe", ".fa-table", ".fa-table-tennis", ".fa-tablet", ".fa-tablet-alt", ".fa-tablets", ".fa-tachometer-alt", ".fa-tag", ".fa-tags", ".fa-tape", ".fa-tasks", ".fa-taxi", ".fa-teeth", ".fa-teeth-open", ".fa-temperature-high", ".fa-temperature-low", ".fa-tenge", ".fa-terminal", ".fa-text-height", ".fa-text-width", ".fa-th", ".fa-th-large", ".fa-th-list", ".fa-the-red-yeti", ".fa-theater-masks", ".fa-thermometer", ".fa-thermometer-empty", ".fa-thermometer-full", ".fa-thermometer-half", ".fa-thermometer-quarter", ".fa-thermometer-three-quarters", ".fa-thumbs-down", ".fa-thumbs-up", ".fa-thumbtack", ".fa-ticket-alt", ".fa-times", ".fa-times-circle", ".fa-tint", ".fa-tint-slash", ".fa-tired", ".fa-toggle-off", ".fa-toggle-on", ".fa-toilet", ".fa-toilet-paper", ".fa-toolbox", ".fa-tools", ".fa-tooth", ".fa-torah", ".fa-torii-gate", ".fa-tractor", ".fa-trademark", ".fa-traffic-light", ".fa-train", ".fa-tram", ".fa-transgender", ".fa-transgender-alt", ".fa-trash", ".fa-trash-alt", ".fa-tree", ".fa-trophy", ".fa-truck", ".fa-truck-loading", ".fa-truck-monster", ".fa-truck-moving", ".fa-truck-pickup", ".fa-tshirt", ".fa-tty", ".fa-tv", ".fa-umbrella", ".fa-umbrella-beach", ".fa-underline", ".fa-undo", ".fa-undo-alt", ".fa-universal-access", ".fa-university", ".fa-unlink", ".fa-unlock", ".fa-unlock-alt", ".fa-upload", ".fa-user", ".fa-user-alt", ".fa-user-alt-slash", ".fa-user-astronaut", ".fa-user-check", ".fa-user-circle", ".fa-user-clock", ".fa-user-cog", ".fa-user-edit", ".fa-user-friends", ".fa-user-graduate", ".fa-user-injured", ".fa-user-lock", ".fa-user-md", ".fa-user-minus", ".fa-user-ninja", ".fa-user-plus", ".fa-user-secret", ".fa-user-shield", ".fa-user-slash", ".fa-user-tag", ".fa-user-tie", ".fa-user-times", ".fa-users", ".fa-users-cog", ".fa-utensil-spoon", ".fa-utensils", ".fa-vector-square", ".fa-venus", ".fa-venus-double", ".fa-venus-mars", ".fa-vial", ".fa-vials", ".fa-video", ".fa-video-slash", ".fa-vihara", ".fa-volleyball-ball", ".fa-volume-down", ".fa-volume-mute", ".fa-volume-off", ".fa-volume-up", ".fa-vote-yea", ".fa-vr-cardboard", ".fa-walking", ".fa-wallet", ".fa-warehouse", ".fa-water", ".fa-weight", ".fa-weight-hanging", ".fa-wheelchair", ".fa-wifi", ".fa-wind", ".fa-window-close", ".fa-window-maximize", ".fa-window-minimize", ".fa-window-restore", ".fa-wine-bottle", ".fa-wine-glass", ".fa-wine-glass-alt", ".fa-won-sign", ".fa-wrench", ".fa-x-ray", ".fa-yen-sign", ".fa-yin-yang"
	];

	public function __construct() { 
		parent::__construct();
		$this->load->model("Menu_model", "", TRUE);
		$this->load->model("User_model", "", TRUE);
	}

	public function gen_table()
	{
		$query=$this->Menu_model->get_all();
		$res = $query->result();
		$num_rows = $query->num_rows();

		$tmpl = array(  'table_open'    => '<table class="table table-striped table-hover dataTable">',
				'row_alt_start'  => '<tr>',
				'row_alt_end'    => '</tr>'
			);

		$this->table->set_template($tmpl);

		$this->table->set_empty("&nbsp;");

		$this->table->set_heading('No', 'Parent', 'Nama Menu', 'Link', 'Status', 'Aksi');

		if ($num_rows > 0)
		{
			$i = 0;

			foreach ($res as $row){
				$sts = '<span class="badge badge-danger">Inactive</span>';
				if($row->sts_menu==1){
					$sts = '<span class="badge badge-success">Active</span>';
				}
				$this->table->add_row(	++$i,
							$row->parent_menu,
							$row->nama_menu,
							$row->link_menu,
							$sts,
							anchor('menu/ubah/'.e_url($row->id_menu),'<span class="fa fa-pencil-alt"></span>',array( 'title' => 'Ubah', 'class' => 'btn btn-primary btn-xs', 'data-toggle' => 'tooltip')).'&nbsp;'.
							'<button type="button" class="btn btn-xs btn-danger btn-hapus" data-id="'.e_url($row->id_menu).'" data-toggle="tooltip" title="Hapus" data-original-title="Hapus"><i class="fa fa-trash"></i></button>'
						);
			}
		}
		return  $this->table->generate();
	}

	public function index(){
		$data = array(
						"page" => "menu_view",
						"ket" => "Data",
						"add" => anchor('menu/tambah', '<i class="fa fa-plus"></i>', array("class" => "btn btn-success", "data-toggle" => "tooltip", "data-placement" => "top", "title" => "Tambah Data")),
						"table" => $this->gen_table()
						);
		$this->load->view('index', $data);
	}

	public function tambah(){
		$data = array(
						"page" => "menu_view",
						"ket" => "Tambah",
						"form" => "menu/add",
						"parent_list" => $this->Menu_model->get_parent()
						);
		$this->load->view('index', $data);
	}

	public function add(){
		$data = $this->input->post();
		unset($data['btnSimpan'], $data['id_menu']);
		print_pre($data);
		if($this->Menu_model->add($data)){
			alert_notif("success");
		}else{
			alert_notif("danger");
		}
		redirect('menu');
	}

	public function ubah($v=''){
		$data = array(
						"page" => "menu_view",
						"ket" => "Ubah",
						"form" => "menu/update",
						"parent_list" => $this->Menu_model->get_parent()
						);
		$q = $this->Menu_model->get_data(d_url($v));
		$res = $q->result();
		foreach ($res as $row) {
			$data['id_menu'] = $row->id_menu;
			$data['order_menu'] = $row->order_menu;
			$data['parent_menu'] = $row->parent_menu;
			$data['nama_menu'] = $row->nama_menu;
			$data['link_menu'] = $row->link_menu;
			$data['icon_menu'] = $row->icon_menu;
		}
		$this->load->view('index', $data);
	}

	public function update()
	{
		$data = $this->input->post();
		$id = $data['id_menu']; 
		unset($data['btnSimpan'], $data['id_menu']);
		print_pre($data);
		if($this->Menu_model->update($data, $id)){
			alert_notif("success");
			redirect('menu');
		}else{
			alert_notif("danger");
			redirect('menu/ubah/'.e_url($id));
		}
	}



	//////////////

	public function fa_list($cari = "")
	{
		/*$data = array(	
				"icon" => $this->icon
				);
		$this->load->view('font_awesome_list', $data);*/
		$ret = "";
		if($cari!=""){
			foreach ($this->icon as $k => $v) {
				if (strpos($v, $cari) !== false) {
				    $tmp_icon[] = $v;
				}
			}
		}else{
			$tmp_icon = $this->icon;
		}
		if(!empty($tmp_icon)){
			foreach($tmp_icon as $k => $v){
				$ret .= '<div class="col-2 mb-2">';
				$ret .= '<button type="button" class="btn btn-xs btn-light btn-icon-pilih" onclick="pilih_icon(\''.$v.'\')" data-icon="'.$v.'">';
				$ret .= '<i class="fa '.str_replace(".", "", $v).'"></i><br>';
				$ret .= '<small>'.$v.'</small>';
				$ret .= '</button></div>';
			}
		}else{
			$ret = "icon not found!";
		}
		echo $ret;
	}

	////


	public function role()
	{
		$data = array(
						"page" => "menu_view",
						"ket" => "Role",
						"role" => "menu/update_role"
						);
		$data['jabatan_all'] = $this->User_model->get_all_jabatan()->result();
		$data['menu_all'] = $this->Menu_model->get_all()->result();
		$this->load->view('index', $data);
	}

	public function load_jabatan_role($id_menu)
	{
		$data = [];
		$ret = '<select class="form-control" name="role[]" id="cb_role" multiple style="height: 160px;" ondblclick="hapus_role(this)">';
		$q = $this->Menu_model->get_role($id_menu);
		$res = $q->result();
		$id_jbt = [];
		foreach ($res as $row) {
			$ret .= "<option value='$row->id_jabatan' selected >$row->nama_jabatan</option>";
			$id_jbt[] = $row->id_jabatan;
		}
		$ret .= '</select>';
		$data['jabatan2'] =  $ret;
		
		$ret = '<select class="form-control" name="jabatan" id="list-jabatan" multiple style="height: 160px;" ondblclick="hapus_jabatan(this)">';
		$q2  = $this->User_model->get_all_jabatan();
		if(!empty($id_jbt)){
			$q2  = $this->Menu_model->get_jabatan_role($id_jbt);
		}
		$res2 = $q2->result();
		foreach ($res2 as $row) {
			$ret .= "<option value='$row->id_jabatan' selected >$row->nama_jabatan</option>";
		}
		$ret .= '</select>';
		$data['jabatan1'] =  $ret;
		//print_pre($data);
		echo json_encode($data);
	}

	public function update_role()
	{
		$role = $this->input->post("role");
		$data = [];
		foreach ($role as $k => $v) {
			$data[$k]['id_jabatan'] = $v;
			$data[$k]['id_menu'] = $this->input->post('menu');
			$data[$k]['access'] = "CRUD";
			$data[$k]['sts_role'] = "1";
		}
		if($this->Menu_model->add_role($data)){
			alert_notif("success");
			redirect('menu/role');
		}else{
			alert_notif("danger");
			redirect('menu/ubah/'.e_url($id));
		}
	}
}

/* End of file Menu.php */
/* Location: ./application/controllers/Menu.php */