<?php

//get scalar values

$url_sc = "http://wb-aggregator.unstable.nbt.io/api/phenomenon/byUomType?type=scalar";

$ch = curl_init($url_sc);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$sc_str = curl_exec($ch);
curl_close($ch); 
$obj_sc = json_decode($sc_str,true);


//get obs
$aaData = array();
$url_obs = "http://wb-aggregator.unstable.nbt.io/api/observations/";

$ch = curl_init($url_obs);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$obs_str = curl_exec($ch);
curl_close($ch); 


$obj_obs = json_decode($obs_str,true);


$features = $obj_obs["features"];


for($y =0; $y < count($features); $y++)
{
	$m_id = $features[$y]["id"];
	
	$tss = $features[$y]["properties"]["timestamp"];
	$ts = strtotime($tss);
	$dates = date("F j, Y, g:i a", $ts);  
	
	$uname = $features[$y]["properties"]["observer"]["properties"]["label"];
	
	$url_meas = "http://wb-aggregator.unstable.nbt.io/api/observation/".$m_id."/measurement";
	
	$ch = curl_init($url_meas);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$meas_str = curl_exec($ch);
	curl_close($ch); 
   
	$obj_meas = json_decode($meas_str,true);
	
	//loop over all obs_meas
	for ($x = 0; $x < count($obj_meas); $x++)
	{
		//check to see if measure is a scalar
		for ($u = 0; $u < count($obj_sc); $u++)
		{
			
			if ($obj_meas[$x]["phenomenon"]["name"] == $obj_sc[$u]["name"])
			{
				//get investigation
				$url_inv = elgg_get_site_url()."services/api/rest/json/?method=wb.get_inv_by_agg_id&agg_id=".$m_id;
		
				$ch = curl_init($url_inv);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

				$inv_str = curl_exec($ch);
				curl_close($ch);
				$obj_inv = json_decode($inv_str,true);
				$inv_name = $obj_inv["results"]["name"];
				if(!$inv_name)
				{
					$inv_name = "--";
				}
				
				
		        $elem = array();
				//it is scalar, so we need to get the data
				array_push($elem,$uname);
				array_push($elem,$inv_name);
				array_push($elem,$obj_sc[$u]["description"]);
				array_push($elem,$obj_meas[$x]["value"]);
				array_push($elem,$obj_sc[$u]["unit"]["name"]);
				array_push($elem,$dates);
				
				//$elem[0] = $uname.",WeatherBlur,".$obj_sc[$u]["name"].",".$obj_meas[$x]["value"].",".$obj_sc[$u]["unit"]["name"].",".$dates;
				array_push($aaData,$elem);
			} 
		}
		
	}
}

//print_r ($aaData);
$aaObj = array( 'aaData' =>$aaData);
echo json_encode($aaObj);



?>
