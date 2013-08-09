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
	$options = array('type' => 'user');
	$content = elgg_get_entities($options);

	header('Content-Type: text/csv');
	header('Content-disposition: attachment;filename=snareport.csv');
	//output 
	echo "User,User Type,Member Since,Logged In #,# Investigations ,Observations Made,Question/Idea Posts,Graph Posts,Map Posts,Image Posts,Video Posts,With Students,With Teachers,With Scientists,With Fisherman,With Members\r\n";

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

		//with students
		$with_students = 0;
		
		//with teachers
		$with_teachers = 0;
		
		//with scientists
		$with_scientists = 0;
		
		//with fishermen
		$with_fishermen = 0;
		
		//with members
		$with_members = 0;
		
	
		//output 
		echo $name;
		echo ",";
		echo $u_type;
		echo ",";
		echo date("F j, Y, g:i a",$first_login);
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
		echo ",";
		echo countem($with_students);
		echo ",";
		echo countem($with_teachers);
		echo ",";
		echo countem($with_scientists);
		echo ",";
		echo countem($with_fishermen);
		echo ",";
		echo countem($with_members);
		echo " \r\n";
	}

?>
