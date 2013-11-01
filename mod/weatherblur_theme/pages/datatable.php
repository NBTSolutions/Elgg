<?php
//set hosts
$srv = $_SERVER['SERVER_NAME'];

$url_agg = "http://wb-aggregator.prod.nbt.io";
$url_elgg = "http://".$srv;


if ($srv == 'localhost')
{
		//assume vagrant development
	$url_agg = "http://wb-aggregator.unstable.nbt.io";
	$url_elgg = "http://demo.nbtsolutions.com/elgg";
}
if ($srv == 'demo.nbtsolutions.com')
{
	$url_agg = "http://wb-aggregator.unstable.nbt.io";
	$url_elgg = "http://demo.nbtsolutions.com/elgg"; 
}
	

//get obs
$now = date(DATE_W3C, time() + (7 * 24 * 60 * 60));
$now = str_replace('+', '-', $now);
// we must supply a date range or we get some cached set
$url_obs = $url_agg.'/api/observation/search?q={"inDateRange":{"begin":"1970-01-01T00:00:00-00:00","end":"'.$now.'"}}';
$ch = curl_init($url_obs);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$obs_str = curl_exec($ch);
curl_close($ch);

//hash of current obs req
$hsh2 = hash ("md5",$obs_str);

//check hash of measurements in cache
$file = "/tmp/wb-obsreq.cache";
$match = false;

if(file_exists($file)) 
{
    $hsh = file_get_contents($file);
	
	//compare hashes of obs and cached hash
	$match = ($hsh == $hsh2);

}

//update cache
file_put_contents($file,$hsh2);

$file = "/tmp/wb-datatable.cache";
//$current_time = time(); 
//$expire_time = 0.25 * 60 * 60; //cache for fifteen minutes
//$file_time = filemtime($file);

if(($match) && (file_exists($file)))
{
	//echo 'returning from cached file';
	echo file_get_contents($file);
}
else
{

	$tzo = 0;

	if(isset($_GET['tzo'])) {
		$tzo = $_GET['tzo'];
	}
	
	//get obs
	$aaData = array();

	$obj_obs = json_decode($obs_str,true);

	$features = $obj_obs["features"];

	$users = array();
	$invs = array();

	for($y =0; $y < count($features); $y++)
	{
		
		$m_id = $features[$y]["id"];
		
		$tss = $features[$y]["properties"]["timestamp"];

		$ts = strtotime($tss)-($tzo*60);
		
		$dates = date("M j, Y, g:i a", $ts);  
		
		$uname = $features[$y]["properties"]["observer"]["properties"]["label"];
		$uguid = $features[$y]["properties"]["observer"]["properties"]["elggId"];
		
		
		$url_meas = $url_agg."/api/observation/".$m_id."/measurement";
		
		
		if ($ch_f)
		{
			curl_setopt($ch_f, CURLOPT_URL, $url_meas);
		}
		else
		{
			$ch_f = curl_init($url_meas);
		}
		curl_setopt($ch_f, CURLOPT_RETURNTRANSFER, true);

		$meas_str = curl_exec($ch_f);
	   
		$meas_str = iconv('UTF-8', 'UTF-8//IGNORE', utf8_encode($meas_str));
		
		
		$obj_meas = json_decode($meas_str,true);
		
	
		
		$desc = "";
		$value= "";
		$name= "";
		
		//loop over all obs_meas
		for ($x = 0; $x < count($obj_meas); $x++)
		{
			if ($obj_meas[$x]["phenomenon"]["unit"]["type"] == "scalar")
			{
				$desc = $obj_meas[$x]["phenomenon"]["description"];
				$value = $obj_meas[$x]["value"];
				$name =	$obj_meas[$x]["phenomenon"]["unit"]["name"];
			
				//get user name
				$uguid = $features[$y]["properties"]["observer"]["properties"]["elggId"];
					
				//sometimes we may not have a username from the label
				if (is_null($uname))
				{

					
					if ($users[$uguid])
					{
						$uname = $users[$uguid];
					}
					else
					{
						$user_url = $url_elgg. "/services/api/rest/json/?method=wb.get_user_info&user_guid=".$uguid."&icon_size=small";
						
						
						if ($ch_u)
						{
							curl_setopt($ch_u, CURLOPT_URL, $user_url);
						}
						else
						{
							$ch_u = curl_init($user_url);
						}
						curl_setopt($ch_u, CURLOPT_RETURNTRANSFER, true);

						$user_str = curl_exec($ch_u);
						$user = json_decode($user_str,true);
						$uname = $user["result"]["users_display_name"];
						if(!$uname)
						{
							$uname = "--";
						}
	
					}
					$users[$uguid] = $uname;
				}
				
				//get investigation
				
				if ($invs[$m_id])
				{
					$inv_name = $invs[$m_id];
				}
				else
				{
					$url_inv = $url_elgg."/services/api/rest/json/?method=wb.get_inv_by_agg_id&agg_id=".$m_id;
					
					
					if ($ch_i)
					{
						curl_setopt($ch_i, CURLOPT_URL, $url_inv);
					}
					else
					{
						$ch_i = curl_init($url_inv);
					}
					curl_setopt($ch_i, CURLOPT_RETURNTRANSFER, true);

					$inv_str = curl_exec($ch_i);
			
					$obj_inv = json_decode($inv_str,true);
					$inv_name = $obj_inv["result"]["name"];
					if(!$inv_name)
					{
						$inv_name = "--";
					}
					
					$invs[$m_id] = $inv_name;
				}
				
				
				$elem = array();
				
				//it is scalar, so we need to get the data
				array_push($elem,$uname);
				array_push($elem,$inv_name);
				array_push($elem,$desc);
				array_push($elem,$value);
				array_push($elem,$name);
				array_push($elem,$dates);
				
				//$elem[0] = $uname.",WeatherBlur,".$obj_sc[$u]["name"].",".$obj_meas[$x]["value"].",".$obj_sc[$u]["unit"]["name"].",".$dates;
				array_push($aaData,$elem);
			} 
		
		}
	}

	//close all connections
	curl_close($ch_f); 
	curl_close($ch_u);
	curl_close($ch_i); 
	$aaObj = array( 'aaData' =>$aaData);
	$jsonobj = json_encode($aaObj);
	echo $jsonobj;
	file_put_contents($file,$jsonobj);
}



?>
