<?php
	include "vendor/autoload.php";
	// echo "<pre>";
	// $all_data_url = "https://api.covid19api.com/all";
	$summary_data_url = "https://corona.lmao.ninja/countries";

	$all_data_client = new GuzzleHttp\Client();
	$all_data_response = $all_data_client->request('GET', $summary_data_url);
	set_time_limit(316000);
	$data = array();

	if($all_data_response->getStatusCode() == 200)
	{
		$all_data_res1 = $all_data_response->getBody();
		$all_data_res11 = json_decode($all_data_res1, true);
		
		$milliseconds = $all_data_res11[0]['updated'];
		$mil = $all_data_res11[0]['updated'];
		$seconds = $mil / 1000;
		$dt = date("F d, Y H:m", $seconds);

		foreach($all_data_res11 as $row)
		{
			$ar = [
					'Country'=>$row['country'],
					'NewConfirmed'=>$row['todayCases'],
					'TotalConfirmed'=>$row['cases'],
					'NewDeaths' => $row['todayDeaths'],
					'TotalDeaths' => $row['deaths'],
					'Recovered' => $row['recovered'],
					'active' => $row['active'],
					'critical' => $row['critical'],
					'casesPerOneMillion' => $row['casesPerOneMillion'],
					'deathsPerOneMillion' => $row['deathsPerOneMillion'],
				];

			array_push($data, $ar);
		}
	}
	$summary_caption_data_url = "https://corona.lmao.ninja/all";
	$summary_caption_data_client = new GuzzleHttp\Client();
	$summary_caption_data_response = $summary_caption_data_client->request('GET', $summary_caption_data_url);
	if($all_data_response->getStatusCode() == 200)
	{
		$summary_caption_data_res1 = $summary_caption_data_response->getBody();
		$summary_caption_data_res11 = json_decode($summary_caption_data_res1, true);
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Live Status of Coronavirus</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
	<link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.2.5/css/rowReorder.dataTables.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css">
	<link rel="stylesheet" type="text/css" href="newstyle.css">
	<style type="text/css">
		.tblconf .sorting_1{
			background-color: #8ACA2B !important;
			color: #fff !important;
		}
		.tbldeath .sorting_1{
			background-color: red !important;
			color: #fff !important;
		}
	</style>
</head>
<body>
	<div class="hed_lin text-center">
		<h2>Live Status of Coronavirus</h2>
	</div>
	<div class="container" id="sec_tblcas">
		<p class="upd_lin"> Last updated: <?php echo $dt; ?></p>
		<hr>
		<div class="sec_cases">
			<div class="row">
				<div class="col-sm-6 pad_0">
					
			<div class="row">
				<div class="col-6">
					<h4>Cases</h4>
					<p class="conf_clr"><?php echo number_format($summary_caption_data_res11['cases'],0,"",","); ?></p>
				</div>
				<div class="col-6">
					<h4>Deaths</h4>
					<p class="death_clr"><?php echo number_format($summary_caption_data_res11['deaths'],0,"",","); ?></p>
				</div>
			</div>
			<div class="row">
				<div class="col-6">
					<h4>Recovered</h4>
					<p class="recov_clr"><?php echo number_format($summary_caption_data_res11['recovered'],0,"",","); ?></p>
				</div>
				<div class="col-6">
					<h4>Active</h4>
					<p class="death_clr"><?php echo number_format($summary_caption_data_res11['active'],0,"",","); ?></p>
				</div>
			</div>
				</div>
				<div class="col-sm-6 pad360 pad_r0 img_lft pad_0">
					<img src="corona.jpg" width="100%">
				</div>
			</div>
			
		</div>
		<p class="clr_rep">Report coronavirus cases</p>
		<table id="example" class="display cell-border" style="width:100%"> 
		<thead>
			<tr>
				<th>Country</th>
				<th>New<br>Cases</th>
				<th>Total<br>Cases</th>
				<th>New<br>Deaths</th>
				<th>Total<br>Deaths</th>
				<th>Recovered</th>
				<th>Active</th>
				<th>Serious, Critical</th>
				<th>Cases/<br>1M pop</th>
				<th>Deaths/<br>1M pop</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($data as $row){	?>
			<tr>
				<td>
					<?php echo $row['Country'] ?>					
				</td>
				<td <?php if($row['NewConfirmed']!=0){ echo 'class="tblconf"'; } ?>>
					<?php if($row['NewConfirmed']!=0){ echo number_format($row['NewConfirmed'],0,"",","); } ?>					
				</td>
				<td>
					<?php if($row['TotalConfirmed']!=0){  echo number_format($row['TotalConfirmed'],0,"",","); } ?>
				</td>
				<td <?php if($row['NewDeaths']!=0){  echo 'class="tbldeath"'; } ?>>
					<?php if($row['NewDeaths']!=0){  echo number_format($row['NewDeaths'],0,"",","); } ?>
				</td>
				<td>
					<?php if($row['TotalDeaths']!=0){  echo number_format($row['TotalDeaths'],0,"",","); } ?>
				</td>
				<td>
					<?php if($row['Recovered']!=0){  echo number_format($row['Recovered'],0,"",","); } ?>
				</td>
				<td>
					<?php if($row['active']!=0){  echo number_format($row['active'],0,"",","); } ?>
				</td>
				<td>
					<?php if($row['critical']!=0){  echo number_format($row['critical'],0,"",","); } ?>						
				</td>
				<td>
					<?php if($row['casesPerOneMillion']!=0){  echo number_format($row['casesPerOneMillion'],0,"",","); } ?>						
				</td>
				<td>
					<?php if($row['deathsPerOneMillion']!=0){  echo $row['deathsPerOneMillion']; } ?>						
				</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
	</div>

	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/rowreorder/1.2.5/js/dataTables.rowReorder.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
	<script>
	$(document).ready(function() {
	    $('#example').DataTable( {
	    	"order": [[ 2, "desc" ]],
			"scrollY":			"400px",
			"scrollCollapse": 	true,
			"paging": 			false,
			language: {
				search: "_INPUT_",
				searchPlaceholder: "Search Country"
			},
			responsive: true,
			columnDefs: [ 
				{ targets:"_all", orderable: true },
				{ targets:[0,1,2,3,4,5,6,7,8,9], className: "desktop" },
				{ targets:[0,1,2], className: "tablet" },
				{ targets:[0], className: "mobile" }
			],
			bAutoWidth: false, 
			aoColumns : [
				{ sWidth: '19%' },
				{ sWidth: '9%' },
				{ sWidth: '9%' },
				{ sWidth: '9%' },
				{ sWidth: '9%' },
				{ sWidth: '9%' },
				{ sWidth: '9%' },
				{ sWidth: '9%' },
				{ sWidth: '9%' },
				{ sWidth: '9%' }
			],
			"orderClasses": false
	    } );
	} );
</script>
</body>
</html>