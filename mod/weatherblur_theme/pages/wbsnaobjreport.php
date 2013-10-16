<?php
	// Load Elgg engine
	include_once dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php";
	

	function countem ($obj)
	{
		if ($obj)
		{
			return sizeof($obj);
		}
		else
		{
			return 0;
		}
	}
 
    
	//get users
	$options = array('type' => 'object', 'limit' => false);
	$content = elgg_get_entities($options);

	header('Content-Type: text/csv');
	header('Content-disposition: attachment;filename=snareport.csv');
	//output
	echo "\r\n----Interactions---------------------------------------------------------------------------------------------------------------------------------------------------\r\n\r\n";	
	echo "Object ID,Time Created,Type,Title,Description,Owner ID, Parent Object ID, Parent Object Type,Parent Object Owner ID,Value\r\n";
	
	foreach ($content as $obj)
	{
		//print_r($obj);
		$u_guid = $obj['guid'];
		$d = $obj['time_created'];
		$d = gmdate("Y-m-d H:i:s", $d);
		$u_type = $obj['subtype'];
		$s_type = $obj->getSubType();
		$title = $obj['title'];
		$desc = $obj['description'];
		$owner_id = $obj['owner_guid'];
		$parent_id = $obj->parent_guid;
		
		$parent_obj = get_entity($parent_id);
		//print_r($parent_obj);
		$parent_type = $parent_obj['type'];
		if ($parent_type == 'group')
		{
			$parent_type = "investigation";
		}
		$parent_own = $parent_obj['owner_guid'];
		$value = "";
		
		
		//output 
		if ($s_type != 'widget' && $s_type !='plugin' && $s_type != 'custom_profile_type' && $s_type != 'custom_profile_field')
		{
			echo $u_guid;
			echo ",";
			echo $d;
			echo ",";
			echo $s_type;
			echo ",'";
			echo $title;
			echo "','";
			echo $desc;
			echo "',";
			echo $owner_id;
			echo ",";
			echo $parent_id;
			echo ",";
			echo $parent_type;
			echo ",";
			echo $parent_own;
			echo ",'";
			echo $value;
			echo "' \r\n";
			
			//get annotations for obj
			$annotations = $obj->getAnnotations();
			//print_r($annotations);
			
			foreach ($annotations as $note)
			{
			
				$title ="";
				$desc ="";
				//print_r ($note);
				$dn = $note->time_created;
				$dn = gmdate("Y-m-d H:i:s", $dn);
				
				echo $note->id;
				echo ",";
				echo $dn;
				echo ",";
				echo $note['name'];
				echo ",'";
				echo $title;
				echo "','";
				echo $desc;
				echo "',";
				echo $note['owner_guid'];
				echo ",";
				echo $owner_id;
				echo ",";
				echo $s_type;
				echo ",";
				echo $u_guid;
				echo ",'";
				echo $note['value'];
				echo "' \r\n";
			}
				
		}
	}
	
	//get users
	$options = array('type' => 'user', 'limit' => false);
	$content = elgg_get_entities($options);
	//output 
	echo "\r\n----Users---------------------------------------------------------------------------------------------------------------------------------------------------\r\n\r\n";
	echo "User ID,User,User Type,Member Since,Logged In #,# Investigations ,Observations Made,Question/Idea Posts,Graph Posts,Map Posts,Image Posts,Video Posts\r\n";

	foreach ($content as $user)
	{
		$u_guid = $user['guid'];
		$u_obj = get_user($u_guid);
		$profile_type_guid = $u_obj->custom_profile_type;
		$u_type = "Unknown";
		if ($profile_type_guid)
		{
			$profile_type = get_entity($profile_type_guid);
			$u_type = $profile_type->getTitle();
		}
	
		$name = $user['name'];
		$login_count = 0;
		if ($user->login_count)
		{
			$login_count = $user->login_count;
		}
		
		$last_login = $user['last_login'];
		$first_login = $user['time_created'];
		$relations = countem(get_users_membership($u_guid)); //get all the investigations
		
		//count obs
		$obs = elgg_get_entities( array(
			  'owner_guid' => $u_guid,
			  'type' => 'object',
			  'subtype' => 'observations',
			  'limit' => false
			));
			
		//count maps
		$maps = elgg_get_entities( array(
		  'owner_guid' => $u_guid,
		  'type' => 'object',
		  'subtype' => 'investigationforumtopic_map',
		  'limit' => false
		));

		//count graphs
		$graphs = elgg_get_entities( array(
		  'owner_guid' => $u_guid,
		  'type' => 'object',
		  'subtype' => 'investigationforumtopic_graph',
		  'limit' => false
		));

		//count imgs
		$imgs = elgg_get_entities( array(
		  'owner_guid' => $u_guid,
		  'type' => 'object',
		  'subtype' => 'investigationforumtopic_image',
		  'limit' => false
		));

		//count video
		$video = elgg_get_entities( array(
		  'owner_guid' => $u_guid,
		  'type' => 'object',
		  'subtype' => 'investigationforumtopic_video',
		  'limit' => false
		));

		//count discussions
		$disc = elgg_get_entities( array(
		  'owner_guid' => $u_guid,
		  'type' => 'object',
		  'subtype' => 'investigationforumtopic_text',
		  'limit' => false
		));

		
	
		//output 
		echo $u_guid;
		echo ",";
		echo $name;
		echo ",";
		echo $u_type;
		echo ",";
		echo date("F j Y g:i a",$first_login);
		echo ",";
		echo $login_count;
		echo ",";
		echo countem($relations);
		echo ",";
		echo countem($obs);
		echo ",";
		echo countem($disc);
		echo ",";
		echo countem($graphs);
		echo ",";
		echo countem($maps);
		echo ",";
		echo countem($imgs);
		echo ",";
		echo countem($video);
		echo " \r\n";
	}
	
	//get users
	$options = array('type' => 'group', 'limit' => false);
	$content = elgg_get_entities($options);

	//output 
	echo "\r\n----Investigations---------------------------------------------------------------------------------------------------------------------------------------------------\r\n\r\n";
	echo "Investigation ID,Investigation, Description\r\n";

	foreach ($content as $inv)
	{
		$u_guid = $inv['guid'];
		$name = $inv['name'];
		$desc = $inv['description'];
		
	
		//output 
		echo $u_guid;
		echo ",'";
		echo $name;
		echo "','";
		echo $desc;
		echo "'\r\n";
	}
	
	//output 
	echo "\r\n----Measurements---------------------------------------------------------------------------------------------------------------------------------------------------\r\n\r\n";
	echo "Observation ID,Measurement Type, Value\r\n";
	$tzo = 0;

	if(isset($_GET['tzo'])) {
		$tzo = $_GET['tzo'];
	}

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

	//get scalar values

	$url_sc = $url_agg."/api/phenomenon/byUomType?type=scalar";

	$ch = curl_init($url_sc);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$sc_str = curl_exec($ch);
	curl_close($ch); 
	$obj_sc = json_decode($sc_str,true);


	//get obs
	$aaData = array();

	$now = date(DATE_W3C, time() + (7 * 24 * 60 * 60));

	$now = str_replace('+', '-', $now);

	// we must supply a date range or we get some cached set
	$url_obs = $url_agg.'/api/observation/search?q={"inDateRange":{"begin":"1970-01-01T00:00:00-00:00","end":"'.$now.'"}}';
	$ch = curl_init($url_obs);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$obs_str = curl_exec($ch);

	curl_close($ch); 

	$obj_obs = json_decode($obs_str,true);


	$features = $obj_obs["features"];


	for($y =0; $y < count($features); $y++)
	{
		
		//print_r($features[$y]);
		$m_id = $features[$y]["id"];
		
		$results = elgg_get_entities_from_metadata(array(
			"type_subtype_pair"	=>	array('object' => 'observation'),
			"metadata_name_value_pairs" => array('agg_id' => $m_id)
		));
	
		$obs_id = $results[0]->guid;
		
		$url_meas = $url_agg."/api/observation/".$m_id."/measurement";
		
		$ch = curl_init($url_meas);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$meas_str = curl_exec($ch);
		curl_close($ch); 
	   
		$meas_str = iconv('UTF-8', 'UTF-8//IGNORE', utf8_encode($meas_str));
		$obj_meas = json_decode($meas_str,true);
		
		//loop over all obs_meas
		if($obs_id)
		{
			for ($x = 0; $x < count($obj_meas); $x++)
			{
				//output 
				echo $obs_id;
				echo ",'";
				echo $obj_meas[$x]["phenomenon"]["name"];
				echo "','";
				echo $obj_meas[$x]["value"];
				echo "'\r\n";
			
			}
		}
	}



?>
