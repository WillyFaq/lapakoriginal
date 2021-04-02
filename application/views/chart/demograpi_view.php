<?php
		
		//echo $sql;
		//echo $this->db->last_query();
		$res = $q->result();
		$data = [];
		$map = json_decode(file_get_contents(base_url('assets/mapping.json')), true);
		foreach ($res as $row) {
			if(isset($map[$row->prov])){
				$data[$map[$row->prov]] = $row->jml;
			}else{
				$data['id-jt'] = $row->jml;
			}
		}
	?>
<div id="container_demo"></div>
<script type="text/javascript">
	//console.log(Highcharts.maps["countries/id/id-all"].features);
	/*var f = Highcharts.maps["countries/id/id-all"].features;
	var pro = [{"id":11,"nama":"Aceh"},{"id":12,"nama":"Sumatera Utara"},{"id":13,"nama":"Sumatera Barat"},{"id":14,"nama":"Riau"},{"id":15,"nama":"Jambi"},{"id":16,"nama":"Sumatera Selatan"},{"id":17,"nama":"Bengkulu"},{"id":18,"nama":"Lampung"},{"id":19,"nama":"Kepulauan Bangka Belitung"},{"id":21,"nama":"Kepulauan Riau"},{"id":31,"nama":"Dki Jakarta"},{"id":32,"nama":"Jawa Barat"},{"id":33,"nama":"Jawa Tengah"},{"id":34,"nama":"Di Yogyakarta"},{"id":35,"nama":"Jawa Timur"},{"id":36,"nama":"Banten"},{"id":51,"nama":"Bali"},{"id":52,"nama":"Nusa Tenggara Barat"},{"id":53,"nama":"Nusa Tenggara Timur"},{"id":61,"nama":"Kalimantan Barat"},{"id":62,"nama":"Kalimantan Tengah"},{"id":63,"nama":"Kalimantan Selatan"},{"id":64,"nama":"Kalimantan Timur"},{"id":65,"nama":"Kalimantan Utara"},{"id":71,"nama":"Sulawesi Utara"},{"id":72,"nama":"Sulawesi Tengah"},{"id":73,"nama":"Sulawesi Selatan"},{"id":74,"nama":"Sulawesi Tenggara"},{"id":75,"nama":"Gorontalo"},{"id":76,"nama":"Sulawesi Barat"},{"id":81,"nama":"Maluku"},{"id":82,"nama":"Maluku Utara"},{"id":91,"nama":"Papua Barat"},{"id":94,"nama":"Papua"}];
	
	f.forEach(function(item, index){
		$("#pre").append(item.properties.name);
		$("#pre").append("<br>");
	});*/
	// Prepare demo data
	// Data is joined to map using value of 'hc-key' property by default.
	// See API docs for 'joinBy' for more info on linking data and map.
	var whr = "<?= $whr; ?>";
	var data = [
		['id-3700', 0],
		<?php foreach ($data as $k => $v) {
			echo "['$k', $v],";
		}?>
	];

	// Create the chart
	Highcharts.mapChart('container_demo', {
		chart: {
			map: 'countries/id/id-all'
		},

		title: {
			text: 'Demograpi Penjualan'
		},

		subtitle: {
			text: ''//'Source map: <a href="http://code.highcharts.com/mapdata/countries/id/id-all.js">Indonesia</a>'
		},

		mapNavigation: {
			enabled: true,
			buttonOptions: {
				verticalAlign: 'bottom'
			}
		},

		colorAxis: {
			min: 0
		},

		plotOptions: {
			series: {
				events: {
					click: click_detail
				}
			}
		},

		series: [{
			data: data,
			name: 'Total Penjualan',
			states: {
				hover: {
					color: '#BADA55'
				}
			},
			dataLabels: {
				enabled: false,
				format: '{point.name}'
			}
		}]
	});

	function click_detail(e){
		var id = e.point.options["hc-key"];
		var url = "<?= base_url('dahsboard/load_detail_demografi/'); ?>";
		$.ajax({
			url: url,
			type:'post',
			data:"id="+id+"&whr="+whr,
			success: function(result){
				$("#modalDemoDetail").modal('show');
				$("#body-modalDemoDetail").html(result);
				//console.log(result);
			}
		});
	}

</script>