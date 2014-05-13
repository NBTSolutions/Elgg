<?php
/**
 * Elgg groups plugin
 *
 * @package ElggGroups
 */

// this is a lame way to do this but I can't think of anything better at this time.
$GLOBALS['WB_USER_TYPES'] = array("Student", "Teacher", "Scientist", "Fisherman", "Community Member");
$GLOBALS['WB_ADVISOR_TYPES'] = array_diff($GLOBALS['WB_USER_TYPES'], array('Student'));

elgg_register_event_handler('init', 'system', 'investigations_init');

// Ensure this runs after other plugins
elgg_register_event_handler('init', 'system', 'investigation_fields_setup', 10000);

/**
 * Initialize the groups plugin.
 */
function investigations_init() {

	elgg_register_js('inv:embed', elgg_get_site_url() . 'mod/investigations/js/show_embed.js', 'footer', 10000);

	elgg_register_library('elgg:investigations', elgg_get_plugins_path() . 'investigations/lib/investigations.php');

	// register group entities for search
	elgg_register_entity_type('group', '');

	// Set up the menu
	$item = new ElggMenuItem('investigations', elgg_echo('Investigations'), 'investigate/all');
	elgg_register_menu_item('site', $item);

	// Register a page handler, so we can have nice URLs
	elgg_register_page_handler('investigate', 'investigation_page_handler');
	elgg_register_page_handler('observation', 'observation_page_handler');

	// Register URL handlers for groups
	elgg_register_entity_url_handler('group', 'investigation', 'investigation_url');
	elgg_register_plugin_hook_handler('entity:icon:url', 'investigate', 'investigation_icon_url_override');

	// Register an icon handler for groups
	elgg_register_page_handler('groupicon', 'investigation_icon_handler');

	// Register some actions
	$action_base = elgg_get_plugins_path() . 'investigations/actions/groups';
	elgg_register_action("investigations/edit", "$action_base/edit.php");
	elgg_register_action("investigate/delete", "$action_base/delete.php");
	elgg_register_action("investigations/featured", "$action_base/featured.php", 'admin');

    elgg_register_action("observation/create_comment", elgg_get_plugins_path() . "investigations/actions/observation/create_comment.php");
    elgg_register_action("observation/delete_comment", elgg_get_plugins_path() . "investigations/actions/observation/delete_comment.php");

	$action_base .= '/membership';
	elgg_register_action("groups/invite", "$action_base/invite.php");
	elgg_register_action("groups/join", "$action_base/join.php");
	elgg_register_action("groups/leave", "$action_base/leave.php");
	elgg_register_action("groups/remove", "$action_base/remove.php");
	elgg_register_action("groups/killrequest", "$action_base/delete_request.php");
	elgg_register_action("groups/killinvitation", "$action_base/delete_invite.php");
	elgg_register_action("groups/addtogroup", "$action_base/add.php");

    // expose get investigations
	// The authentication token api
	expose_function(
		"wb.login_user",
		"login_user",
		array(
			'username' => array ('type' => 'string'),
			'password' => array ('type' => 'string')
		),
		'Get List of Investigations for a given user',
		'GET',
		false,
		false
	);

    expose_function(
        "wb.logout_user",
        "logout_user",
        array(),
        '',
        'GET',
        false,
        false
    );

	expose_function(
		"wb.get_invs",
		"get_invs",
		array(
			'username' => array ('type' => 'string'),
			'password' => array ('type' => 'string')
		),
		'Get List of Investigations for a given user',
		'GET',
		false,
		false
	);

    expose_function(
        "wb.get_all_invs",
        "get_all_invs",
        array(),
        '',
        'GET',
        false,
        false
    );

    expose_function(
        "wb.get_disc_by_id",
        "get_disc_by_id",
        array(
            'id' => array('type' => 'int')
        ),
        '',
        'GET',
        false,
        false
    );

    expose_function(
        "wb.comment_on",
        "comment_on",
        array(
            'id' => array('type' => 'int'),
            'type' => array('type' => 'string'),
            'comment' => array('type' => 'string')
        ),
        '',
        'GET',
        false,
        false
    );

    expose_function(
        'wb.get_comments',
        'get_comments',
        array(
            'id' => array('type' => 'int'),
            'type' => array('type' => 'string'),
            'limit' => array('type' => 'int', 'required' => false),
            'offset' => array('type' => 'int', 'required' => false)
        ),
        '',
        'GET',
        false,
        false
    );

    expose_function(
        'wb.delete_comment',
        'delete_comment',
        array(
            'id' => array('type' => 'int')
        ),
        '',
        'GET',
        false,
        false
    );

    expose_function(
        'wb.delete_discussion',
        'delete_discussion',
        array(
            'id' => array('type' => 'int')
        ),
        '',
        'GET',
        false,
        false
    );

    expose_function(
        "wb.get_inv_by_id",
        "get_inv_by_id",
        array(
            'id' => array('type' => 'int', 'required' => true)
        ),
        '',
        'GET',
        false,
        false
    );

    expose_function(
        "wb.get_invs_by_token",
        "get_invs_by_token",
        array(
            'token' => array('type' => 'string')
        ),
        '',
        'GET',
        false,
        false
    );

    expose_function(
        "wb.create_obs",
        "create_obs",
        array(
            'inv_guid' => array('type' => 'int'),
            'token'     => array('type' => 'string'),
            'agg_id'    => array('type' => 'string'),
            'with_users' => array('type' => 'string', 'required' => false)
        ),
        'Create Observation for an investigation',
        'GET',
        false,
        false
    );

    expose_function(
        "wb.assoc_obs_with_users",
        "assoc_obs_with_users",
        array(
            'agg_id' => array('type' => 'int'),
            'with_users' => array('type' => 'string', 'required' => false)
        ),
        '',
        'GET',
        false,
        false
    );

    // expose_function(
    //     "wb.create_obs_2",
    //     "create_obs_2",
    //     array(
    //         'inv_guid'  => array('type' => 'int'),
    //         'token'     => array('type' => 'string'),
    //         'agg_id'    => array('type' => 'string'),
    //         'collaborators' => array('type' => 'string')
    //     ),
    //     'Create Observation for an investigation',
    //     'GET',
    //     false,
    //     false
    // );

    expose_function(
        "wb.get_obs_by_user_type",
        "get_obs_by_user_type",
        array(
            'user_type' => array('type' => 'string'),
            'min_date' => array('type' => 'int'),
            'max_date' => array('type' => 'int')
        ),
        'Get observations from an investigation',
        'GET',
        false,
        false
    );

    expose_function(
        "wb.get_obs_by_inv",
        "get_obs_by_inv",
        array(
            'inv_id' => array('type' => 'int')
        ),
        '',
        'GET',
        false,
        false
    );


    expose_function(
        "wb.get_obs",
        "get_obs",
        array(
            'limit' => array('type' => 'int', 'required' => false),
            'offset' => array('type' => 'int', 'required' => false)
        ),
        'Get observations',
        'GET',
        false,
        false
    );

    expose_function(
        "wb.get_obs_paged",
        "get_obs_paged",
        array(
            'limit' => array('type' => 'int', 'required' => false),
            'offset' => array('type' => 'int', 'required' => false)
        ),
        '',
        'GET',
        false,
        false
    );

    expose_function(
        "wb.get_obs_paged_from_elgg",
        "get_obs_paged_from_elgg",
        array(
            'limit' => array('type' => 'int', 'required' => false),
            'offset' => array('type' => 'int', 'required' => false)
        ),
        '',
        'GET',
        false,
        false
    );

    expose_function(
        "wb.comment_on_obs",
        "comment_on_obs",
        array(
            'observation_guid' => array('type' => 'int'),
            'comment' => array('type' => 'string'),
            'token' => array('type' => 'string')
        ),
        '',
        'GET',
        false,
        false
    );

    expose_function(
        "wb.comment_on_obs_by_agg_id",
        "comment_on_obs_by_agg_id",
        array(
            'agg_id' => array('type' => 'int'),
            'comment' => array('type' => 'string'),
            'token' => array('type' => 'string')
        ),
        '',
        'POST',
        false,
        false
    );

    expose_function(
        "wb.toggle_like_obs",
        "toggle_like_obs",
        array(
            'observation_guid' => array('type' => 'int')
        ),
        '',
        'GET',
        false,
        false
    );

    expose_function(
        'wb.toggle_like_entity',
        'toggle_like_entity',
        array(
            entity_guid => array('type' => 'int')
        ),
        '',
        'GET',
        false,
        false
    );

    expose_function(
        "wb.toggle_like_obs_by_agg_id",
        "toggle_like_obs_by_agg_id",
        array(
            'agg_id' => array('type' => 'string')
        ),
        '',
        'GET',
        false,
        false
    );

    expose_function(
        "wb.get_likes",
        "get_likes",
        array(
            'observation_guid' => array('type' => 'int')
        ),
        '',
        'GET',
        false,
        false
    );

    expose_function(
        "wb.get_likes_by_agg_id",
        "get_likes_by_agg_id",
        array(
            'agg_id' => array('type' => 'string')
        ),
        '',
        'GET',
        false,
        false
    );

    expose_function(
        "wb.get_my_obs_like",
        "get_my_obs_like",
        array(
            'observation_guid' => array('type' => 'int')
        ),
        '',
        'GET',
        false,
        false
    );

    expose_function(
        "wb.get_my_obs_like_by_agg_id",
        "get_my_obs_like_by_agg_id",
        array(
            'agg_id' => array('type' => 'string')
        ),
        '',
        'GET',
        false,
        false
    );

    expose_function(
        "wb.is_logged_in",
        "is_logged_in",
        array(),
        '',
        'GET',
        false,
        false
    );

    expose_function(
        "wb.get_comments_on_obs",
        "get_comments_on_obs",
        array(
            'observation_guid' => array('type' => 'int')
        ),
        '',
        'GET',
        false,
        false
    );

    expose_function(
        "wb.get_obs_elgg_data_by_agg_id",
        "get_obs_elgg_data_by_agg_id",
        array(
            'agg_id' => array('type' => 'string')
        ),
        '',
        'GET',
        false,
        false
    );

    expose_function(
        "wb.edit_user_info_by_username",
        "edit_user_info_by_username",
        array(
            'username' => array('type' => 'string'),
            'displayname' => array('type' => 'string'),
            'profiletype' => array('type' => 'int', 'required' => false, 'default' => 44),
            'description' => array('type' => 'string', 'required' => false, 'default' => ""),
            'location' => array('type' => 'string', 'required' => false, 'default' => ""),
            'interests' => array('type' => 'string', 'required' => false, 'default' => ""),
            'skills' => array('type' => 'string', 'required' => false, 'default' => ""),
            'contactemail' => array('type' => 'string', 'required' => false, 'default' => ""),
            'website' => array('type' => 'string', 'required' => false, 'default' => ""),
            'twitter' => array('type' => 'string', 'required' => false, 'default' => ""),
            'school' => array('type' => 'string', 'required' => false, 'default' => ""),
            'video' => array('type' => 'string', 'required' => false, 'default' => "")
            ),
        '',
        'POST',
        false,
        false
    );

    expose_function(
        "wb.get_user_info",
        "get_user_info",
        array(
            'user_guid' => array('type' => 'string'),
            'icon_size' => array('type' => 'string')
        ),
        '',
        'GET',
        false,
        false
    );

    expose_function(
        "wb.get_user_info_by_username",
        "get_user_info_by_username",
        array(
            'username' => array('type' => 'string'),
            'icon_size' => array('type' => 'string')
        ),
        '',
        'GET',
        false,
        false
    );

    expose_function(
        "wb.get_user_info_by_agg_id",
        "get_user_info_by_agg_id",
        array(
            'agg_id' => array('type' => 'string'),
            'icon_size' => array('type' => 'string')
        ),
        '',
        'GET',
        false,
        false
    );

	expose_function(
        "wb.get_inv_by_agg_id",
        "get_inv_by_agg_id",
        array(
            'agg_id' => array('type' => 'string')
        ),
        '',
        'GET',
        false,
        false
    );

    expose_function(
        "wb.delete_obs_by_agg_id",
        "delete_obs_by_agg_id",
        array(
            'agg_id' => array('type' => 'string')
        ),
        '',
        'GET',
        false,
        false
    );

    expose_function(
        "wb.delete_obs_by_guid",
        "delete_obs_by_guid",
        array(
            'agg_id' => array('type' => 'string')
        ),
        '',
        'GET',
        false,
        false
    );

    expose_function(
        "wb.rotate_image_by_agg_id",
        "rotate_image_by_agg_id",
        array(
            'degrees' => array('type' => 'string'),
            'agg_id' => array('type' => 'string')
        ),
        '',
        'GET',
        false,
        false
    );

    expose_function(
        "wb.create_discussion",
        "create_discussion",
        array(
            'name' => array('type' => 'string'),
            'description' => array('type' => 'string'),
            'subtype' => array('type' => 'string', 'default' => ''),
            'container_guid' => array('type' => 'int'),
            'video' => array('type' => 'string', 'required' => false, 'default' => '')
        ),
        '',
        'POST',
        false,
        false
    );

    expose_function(
        'wb.create_messageboard',
        'create_messageboard',
        array(
            'description' => array('type' => 'string'),
            'subtype' => array('type' => 'string'),
            'username' => array('type' => 'string')
        ),
        '',
        'GET',
        false,
        false
    );

    expose_function(
        'wb.get_messageboard',
        'get_messageboard',
        array(
            'username' => array('type' => 'string')
        ),
        '',
        'GET',
        false,
        false
    );

    expose_function(
        "wb.create_investigation",
        "create_investigation",
        array(
            'name' => array('type' => 'string'),
            'description' => array('type' => 'string'),
            'brief_description' => array('type' => 'string'),
            'tags' => array('type' => 'string', 'required' => false),
            'proposal' => array('type' => 'string', 'required' => false),
            'icon' => array('type' => 'string', 'required' => false),
            'advisor_guid' => array('type' => 'int', 'required' => false)
        ),
        '',
        'GET',
        false,
        false
    );

    expose_function(
        "wb.edit_investigation",
        "edit_investigation",
        array(
            'guid' => array('type' => 'int'),
            'name' => array('type' => 'string'),
            'description' => array('type' => 'string'),
            'brief_description' => array('type' => 'string'),
            'advisor_guid' => array('type' => 'int', 'required' => false, 'default' => ""),
            'tags' => array('type' => 'string', 'required' => false, 'default' => "")
        ),
        '',
        'POST',
        false,
        false
    );

    expose_function(
        "wb.get_members",
        "get_members",
        array(
            'page' => array('type' => 'int', 'required' => false, 'default' => 0),
            'search' => array('type' => 'string', 'required' => false, 'default' => ""),
            'type_filter' => array('type' => 'int', 'required' => false, 'default' => 0),
            'school_filter' => array('type' => 'string', 'required' => false, 'default' => "")
        ),
        '',
        'GET',
        false,
        false
    );

    expose_function(
        "wb.get_members_by_school",
        "get_members_by_school",
        array(
            'search' => array('type' => 'string', 'default' => '')
        ),
        '',
        'GET',
        false,
        false
    );

    expose_function(
        'wb.create_i_wonder',
        'create_i_wonder',
        array(
            'question' => array('type' => 'string'),
        ),
        '',
        'GET',
        false,
        false
    );

    expose_function(
        'wb.get_latest_i_wonder_question',
        'get_latest_i_wonder_question',
        array(),
        '',
        'GET',
        false,
        false
    );

    expose_function(
        'wb.get_activities',
        'get_activities',
        array(
            'limit' => array('type' => 'string'),
            'offset' => array('type' => 'string')
        ),
        '',
        'GET',
        false,
        false
    );

    expose_function(
        'wb.get_news',
        'get_news',
        array(
            'limit' => array('type' => 'string'),
            'offset' => array('type' => 'string')
        ),
        '',
        'GET',
        false,
        false
    );

    expose_function(
        'wb.get_article_by_id',
        'get_article_by_id',
        array(
            'id' => array('type' => 'int')
        ),
        '',
        'GET',
        false,
        false
    );

    expose_function(
        "wb.create_article",
        "create_article",
        array(
            'title' => array('type' => 'string'),
            'description' => array('type' => 'string'),
            'excerpt' => array('type' => 'string'),
            'tags' => array('type' => 'string', 'required' => false, 'default' => "")
        ),
        '',
        'POST',
        false,
        false
    );

    expose_function(
        "wb.edit_article",
        "edit_article",
        array(
            'guid' => array('type' => 'int'),
            'title' => array('type' => 'string'),
            'description' => array('type' => 'string'),
            'excerpt' => array('type' => 'string'),
            'tags' => array('type' => 'string', 'required' => false, 'default' => "")
        ),
        '',
        'POST',
        false,
        false
    );

    expose_function(
        "wb.delete_article",
        "delete_article",
        array(
            'guid' => array('type' => 'int')
        ),
        '',
        'GET',
        false,
        false
    );

    expose_function(
        'wb.get_obs_by_username',
        'get_obs_by_username',
        array(
            'username' => array('type' => 'string'),
            'limit' => array('type' => 'int', 'required' => false, 'default' => 4)
        ),
        '',
        'GET',
        false,
        false
    );

    expose_function(
        'wb.get_user_stats',
        'get_user_stats',
        array(
            'username' => array('type' => 'string')
        ),
        '',
        'GET',
        false,
        false
    );

    expose_function(
        'wb.get_inv_by_username',
        'get_inv_by_username',
        array(
            'username' => array('type' => 'string')
        ),
        '',
        'GET',
        false,
        false
    );

    expose_function(
        'wb.delete_user',
        'delete_user',
        array(
            'username' => array('type' => 'string')
        ),
        '',
        'GET',
        false,
        false
    );

    expose_function(
        'wb.create_user',
        'create_user',
        array(
            'displayname' => array('type' => 'string'),
            'username' => array('type' => 'string'),
            'email' => array('type' => 'string'),
            'password' => array('type' => 'string'),
            'password2' => array('type' => 'string'),
            'profile_type' => array('type' => 'int', 'default' => 44)
        ),
        '',
        'GET',
        false,
        false
    );

    expose_function(
        'wb.get_profile_type',
        'get_profile_type',
        array(),
        '',
        'GET',
        false,
        false
    );

    expose_function(
        'wb.get_school_list',
        'get_school_list',
        array(),
        '',
        'GET',
        false,
        false
    );

    expose_function(
        'wb.user_exists_by_email',
        'user_exists_by_email',
        array(
            'email' => array('type' => 'string')
        ),
        '',
        'GET',
        false,
        false
    );

    expose_function(
        'wb.user_exists_by_username',
        'user_exists_by_username',
        array(
            'username' => array('type' => 'string')
        ),
        '',
        'GET',
        false,
        false
    );

    expose_function(
        'wb.request_new_password',
        'request_new_password',
        array(
            'email' => array('type' => 'string')
        ),
        '',
        'GET',
        false,
        false
    );

    expose_function(
      'wb.send_new_password',
      'send_new_password',
      array(
        'user_guid' => array('type' => 'int'),
        'conf_code' => array('type' => 'string')
      ),
      '',
      'GET',
      false,
      false
    );

    expose_function(
      'wb.change_password',
      'change_password',
      array(
        'user_guid' => array('type' => 'int'),
        'current_password' => array('type' => 'string'),
        'password' => array('type' => 'string'),
        'password2' => array('type' => 'string')
      ),
      '',
      'POST',
      false,
      false
    );

    expose_function(
      'wb.change_email',
      'change_email',
      array(
        'email' => array('type' => 'string'),
        'user_guid' => array('type' => 'int')
      ),
      '',
      'POST',
      false,
      false
    );

    expose_function(
        'wb.delete_investigation',
        'delete_investigation',
        array(
            'guid' => array('type' => 'int')
        ),
        '',
        'GET',
        false,
        false
    );

    expose_function(
        'wb.create_inv',
        'create_inv',
        array(
            'name' => array('type' => 'string'),
            'description' => array('type' => 'string'),
            'brief_description' => array('type' => 'string'),
            'advisor_guid' => array('type' => 'string', 'required' => false, 'default' => ""),
            'tags' => array('type' => 'string', 'required' => false, 'default' => ""),
        ),
        '',
        'POST',
        false,
        false
    );

    expose_function(
        'wb.search_site',
        'search_site',
        array(
            'terms' => array('type' => 'string')
        ),
        '',
        'GET',
        false,
        false
    );

	// Add some widgets
	elgg_register_widget_type('a_users_groups', elgg_echo('investigations:widget:membership'), elgg_echo('investigations:widgets:description'));

	// add group activity tool option
	//add_group_tool_option('activity', elgg_echo('investigations:enableactivity'), true);

    //elgg investigation entity
	//elgg_extend_view('investigations/tool_latest', 'investigations/profile/activity_module');

    // register search hooks
    // register some default search hooks
    elgg_register_plugin_hook_handler('search', 'object', 'search_objects_hook');
    elgg_register_plugin_hook_handler('search', 'user', 'search_users_hook');
    elgg_register_plugin_hook_handler('search', 'group', 'search_groups_hook');

    // tags and comments are a bit different.
    // register a search types and a hooks for them.
    elgg_register_plugin_hook_handler('search_types', 'get_types', 'search_custom_types_tags_hook');
    elgg_register_plugin_hook_handler('search', 'tags', 'search_tags_hook');

    elgg_register_plugin_hook_handler('search_types', 'get_types', 'search_custom_types_comments_hook');
    elgg_register_plugin_hook_handler('search', 'comments', 'search_comments_hook');


	// add link to owner block
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'investigation_activity_owner_block_menu');

	// group entity menu
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'investigation_entity_menu_setup');

	// group user hover menu
	elgg_register_plugin_hook_handler('register', 'menu:user_hover', 'investigation_user_entity_menu_setup');

	// delete and edit annotations for topic replies
	elgg_register_plugin_hook_handler('register', 'menu:annotation', 'investigation_annotation_menu_setup');

	//extend some views
	elgg_extend_view('css/elgg', 'groups/css');
	elgg_extend_view('js/elgg', 'groups/js');

	// Access permissions
	elgg_register_plugin_hook_handler('access:collections:write', 'all', 'investigation_write_acl_plugin_hook');

	// Register profile menu hook
	elgg_register_plugin_hook_handler('profile_menu', 'profile', 'forum_profile_menu');
	elgg_register_plugin_hook_handler('profile_menu', 'profile', 'investigation_activity_profile_menu');

	// allow ecml in discussion and profiles
	elgg_register_plugin_hook_handler('get_views', 'ecml', 'investigations_ecml_views_hook');
	elgg_register_plugin_hook_handler('get_views', 'ecml', 'investigationprofile_ecml_views_hook');

	// Register a handler for create groups
	elgg_register_event_handler('create', 'group', 'investigation_create_event_listener');

	// Register a handler for delete groups
	elgg_register_event_handler('delete', 'group', 'investigation_delete_event_listener');

	elgg_register_event_handler('join', 'group', 'investigation_user_join_event_listener');
	elgg_register_event_handler('leave', 'group', 'investigation_user_leave_event_listener');
	elgg_register_event_handler('pagesetup', 'system', 'investigation_setup_sidebar_menus');

	elgg_register_plugin_hook_handler('access:collections:add_user', 'collection', 'investigation_access_collection_override');

	elgg_register_event_handler('upgrade', 'system', 'groups_run_upgrades');

}

/**
 * This function loads a set of default fields into the profile, then triggers
 * a hook letting other plugins to edit add and delete fields.
 *
 * Note: This is a system:init event triggered function and is run at a super
 * low priority to guarantee that it is called after all other plugins have
 * initialized.
 */
function investigation_fields_setup() {

	$profile_defaults = array(
		'description' => 'longtext',
		'briefdescription' => 'text',
		'interests' => 'tags',
		//'website' => 'url',
	);

	$profile_defaults = elgg_trigger_plugin_hook('profile:fields', 'group', NULL, $profile_defaults);

	elgg_set_config('investigate', $profile_defaults);

	// register any tag metadata names
	foreach ($profile_defaults as $name => $type) {
		if ($type == 'tags') {
			elgg_register_tag_metadata_name($name);

			// only shows up in search but why not just set this in en.php as doing it here
			// means you cannot override it in a plugin
			add_translation(get_current_language(), array("tag_names:$name" => elgg_echo("investigations:$name")));
		}
	}
}

/**
 * Configure the groups sidebar menu. Triggered on page setup
 *
 */
function investigation_setup_sidebar_menus() {

	// Get the page owner entity
	$page_owner = elgg_get_page_owner_entity();

	if (elgg_in_context('investigation_profile')) {
		if (elgg_is_logged_in() && $page_owner->canEdit() && !$page_owner->isPublicMembership()) {
			$url = elgg_get_site_url() . "investigate/requests/{$page_owner->getGUID()}";

			$count = elgg_get_entities_from_relationship(array(
				'type' => 'user',
				'relationship' => 'membership_request',
				'relationship_guid' => $page_owner->getGUID(),
				'inverse_relationship' => true,
				'count' => true,
			));

			if ($count) {
				$text = elgg_echo('investigations:membershiprequests:pending', array($count));
			} else {
				$text = elgg_echo('investigations:membershiprequests');
			}

			elgg_register_menu_item('page', array(
				'name' => 'membership_requests',
				'text' => $text,
				'href' => $url,
			));
		}
	}
	if (elgg_get_context() == 'investigations' && !elgg_instanceof($page_owner, 'group')) {
		elgg_register_menu_item('page', array(
			'name' => 'investigations:all',
			'text' => elgg_echo('investigations:all'),
			'href' => 'investigate/all',
		));

		$user = elgg_get_logged_in_user_entity();
		if ($user) {
			$url =  "investigate/owner/$user->username";
			$item = new ElggMenuItem('investigations:owned', elgg_echo('investigations:owned'), $url);
			elgg_register_menu_item('page', $item);

			$url = "investigate/member/$user->username";
			$item = new ElggMenuItem('investigations:member', elgg_echo('investigations:yours'), $url);
			elgg_register_menu_item('page', $item);

			$url = "investigate/invitations/$user->username";
			$invitations = investigation_get_invited_groups($user->getGUID());
			if (is_array($invitations) && !empty($invitations)) {
				$invitation_count = count($invitations);
				$text = elgg_echo('investigations:invitations:pending', array($invitation_count));
			} else {
				$text = elgg_echo('investigations:invitations');
			}

			$item = new ElggMenuItem('investigations:user:invites', $text, $url);
			elgg_register_menu_item('page', $item);
		}
	}
}

/**
 * Groups page handler
 *
 * URLs take the form of
 *  All groups:           investigations/all
 *  User's owned groups:  groups/owner/<username>
 *  User's member groups: groups/member/<username>
 *  Group profile:        groups/profile/<guid>/<title>
 *  New group:            groups/add/<guid>
 *  Edit group:           groups/edit/<guid>
 *  Group invitations:    groups/invitations/<username>
 *  Invite to group:      groups/invite/<guid>
 *  Membership requests:  groups/requests/<guid>
 *  Group activity:       groups/activity/<guid>
 *  Group members:        groups/members/<guid>
 *
 * @param array $page Array of url segments for routing
 * @return bool
 */
function investigation_page_handler($page) {
	global $CONFIG;
	elgg_unregister_css('admin');
	elgg_unregister_css('settings');
	elgg_register_css('functions', elgg_get_site_url() . 'mod/weatherblur_theme/css/functions.less');
	elgg_load_css('functions');
	elgg_register_css('button', elgg_get_site_url() . 'mod/weatherblur_theme/css/button2.less');
	elgg_load_css('button');
	elgg_register_css('inv-less', elgg_get_site_url() . 'mod/weatherblur_theme/css/inv.less');
	elgg_load_css('inv-less');
	elgg_register_css('discussion', elgg_get_site_url() . 'mod/weatherblur_theme/css/discussion_block.less');
	elgg_load_css('discussion');
	/* needed for discussion icons */
	elgg_register_css('interactions', elgg_get_site_url() . 'mod/interactions/css/interactions.css');
	elgg_load_css('interactions');


	// forward old profile urls
	if (is_numeric($page[0])) {
		$group = get_entity($page[0]);
		if (elgg_instanceof($group, 'group', '', 'ElggGroup')) {
			system_message(elgg_echo('changebookmark'));
			forward($group->getURL());
		}
	}

	elgg_load_library('elgg:investigations');

	if (!isset($page[0])) {
		$page[0] = 'all';
	}

	elgg_push_breadcrumb(elgg_echo('investigations'), "investigate/all");

	switch ($page[0]) {
		case 'all':
			investigations_handle_all_page();
			break;
		case 'search':
			groups_search_page();
			break;
		case 'owner':
			groups_handle_owned_page();
			break;
		case 'member':
			set_input('username', $page[1]);
			groups_handle_mine_page();
			break;
		case 'invitations':
			set_input('username', $page[1]);
			groups_handle_invitations_page();
			break;
		case 'add':
			investigations_handle_edit_page('add');
			break;
		case 'edit':
			investigations_handle_edit_page('edit', $page[1]);
			break;
		case 'profile':
			investigations_handle_profile_page($page[1]);
			break;
		case 'activity':
			groups_handle_activity_page($page[1]);
			break;
		case 'members':
			groups_handle_members_page($page[1]);
			break;
		case 'invite':
			groups_handle_invite_page($page[1]);
			break;
		case 'requests':
			groups_handle_requests_page($page[1]);
			break;
		default:
			return false;
	}
	return true;
}

function observation_page_handler($page) {
	global $CONFIG;
    elgg_load_library('elgg:investigations');

    if($page[0] == 'all') {
        list_all_observations();
    }
    else {
        elgg_register_js('video-js', 'http://vjs.zencdn.net/c/video.js', 'footer', 10000);

        elgg_register_css('video-js-css', 'http://vjs.zencdn.net/c/video-js.css');
        elgg_register_css('observation-detail', elgg_get_site_url() . 'mod/investigations/css/observation-detail.less');

        elgg_load_css('observation-detail');
        elgg_load_css('video-js-css');
        elgg_load_js('video-js');

        observation_page($page[0]);
    }
    return true;
}

/**
 * Handle group icons.
 *
 * @param array $page
 * @return void
 */
function investigation_icon_handler($page) {

	// The username should be the file we're getting
	if (isset($page[0])) {
		set_input('group_guid', $page[0]);
	}
	if (isset($page[1])) {
		set_input('size', $page[1]);
	}
	// Include the standard profile index
	$plugin_dir = elgg_get_plugins_path();
	include("$plugin_dir/groups/icon.php");
	return true;
}

/**
 * Populates the ->getUrl() method for group objects
 *
 * @param ElggEntity $entity File entity
 * @return string File URL
 */
function investigation_url($entity) {
	$title = elgg_get_friendly_title($entity->name);

	return "investigate/profile/{$entity->guid}/$title";
}

/**
 * Override the default entity icon for groups
 *
 * @return string Relative URL
 */
function investigation_icon_url_override($hook, $type, $returnvalue, $params) {
	/* @var ElggGroup $group */
	$group = $params['entity'];
	$size = $params['size'];

	$icontime = $group->icontime;
	// handle missing metadata (pre 1.7 installations)
	if (null === $icontime) {
		$file = new ElggFile();
		$file->owner_guid = $group->owner_guid;
		$file->setFilename("groups/" . $group->guid . "large.jpg");
		$icontime = $file->exists() ? time() : 0;
		create_metadata($group->guid, 'icontime', $icontime, 'integer', $group->owner_guid, ACCESS_PUBLIC);
	}
	if ($icontime) {
		// return thumbnail
		return "groupicon/$group->guid/$size/$icontime.jpg";
	}

	return "mod/groups/graphics/default{$size}.gif";
}

/**
 * Add owner block link
 */
function investigation_activity_owner_block_menu($hook, $type, $return, $params) {
	if (elgg_instanceof($params['entity'], 'group')) {
		if ($params['entity']->activity_enable != "no") {
			$url = "groups/activity/{$params['entity']->guid}";
			$item = new ElggMenuItem('activity', elgg_echo('investigations:activity'), $url);
			$return[] = $item;
		}
	}

	return $return;
}

/**
 * Add links/info to entity menu particular to group entities
 */
function investigation_entity_menu_setup($hook, $type, $return, $params) {
	if (elgg_in_context('widgets')) {
		return $return;
	}

	$entity = $params['entity'];
	$handler = elgg_extract('handler', $params, false);
	if ($handler != 'investigate') {
		return $return;
	}

	foreach ($return as $index => $item) {
		if (in_array($item->getName(), array('access', 'likes', 'edit', 'delete'))) {
			unset($return[$index]);
		}
	}

	// membership type
	$membership = $entity->membership;
	if ($membership == ACCESS_PUBLIC) {
		$mem = elgg_echo("investigations:open");
	} else {
		$mem = elgg_echo("investigations:closed");
	}
	$options = array(
		'name' => 'membership',
		'text' => $mem,
		'href' => false,
		'priority' => 100,
	);
	$return[] = ElggMenuItem::factory($options);

	// number of members
	$num_members = get_group_members($entity->guid, 10, 0, 0, true);
	$members_string = elgg_echo('investigations:member');
	$options = array(
		'name' => 'members',
		'text' => $num_members . ' ' . $members_string,
		'href' => false,
		'priority' => 200,
	);
	$return[] = ElggMenuItem::factory($options);

	// feature link
	if (elgg_is_admin_logged_in()) {
		$isFeatured = $entity->featured_group == "yes";

		if (!$isFeatured) {

			$return[] = ElggMenuItem::factory(array(
				'name' => 'feature',
				'text' => elgg_echo("investigations:makefeatured"),
				'href' => elgg_add_action_tokens_to_url("action/investigations/featured?group_guid={$entity->guid}&action_type=feature"),
				'priority' => 300,
				'item_class' => $isFeatured ? 'hidden' : '',
			));
		} else {

			$return[] = ElggMenuItem::factory(array(
				'name' => 'unfeature',
				'text' => elgg_echo("investigations:makeunfeatured"),
				'href' => elgg_add_action_tokens_to_url("action/investigations/featured?group_guid={$entity->guid}&action_type=unfeature"),
				'priority' => 300,
				'item_class' => $isFeatured ? '' : 'hidden',
			));
		}
	}

	return $return;
}

/**
 * Add a remove user link to user hover menu when the page owner is a group
 */
function investigation_user_entity_menu_setup($hook, $type, $return, $params) {
	if (elgg_is_logged_in()) {
		$group = elgg_get_page_owner_entity();

		// Check for valid group
		if (!elgg_instanceof($group, 'group')) {
			return $return;
		}

		$entity = $params['entity'];

		// Make sure we have a user and that user is a member of the group
		if (!elgg_instanceof($entity, 'user') || !$group->isMember($entity)) {
			return $return;
		}

		// Add remove link if we can edit the group, and if we're not trying to remove the group owner
		if ($group->canEdit() && $group->getOwnerGUID() != $entity->guid) {
			$remove = elgg_view('output/confirmlink', array(
				'href' => "action/groups/remove?user_guid={$entity->guid}&group_guid={$group->guid}",
				'text' => elgg_echo('investigations:removeuser'),
			));

			$options = array(
				'name' => 'removeuser',
				'text' => $remove,
				'priority' => 999,
			);
			$return[] = ElggMenuItem::factory($options);
		}
	}

	return $return;
}

/**
 * Add edit and delete links for forum replies
 */
function investigation_annotation_menu_setup($hook, $type, $return, $params) {
	if (elgg_in_context('widgets')) {
		return $return;
	}

	$annotation = $params['annotation'];

	if ($annotation->name != 'group_topic_post') {
		return $return;
	}

	if ($annotation->canEdit()) {
		$url = elgg_http_add_url_query_elements('action/discussion/reply/delete', array(
			'annotation_id' => $annotation->id,
		));

		$options = array(
			'name' => 'delete',
			'href' => $url,
			'text' => "<span class=\"elgg-icon elgg-icon-delete\"></span>",
			'confirm' => elgg_echo('deleteconfirm'),
			'encode_text' => false
		);
		$return[] = ElggMenuItem::factory($options);

		$url = elgg_http_add_url_query_elements('discussion', array(
			'annotation_id' => $annotation->id,
		));

		$options = array(
			'name' => 'edit',
			'href' => "#edit-annotation-$annotation->id",
			'text' => elgg_echo('edit'),
			'encode_text' => false,
			'rel' => 'toggle',
		);
		$return[] = ElggMenuItem::factory($options);
	}

	return $return;
}

/**
 * Groups created so create an access list for it
 */
function investigation_create_event_listener($event, $object_type, $object) {
	$ac_name = elgg_echo('investigations:group') . ": " . $object->name;
	$group_id = create_access_collection($ac_name, $object->guid);
	if ($group_id) {
		$object->group_acl = $group_id;
	} else {
		// delete group if access creation fails
		return false;
	}

	return true;
}

/**
 * Return the write access for the current group if the user has write access to it.
 */
function investigation_write_acl_plugin_hook($hook, $entity_type, $returnvalue, $params) {
	$page_owner = elgg_get_page_owner_entity();
	$user_guid = $params['user_guid'];
	$user = get_entity($user_guid);
	if (!$user) {
		return $returnvalue;
	}

	// only insert group access for current group
	if ($page_owner instanceof ElggGroup) {
		if ($page_owner->canWriteToContainer($user_guid)) {
			$returnvalue[$page_owner->group_acl] = elgg_echo('investigations:group') . ': ' . $page_owner->name;

			unset($returnvalue[ACCESS_FRIENDS]);
		}
	} else {
		// if the user owns the group, remove all access collections manually
		// this won't be a problem once the group itself owns the acl.
		$groups = elgg_get_entities_from_relationship(array(
					'relationship' => 'member',
					'relationship_guid' => $user_guid,
					'inverse_relationship' => FALSE,
					'limit' => false
				));

		if ($groups) {
			foreach ($groups as $group) {
				unset($returnvalue[$group->group_acl]);
			}
		}
	}

	return $returnvalue;
}

/**
 * Groups deleted, so remove access lists.
 */
function investigation_delete_event_listener($event, $object_type, $object) {
	delete_access_collection($object->group_acl);

	return true;
}

/**
 * Listens to a group join event and adds a user to the group's access control
 *
 */
function investigation_user_join_event_listener($event, $object_type, $object) {

	$group = $object['group'];
	$user = $object['user'];
	$acl = $group->group_acl;

	add_user_to_access_collection($user->guid, $acl);

	return true;
}

/**
 * Make sure users are added to the access collection
 */
function investigation_access_collection_override($hook, $entity_type, $returnvalue, $params) {
	if (isset($params['collection'])) {
		if (elgg_instanceof(get_entity($params['collection']->owner_guid), 'group')) {
			return true;
		}
	}
}

/**
 * Listens to a group leave event and removes a user from the group's access control
 *
 */
function investigation_user_leave_event_listener($event, $object_type, $object) {

	$group = $object['group'];
	$user = $object['user'];
	$acl = $group->group_acl;

	remove_user_from_access_collection($user->guid, $acl);

	return true;
}

/**
 * Grabs groups by invitations
 * Have to override all access until there's a way override access to getter functions.
 *
 * @param int  $user_guid    The user's guid
 * @param bool $return_guids Return guids rather than ElggGroup objects
 *
 * @return array ElggGroups or guids depending on $return_guids
 */
function investigation_get_invited_groups($user_guid, $return_guids = FALSE) {
	$ia = elgg_set_ignore_access(TRUE);
	$groups = elgg_get_entities_from_relationship(array(
		'relationship' => 'invited',
		'relationship_guid' => $user_guid,
		'inverse_relationship' => TRUE,
		'limit' => 0,
	));
	elgg_set_ignore_access($ia);

	if ($return_guids) {
		$guids = array();
		foreach ($groups as $group) {
			$guids[] = $group->getGUID();
		}

		return $guids;
	}

	return $groups;
}

/**
 * Join a user to a group, add river event, clean-up invitations
 *
 * @param ElggGroup $group
 * @param ElggUser  $user
 * @return bool
 */
function investigations_join_investigation($group, $user) {

	// access ignore so user can be added to access collection of invisible group
	$ia = elgg_set_ignore_access(TRUE);

	$result = $group->join($user);
	elgg_set_ignore_access($ia);

	if ($result) {
		// flush user's access info so the collection is added
		get_access_list($user->guid, 0, true);

		// Remove any invite or join request flags
		remove_entity_relationship($group->guid, 'invited', $user->guid);
		remove_entity_relationship($user->guid, 'membership_request', $group->guid);

		add_to_river(array(
			'view' => 'river/relationship/member/create',
			'action_type' => 'join',
			'subject_guid' => $user->guid,
			'object_guid' => $group->guid,
		));

		return true;
	}

	return false;
}

/**
 * Function to use on groups for access. It will house private, loggedin, public,
 * and the group itself. This is when you don't want other groups or access lists
 * in the access options available.
 *
 * @return array
 */
function investigation_access_options($group) {
	$access_array = array(
		ACCESS_PRIVATE => 'private',
		ACCESS_LOGGED_IN => 'logged in users',
		ACCESS_PUBLIC => 'public',
		$group->group_acl => elgg_echo('investigations:acl', array($group->name)),
	);
	return $access_array;
}

function investigation_activity_profile_menu($hook, $entity_type, $return_value, $params) {

	if ($params['owner'] instanceof ElggGroup) {
		$return_value[] = array(
			'text' => elgg_echo('investigations:activity'),
			'href' => "groups/activity/{$params['owner']->getGUID()}"
		);
	}
	return $return_value;
}

/**
 * Parse ECML on group discussion views
 */
function investigations_ecml_views_hook($hook, $entity_type, $return_value, $params) {
	$return_value['forum/viewposts'] = elgg_echo('investigations:ecml:discussion');

	return $return_value;
}

/**
 * Parse ECML on group profiles
 */
function investigationprofile_ecml_views_hook($hook, $entity_type, $return_value, $params) {
	$return_value['groups/groupprofile'] = elgg_echo('investigations:ecml:groupprofile');

	return $return_value;
}



/**
 * Discussion
 *
 */

elgg_register_event_handler('init', 'system', 'investigation_discussion_init');

/**
 * Initialize the discussion component
 */
function investigation_discussion_init() {

	elgg_register_library('elgg:investigation_discussion', elgg_get_plugins_path() . 'investigations/lib/investigation_discussion.php');

	elgg_register_page_handler('investigation_discussion', 'investigation_discussion_page_handler');
	elgg_register_page_handler('forum', 'investigation_discussion_forum_page_handler');

	elgg_register_entity_url_handler('object', 'investigationforumtopic', 'investigation_discussion_override_topic_url');
	elgg_register_entity_url_handler('object', 'investigationforumtopic_image', 'investigation_discussion_override_topic_url');
	elgg_register_entity_url_handler('object', 'investigationforumtopic_video', 'investigation_discussion_override_topic_url');
	elgg_register_entity_url_handler('object', 'investigationforumtopic_text', 'investigation_discussion_override_topic_url');
	elgg_register_entity_url_handler('object', 'investigationforumtopic_graph', 'investigation_discussion_override_topic_url');
	elgg_register_entity_url_handler('object', 'investigationforumtopic_map', 'investigation_discussion_override_topic_url');

	// commenting not allowed on discussion topics (use a different annotation)
	elgg_register_plugin_hook_handler('permissions_check:comment', 'object', 'investigation_discussion_comment_override');

	$action_base = elgg_get_plugins_path() . 'investigations/actions/discussion';
	elgg_register_action('investigation_discussion/save', "$action_base/save.php");
	elgg_register_action('investigation_discussion/delete', "$action_base/delete.php");
	elgg_register_action('investigation_discussion/reply/save', "$action_base/reply/save.php");
	elgg_register_action('investigation_discussion/reply/delete', "$action_base/reply/delete.php");

	// add link to owner block
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'investigation_discussion_owner_block_menu');

	// Register for search.
	elgg_register_entity_type('object', 'investigationforumtopic');
	elgg_register_entity_type('object', 'investigationforumtopic_image');
	elgg_register_entity_type('object', 'investigationforumtopic_video');
	elgg_register_entity_type('object', 'investigationforumtopic_text');
	elgg_register_entity_type('object', 'investigationforumtopic_map');
	elgg_register_entity_type('object', 'investigationforumtopic_graph');

	// because replies are not comments, need of our menu item
	elgg_register_plugin_hook_handler('register', 'menu:river', 'investigation_discussion_add_to_river_menu');

	// add the forum tool option
	//add_group_tool_option('forum', elgg_echo('investigations:enableforum'), true);

	//elgg_extend_view('investigations/tool_latest', 'discussion/group_module');
	//elgg_extend_view('investigations/tool_latest', 'discussion/investigation_modules');
	//not needed, jeeez.

	// notifications
	register_notification_object('object', 'investigationforumtopic', elgg_echo('investigation_discussion:notification:topic:subject'));
	register_notification_object('object', 'investigationforumtopic_image', elgg_echo('investigation_discussion:notification:topic:subject'));
	register_notification_object('object', 'investigationforumtopic_video', elgg_echo('investigation_discussion:notification:topic:subject'));
	register_notification_object('object', 'investigationforumtopic_text', elgg_echo('investigation_discussion:notification:topic:subject'));
	register_notification_object('object', 'investigationforumtopic_map', elgg_echo('investigation_discussion:notification:topic:subject'));
	register_notification_object('object', 'investigationforumtopic_graph', elgg_echo('investigation_discussion:notification:topic:subject'));
	elgg_register_plugin_hook_handler('notify:entity:message', 'object', 'investigationforumtopic_notify_message');
	elgg_register_event_handler('create', 'annotation', 'investigation_discussion_reply_notifications');
	elgg_register_plugin_hook_handler('notify:annotation:message', 'group_topic_post', 'investigation_discussion_create_reply_notification');
}

/**
 * Exists for backwards compatibility for Elgg 1.7
 */
function investigation_discussion_forum_page_handler($page) {
	switch ($page[0]) {
		case 'topic':
			header('Status: 301 Moved Permanently');
			forward("/investigation_discussion/view/{$page[1]}/{$page[2]}");
			break;
		default:
			return false;
	}
}

/**
 * Discussion page handler
 *
 * URLs take the form of
 *  All topics in site:    investigation_discussion/all
 *  List topics in forum:  investigation_discussion/owner/<guid>
 *  View discussion topic: investigation_discussion/view/<guid>
 *  Add discussion topic:  investigation_discussion/add/<guid>
 *  Edit discussion topic: investigation_discussion/edit/<guid>
 *
 * @param array $page Array of url segments for routing
 * @return bool
 */
function investigation_discussion_page_handler($page) {

	elgg_load_library('elgg:investigation_discussion');

	elgg_register_css('functions', elgg_get_site_url() . 'mod/weatherblur_theme/css/functions.less');
	elgg_load_css('functions');
	elgg_register_css('discussion-detail', elgg_get_site_url() . 'mod/weatherblur_theme/css/discussion_detail.less');
	elgg_load_css('discussion-detail');
	elgg_register_css('discussion', elgg_get_site_url() . 'mod/weatherblur_theme/css/discussion_block.less');
	elgg_load_css('discussion');

	if (!isset($page[0])) {
		$page[0] = 'all';
	}

	elgg_push_breadcrumb(elgg_echo('discussion'), 'investigation_discussion/all');

	switch ($page[0]) {
		case 'all':
			investigation_discussion_handle_all_page();
			break;
		case 'owner':
			investigation_discussion_handle_list_page($page[1]);
			break;
		case 'add':
			investigation_discussion_handle_edit_page('add', $page[1]);
			break;
		case 'edit':
			investigation_discussion_handle_edit_page('edit', $page[1]);
			break;
		case 'view':
			investigation_discussion_handle_view_page($page[1]);
			break;
		default:
			return false;
	}
	return true;
}

/**
 * Override the discussion topic url
 *
 * @param ElggObject $entity Discussion topic
 * @return string
 */
function investigation_discussion_override_topic_url($entity) {
	return 'investigation_discussion/view/' . $entity->guid . '/' . elgg_get_friendly_title($entity->title);
}

/**
 * We don't want people commenting on topics in the river
 */
function investigation_discussion_comment_override($hook, $type, $return, $params) {
	if (elgg_instanceof($params['entity'], 'object', 'investigationforumtopic') || elgg_instanceof($params['entity'], 'object', 'investigationforumtopic_image') || elgg_instanceof($params['entity'], 'object', 'investigationforumtopic_video') || elgg_instanceof($params['entity'], 'object', 'investigationforumtopic_text') || elgg_instanceof($params['entity'], 'object', 'investigationforumtopic_map') || elgg_instanceof($params['entity'], 'object', 'investigationforumtopic_graph')) {
		return false;
	}
}

/**
 * Add owner block link
 */
function investigation_discussion_owner_block_menu($hook, $type, $return, $params) {
	if (elgg_instanceof($params['entity'], 'group')) {
		if ($params['entity']->forum_enable != "no") {
			$url = "investigation_discussion/owner/{$params['entity']->guid}";
			$item = new ElggMenuItem('investigation_discussion', elgg_echo('investigation_discussion:group'), $url);
			$return[] = $item;
		}
	}

	return $return;
}

/**
 * Add the reply button for the river
 */
function investigation_discussion_add_to_river_menu($hook, $type, $return, $params) {
	if (elgg_is_logged_in() && !elgg_in_context('widgets')) {
		$item = $params['item'];
		$object = $item->getObjectEntity();
		if (elgg_instanceof($object, 'object', 'investigationforumtopic') || elgg_instanceof($object, 'object', 'investigationforumtopic_image') || elgg_instanceof($object, 'object', 'investigationforumtopic_video' || elgg_instanceof($object, 'object', 'investigationforumtopic_text') || elgg_instanceof($object, 'object', 'investigationforumtopic_graph') || elgg_instanceof($object, 'object', 'investigationforumtopic_map'))) {
			if ($item->annotation_id == 0) {
				$group = $object->getContainerEntity();
				if ($group && ($group->canWriteToContainer() || elgg_is_admin_logged_in())) {
					$options = array(
						'name' => 'reply',
						'href' => "#groups-reply-$object->guid",
						'text' => elgg_view_icon('speech-bubble'),
						'title' => elgg_echo('reply:this'),
						'rel' => 'toggle',
						'priority' => 50,
					);
					$return[] = ElggMenuItem::factory($options);
				}
			}
		}
	}

	return $return;
}

/**
 * Create investigation_discussion notification body
 *
 * @todo namespace method with 'discussion'
 *
 * @param string $hook
 * @param string $type
 * @param string $message
 * @param array  $params
 */
function investigationforumtopic_notify_message($hook, $type, $message, $params) {
	$entity = $params['entity'];
	$to_entity = $params['to_entity'];
	$method = $params['method'];

	if (($entity instanceof ElggEntity) && ($entity->getSubtype() == 'investigationforumtopic' || $entity->getSubtype() == 'investigationforumtopic_image' || $entity->getSubtype() == 'investigationforumtopic_video' || $entity->getSubtype() == 'investigationforumtopic_text' || $entity->getSubtype() == 'investigationforumtopic_map' || $entity->getSubtype() == 'investigationforumtopic_graph')) {
		$descr = $entity->description;
		$title = $entity->title;
		$url = $entity->getURL();
		$owner = $entity->getOwnerEntity();
		$group = $entity->getContainerEntity();

		return elgg_echo('investigations:notification', array(
			$owner->name,
			$group->name,
			$entity->title,
			$entity->description,
			$entity->getURL()
		));
	}

	return null;
}

/**
 * Create discussion reply notification body
 *
 * @param string $hook
 * @param string $type
 * @param string $message
 * @param array  $params
 */
function investigation_discussion_create_reply_notification($hook, $type, $message, $params) {
	$reply = $params['annotation'];
	$method = $params['method'];
	$topic = $reply->getEntity();
	$poster = $reply->getOwnerEntity();
	$group = $topic->getContainerEntity();

	return elgg_echo('investigation_discussion:notification:reply:body', array(
		$poster->name,
		$topic->title,
		$group->name,
		$reply->value,
		$topic->getURL(),
	));
}

/**
 * Catch reply to discussion topic and generate notifications
 *
 * @todo this will be replaced in Elgg 1.9 and is a clone of object_notifications()
 *
 * @param string         $event
 * @param string         $type
 * @param ElggAnnotation $annotation
 * @return void
 */
function investigation_discussion_reply_notifications($event, $type, $annotation) {
	global $CONFIG, $NOTIFICATION_HANDLERS;

	if ($annotation->name !== 'group_topic_post') {
		return;
	}

	// Have we registered notifications for this type of entity?
	$object_type = 'object';
	$object_subtype = 'investigationforumtopic';

	$topic = $annotation->getEntity();
	if (!$topic) {
		return;
	}

	$poster = $annotation->getOwnerEntity();
	if (!$poster) {
		return;
	}

	if (isset($CONFIG->register_objects[$object_type][$object_subtype])) {
		$subject = $CONFIG->register_objects[$object_type][$object_subtype];
		$string = $subject . ": " . $topic->getURL();

		// Get users interested in content from this person and notify them
		// (Person defined by container_guid so we can also subscribe to groups if we want)
		foreach ($NOTIFICATION_HANDLERS as $method => $foo) {
			$interested_users = elgg_get_entities_from_relationship(array(
				'relationship' => 'notify' . $method,
				'relationship_guid' => $topic->getContainerGUID(),
				'inverse_relationship' => true,
				'type' => 'user',
				'limit' => 0,
			));

			if ($interested_users && is_array($interested_users)) {
				foreach ($interested_users as $user) {
					if ($user instanceof ElggUser && !$user->isBanned()) {
						if (($user->guid != $poster->guid) && has_access_to_entity($topic, $user) && $topic->access_id != ACCESS_PRIVATE) {
							$body = elgg_trigger_plugin_hook('notify:annotation:message', $annotation->getSubtype(), array(
								'annotation' => $annotation,
								'to_entity' => $user,
								'method' => $method), $string);
							if (empty($body) && $body !== false) {
								$body = $string;
							}
							if ($body !== false) {
								notify_user($user->guid, $topic->getContainerGUID(), $subject, $body, null, array($method));
							}
						}
					}
				}
			}
		}
	}
}

/**
 * A simple function to see who can edit a group discussion post
 * @param the comment $entity
 * @param user who owns the group $group_owner
 * @return boolean
 */
function investigations_can_edit_discussion($entity, $group_owner) {

	//logged in user
	$user = elgg_get_logged_in_user_guid();

    return true;

	if (($entity->owner_guid == $user) || $group_owner == $user || elgg_is_admin_logged_in()) {
		return true;
	} else {
		return false;
	}
}

/**
 * Process upgrades for the groups plugin
 */
function investigations_run_upgrades() {
	$path = elgg_get_plugins_path() . 'groups/upgrades/';
	$files = elgg_get_upgrade_files($path);
	foreach ($files as $file) {
		include "$path{$file}";
	}
}

// copied from thewire/start.php
function investigation_filter($text) {
	global $CONFIG;

	$text = ' ' . $text;

	// email addresses
	$text = preg_replace(
				'/(^|[^\w])([\w\-\.]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,})/i',
				'$1<a href="mailto:$2@$3">$2@$3</a>',
				$text);

	// links
	$text = parse_urls($text);

	// usernames
	$text = preg_replace(
				'/(^|[^\w])@([\p{L}\p{Nd}._]+)/u',
				'$1<a href="' . $CONFIG->wwwroot . 'thewire/owner/$2">@$2</a>',
				$text);

	$text = trim($text);

	return $text;
}

// start rest calls
function login_user($username, $password) {

  $username = urldecode($username);
  $password = urldecode($password);

	if (true === elgg_authenticate($username, $password)) {

		$token = create_user_token($username, PHP_INT_MAX);
        $user_guid = validate_user_token($token, null);

        $user = get_user($user_guid);
        login($user, false);

		return is_logged_in();
	}
    else {
	    throw new SecurityException(elgg_echo('SecurityException:authenticationfailed'));
    }
}

function logout_user() {
    return logout();
}

function get_invs($username, $password) {
    // New users need to be added to wb-aggregator so we make this curl request for this reason.
    $app_env = getenv("APP_ENV");
    $app_env = $app_env == "prod" ? $app_env : "unstable";

	if (true === elgg_authenticate($username, $password)) {
		$token = create_user_token($username, PHP_INT_MAX);
	}
    else {
	    throw new SecurityException(elgg_echo('SecurityException:authenticationfailed'));
    }

    $user_guid = validate_user_token($token, null);
    $user = get_user($user_guid);

    $profile_type_guid = $user->custom_profile_type;
    $profile_type = get_entity($profile_type_guid);

    $post_fields = array(
        'class' => 'wb.api.User',
        'elggGroup' => $profile_type ? $profile_type : '',
        'elggHost' => elgg_get_site_url(),
        'elggId' => $user_guid,
        // not sure why this image is here but travis has it setup this way and don't want to change it
        'image' => 'http://demo.nbtsolutions.com/elgg/_graphics/icons/user/defaultsmall.gif'
    );

    curl_setopt_array($ch, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => "http://wb-aggregator.".$app_env.".nbt.io/api/observer/user",
        CURLOPT_POST => count($post_fields)
    ));

    $obs_measurement = curl_exec($ch);

    create_agg_user($user);

    login($user, false);

    $dbprefix = elgg_get_config('dbprefix');

	$results = elgg_get_entities_from_relationship(array(
        'type_subtype_pair'	=>	array('group' => 'investigation'),
        'limit' => 0,
        /*
		'relationship' => 'member',
		'relationship_guid' => $user_guid,
		'inverse_relationship' => false,
		'full_view' => false,
        */
		'joins' => array("JOIN {$dbprefix}groups_entity ge ON e.guid = ge.guid"),
		'order_by' => 'ge.name asc'
	));

    // build out our list of investigation names/ids
    $investigations = array(
        'user_guid' => intval($user_guid),
        'username' => $username,
        'icon' => $user->getIcon('small'),
        'token' => $token,
        'investigations' => array()
    );
    foreach($results as $result) {
        $investigations['investigations'][] = array(
            'name' => $result->name,
            'guid' => $result->guid
        );
    }
    return $investigations;
}

function get_all_invs() {
    $inv = array();

    $results = elgg_get_entities(array(
        'type_subtype_pair'	=>	array('group' => 'investigation'),
        'limit' => 0
    ));

    foreach($results as $result) {
        $e = $result->getEntitiesFromRelationship('advisor', true);

        $inv_likes = $result->getAnnotations('likes');
        $user = get_user($result->owner_guid);
        $i_liked = false;
        $user_guid = elgg_get_logged_in_user_guid();

        foreach($inv_likes as $like) {
            if($like->owner_guid == $user_guid) {
                $i_liked = true;
            }
        }

        $inv[] = array(
            "id" => $result->guid,
            "name" => $result->name,
            "coordinator" => $result->getOwnerEntity()->get('name'),
            "advisor" => $e ? $e[0]->get("name") : "",
            "image" => $result->getIcon("large"),
            "description" => $result->description,
            "like_count" => count($inv_likes),
            "i_liked" => $i_liked
        );
    }

    return $inv;

}

function get_discs_by_inv_id($id, $limit = null, $discussion_subtype = array()) {

    $user_guid = elgg_get_logged_in_user_guid();
    $thumbnail_filepath = "";
    $large_filepath = "";

    $ignore = elgg_set_ignore_access();
	$discussions = elgg_get_entities(array(
        'container_guid' => array($id),
		'type' => 'object',
		'subtypes' => $discussion_subtype,
		'order_by' => 'e.last_action desc',
        'limit' => $limit,
		'full_view' => false
	));

    foreach($discussions as $discussion) {

        $user = get_user($discussion->owner_guid);
        $likes = $discussion->getAnnotations('likes');
        $i_liked = false;
        $comments = array();

        foreach($likes as $like) {
            if($like->owner_guid == $user_guid) {
                $i_liked = true;
            }
        }

        $ignore = elgg_set_ignore_access(true);
        $result_comments = $discussion->getAnnotations('group_topic_post', 3, 0, 'desc');
        elgg_set_ignore_access($ignore);

        $file_thumbnail = elgg_get_entities_from_relationship(array(
            'relationship' => 'thumbnail_file',
            'relationship_guid' => $discussion->guid,
            'inverse_relationship' => true
        ));

        if($file_thumbnail) {
            $thumbnail_filepath = elgg_get_site_url().'file/download/'.$file_thumbnail[0]->guid.'/'.$file_thumbnail[0]->getFilename();
        }
        else {
            $thumbnail_filepath = "";
        }

        // large file
        $file_large = elgg_get_entities_from_relationship(array(
            'relationship' => 'large_file',
            'relationship_guid' => $discussion->guid,
            'inverse_relationship' => true
        ));

        if($file_large) {
            $large_filepath = elgg_get_site_url().'file/download/'.$file_large[0]->guid.'/'.$file_large[0]->getFilename();
        }
        else {
            $large_filepath = "";
        }

        foreach($result_comments as $comment) {

            $comment_user = get_user($comment->owner_guid);

            $comments[] = array(
                'description' => $comment->value,
                'like_count' => 0,
                'date' => elgg_get_friendly_time($comment->time_created),
                'user' => array(
                    'id' => $comment_user->guid,
                    'displayname' => $comment_user->name,
                    'username' => $comment_user->username,
                    'image' => $comment_user->getIcon('small')
                )
            );
        }

        $discussion_return_result[] = array(
            'id' => $discussion->guid,
            'name' => $discussion->title,
            'thumbnail_filepath' => $thumbnail_filepath,
            'large_filepath' => $large_filepath,
            'username' => $user->username,
            'displayname' => $user->name,
            'userIcon' => $user->getIcon('small'),
            'date' => elgg_get_friendly_time($discussion->time_created),
            'description' => $discussion->description,
            'like_count' => count($likes),
            'i_liked' => $i_liked,
            'comments' => $comments,
            'comment_count' => count($discussion->getAnnotations('group_topic_post')),
            'video' => $discussion->video,
            'type' => $discussion->getSubtype()
        );
    }
    elgg_set_ignore_access($ignore);

    return $discussion_return_result;
}

function get_disc_by_id($id) {

    $discussion = array();
    $comments = array();

    // get discussion object
    $results = elgg_get_entities(array(
        'guid' => array($id)
    ));

    $result = $results[0];

    $ignore = elgg_set_ignore_access(true);
    $elgg_comments = $results[0]->getAnnotations('group_topic_post', 0, 0, 'desc');
    elgg_set_ignore_access($ignore);

    foreach($elgg_comments as $elgg_comment) {

        $user = get_user($elgg_comment->owner_guid);

        $comments[] = array(
            'description' => $elgg_comment->value,
            'like_count' => 0,
            'date' => elgg_get_friendly_time($elgg_comment->time_created),
            'user' => array(
                'id' => $user->guid,
                'displayname' => $user->name,
                'username' => $user->username,
                'image' => $user->getIcon('small')
            )
        );
    }

    $discussion = array(
        'id' => $result->guid,
        'name' => $result->title,
        'date' => elgg_get_friendly_time($result->time_created),
        'description' => $result->description,
        'comments' => $comments,
        'like_count' => 0
    );

    return $discussion;

}

// create a thread on a user_guid
function create_messageboard($description, $subtype, $username) {

    // is user logged in
    $user = get_user_by_username($username);

    if(!$user) {
        throw Exception("Please use a valid username to perform this action.");
    }

    if(!elgg_is_logged_in()) {
        throw Exception("Please login to perform this action.");
    }

    switch($subtype) {
        case 'messageboard':
            $subtype = "messageboard";
            break;
        default:
            $subtype = "messageboard";
    }

    $logged_in_user_guid = elgg_get_logged_in_user_guid();
    $access_id = ACCESS_PUBLIC;
    $title = $description;

    if(strlen($title) > 64) {
        $title = substr($title, 0, 45) . '...';
    }

    $topic = new ElggObject();
    $topic->subtype = $subtype;
    $topic->title = $title;
    $topic->description = $description;
    $topic->access_id = $access_id;
    $topic->owner_guid = elgg_get_logged_in_user_guid();

    $result = $topic->save();
    if (!$result) {
        throw new Exception(elgg_echo('discussion:error:notsaved'));
    }

    add_entity_relationship($user->guid, 'messageboard', $topic->guid);

    $result = add_to_river(
        'river/object/groupforumtopic/create',
        'post',
        $logged_in_user_guid,
        $topic->guid
    );

    var_dump($logged_in_user_guid);

    return $result;

}

function get_messageboard($username) {

    $user = get_user_by_username($username);
    $messages = array();

    if(!$user) {
        throw new Exception("Please use a valid username to perform this action.");
    }

    $results = elgg_get_entities_from_relationship(array(
        'relationship' => 'messageboard',
        'relationship_guid' => $user->guid
    ));

    foreach($results as $result) {

        $owner = get_user($result->owner_guid);

        $messages[] = array(
            'id' => $result->guid,
            'title' => $result->title,
            'date' => elgg_get_friendly_time($result->time_created),
            'username' => $owner->username,
            'displayname' => $owner->name,
            'icon' => $owner->getIcon('tiny')

        );
    }

    return $messages;

}

// create a thread on any elgg object using a container_guid
function create_discussion($name, $description, $subtype, $container_guid, $video) {

    if(!elgg_is_logged_in()) {
        throw new Exception("Please login to perform this action");
    }

    // may need to deal with a file
    $filename = 'discussion_file';
    $file = $_FILES[$filename];

    switch ($subtype) {
        case 'video':
            $subtype = "investigationforumtopic_video";
            break;
        case 'map':
            $subtype = "investigationforumtopic_map";
            break;
        case 'camera':
            $subtype = "investigationforumtopic_image";
            break;
        case 'graph':
            $subtype = "investigationforumtopic_graph";
            break;
        default:
            $subtype = "investigationforumtopic_text";
    }

    $title = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    //$status = get_input("status");
    $access_id = ACCESS_PUBLIC;
    //$container_guid = (int) get_input('container_guid');
    //$guid = (int) get_input('topic_guid');
    //$tags = get_input("tags");

    // validation of inputs
    if (!$name || !$description) {
        throw new Exception('Please enter a name and title');
    }

    $ignore = elgg_set_ignore_access(true);
    $topic = new ElggObject();
    $topic->subtype = $subtype;
    $topic->title = urldecode($title);
    $topic->description = urldecode($description);
    //$topic->status = $status;
    $topic->access_id = $access_id;
    $topic->container_guid = $container_guid;
    $topic->owner_guid = elgg_get_logged_in_user_guid();
    $topic->video = $video;

    $tags = explode(",", $tags);
    $topic->tags = $tags;

    $result = $topic->save();

    if($file) {

        // original
        $fh_original = new ElggFile();
        $fh_original->owner_guid = $topic->owner_guid;
        $fh_original->name = 'topic_' . $topic->guid . '_' . $file['name'];
        $fh_original->setFilename('topic_' . $topic->guid . '_' . $file['name']);
        $fh_original->set('file category', 'discussion_file');
        $fh_original->open('write');
        $fh_original->write(get_uploaded_file($filename));
        $fh_original->close();
        $fh_original->save();

        $resized_large_file = get_resized_image_from_existing_file($fh_original->getFilenameOnFilestore(), 600, 400, 0, 0, 0, 0, false);
        $resized_thumbnail_file = get_resized_image_from_existing_file($fh_original->getFilenameOnFilestore(), 250, 250, 0, 0, 0, 0, false);

        $fh_large = new ElggFile();
        $fh_large->owner_guid = $topic->owner_guid;
        $fh_large->name = 'topic_' . $topic->guid . '_large_' . $file['name'];
        $fh_large->setFilename('topic_' . $topic->guid . '_large_' . $file['name']);
        $fh_large->set('file category', 'discussion_file');
        $fh_large->open('write');
        $fh_large->write($resized_large_file);
        $fh_large->close();
        $fh_large->save();

        // thumbnail
        $fh_thumbnail = new ElggFile();
        $fh_thumbnail->owner_guid = $topic->owner_guid;
        $fh_thumbnail->name = 'topic_' . $topic->guid . '_thumbnail_' . $file['name'];
        $fh_thumbnail->setFilename('topic_' . $topic->guid . '_thumbnail_' . $file['name']);
        $fh_thumbnail->set('file category', 'discussion_file');
        $fh_thumbnail->open('write');
        $fh_thumbnail->write($resized_thumbnail_file);
        $fh_thumbnail->close();
        $fh_thumbnail->save();

        add_entity_relationship($fh_original->getGUID(), 'original_file', $topic->guid);
        add_entity_relationship($fh_large->getGUID(), 'large_file', $topic->guid);
        add_entity_relationship($fh_thumbnail->getGUID(), 'thumbnail_file', $topic->guid);
    }

    elgg_set_ignore_access($ignore);

    if (!$result) {
        throw new Exception(elgg_echo('discussion:error:notsaved'));
    }

    add_to_river('river/object/groupforumtopic/create', 'create', elgg_get_logged_in_user_guid(), $topic->guid);

    return array(
        "guid" => $topic->guid
    );
}

function get_entity_by_name($name) {

    $results = elgg_get_entities_from_metadata(array(
        type => 'group'
    ));

    foreach($results as $result) {
        if($result->name == $name) {
            return $result;
        }
    }
    return false;
}

function create_i_wonder($question) {

    $question = urldecode($question);

    // if logged in you can ask a question
    $results = is_logged_in();
    $token = $results['token'];
    $user_guid = validate_user_token($token, null);

	if ($user_guid) {
        // find i wonder investigation
        // if does not exist create it
        $i_wonder_name = "I Wonder";
        $i_wonder_inv = get_entity_by_name($i_wonder_name);

        if($i_wonder_inv == false) {
            //create investigation
            $name = "I Wonder";
            $description = "";
            $brief_description = "";
            $tags = "";
            $proposal = "";
            $icon = "";
            $advisor_guid = 0;

            $ignore = elgg_set_ignore_access(true);
            $i_wonder_inv_guid = create_investigation($name, $description, $brief_description, $tags, $proposal, $icon, $advisor_guid);
            $i_wonder_inv_guid = $i_wonder_inv_guid['guid'];
            elgg_set_ignore_access($ignore);
        }
        else {
            $i_wonder_inv_guid = $i_wonder_inv->guid;
        }

        $name = substr($question, 0, 50) . '...';
        $description = $question;
        $brief_description = substr($question, 0, 50) . '...';
        $subtype = "investigationforumtopic_text";
        $tags = "";
        $container_guid = $i_wonder_inv_guid;

        // create discussion
        $ignore = elgg_set_ignore_access(true);
        $result = create_discussion($name, $description, $subtype, $container_guid);
        elgg_set_ignore_access($ignore);
	}
    else {
	    throw new SecurityException(elgg_echo('SecurityException:authenticationfailed'));
    }

    return array('great' => $result);
}

function get_latest_i_wonder_question() {

    $i_wonder_name = "I Wonder";
    $limit = 3;
    $i_wonder_inv = get_entity_by_name($i_wonder_name);

    if($i_wonder_inv != false) {
        $i_wonder_inv_guid = $i_wonder_inv->guid;

        return get_discs_by_inv_id($i_wonder_inv_guid, $limit);
    }
    else {
	    throw new Exception("No I Wonder Investigation");
    }

}

function delete_investigation($guid) {

    $entity = get_entity($guid);

    if (($entity) && ($entity instanceof ElggGroup)) {

        $logged_in_user = elgg_get_logged_in_user_entity();

        // allow deletion if you are and admin or this user
        if(!elgg_is_logged_in() || elgg_is_logged_in() && ($logged_in_user->guid != $entity->owner_guid && !elgg_is_admin_logged_in())) {
            throw new Exception('You need to be logged in either as an admin or as the investigation owner to delete this investigation.');
        }

        // delete group icons
        $owner_guid = $entity->owner_guid;
        $prefix = "groups/" . $entity->guid;
        $imagenames = array('.jpg', 'tiny.jpg', 'small.jpg', 'medium.jpg', 'large.jpg');
        $img = new ElggFile();
        $img->owner_guid = $owner_guid;
        foreach ($imagenames as $name) {
            $img->setFilename($prefix . $name);
            $img->delete();
        }

        // delete group
        $result = $entity->delete();
        if($result) {
            return true;
        } else {
            throw new Exception('Deletion was not successful');
        }
    } else {
        throw new Exception('Not a valid Investigation guid');
    }

}

function edit_investigation($guid, $name, $description, $brief_description, $advisor_guid = null, $tags = null) {

    $icon_formname = 'icon';
    $proposal_formname = 'proposal';

    $uploaded_icon = $_FILES[$icon_formname];
    $proposal = $_FILES[$proposal_formname];

    $inv = get_entity($guid);

    if (($inv) && ($inv instanceof ElggGroup)) {

        $logged_in_user = elgg_get_logged_in_user_entity();

        // allow deletion if you are and admin or this user
        if(!elgg_is_logged_in() || elgg_is_logged_in() && ($logged_in_user->guid != $inv->owner_guid && !elgg_is_admin_logged_in())) {
            throw new Exception('You need to be logged in either as an admin or as the investigation owner to delete this investigation.');
        }

        $ignore = elgg_set_ignore_access(true);

        $inv->name = urldecode($name);
        $inv->description = urldecode($description);
        $inv->briefdescription = urldecode($brief_description);
        $inv->interests = "";
        $inv->membership = ACCESS_PUBLIC;
        $inv->access_id = ACCESS_PUBLIC;
        $inv->subtype = 'investigation';

        $inv->save();

        // store the advisor guid as a relationship:
        if (isset($advisor_guid) && $advisor_guid != "") {
            $advisor_user = get_user($advisor_guid);
            remove_entity_relationships($inv->guid, 'advisor', true);
            add_entity_relationship($advisor_guid, 'advisor', $inv->guid);

            //if not a member add to investigation
            if(!$inv->isMember($advisor_user)) {
               investigations_join_investigation($inv, $advisor_user);
            }
        }
        else {
            remove_entity_relationships($inv->guid, 'advisor', true);
        }

        // proposal test
        if (!empty($proposal['type'])) {
            if (strpos($_FILES['proposal']['type'], 'pdf') === false) {
                throw new Exception('Proposals must be PDF format');
            } else {
                // remove any existing proposals before linking
                // this new one to our investigation:
                $existing = elgg_get_entities_from_relationship(array(
                    'relationship' => 'proposal',
                    'relationship_guid' => $inv->guid,
                    'inverse_relationship' => true
                ));
                foreach($existing as $old) {
                    $old->delete();
                }

                $fh = new ElggFile();
                $fh->owner_guid = $inv->owner_guid;
                $fh->name = 'proposal_' . $inv->guid . '.pdf';
                $fh->setFilename('groups/proposal_' . $inv->guid . '.pdf');
                $fh->set('file category', 'proposal');
                $fh->open('write');
                $fh->write(get_uploaded_file($proposal));
                $fh->close();
                $fh->save();

                remove_entity_relationships($inv->guid, 'proposal', true);
                add_entity_relationship($fh->getGUID(), 'proposal', $inv->guid);
            }
        }

        $has_uploaded_icon = (!empty($uploaded_icon['type']) && substr_count($uploaded_icon['type'], 'image/'));

        if ($has_uploaded_icon) {

            $icon_sizes = elgg_get_config('icon_sizes');

            $prefix = "groups/" . $inv->guid;

            $filehandler = new ElggFile();
            $filehandler->owner_guid = $inv->owner_guid;
            $filehandler->setFilename($prefix . ".jpg");
            $filehandler->open("write");
            $filehandler->write(get_uploaded_file($icon_formname));
            $filehandler->close();
            $filename = $filehandler->getFilenameOnFilestore();

            $sizes = array('tiny', 'small', 'medium', 'large');

            $thumbs = array();
            foreach ($sizes as $size) {
                $thumbs[$size] = get_resized_image_from_existing_file(
                    $filename,
                    $icon_sizes[$size]['w'],
                    $icon_sizes[$size]['h'],
                    $icon_sizes[$size]['square']
                );
            }

            if ($thumbs['tiny']) { // just checking if resize successful
                $thumb = new ElggFile();
                $thumb->owner_guid = $inv->owner_guid;
                $thumb->setMimeType('image/jpeg');

                foreach ($sizes as $size) {
                    $thumb->setFilename("{$prefix}{$size}.jpg");
                    $thumb->open("write");
                    $thumb->write($thumbs[$size]);
                    $thumb->close();
                }

                $inv->icontime = time();
            }
        }

        elgg_set_ignore_access($ignore);

        return array(
            'guid' => $inv->guid
        );

    }
    else {
        throw new Exception('Not a valid Investigation guid');
    }

}

function create_inv($name, $description, $brief_description, $advisor_guid, $tags) {

    $icon_formname = 'icon';
    $proposal_formname = 'proposal';

    $uploaded_icon = $_FILES[$icon_formname];
    $proposal = $_FILES[$proposal_formname];

    // Validate create
    if (!$name) {
        throw new Exception(elgg_echo("investigations:notitle"));
    }

    $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');

    // get current user
    $user = elgg_get_logged_in_user_entity();

    if ($user == null) {
        throw new Exception(elgg_echo("investigations:cantcreate"));
    }

    $group = new ElggGroup();

    $group->name = urldecode($name);
    $group->description = urldecode($description);
    $group->briefdescription = urldecode($brief_description);
    $group->interests = "";
    $group->membership = ACCESS_PUBLIC;
    $group->access_id = ACCESS_PUBLIC;
    $group->subtype = 'investigation';

    $group->save();

    // after it's been saved up above (in the mess), update the briefdescription
    // in order to restrict its length:
    /*
    $brief = $group->getMetaData('briefdescription');
    if ($brief && strlen($brief) > 255) {
        $group->setMetaData('briefdescription', substr($brief, 0, 255) . '...');
        // Note: this is messy in that it's leaving extra lines in the metastrings
        // table, but that table is kind of a disaster anyway so it's not worth it
        // to try and fix.
    }
     */

    // store the advisor guid as a relationship:
    if (isset($advisor_guid)) {
        $advisor_user = get_user($advisor_guid);
        remove_entity_relationships($group->guid, 'advisor', true);
        $ok = add_entity_relationship($advisor_guid, 'advisor', $group->guid);

        //if not a member add to investigation
        // if(!$group->isMember($advisor_user)) {
        //    investigations_join_investigation($group, $advisor_user);
        // }
    }

    // @todo this should not be necessary...
    elgg_set_page_owner_guid($group->guid);

    $group->join($user);
    add_to_river('river/investigation/create', 'create', $user->guid, $group->guid);

    // proposal test
    if (!empty($proposal['type'])) {
        if (strpos($proposal['type'], 'pdf') === false) {
            throw new Exception('Proposals must be PDF format');
        } else {
            // remove any existing proposals before linking
            // this new one to our investigation:
            $existing = elgg_get_entities_from_relationship(array(
                'relationship' => 'proposal',
                'relationship_guid' => $group->guid,
                'inverse_relationship' => true
            ));
            foreach($existing as $old) {
                $old->delete();
            }

            $fh = new ElggFile();
            $fh->owner_guid = $group->owner_guid;
            $fh->name = 'proposal_' . $group->guid . '.pdf';
            $fh->setFilename('groups/proposal_' . $group->guid . '.pdf');
            $fh->set('file category', 'proposal');
            $fh->open('write');
            $fh->write(get_uploaded_file($proposal_formname));
            $fh->close();
            $fh->save();

            remove_entity_relationships($group->guid, 'proposal', true);
            add_entity_relationship($fh->getGUID(), 'proposal', $group->guid);
        }
    }

    $has_uploaded_icon = (!empty($uploaded_icon['type']) && substr_count($uploaded_icon['type'], 'image/'));

    if ($has_uploaded_icon) {

        $icon_sizes = elgg_get_config('icon_sizes');

        $prefix = "groups/" . $group->guid;

        $filehandler = new ElggFile();
        $filehandler->owner_guid = $group->owner_guid;
        $filehandler->setFilename($prefix . ".jpg");
        $filehandler->open("write");
        $filehandler->write(get_uploaded_file($icon_formname));
        $filehandler->close();
        $filename = $filehandler->getFilenameOnFilestore();

        $sizes = array('tiny', 'small', 'medium', 'large');

        $thumbs = array();
        foreach ($sizes as $size) {
            $thumbs[$size] = get_resized_image_from_existing_file(
                $filename,
                $icon_sizes[$size]['w'],
                $icon_sizes[$size]['h'],
                $icon_sizes[$size]['square']
            );
        }

        if ($thumbs['tiny']) { // just checking if resize successful
            $thumb = new ElggFile();
            $thumb->owner_guid = $group->owner_guid;
            $thumb->setMimeType('image/jpeg');

            foreach ($sizes as $size) {
                $thumb->setFilename("{$prefix}{$size}.jpg");
                $thumb->open("write");
                $thumb->write($thumbs[$size]);
                $thumb->close();
            }

            $group->icontime = time();
        }
    }
    return array(
        'guid' => $group->guid,
        'ok' => $ok
    );

}

// todo get tags to work maybe we don't need them
function create_investigation($name, $description, $brief_description, $tags, $proposal, $icon, $advisor_guid) {

    // Validate create
    if (!$name) {
        throw new Exception(elgg_echo("investigations:notitle"));
    }

    $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');

    // get current user
    $user = elgg_get_logged_in_user_entity();

    if ($user == null) {
        throw new Exception(elgg_echo("investigations:cantcreate"));
    }

    $group = new ElggGroup();

    $group->name = urldecode($name);
    $group->description = urldecode($description);
    $group->briefdescription = urldecode($brief_description);
    $group->interests = "";
    $group->membership = ACCESS_PUBLIC;
    $group->access_id = ACCESS_PUBLIC;
    $group->subtype = 'investigation';

    $group->save();

    // after it's been saved up above (in the mess), update the briefdescription
    // in order to restrict its length:
    /*
    $brief = $group->getMetaData('briefdescription');
    if ($brief && strlen($brief) > 255) {
        $group->setMetaData('briefdescription', substr($brief, 0, 255) . '...');
        // Note: this is messy in that it's leaving extra lines in the metastrings
        // table, but that table is kind of a disaster anyway so it's not worth it
        // to try and fix.
    }
     */

    // store the advisor guid as a relationship:
    if ($advisor_guid) {
        $advisor_user = get_user($advisor_guid);
        remove_entity_relationships($group->guid, 'advisor', true);
        add_entity_relationship($advisor_guid, 'advisor', $group->guid);

        //if not a member add to investigation
        if(!$group->isMember($advisor_user)) {
           investigations_join_investigation($group, $advisor_user);
        }
    }

    // @todo this should not be necessary...
    elgg_set_page_owner_guid($group->guid);

    $group->join($user);
    add_to_river('river/investigation/create', 'create', $user->guid, $group->guid);

    // proposal test
    if (!empty($_FILES['proposal']['type'])) {
        if (strpos($_FILES['proposal']['type'], 'pdf') === false) {
            throw new Exception('Proposals must be PDF format');
        } else {
            // remove any existing proposals before linking
            // this new one to our investigation:
            $existing = elgg_get_entities_from_relationship(array(
                'relationship' => 'proposal',
                'relationship_guid' => $group->guid,
                'inverse_relationship' => true
            ));
            foreach($existing as $old) {
                $old->delete();
            }

            $fh = new ElggFile();
            $fh->owner_guid = $group->owner_guid;
            $fh->name = 'proposal_' . $group->guid . '.pdf';
            $fh->setFilename('groups/proposal_' . $group->guid . '.pdf');
            $fh->set('file category', 'proposal');
            $fh->open('write');
            $fh->write(get_uploaded_file('proposal'));
            $fh->close();
            $fh->save();

            remove_entity_relationships($group->guid, 'proposal', true);
            add_entity_relationship($fh->getGUID(), 'proposal', $group->guid);
        }
    }

    $has_uploaded_icon = (!empty($_FILES['icon']['type']) && substr_count($_FILES['icon']['type'], 'image/'));

    if ($has_uploaded_icon) {

        $icon_sizes = elgg_get_config('icon_sizes');

        $prefix = "groups/" . $group->guid;

        $filehandler = new ElggFile();
        $filehandler->owner_guid = $group->owner_guid;
        $filehandler->setFilename($prefix . ".jpg");
        $filehandler->open("write");
        $filehandler->write(get_uploaded_file('icon'));
        $filehandler->close();
        $filename = $filehandler->getFilenameOnFilestore();

        $sizes = array('tiny', 'small', 'medium', 'large');

        $thumbs = array();
        foreach ($sizes as $size) {
            $thumbs[$size] = get_resized_image_from_existing_file(
                $filename,
                $icon_sizes[$size]['w'],
                $icon_sizes[$size]['h'],
                $icon_sizes[$size]['square']
            );
        }

        if ($thumbs['tiny']) { // just checking if resize successful
            $thumb = new ElggFile();
            $thumb->owner_guid = $group->owner_guid;
            $thumb->setMimeType('image/jpeg');

            foreach ($sizes as $size) {
                $thumb->setFilename("{$prefix}{$size}.jpg");
                $thumb->open("write");
                $thumb->write($thumbs[$size]);
                $thumb->close();
            }

            $group->icontime = time();
        }
    }
    return array(
        'guid' => $group->guid
    );

}

function comment_on($id, $type, $comment) {

    $comment = urldecode($comment);

    // is user logged in?
    if(elgg_is_logged_in()) {

        $user_guid = elgg_get_logged_in_user_guid();

        //use annotate
        $results = elgg_get_entities(array(
            'guid' => array($id)
        ));

        $ignore = elgg_set_ignore_access(true);
        $reply_id = $results[0]->annotate($type, $comment, -1);

        $result = add_to_river('river/annotation/group_topic_post/reply', 'reply', $user_guid, $id, "", 0, $reply_id);
        //$result = add_to_river('river/object/groupforumtopic/create', 'reply', $user_guid, $id, '', 0, $reply_id);
	    //add_to_river('river/annotation/group_topic_post/reply', 'reply', $user->guid, $topic->guid, "", 0, $reply_id);
        elgg_set_ignore_access($ignore);

        return $result;
    }
    else {
        throw new Exception("Please login to write to the messageboard");
    }
}

function delete_comment($id){

  $comment = elgg_get_annotation_from_id($id);

  $logged_in_user = elgg_get_logged_in_user_entity();

  // allow deletion if you are and admin or this user
  if(!elgg_is_logged_in() || elgg_is_logged_in() && ($logged_in_user->guid != $comment->owner_guid && !elgg_is_admin_logged_in())) {
      throw new Exception('You need to be logged in either as an admin or as the comment owner to delete this comment.');
  }
  else{
    $comment->delete();
    return true;
  }

}

function delete_discussion($id){

  $discussion = get_entity($id);

  $logged_in_user = elgg_get_logged_in_user_entity();

  // allow deletion if you are and admin or this user
  if(!elgg_is_logged_in() || elgg_is_logged_in() && ($logged_in_user->guid != $comment->owner_guid && !elgg_is_admin_logged_in())) {
      throw new Exception('You need to be logged in either as an admin or as the discussion owner to delete this discussion.');
  }
  else{
    $discussion->delete();
    return true;
  }

}

function get_comments($id, $type, $limit, $offset) {

    $object = array();
    $comments = array();
    $user_guid = elgg_get_logged_in_user_guid();


    // get object
    $ignore = elgg_set_ignore_access();
    $results = elgg_get_entities(array(
        'guid' => array($id)
    ));
    elgg_set_ignore_access($ignore);

    $result = $results[0];
    $discussion_likes = $result->getAnnotations('likes');
    $i_liked = false;
    $started_user = get_user($result->owner_guid);

    $file_thumbnail = elgg_get_entities_from_relationship(array(
        'relationship' => 'thumbnail_file',
        'relationship_guid' => $id,
        'inverse_relationship' => true
    ));

    if($file_thumbnail) {
        $thumbnail_filepath = elgg_get_site_url().'file/download/'.$file_thumbnail[0]->guid.'/'.$file_thumbnail[0]->getFilename();
    }
    else {
        $thumbnail_filepath = "";
    }

    // large file
    $file_large = elgg_get_entities_from_relationship(array(
        'relationship' => 'large_file',
        'relationship_guid' => $id,
        'inverse_relationship' => true
    ));

    if($file_large) {
        $large_filepath = elgg_get_site_url().'file/download/'.$file_large[0]->guid.'/'.$file_large[0]->getFilename();
    }
    else {
        $large_filepath = "";
    }

    foreach($discussion_likes as $discussion_like) {
        if($discussion_like->owner_guid == $user_guid) {
            $i_liked = true;
        }
    }

    $ignore = elgg_set_ignore_access(true);
    $elgg_comments = $results[0]->getAnnotations($type, $limit, $offset, 'desc');
    $comment_count = count($results[0]->getAnnotations($type));
    elgg_set_ignore_access($ignore);

    foreach($elgg_comments as $elgg_comment) {

        $user = get_user($elgg_comment->owner_guid);

        $comments[] = array(
            'id' => $elgg_comment->id,
            'description' => $elgg_comment->value,
            'like_count' => 0,
            'date' => elgg_get_friendly_time($elgg_comment->time_created),
            'user' => array(
                'id' => $user->guid,
                'displayname' => $user->name,
                'username' => $user->username,
                'image' => $user->getIcon('small')
            )
        );
    }

    $object = array(
        'id' => $result->guid,
        'type' => $result->getSubtype(),
        'name' => $result->title,
        'thumbnail_filepath' => $thumbnail_filepath,
        'large_filepath' => $large_filepath,
        'date' => elgg_get_friendly_time($result->time_created),
        'description' => $result->description,
        'parent_guid' => $result->container_guid,
        'comments' => $comments,
        'comment_count' => $comment_count,
        'like_count' => count($discussion_likes),
        'i_liked' => $i_liked,
        'video' => $result->video,
        'username' => $started_user->username,
        'displayname' => $started_user->name
    );

    return $object;
}

function get_inv_by_id($id) {
    $discussion_subtype = array('investigationforumtopic_map', 'investigationforumtopic_graph', 'investigationforumtopic_image', 'investigationforumtopic_video', 'investigationforumtopic_text', 'investigationforumtopic');
    $discussion_return_result = array();

    $result = elgg_get_entities(array(
        'type_subtype_pair'	=>	array('group' => 'investigation'),
        'guid' => array($id)
    ));
    $result = $result[0];

    $inv_likes = $result->getAnnotations('likes');
    $user = get_user($result->owner_guid);
    $i_liked = false;
    $user_guid = elgg_get_logged_in_user_guid();

    foreach($inv_likes as $like) {
        if($like->owner_guid == $user_guid) {
            $i_liked = true;
        }
    }

    $coordinator = get_user($result->owner_guid);
    $e = $result->getEntitiesFromRelationship('advisor', true);

    $inv = array(
        "id" => $result->guid,
        "name" => $result->name,
        "like_count" => count($inv_likes),
        "user" => array(
            "displayname" => $user->name,
            "username" => $user->username,
            "tiny_icon" => $user->getIcon('tiny'),
            "small_icon" => $user->getIcon('small'),
            "medium_icon" => $user->getIcon('medium'),
            "large_icon" => $user->getIcon('large')
        ),
        "age" => elgg_get_friendly_time($result->time_created),
        "i_liked" => $i_liked,
        "coordinator" => array(
            "username" => $coordinator->get("username"),
            "displayname" => $coordinator->get("name"),
            "image" => $coordinator->getIcon("medium")
        ),
        "advisor" => array(
            "username" => $e ? $e[0]->get("username") : "",
            "displayname" => $e ? $e[0]->get("name") : "",
            "image" => $e ? $e[0]->getIcon("medium") : "",
            "id" => $e ? $e[0]->guid : ""
        ),
        "image" => $result->getIcon("large"),
        "description" => $result->description,
        "briefDescription" => $result->briefdescription,
        "discussions" => get_discs_by_inv_id($result->guid, null, $discussion_subtype)
    );

    return $inv;

}

function get_invs_by_token($token) {

    $user_guid = validate_user_token($token, null);
    $user = get_user($user_guid);

    create_agg_user($user);

    login($user, false);

    $dbprefix = elgg_get_config('dbprefix');

	$results = elgg_get_entities_from_relationship(array(
        'type_subtype_pair'	=>	array('group' => 'investigation'),
        'limit' => 0,
        /*
		'relationship' => 'member',
		'relationship_guid' => $user_guid,
		'inverse_relationship' => false,
		'full_view' => false,
        */
		'joins' => array("JOIN {$dbprefix}groups_entity ge ON e.guid = ge.guid"),
		'order_by' => 'ge.name asc'
	));

    // build out our list of investigation names/ids
    $investigations = array(
        'user_guid' => intval($user_guid),
        'username' => $user->name,
        'user_display_name' => $user->name,
        'token' => $token,
        'investigations' => array()
    );
    foreach($results as $result) {
        $investigations['investigations'][] = array(
            'name' => $result->name,
            'guid' => $result->guid
        );
    }
    return $investigations;
}

// create observation
function create_obs($inv_guid, $token, $agg_id, $with_users) {
    // are you logged in?

    $user_guid = validate_user_token($token, null);
    if($user_guid) {

        $user = get_user($user_guid);
        $investigation = get_entity($inv_guid);

        // check if user is part of this investigation
        //if($investigation->isMember($user)) {

            $profile_type_guid = $user->custom_profile_type;
            $profile_type = get_entity($profile_type_guid);

            $observation = new ElggObject();

            $observation->subtype = "observation";
            $observation->access_id = 2;

            //this is needed to set the owner_guid
            $ignore = elgg_set_ignore_access(true);
            $observation->owner_guid = $user_guid;

            $observation->user_type = $profile_type->getTitle();
            $observation->agg_id = $agg_id;

            $observation->save();

            if($with_users){

              // Set the user relationships
              $with_users = explode(',', $with_users);

              foreach($with_users as $key => $value){
                add_entity_relationship($observation->guid, 'with_users', (int)$value);
              }

            }

            elgg_set_ignore_access($ignore);

            // I can only add metadata after the initial save of a new object
            $observation->parent_guid = $inv_guid;
            $observation->save();

            // post notification to the river
            add_to_river('river/object/investigation/create', 'create', $user_guid, $observation->guid);

            return $observation->guid;
        //}
        //not part of this investigation
        /*
        else {
            // not a member of this investigation
            throw new Exception('User not a member of this investigation.');
        }
         */
    }
    else {
        // not a valid token
	    throw new SecurityException(elgg_echo('SecurityException:authenticationfailed'));
    }
}

function assoc_obs_with_users($agg_id, $with_users){

  $results = elgg_get_entities_from_metadata(array(
      "type_subtype_pair"	=>	array('object' => 'observation'),
      "metadata_name_value_pairs" => array('agg_id' => $agg_id)
  ));

  if($results){
    // Set the user relationships
    $with_users = explode(',', $with_users);

    foreach($with_users as $key => $value){
      add_entity_relationship($results[0]->guid, 'with_users', (int)$value);
    }

    return $results[0]->guid;
  }
  else{
    throw new Exception('Could not find this observation');
  }


}

// function create_obs_2($inv_guid, $token, $agg_id, $collaborators) {
//     // are you logged in?
//
//     $collaborators = explode(',', $collaborators);
//
//     $user_guid = validate_user_token($token, null);
//     if($user_guid) {
//         $user = get_user($user_guid);
//         $investigation = get_entity($inv_guid);
//
//         // check if user is part of this investigation
//         if($investigation->isMember($user)) {
//
//             $profile_type_guid = $user->custom_profile_type;
//             $profile_type = get_entity($profile_type_guid);
//
//             $observation = new ElggObject();
//
//             $observation->subtype = "observation";
//             $observation->access_id = 2;
//
//             //this is needed to set the owner_guid
//             $ignore = elgg_set_ignore_access(true);
//             $observation->owner_guid = $user_guid;
//
//             $observation->user_type = $profile_type->getTitle();
//             $observation->agg_id = $agg_id;
//             $observation->save();
//
//             elgg_set_ignore_access($ignore);
//
//             // I can only add metadata after the initial save of a new object
//             $observation->parent_guid = $inv_guid;
//             $observation->save();
//
//             // attached collaborators to it
//             foreach($collaborators as $collaborator) {
//                 add_entity_relationship($observation->guid, 'collaborator', $collaborator);
//             }
//
//             // post notification to the river
//             add_to_river('river/object/investigation/create', 'create', $user_guid, $observation->guid);
//
//             return $observation->guid;
//         }
//         //not part of this investigation
//         else {
//             // not a member of this investigation
//             throw new Exception('User not a member of this investigation.');
//         }
//     }
//     else {
//         // not a valid token
// 	    throw new SecurityException(elgg_echo('SecurityException:authenticationfailed'));
//     }
// }

function get_obs($offset, $limit) {
    $results = elgg_get_entities(array(
        'type_subtype_pair'	=>	array('object' => 'observation'),
        'limit' => $limit,
        'offset' => $offset
    ));

    $obs = array();
    foreach($results AS $result) {
        $user = get_entity($result->owner_guid);
        $obs[] = array(
            "name" => $user->name,
            "obs_guid" => $result->guid,
            "agg_id" => $result->agg_id,
            "time_created" => $result->time_created
        );
    }

    return $obs;

}

function get_obs_paged_from_elgg($offset, $limit) {

    $limit = $_GET["limit"] ? $_GET["limit"] : "ALL";
    $offset = $_GET["offset"] ? $_GET["offset"] : "0";

    $elgg_obs = elgg_get_entities_from_metadata(array(
        "metadata_name" => "agg_id",
        "order_by" => "time_created desc",
        "limit" => $limit,
        "offset" => $offset
    ));

    $final = array();

    // return empty array if no results
    if(!$elgg_obs){
      return $final;
    }

    $hostname = 'ec2-54-225-138-16.compute-1.amazonaws.com';
    $port = '5972';
    $dbName = 'd2br84lqj1ij30';
    $dbUser = 'u3l9t7fso9lsat';
    $dbPass = 'p7oqdm3h5jtre5180hhjsomls6f';

    try{
        $dbObject = new PDO('pgsql:host='.$hostname.";port=".$port.";dbname=".$dbName, $dbUser, $dbPass);
        $dbObject->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e){
        throw new Exception('Connection failed... '.$e->getMessage());
    }

    $app_env = getenv("APP_ENV");
    // $app_env = $app_env == "prod" ? $app_env : "unstable";
    $app_env = "prod";
    $server_env = "prod";

    $elgg_agg_ids = "";

    foreach($elgg_obs as $key => $elgg_obj){

      if($elgg_obj->agg_id == ''){
        $elgg_obs = array_splice($elgg_obs, $key, 1);
      }

      $elgg_agg_ids .= "'".$elgg_obj->agg_id."', ";
    }

    $query_ids = substr($elgg_agg_ids, 0, -2);

      $query = "SELECT obs.id AS observation_id, uri as user, categories_json as categories, timestamp, (
                      SELECT array_to_json(array_agg(row_to_json(results))) FROM (
                          SELECT meta_elt as image
                          FROM public.".$server_env."_measurement
                          JOIN public.".$server_env."_measurement_meta
          ON public.".$server_env."_measurement_meta.meta = public.".$server_env."_measurement.id
                          WHERE observation_id = obs.id AND phenomenon_id != 'video' AND meta_idx = 'url'
                      ) results) AS media
                      FROM public.".$server_env."_observation obs
                      LEFT JOIN public.".$server_env."_observer
                          ON public.".$server_env."_observer.id = obs.observer_id
                      WHERE obs.id IN (".$query_ids.")";

      $prepared = $dbObject->prepare($query);

      if(!$prepared){
        print "<p>DATABASE CONNECTION ERROR:</p>";
        print_r($dbObject->errorInfo());
        die;
      }

      $prepared->execute();

      $results = $prepared->fetchAll(PDO::FETCH_ASSOC);

    foreach($elgg_obs as $key => $elgg_obj){

      foreach($results as $result){

        if($result["observation_id"] == $elgg_obj->agg_id){
          $agg_results = $result;
        }
      }
      $final[$key] = array();

      // pull the user id from the aggregators annoying format :/
      $user_id = $agg_results['user'];
      $user_id = explode('/', $user_id);
      $user_id = $user_id[count($user_id) - 1];

      // fix on production
      // $user_id = 49;

      $user = get_user($user_id);
      $categories = json_decode($agg_results['categories']);

      $ignore = elgg_set_ignore_access(true);
      $likes = $elgg_obj->getAnnotations('likes');
      $i_liked = false;
      $user_guid = elgg_get_logged_in_user_guid();

      foreach($likes as $like) {
          if($like->owner_guid == $user_guid) {
              $i_liked = true;
          }
      }
      $like_count = count($elgg_obj->getAnnotations('likes')) + count($elgg_obj->getAnnotations('observation_likes'));
      elgg_set_ignore_access($ignore);

      $final[$key]['observation_id'] = $elgg_obj->agg_id;

      if($user) {
          $final[$key]['user'] = array(
              displayname => $user->name,
              username => $user->username,
              tiny_icon => $user->getIcon('tiny'),
              small_icon => $user->getIcon('small'),
              medium_icon => $user->getIcon('medium'),
              large_icon => $user->getIcon('large')
          );
      }
      else {
          $final[$key]['user'] = $user_id;
      }

      $with_users = array();

      $with_users_relationship = elgg_get_entities_from_relationship(array(
          'types'	=>	'user',
          'relationship' => 'with_users',
          'relationship_guid' => $elgg_obj->guid,
          'inverse_relationship' => false,
          'full_view' => false
      ));

      foreach($with_users_relationship as $user){
        $with_users[] = array(
          displayname => $user->name,
          username => $user->username,
          tiny_icon => $user->getIcon('tiny'),
          small_icon => $user->getIcon('small'),
          medium_icon => $user->getIcon('medium'),
          large_icon => $user->getIcon('large')
        );
      }

      $final[$key]['with_users'] = $with_users;

      $final[$key]['categories'] = $categories;

      $final[$key]['timestamp'] = date('F jS, Y', $elgg_obj->time_created);

      $media = json_decode($agg_results['media']);

      if($media[0]->image) {
          $media_array = explode('.', $media[0]->image);
          $media = array_slice($media_array, 0, count($media_array) - 1);
          $media = join('.', $media);
          $media .= '-thumb.'.$media_array[count($media_array) - 1];
      }
      else {
          $media = "";
      }

      $final[$key]['media'] = $media;

      $final[$key]['id'] = $elgg_obj->guid;
      $final[$key]['like_count'] = $like_count;
      $final[$key]['i_liked'] = $i_liked;
      $final[$key]['comment_count'] = count(get_comments_on_obs($elgg_obj->guid));

    }

    $dbObject = null;

    return $final;
    // return array($results);

}


function get_obs_paged($offset, $limit) {

    $limit = $_GET["limit"] ? $_GET["limit"] : "ALL";
    $offset = $_GET["offset"] ? $_GET["offset"] : "0";

    $hostname = 'ec2-54-225-138-16.compute-1.amazonaws.com';
    $port = '5972';
    $dbName = 'd2br84lqj1ij30';
    $dbUser = 'u3l9t7fso9lsat';
    $dbPass = 'p7oqdm3h5jtre5180hhjsomls6f';

    try{
        $dbObject = new PDO('pgsql:host='.$hostname.";port=".$port.";dbname=".$dbName, $dbUser, $dbPass);
        $dbObject->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e){
        throw new Exception('Connection failed... '.$e->getMessage());
    }

    $app_env = getenv("APP_ENV");
    // $app_env = $app_env == "prod" ? $app_env : "unstable";
    $app_env = "prod";
    $server_env = "prod";

    $query = "SELECT obs.id AS observation_id, uri as user, categories_json as categories, timestamp, (
                    SELECT array_to_json(array_agg(row_to_json(results))) FROM (
                        SELECT meta_elt as image
                        FROM public.".$server_env."_measurement
                        JOIN public.".$server_env."_measurement_meta
				ON public.".$server_env."_measurement_meta.meta = public.".$server_env."_measurement.id
                        WHERE observation_id = obs.id AND phenomenon_id != 'video' AND meta_idx = 'url'
                    ) results) AS media
                    FROM public.".$server_env."_observation obs
                    LEFT JOIN public.".$server_env."_observer
                        ON public.".$server_env."_observer.id = obs.observer_id
                    ORDER BY timestamp DESC
                    LIMIT ".$limit."
                    OFFSET ".$offset;

    $prepared = $dbObject->prepare($query);

    if(!$prepared){
      print "<p>DATABASE CONNECTION ERROR:</p>";
      print_r($dbObject->errorInfo());
      die;
    }

    $prepared->execute();

    $results = $prepared->fetchAll(PDO::FETCH_ASSOC);

    $dbObject = null;

    foreach($results as $row => $result) {

        $elgg_obs = elgg_get_entities_from_metadata(array(
            "metadata_name_value_pairs" => array('agg_id' => $result['observation_id'])
        ));

        // take first result
        $elgg_obs = $elgg_obs[0];
        if($elgg_obs) {
            $results[$row]['id'] = $elgg_obs->guid;

            // pull the user id from the aggregators annoying format :/
            $user_id = $result['user'];
            $user_id = explode('/', $user_id);
            $user_id = $user_id[count($user_id) - 1];

            // fix on production
            //$user_id = 49;

            $user = get_user($user_id);
            $categories = json_decode($result['categories']);

            $ignore = elgg_set_ignore_access(true);
            $likes = $elgg_obs->getAnnotations('likes');
            $i_liked = false;
            $user_guid = elgg_get_logged_in_user_guid();

            foreach($likes as $like) {
                if($like->owner_guid == $user_guid) {
                    $i_liked = true;
                }
            }
            $like_count = count($elgg_obs->getAnnotations('likes')) + count($elgg_obs->getAnnotations('observation_likes'));
            elgg_set_ignore_access($ignore);

            if($user) {
                $results[$row]['user'] = array(
                    displayname => $user->name,
                    username => $user->username,
                    tiny_icon => $user->getIcon('tiny'),
                    small_icon => $user->getIcon('small'),
                    medium_icon => $user->getIcon('medium'),
                    large_icon => $user->getIcon('large')
                );
            }
            else {
                $results[$row]['user'] = $user_id;
            }

            $results[$row]['categories'] = $categories;

            $media = json_decode($result['media']);

            if($media[0]->image) {
                $media_array = explode('.', $media[0]->image);
                $media = array_slice($media_array, 0, count($media_array) - 1);
                $media = join('.', $media);
                $media .= '-thumb.'.$media_array[count($media_array) - 1];
            }
            else {
                $media = "";
            }

            $results[$row]['media'] = $media;

            $results[$row]['timestamp'] = date('F jS, Y', strtotime($result['timestamp']));
            $results[$row]['like_count'] = $like_count;
            $results[$row]['i_liked'] = $i_liked;
            $results[$row]['comment_count'] = count(get_comments_on_obs($elgg_obs->guid));
        }
        else {
            unset($results[$row]);
        }
    }

    return $results;

}

function get_obs_by_inv($investigation_guid) {
    // are you logged in?
    // passing in null as 2nd param means we will use the default timeout 60mins unless core is modified
    $investigation = get_entity($investigation_guid);

    $results = elgg_get_entities(array(
        'type_subtype_pair'	=>	array('object' => 'observation'),
        'parent_guid' => $investigation_guid
    ));

    $observations = array();

    foreach($results as $result) {

        // get username and link
        $user = get_entity($result->owner_guid);
        $likes = get_likes($result->guid, 0);

        $observations[] = array(
            "guid" => $result->guid,
            "investigation_name" => $investigation->name,
            "users_display_name" => $user->name,
            "all_likes" => $likes['all_likes'],
            "time_created" => $result->time_created
        );
    }

    return $observations;
}


function get_obs_by_username($username, $limit) {
    $user = get_user_by_username($username);
    $observations = array();

    $results = elgg_get_entities(array(
        "type_subtype_pair"	=>	array('object' => 'observation'),
        "owner_guids" => array($user->guid),
        "limit" => $limit
        //"metadata_name_value_pairs" => array('agg_id' => $agg_id)
    ));

    $secondary_results = elgg_get_entities_from_relationship(array(
      "type_subtype_pair"	=>	array('object' => 'observation'),
      'relationship' => 'with_users',
      "relationship_guid" => $user->guid,
      'inverse_relationship' => true,
      'full_view' => false,
      "limit" => $limit
    ));

    foreach($secondary_results as $item){
      $results[] = $item;
    }

    function sortObs($a, $b){
      return $b->time_created - $a->time_created;
    }

    usort($results, 'sortObs');

    foreach($results as $key => $observation) {
      if($key < $limit){
      		$inv_guid = $observation->parent_guid;
      		$inv = get_entity($inv_guid);

          $obs_users = elgg_get_entities_from_relationship(array(
            'types'	=>	'user',
            'relationship' => 'with_users',
            'relationship_guid' => $observation->guid,
            'inverse_relationship' => false,
            'full_view' => false
          ));

          $with_users = array();
          // If current user is not owner of observation, push owner in as an associated user
          if($obs_users){

            if((int)$user->guid !== (int)$observation->owner_guid){

              $owner = get_user($observation->owner_guid);

              $with_users[] = array(
                displayname => $owner->name,
                username => $owner->username,
                tiny_icon => $owner->getIcon('tiny'),
                small_icon => $owner->getIcon('small'),
                medium_icon => $owner->getIcon('medium'),
                large_icon => $owner->getIcon('large')
              );
            }

            foreach($obs_users as $person){
              if($person->username !== $username){

                $with_users[] = array(
                  displayname => $person->name,
                  username => $person->username,
                  tiny_icon => $person->getIcon('tiny'),
                  small_icon => $person->getIcon('small'),
                  medium_icon => $person->getIcon('medium'),
                  large_icon => $person->getIcon('large')
                );
              }
            }
          }

          $observations[] = array(
              id => $observation->guid,
              agg_id => $observation->agg_id,
              investigation => $inv->name,
              date => elgg_get_friendly_time($observation->time_created),
              inv_id => $inv->guid,
              with_users => $with_users
          );
      }
    }

    return $observations;

}

function get_obs_by_user_type($user_type, $min_date, $max_date) {

    $results = elgg_get_entities_from_metadata(array(
        'user_type' => $user_type,
        'created_time_lower' => $min_date,
        'created_time_upper' => $max_date
    ));

    $return = array();

    foreach($results AS $result) {
        $temp = get_metadata_byname($result->guid, 'agg_id');
        $user = get_entity($result->owner_guid);
        if($temp->value != NULL) {
            $return[] = array(
                "agg_ids" => $temp->value,
                "user_display_name" => $user->name,
                "username" => $user->username,
                "user_guid" => $user->guid
            );
        }
    }
    return $return;
}

// returns true if like, false if unliked
function toggle_like_obs($observation_guid) {
    // are you logged in?
    // passing in null as 2nd param means we will use the default timeout 60mins unless core is modified
    $results = is_logged_in();
    $token = $results['token'];
    $user_guid = validate_user_token($token, null);
    if($user_guid) {

        $obs = get_entity($observation_guid);
        $results = $obs->getAnnotations("observation_like");
        $my_like = 0;

        foreach($results as $result) {
            if($result->owner_guid == $user_guid) {
                $my_like = $result;
            }
        }

        // like this observation
        if(!$my_like) {

            // need to ignore access to set owner_id
            $ignore = elgg_set_ignore_access(true);
            $id = $obs->annotate('observation_like', 1, 2, $user_guid, 'integer');
            $obs->save();
            elgg_set_ignore_access($ignore);
            return 1;
        }
        // unlike this observation
        else {
            elgg_delete_annotation_by_id($my_like->id);
            return 0;
        }
    }
    else {
        // not a valid login
	    throw new SecurityException(elgg_echo('SecurityException:authenticationfailed'));
    }
}

function toggle_like_entity($entity_guid) {

    if(is_logged_in()) {

        $ignore = elgg_set_ignore_access(true);
        $entity = get_entity($entity_guid);
        $i_liked = true;
        $user_guid = elgg_get_logged_in_user_guid();

        $likes = elgg_get_annotations(array(
            'guid' => $entity_guid,
            'annotation_owner_guid' => $user_guid,
            'annotation_name' => 'likes'
        ));

        // remove like
        if($likes) {
            foreach($likes as $like) {
                $like->delete();
            }
            $i_liked = false;
        }
        else {
            $id = $entity->annotate('likes', 1, 2, $user_guid, 'integer');
        }

        $like_count = count($entity->getAnnotations('likes')) + count($entity->getAnnotations('observation_likes'));
        elgg_set_ignore_access($ignore);

        return array(
            'i_liked' => $i_liked,
            'like_count' => $like_count
        );

    }
    else {
        // not a valid login
	    throw new SecurityException(elgg_echo('SecurityException:authenticationfailed'));
    }
}

function toggle_like_obs_by_agg_id($agg_id) {

    $results = elgg_get_entities_from_metadata(array(
        "type_subtype_pair"	=>	array('object' => 'observation'),
        "metadata_name_value_pairs" => array('agg_id' => $agg_id)
    ));

    if($results) {
        return toggle_like_obs($results[0]->guid);
    }
    else {
        return 0;
    }
}

function get_likes($observation_guid) {

    $obs = get_entity($observation_guid);
    $all_likes = $obs->getAnnotations("observation_like");

    return array(
        "all_likes" => count($all_likes)
    );

}

function get_likes_by_agg_id($agg_id) {

    $results = elgg_get_entities_from_metadata(array(
        "type_subtype_pair"	=>	array('object' => 'observation'),
        "metadata_name_value_pairs" => array('agg_id' => $agg_id)
    ));

    if($results) {
        return get_likes($results[0]->guid);
    }
    else {
        return 0;
    }
}

function get_my_obs_like($observation_guid) {

    $user_guid = elgg_get_logged_in_user_guid();

    if($user_guid) {

        $obs = get_entity($observation_guid);
        $likes = $obs->getAnnotations("observation_like");
        $my_like = 0;

        foreach($likes as $like) {
            if($like->owner_guid == $user_guid) {
                $my_like = 1;
            }
        }

        return $my_like;

    }
    else {
        throw new Exception('User not logged in');
    }

}

function get_my_obs_like_by_agg_id($agg_id) {

    $results = elgg_get_entities_from_metadata(array(
        "type_subtype_pair"	=>	array('object' => 'observation'),
        "metadata_name_value_pairs" => array('agg_id' => $agg_id)
    ));

    if($results) {
        return get_my_obs_like($results[0]->guid);
    }
    else {
        return 0;
    }
}
function get_comments_on_obs($observation_guid) {

        $ignore = elgg_set_ignore_access(true);
        $obs = get_entity($observation_guid);
        $comments = $obs->getAnnotations("observation_comments");

        $results = array();

        foreach($comments as $comment) {
            $results[] = array(
                "id" => $comment->id,
                "time_created" => $comment->time_created,
                "user_guid" => $comment->owner_guid,
                "value" => $comment->value
            );
        }

        elgg_set_ignore_access($ignore);
        return $results;
}

function get_obs_elgg_data_by_agg_id($agg_id) {

    $results = elgg_get_entities_from_metadata(array(
        "type_subtype_pair"	=>	array('object' => 'observation'),
        "metadata_name_value_pairs" => array('agg_id' => $agg_id)
    ));

    if($results) {

        // Get the like_count and i_liked
        $ignore = elgg_set_ignore_access(true);
        $likes = $results[0]->getAnnotations('likes');
        $i_liked = false;
        $user_guid = elgg_get_logged_in_user_guid();

        foreach($likes as $like) {
            if($like->owner_guid == $user_guid) {
                $i_liked = true;
            }
        }

        $like_count = count($results[0]->getAnnotations('likes')) + count($results[0]->getAnnotations('observation_likes'));

        $with_users = array();

        $with_users_relationship = elgg_get_entities_from_relationship(array(
            'types'	=>	'user',
            'relationship' => 'with_users',
            'relationship_guid' => $results[0]->guid,
            'inverse_relationship' => false,
            'full_view' => false
        ));

        foreach($with_users_relationship as $user){
          $with_users[] = array(
            displayname => $user->name,
            username => $user->username,
            tiny_icon => $user->getIcon('tiny'),
            small_icon => $user->getIcon('small'),
            medium_icon => $user->getIcon('medium'),
            large_icon => $user->getIcon('large')
          );
        }

        $comments =  get_comments_on_obs($results[0]->guid);

        foreach($comments as $key => $val){
          $user_info = get_user_info($val["user_guid"], "tiny");
          $val["username"] = $user_info["username"];
          $val["display_name"] = $user_info["users_display_name"];
          $val["icon"] = $user_info["image"];
          $val["time"] = elgg_get_friendly_time($val["time_created"]);
          $finalComments[] = $val;
        }
        elgg_set_ignore_access($ignore);

        return array("guid" => $results[0]->guid, "comments" => $finalComments, "like_count" => $like_count, "i_liked" => $i_liked, "with_users" => $with_users);
    }
    else {
        return 0;
    }
}

function comment_on_obs($observation_guid, $comment, $token) {
    // are you logged in?
    // passing in null as 2nd param means we will use the default timeout 60mins unless core is modified
    $user_guid = validate_user_token($token, null);
    if($user_guid) {

        // need to ignore access to set owner_id
        $ignore = elgg_set_ignore_access(true);
        $observation = get_entity($observation_guid);
        $id = $observation->annotate('observation_comments', $comment, 2, $user_guid, 'text');
        $observation->save();
        elgg_set_ignore_access($ignore);

        return $id;

    }
    else {
        // not a valid login
	    throw new SecurityException(elgg_echo('SecurityException:authenticationfailed'));
    }

}

function comment_on_obs_by_agg_id($agg_id, $comment, $token) {
    // are you logged in?
    // passing in null as 2nd param means we will use the default timeout 60mins unless core is modified

    $results = elgg_get_entities_from_metadata(array(
        "type_subtype_pair"	=>	array('object' => 'observation'),
        "metadata_name_value_pairs" => array('agg_id' => $agg_id)
    ));

    $user_guid = validate_user_token($token, null);
    if($user_guid) {

        // need to ignore access to set owner_id
        $ignore = elgg_set_ignore_access(true);
        $observation = get_entity($results[0]->guid);
        $id = $observation->annotate('observation_comments', $comment, 2, $user_guid, 'text');
        $observation->save();
        elgg_set_ignore_access($ignore);

        return $id;

    }
    else {
        // not a valid login
      throw new SecurityException(elgg_echo('SecurityException:authenticationfailed'));
    }

}

// list of observations by date/user
function is_logged_in() {

    if(elgg_is_logged_in()) {
        $user_guid = elgg_get_logged_in_user_guid();
        $token = get_user_tokens($user_guid);
        $user = get_user($user_guid);

        if($token) {
            return array(
                "user_guid" => $user->guid,
                "name" => $user->name,
                "username" => $user->username,
                "icon" => $user->getIcon('tiny'),
                "token" => $token ? $token[0]->token : 0,
                "is_admin" => elgg_is_admin_logged_in()
            );
        }
        else {
            $token = create_user_token($user->username, PHP_INT_MAX);

            return array(
                "user_guid" => $user->guid,
                "name" => $user->name,
                "username" => $user->username,
                "icon" => $user->getIcon('tiny'),
                "token" => $token,
                "is_admin" => elgg_is_admin_logged_in()
            );

        }
    }
    else {
        return array(
            "user_guid" => 0,
            "name" => "",
            "username" => "",
            "icon" => "",
            "token" => 0,
            "is_admin" => false
        );
    }
}

/*
tiny, topbar, small, medium, large, master
*/

function get_user_info_by_agg_id($agg_id, $icon_size) {
    $results = elgg_get_entities_from_metadata(array(
        "type_subtype_pair"	=>	array('object' => 'observation'),
        "metadata_name_value_pairs" => array('agg_id' => $agg_id)
    ));

    //$results = elgg_get_entity_metadata_where_sql("e", "metadata", null, null, array('name' => 'agg_id', 'value' => '10'));

    if($results)
	{
        return get_user_info($results[0]->owner_guid, $icon_size);
    }
    else {
        return 0;
    }
}

function get_inv_by_agg_id($agg_id) {
    $results = elgg_get_entities_from_metadata(array(
        "type_subtype_pair"	=>	array('object' => 'observation'),
        "metadata_name_value_pairs" => array('agg_id' => $agg_id)
    ));

    if($results) {

		$obs = get_entity($results[0]->guid);
		$inv_guid = $obs->parent_guid;
		$inv = get_entity($inv_guid);
		if ($inv)
		{
			return array(
				"guid" => $inv->guid,
				"name" => $inv->name
			);
		}
		else
		{
			return 0;
		}
    }
    else {
        return 0;
    }
}

function get_user_info_by_username($username, $icon_size) {
    $user = get_user_by_username($username);
    if($user) {
        return get_user_info($user->guid, $icon_size);
    }
    else {
        throw Exception('could not find user');
    }
}

function get_user_info($user_guid, $icon_size) {
    //get user by user name
    $user = get_user($user_guid);
    $site = elgg_get_site_entity();

    $profile_type_guid = $user->custom_profile_type;
    $profile_type = get_entity($profile_type_guid);

    $skills = $user->skills;
    $interests = $user->interests;

    $returned_info =  array(
        "id" => $user->guid,
        "users_display_name" => $user->name,
        "username" => $user->username,
        "image" => $user->getIconUrl($icon_size),
        "email" => $user->email,
        "profile_type" => $profile_type ? $profile_type->getTitle() : '',
        "joined" => elgg_get_friendly_time($user->time_created),
        "description" => $user->description,
        "brief_description" => $user->briefdescription,
        "location" => $user->location,
        "interests" => is_array($interests) ? $interests : array($interests),
        "skills" => is_array($skills) ? $skills : array($skills),
        "contactemail" => $user->contactemail,
        "phone" => $user->phone,
        "mobile" => $user->mobile,
        "website" => $user->website,
        "twitter" => $user->twitter,
        "school" => $user->school,
        "video" => $user->video
    );

    foreach($returned_info as $key => $value){

      $returned_info[$key] = ($value == null || $value == "null") ? "" : $value;

    }

    return $returned_info;
}

function edit_user_info_by_username($username, $displayname, $profiletype, $description, $location, $interests, $skills, $contactemail, $website, $twitter, $school, $video) {

    // Validate edit
    if (!$displayname) {
        throw new Exception(elgg_echo("profile:nodisplayname"));
    }

    // $displayname = htmlspecialchars($displayname, ENT_QUOTES, 'UTF-8');

    // get current user
    $logged_in_user = elgg_get_logged_in_user_entity();

    // allow deletion if you are and admin or this user
    if(!elgg_is_logged_in() || elgg_is_logged_in() && ($logged_in_user->username != $username && !elgg_is_admin_logged_in())) {
        throw new Exception('You need to be logged in either as an admin or as this user to edit this profile.');
    }

    // may need to deal with a file
    $filename = 'avatar';
    $newFile = $_FILES[$filename];

    $getUser = get_user_info_by_username($username, "large");

    $userInfo = get_entity($getUser["id"]);

    $userInfo->name = trim(urldecode($displayname));
    $userInfo->custom_profile_type = trim(urldecode($profiletype));
    // $userInfo->username;
    // $userInfo->getIconUrl($icon_size);
    // $userInfo->email;
    $userInfo->description = trim(urldecode($description));
    // $userInfo->briefdescription;
    $userInfo->location = trim(urldecode($location));
    $userInfo->interests = json_decode($interests);
    $userInfo->skills = json_decode($skills);
    $userInfo->contactemail = trim(urldecode($contactemail));
    // $userInfo->phone;
    // $userInfo->mobile;
    $userInfo->website = trim(urldecode($website));
    $userInfo->twitter = trim(urldecode($twitter));
    $userInfo->school = trim(urldecode($school));
    $userInfo->video = trim(urldecode($video));

    $userInfo->save();

    if($newFile) {

      $icon_sizes = elgg_get_config('icon_sizes');

      // get the images and save their file handlers into an array
      // so we can do clean up if one fails.
      $files = array();

      foreach ($icon_sizes as $name => $size_info) {
        $resized = get_resized_image_from_uploaded_file('avatar', $size_info['w'], $size_info['h'], false, $size_info['upscale']);

        if ($resized) {
          //@todo Make these actual entities.  See exts #348.
          $file = new ElggFile();
          $file->owner_guid = $userInfo->guid;
          $file->setFilename("profile/{$userInfo->guid}{$name}.jpg");
          $file->open('write');
          $file->write($resized);
          $file->close();
          $files[] = $file;

        } else {
          // cleanup on fail
          foreach ($files as $file) {
            $file->delete();
          }

          register_error(elgg_echo('avatar:resize:fail'));
        }
      }

      $userInfo->icontime = time();
      if (elgg_trigger_event('profileiconupdate', $userInfo->type, $userInfo)) {
        system_message(elgg_echo("avatar:upload:success"));
      }
      else{
        throw new Exception('Failed to save new avatar');
      }

    }

    return array(
            'username' => $username
        );
}

function get_members($page, $search, $typeFilter, $schoolFilter) {

    //$results = get_data("SELECT guid FROM elgg_users_entity WHERE name LIKE '%jo%';");
    $limit = 12;
    $offset = $page * $limit;

    $schoolWhere = ($schoolFilter !== "") ? array('name' => 'school', 'value' => $schoolFilter) : null;
    $typeWhere = ($typeFilter !== 0) ? array('name' => 'custom_profile_type', 'value' => $typeFilter) : null;

    $results = elgg_get_entities_from_metadata(array(
        'types' => 'user',
        //'callback' => 'my_get_entity_callback',
        'limit' => $limit,
        'offset' => $offset,
        'metadata_name_value_pairs' => array($typeWhere, $schoolWhere),
        'metadata_name_value_pairs_operator' => 'AND',
        'joins' => array("JOIN {$CONFIG->dbprefix}elgg_users_entity users ON (e.guid = users.guid)"),
        'wheres' => array("(users.name LIKE '%".$search."%' OR users.username LIKE '%".$search."%')")
    ));

    $members = array();

    foreach($results as $result) {
        $profile_type_guid = $result->custom_profile_type;
        $profile_type = get_entity($profile_type_guid);

        $members[] = array(
            "displayname" => $result->name,
            "username" => $result->username,
            "icon" => $result->getIconUrl("large"),
            "medium_icon" => $result->getIconUrl("medium"),
            "profile_type" => $profile_type ? $profile_type->getTitle() : '',
            "school" => $result->school,
            "id" => $result->guid
        );

    }

    return $members;
}

function get_members_by_school($search) {

  $limit = 0;

  $results = elgg_get_entities_from_metadata(array(
      'types' => 'user',
      'limit' => $limit,
      'metadata_name_value_pairs' => array(array('name' => 'school', 'operand' => 'LIKE', 'value' => '%'.$search.'%', 'case_sensitive' => false))
  ));

  $members = array();
  $schools = array();

  foreach($results as $key => $result){
      $members[] = array(
        "displayname" => $result->name,
        "username" => $result->username,
        "icon" => $result->getIconUrl("large"),
        "medium_icon" => $result->getIconUrl("medium"),
        "profile_type" => $profile_type ? $profile_type->getTitle() : '',
        "school" => $result->school,
        "id" => $result->guid
      );
      if(!in_array($result->school, $schools, true)){
          $schools[] = $result->school;
      }
  }

  $final = array(
    'schools' => $schools,
    'members' => $members
  );

  return $final;

}

function get_people_picker_people($search) {
    // get people

    // get school

    // get people from school

}

function delete_obs_by_agg_id($agg_id) {
    $results = elgg_get_entities_from_metadata(array(
        "type_subtype_pair"	=>	array('object' => 'observation'),
        "metadata_name_value_pairs" => array('agg_id' => $agg_id)
    ));

    if($results) {
        return delete_obs_by_guid($results[0]->guid);
    }
    else {
        throw new Exception('No valid observations with that aggregator id');
    }
}

function delete_obs_by_guid($guid) {

    $user_guid = elgg_get_logged_in_user_guid();
    if($user_guid > 0) {
        $user = get_user($user_guid);
    }

    $obs = get_entity($guid);
    if($obs) {
        //if owner or admin
        if(elgg_is_admin_logged_in() || $user->guid == $obs->owner_guid) {
            return delete_entity($obs->guid, true);
        }
        else {
            throw new Exception('You do not have permissions to delete this observation');
        }
    }
    else {
        throw new Exception('This is not a valid observation id');
    }
}

function create_agg_user($user) {
    // New users need to be added to wb-aggregator so we make this curl request for this reason.
    $app_env = getenv("APP_ENV");
    $app_env = $app_env == "prod" ? $app_env : "unstable";

    $profile_type_guid = $user->custom_profile_type;
    $profile_type = get_entity($profile_type_guid);

    $post_fields = array(
        'class' => 'wb.api.User',
        'elggGroup' => $profile_type ? $profile_type->getTitle() : '',
        'elggHost' => elgg_get_site_url(),
        'elggId' => $user->guid,
        // not sure why this image is here but travis has it setup this way and don't want to change it
        'image' => 'http://demo.nbtsolutions.com/elgg/_graphics/icons/user/defaultsmall.gif'
    );

    $ch = curl_init();

    curl_setopt_array($ch, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => "http://wb-aggregator.".$app_env.".nbt.io/api/observer/user",
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => $post_fields
    ));

    curl_exec($ch);
}

function rotate_image_by_agg_id($rotate_degrees, $agg_id) {

    $user_guid = elgg_get_logged_in_user_guid();
    if($user_guid > 0) {
        $user = get_user($user_guid);
    }
    else {
        throw new Exception("You need to login");
    }

    $results = elgg_get_entities_from_metadata(array(
        "type_subtype_pair"	=>	array('object' => 'observation'),
        "metadata_name_value_pairs" => array('agg_id' => $agg_id)
    ));

    $obs = get_entity($results[0]->guid);

    if($obs) {
        //if owner or admin
        if(elgg_is_admin_logged_in() || $user->guid == $obs->owner_guid) {

            // get all of our data from the aggregator
            $ch = curl_init();

            //$app_env = getenv("APP_ENV");
            //$app_env = $app_env ? $app_env : "unstable";
            $app_env = 'prod';

            curl_setopt_array($ch, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => "http://wb-aggregator.".$app_env.".nbt.io/api/observation/" . $agg_id . "/measurements"
            ));

            $obs_measurement = curl_exec($ch);

            //convert to utf8
            $observation = json_decode(stripslashes($obs_measurement));

            foreach($observation as $measurement) {
                if($measurement->value == "image") {
                    $picture = explode('/', $measurement->meta->url);
                    $s3_image_name = $picture[count($picture) - 1];
                }
            }

            // if no image. give up.
            if(!$s3_image_name) {
                throw new Exception('No image for this observation.');
            }

            if($rotate_degrees != -90 && $rotate_degrees != 90) {
                throw new Exception('Please provide valid degrees to rotate by either (90 or -90)');
            }

            // used to temporarily store the rotated image before sending it to s3
            $temp_folder = 'tmp/';
            $thumbnail_width = 270;
            $thumbnail_height = 170;

            $s3_key = 'AKIAJ7SN4WICVZFZO6KQ';
            $s3_secret = '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';
            $s3_bucket = 'weatherblur-media';
            $s3_content_type = 'image/';
            $s3_url = 'https://s3.amazonaws.com/weatherblur-media/';

            // get filename and extension. Assuming only one . in full image name
            list($image_name, $image_ext) = explode(".", $s3_image_name);

            // Download the image
            $gd_image = imagecreatefromstring(file_get_contents($s3_url.$s3_image_name));
            if(!$gd_image){
                throw new Exception('Downloading image from s3 failed');
            }

            // if we use imagecreatetruecolor on pngs it will message up things
            $gd_image_thumbnail = $image_ext == 'jpg' ? imagecreatetruecolor($thumbnail_width, $thumbnail_height) : imagecreate($thumbnail_width, $thumbnail_height);

            $gd_image = imagerotate($gd_image, $rotate_degrees, 0);

            $image_width = imagesx($gd_image);
            $image_height = imagesy($gd_image);
            $ratio = $thumbnail_width / $thumbnail_height;

            $src_width = intval(($image_width / $ratio) > $image_height ? $image_height * $ratio : $image_width);
            $src_height = intval(($image_width / $ratio) > $image_height ? $image_height : $image_width / $ratio);
            $src_x = intval($image_width * .5 - ($src_width * .5));
            $src_y = intval($image_height * .5 - ($src_height * .5));

            // thumbnail copy
            imagecopyresampled($gd_image_thumbnail, $gd_image, 0, 0, $src_x, $src_y, 270, 170, $src_width, $src_height);

            // save image locally then upload it to s3
            $s3_content_type = write_temp_image($gd_image, $image_ext, $temp_folder, $s3_image_name);
            imagedestroy($gd_image);

            // thumbnail
            $s3_content_type = write_temp_image($gd_image_thumbnail, $image_ext, $temp_folder, $image_name.'-thumb.'.$image_ext);
            imagedestroy($gd_image_thumbnail);

            // loading this here to save memory
            include 'aws.phar';
            //use Aws\S3\S3Client;

            // you can get it from http://aws.amazon.com/sdkforphp/
            $client = Aws\S3\S3Client::factory(array(
                'key'	=> $s3_key,
                'secret' => $s3_secret
            ));

            $rotated_image_data = get_temp_image($temp_folder, $s3_image_name);
            write_to_s3($s3_bucket, $s3_content_type, $s3_image_name, $rotated_image_data, $client);
            unset($rotated_image_data);

            $rotated_image_thumb_data = get_temp_image($temp_folder, $image_name.'-thumb.'.$image_ext);
            write_to_s3($s3_bucket, $s3_content_type, $image_name.'-thumb.'.$image_ext, $rotated_image_thumb_data, $client);
            unset($rotates_image_data);

            unlink(dirname(__FILE__).'/'.$temp_folder.$s3_image_name);
            unlink(dirname(__FILE__).'/'.$temp_folder.$image_name.'-thumb.'.$image_ext);

            return $rotate_degrees;
        }
        else {
            throw new Exception('You do not have permission to rotate this observation');
        }
    }
    else {
        throw new Exception('This is not a valid aggregator id');
    }
}

function get_temp_image($folder, $image_name) {
    $image_data = file_get_contents(dirname(__FILE__).'/'.$folder.$image_name);
    if(!$image_data) {
        throw new Exception('Unable to load new rotated image');
    }
    return $image_data;
}

function write_temp_image($image, $image_ext, $folder, $image_name) {
    // create image based on file extension
    if($image_ext == 'jpg' || $image_ext == 'jpeg') {
        $result = imagejpeg($image, dirname(__FILE__).'/'.$folder.$image_name, 100);
        return 'jpg';
    }
    else if($image_ext == 'png') {
        // important for transparency
        imageAlphaBlending($image, true);
        imageSaveAlpha($image, true);

        $result = imagepng($image, dirname(__FILE__).'/'.$folder.$image_name);
        return 'png';
    }
    // file format not supported
    else {
        throw new Exception('file format not supported');
    }
}

function write_to_s3($bucket, $content_type, $image_name, $image_data, $client, $file_acl = 'public-read') {

    $result = $client->putObject(array(
        'Bucket' => $bucket,
        'contentType' => $content_type,
        'Key'	=> $image_name,
        'Body'	=> $image_data,
        'ACL'	=> $file_acl
    ));
}

function get_news($limit, $offset) {

    $news = array();
    $user_guid = elgg_get_logged_in_user_guid();

    $results = elgg_get_entities(array(
        'type_subtype_pair'	=>	array('object' => 'news'),
        'limit' => $limit,
        'offset' => $offset
    ));

    foreach($results as $result) {

        $user = get_user($result->owner_guid);

        $likes = $result->getAnnotations('likes');
        $i_liked = false;
        $comments = array();

        foreach($likes as $like) {
            if($like->owner_guid == $user_guid) {
                $i_liked = true;
            }
        }

        $ignore = elgg_set_ignore_access(true);
        $result_comments = $result->getAnnotations('group_topic_post', 3, 0, 'desc');

        $file_thumbnail = elgg_get_entities_from_relationship(array(
            'relationship' => 'thumbnail_file',
            'relationship_guid' => $result->guid,
            'inverse_relationship' => true
        ));

        if($file_thumbnail) {
            $thumbnail_filepath = elgg_get_site_url().'file/download/'.$file_thumbnail[0]->guid.'/'.$file_thumbnail[0]->getFilename();
        }
        else {
            $thumbnail_filepath = "";
        }

        // large file
        $file_large = elgg_get_entities_from_relationship(array(
            'relationship' => 'large_file',
            'relationship_guid' => $result->guid,
            'inverse_relationship' => true
        ));

        if($file_large) {
            $large_filepath = elgg_get_site_url().'file/download/'.$file_large[0]->guid.'/'.$file_large[0]->getFilename();
        }
        else {
            $large_filepath = "";
        }

        foreach($result_comments as $comment) {

            $comment_user = get_user($comment->owner_guid);

            $comments[] = array(
                'description' => $comment->value,
                'like_count' => 0,
                'date' => elgg_get_friendly_time($comment->time_created),
                'user' => array(
                    'id' => $comment_user->guid,
                    'displayname' => $comment_user->name,
                    'username' => $comment_user->username,
                    'image' => $comment_user->getIcon('small')
                )
            );
        }

        $news[] = array(
            'id' => $result->guid,
            'displayname' => $user->name,
            'username' => $user->username,
            'userIcon' => $user->getIcon('small'),
            'title' => $result->title,
            'description' => $result->description,
            'excerpt' => $result->excerpt,
            'date' => elgg_get_friendly_time($result->time_created),
            'thumbnail_filepath' => $thumbnail_filepath,
            'large_filepath' => $large_filepath,
            'like_count' => count($likes),
            'i_liked' => $i_liked,
            'comments' => $comments,
            'comment_count' => count($result->getAnnotations('group_topic_post')),
            'video' => $result->video,
            'type' => $result->getSubtype()
        );
    }

    elgg_set_ignore_access($ignore);

    return $news;
}

function get_article_by_id($id) {

    $news = array();
    $user_guid = elgg_get_logged_in_user_guid();

    $results = elgg_get_entities(array(
        'type_subtype_pair'	=>	array('object' => 'news'),
        'guid' => $id,
        'limit' => $limit,
        'offset' => $offset
    ));
    $result = $results[0];

    $user = get_user($result->owner_guid);

    $likes = $result->getAnnotations('likes');
    $i_liked = false;
    $comments = array();

    foreach($likes as $like) {
        if($like->owner_guid == $user_guid) {
            $i_liked = true;
        }
    }

    $ignore = elgg_set_ignore_access(true);
    $result_comments = $result->getAnnotations('group_topic_post', 3, 0, 'desc');

    $file_thumbnail = elgg_get_entities_from_relationship(array(
        'relationship' => 'thumbnail_file',
        'relationship_guid' => $result->guid,
        'inverse_relationship' => true
    ));

    if($file_thumbnail) {
        $thumbnail_filepath = elgg_get_site_url().'file/download/'.$file_thumbnail[0]->guid.'/'.$file_thumbnail[0]->getFilename();
    }
    else {
        $thumbnail_filepath = "";
    }

    // large file
    $file_large = elgg_get_entities_from_relationship(array(
        'relationship' => 'large_file',
        'relationship_guid' => $result->guid,
        'inverse_relationship' => true
    ));

    if($file_large) {
        $large_filepath = elgg_get_site_url().'file/download/'.$file_large[0]->guid.'/'.$file_large[0]->getFilename();
    }
    else {
        $large_filepath = "";
    }

    foreach($result_comments as $comment) {

        $comment_user = get_user($comment->owner_guid);

        $comments[] = array(
            'description' => $comment->value,
            'like_count' => 0,
            'date' => elgg_get_friendly_time($comment->time_created),
            'user' => array(
                'id' => $comment_user->guid,
                'displayname' => $comment_user->name,
                'username' => $comment_user->username,
                'image' => $comment_user->getIcon('small')
            )
        );
    }

    $news = array(
        'id' => $result->guid,
        'displayname' => $user->name,
        'username' => $user->username,
        'userIcon' => $user->getIcon('small'),
        'title' => $result->title,
        'description' => $result->description,
        'excerpt' => $result->excerpt,
        'date' => elgg_get_friendly_time($result->time_created),
        'thumbnail_filepath' => $thumbnail_filepath,
        'large_filepath' => $large_filepath,
        'like_count' => count($likes),
        'i_liked' => $i_liked,
        'comments' => $comments,
        'comment_count' => count($result->getAnnotations('group_topic_post')),
        'video' => $result->video,
        'type' => $result->getSubtype()
    );

    elgg_set_ignore_access($ignore);

    return $news;
}

function create_article($title, $description, $excerpt, $tags){

  if(!elgg_is_logged_in()) {
      throw new Exception("Please login to perform this action");
  }

  // may need to deal with a file
  $filename = 'article_file';
  $file = $_FILES[$filename];

  $title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
  //$status = get_input("status");
  $access_id = ACCESS_PUBLIC;
  //$container_guid = (int) get_input('container_guid');
  //$guid = (int) get_input('topic_guid');
  //$tags = get_input("tags");

  $ignore = elgg_set_ignore_access(true);
  $article = new ElggObject();
  $article->subtype = 'news';
  $article->title = urldecode($title);
  $article->description = urldecode($description);
  $article->excerpt = urldecode($excerpt);
  //$topic->status = $status;
  $article->access_id = $access_id;
  $article->container_guid = $container_guid;
  $article->owner_guid = elgg_get_logged_in_user_guid();
  $article->video = $video;

  $tags = explode(",", $tags);
  $article->tags = $tags;

  $result = $article->save();

  if($file) {

      // original
      $fh_original = new ElggFile();
      $fh_original->owner_guid = $article->owner_guid;
      $fh_original->name = 'article_' . $article->guid . '_' . $file['name'];
      $fh_original->setFilename('article_' . $article->guid . '_' . $file['name']);
      $fh_original->set('file category', 'article_file');
      $fh_original->open('write');
      $fh_original->write(get_uploaded_file($filename));
      $fh_original->close();
      $fh_original->save();

      $resized_large_file = get_resized_image_from_existing_file($fh_original->getFilenameOnFilestore(), 600, 400, 0, 0, 0, 0, false);
      $resized_thumbnail_file = get_resized_image_from_existing_file($fh_original->getFilenameOnFilestore(), 250, 250, 0, 0, 0, 0, false);

      $fh_large = new ElggFile();
      $fh_large->owner_guid = $article->owner_guid;
      $fh_large->name = 'article_' . $article->guid . '_large_' . $file['name'];
      $fh_large->setFilename('article_' . $article->guid . '_large_' . $file['name']);
      $fh_large->set('file category', 'discussion_file');
      $fh_large->open('write');
      $fh_large->write($resized_large_file);
      $fh_large->close();
      $fh_large->save();

      // thumbnail
      $fh_thumbnail = new ElggFile();
      $fh_thumbnail->owner_guid = $article->owner_guid;
      $fh_thumbnail->name = 'article_' . $article->guid . '_thumbnail_' . $file['name'];
      $fh_thumbnail->setFilename('article_' . $article->guid . '_thumbnail_' . $file['name']);
      $fh_thumbnail->set('file category', 'discussion_file');
      $fh_thumbnail->open('write');
      $fh_thumbnail->write($resized_thumbnail_file);
      $fh_thumbnail->close();
      $fh_thumbnail->save();

      add_entity_relationship($fh_original->getGUID(), 'original_file', $article->guid);
      add_entity_relationship($fh_large->getGUID(), 'large_file', $article->guid);
      add_entity_relationship($fh_thumbnail->getGUID(), 'thumbnail_file', $article->guid);
  }

  elgg_set_ignore_access($ignore);

  if (!$result) {
      throw new Exception('Could not save this article');
  }

  add_to_river('river/object/groupforumtopic/create', 'create', elgg_get_logged_in_user_guid(), $article->guid);

  return array(
      "guid" => $article->guid
  );

}

function edit_article($guid, $title, $description, $excerpt, $tags){

  $results = elgg_get_entities(array(
      'type_subtype_pair'	=>	array('object' => 'news'),
      'guid' => $guid
  ));
  $article = $results[0];

  $logged_in_user = elgg_get_logged_in_user_entity();

  // allow edit if you are and admin or this user
  if(!elgg_is_logged_in() || elgg_is_logged_in() && ($logged_in_user->guid != $article->owner_guid && !elgg_is_admin_logged_in())) {
      throw new Exception('You need to be logged in either as an admin or as the article owner to edit this article.');
  }

  // may need to deal with a file
  $filename = 'article_file';
  $file = $_FILES[$filename];

  $title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');

  $ignore = elgg_set_ignore_access(true);
  $article->title = urldecode($title);
  $article->description = urldecode($description);
  $article->excerpt = urldecode($excerpt);

  $tags = explode(",", $tags);
  $article->tags = $tags;

  $result = $article->save();

  if($file) {

      // original
      $fh_original = new ElggFile();
      $fh_original->owner_guid = $article->owner_guid;
      $fh_original->name = 'article_' . $article->guid . '_' . $file['name'];
      $fh_original->setFilename('article_' . $article->guid . '_' . $file['name']);
      $fh_original->set('file category', 'article_file');
      $fh_original->open('write');
      $fh_original->write(get_uploaded_file($filename));
      $fh_original->close();
      $fh_original->save();

      $resized_large_file = get_resized_image_from_existing_file($fh_original->getFilenameOnFilestore(), 600, 400, 0, 0, 0, 0, false);
      $resized_thumbnail_file = get_resized_image_from_existing_file($fh_original->getFilenameOnFilestore(), 250, 250, 0, 0, 0, 0, false);

      $fh_large = new ElggFile();
      $fh_large->owner_guid = $article->owner_guid;
      $fh_large->name = 'article_' . $article->guid . '_large_' . $file['name'];
      $fh_large->setFilename('article_' . $article->guid . '_large_' . $file['name']);
      $fh_large->set('file category', 'discussion_file');
      $fh_large->open('write');
      $fh_large->write($resized_large_file);
      $fh_large->close();
      $fh_large->save();

      // thumbnail
      $fh_thumbnail = new ElggFile();
      $fh_thumbnail->owner_guid = $article->owner_guid;
      $fh_thumbnail->name = 'article_' . $article->guid . '_thumbnail_' . $file['name'];
      $fh_thumbnail->setFilename('article_' . $article->guid . '_thumbnail_' . $file['name']);
      $fh_thumbnail->set('file category', 'discussion_file');
      $fh_thumbnail->open('write');
      $fh_thumbnail->write($resized_thumbnail_file);
      $fh_thumbnail->close();
      $fh_thumbnail->save();

      add_entity_relationship($fh_original->getGUID(), 'original_file', $article->guid);
      add_entity_relationship($fh_large->getGUID(), 'large_file', $article->guid);
      add_entity_relationship($fh_thumbnail->getGUID(), 'thumbnail_file', $article->guid);
  }

  $article->save();

  elgg_set_ignore_access($ignore);

  if (!$result) {
      throw new Exception('Could not save this article');
  }

  return array(
      "guid" => $article->guid
  );

}

function delete_article($id){

  $article = get_entity($id);

  $logged_in_user = elgg_get_logged_in_user_entity();

  // allow deletion if you are and admin or this user
  if(!elgg_is_logged_in() || elgg_is_logged_in() && ($logged_in_user->guid != $article->owner_guid && !elgg_is_admin_logged_in())) {
      throw new Exception('You need to be logged in either as an admin or as the article owner to delete this article.');
  }
  else{
    $article->delete();
    return true;
  }

}


function get_activities($limit, $offset) {

    $results = elgg_get_river(array(
        'limit' => $limit,
        'offset' => $offset
    ));

    $return_val = array();

    //$results = elgg_list_river(array('limit' => 3), "page/components/homepage-activity-list");

    foreach($results as $result) {
        var_dump($result->view);
        $return_val[] = elgg_view($result->view, $result);
    }

    echo elgg_view('core/river/sidebar')."\n\n";

    return $return_val;
}

function get_user_stats($username) {

    $user = get_user_by_username($username);
    $user_guid = $user->guid;

    $relations = get_users_membership($user_guid); //get all the groups the user is belonging

    //count obs
    $obs = elgg_get_entities( array(
      'owner_guid' => $user_guid,
      'type_subtype_pair'	=>	array('object' => 'observation'),
      'limit' => false
    ));

    //count maps
    $maps = elgg_get_entities( array(
      'owner_guid' => $user_guid,
      'type' => 'object',
      'subtype' => 'investigationforumtopic_map',
      'limit' => false
    ));

    //count graphs
    $graphs = elgg_get_entities( array(
      'owner_guid' => $user_guid,
      'type' => 'object',
      'subtype' => 'investigationforumtopic_graph',
      'limit' => false
    ));

    //count imgs
    $imgs = elgg_get_entities( array(
      'owner_guid' => $user_guid,
      'type' => 'object',
      'subtype' => 'investigationforumtopic_image',
      'limit' => false
    ));

    //count video
    $video = elgg_get_entities( array(
      'owner_guid' => $user_guid,
      'type' => 'object',
      'subtype' => 'investigationforumtopic_video',
      'limit' => false
    ));

    //count discussions
    $disc = elgg_get_entities( array(
      'owner_guid' => $user_guid,
      'type' => 'object',
      'subtype' => 'investigationforumtopic_text',
      'limit' => false
    ));

    return array(
        investigations => count($relations),
        observations => count($obs),
        maps => count($maps),
        graphs => count($graphs),
        images => count($img),
        video => count($video),
        discussions => count($disc)
    );
}

function get_inv_by_username($username) {
    $user = get_user_by_username($username);
    $user_guid = $user->guid;
    $investigations = array();

    create_agg_user($user);

    login($user, false);

    $dbprefix = elgg_get_config('dbprefix');

	$results = elgg_get_entities_from_relationship(array(
        'type_subtype_pair'	=>	array('group' => 'investigation'),
		'relationship' => 'member',
		'relationship_guid' => $user_guid,
		'inverse_relationship' => false,
		'full_view' => false,
		'joins' => array("JOIN {$dbprefix}groups_entity ge ON e.guid = ge.guid"),
		'order_by' => 'ge.name asc'
	));

    // build out our list of investigation names/ids
    foreach($results as $result) {
        $investigations[] = array(
            'name' => $result->name,
            'id' => $result->guid
        );
    }
    return $investigations;
}

function delete_user($username) {

    $user = get_user_by_username(urldecode($username));
    if(!$user) {
        throw new Exception('Not a valid username');
    }

    $logged_in_user = elgg_get_logged_in_user_entity();

    // allow deletion if you are and admin or this user
    if(!elgg_is_logged_in() || elgg_is_logged_in() && ($logged_in_user->guid != $user->guid && !elgg_is_admin_logged_in())) {
        throw new Exception('You need to be logged in either as an admin or as '.$username.' to delete this user.');
    }

    $result = array(
        'am-i-logged-in?' => elgg_is_logged_in(),
        'am-i-an-admin' => elgg_is_admin_logged_in(),
        'logged-in-user' => $logged_in_user->guid,
        'username' => $user->guid
    );

    $result = $user->delete(true);

    return $result;
}

function create_user($displayname, $username, $email, $password, $password2, $profile_type) {

    $name = urldecode($displayname);
    $username = urldecode($username);
    $email = urldecode($email);
    $password = urldecode($password);
    $password2 = urldecode($password2);
    $profile_type = urldecode($profile_type);

        try {
            if (trim($password) == "" || trim($password2) == "") {
                throw new RegistrationException(elgg_echo('RegistrationException:EmptyPassword'));
            }

            if (strcmp($password, $password2) != 0) {
                throw new RegistrationException(elgg_echo('RegistrationException:PasswordMismatch'));
            }

            $guid = register_user($username, $password, $name, $email, false);
            return $guid;

            if ($guid) {
                $new_user = get_entity($guid);

                // allow plugins to respond to self registration
                // note: To catch all new users, even those created by an admin,
                // register for the create, user event instead.
                // only passing vars that aren't in ElggUser.
                $params = array(
                    'user' => $new_user,
                    'password' => $password,
                    'friend_guid' => $friend_guid,
                    'invitecode' => $invitecode
                );
                $new_user->custom_profile_type = $profile_type;
                 return $new_user->save();

                // @todo should registration be allowed no matter what the plugins return?
                if (!elgg_trigger_plugin_hook('register', 'user', $params, TRUE)) {
                    $ia = elgg_set_ignore_access(true);
                    $new_user->delete();
                    elgg_set_ignore_access($ia);
                    // @todo this is a generic messages. We could have plugins
                    // throw a RegistrationException, but that is very odd
                    // for the plugin hooks system.
                    throw new RegistrationException(elgg_echo('registerbad'));
                }

                //system_message(elgg_echo("registerok", array(elgg_get_site_entity()->name)));

                // if exception thrown, this probably means there is a validation
                // plugin that has disabled the user
                try {
                    login($new_user);
                } catch (LoginException $e) {
                    // do nothing
                }

                // Forward on success, assume everything else is an error...
                //forward();
            } else {
                throw new RegistrationException('There was some problem registering a new user please try again.');
            }
        } catch (RegistrationException $r) {
            throw new RegistrationException($r->getMessage());
        }

}

function user_exists_by_email($email) {

    $email = urldecode($email);

    $result = get_user_by_email($email);

    return array(
        exists => (count($result) > 0)
    );
}

function user_exists_by_username($username) {

    $username = urldecode($username);

    $result = get_user_by_username($username);

    return array(
        exists => ($result != false)
    );
}

function request_new_password($email) {

  // allow email addresses
  if (strpos($email, '@') !== false && ($users = get_user_by_email($email))) {
  	$username = $users[0]->username;
  }
  else{
    throw new Exception('no account associated with this email.');
  }

  $user = get_user_by_username($username);

  if ($user) {

    $user_guid = $user->guid;

  	// generate code
     $code = generate_random_cleartext_password();
     $user->setPrivateSetting('passwd_conf_code', $code);

     // generate link
     $app_env = getenv("APP_ENV");
     $link = $app_env == "prod" ? elgg_get_site_url() . "#/resetpassword/$user_guid/$code" : elgg_get_site_url() . "#/resetpassword/$user_guid/$code";

      $emailBody = "Hi %s,

    Somebody (from the IP address %s) has requested a new password for their account.

    If you requested this, click on the link below. Otherwise ignore this email.

    %s
    ";

     // generate email
     //$email = elgg_echo('email:resetreq:body', array($user->name, $_SERVER['REMOTE_ADDR'], $link));
     $email = elgg_echo($emailBody, array($user->name, $_SERVER['REMOTE_ADDR'], $link));

     return notify_user($user->guid, elgg_get_site_entity()->guid,
       elgg_echo('email:resetreq:subject'), $email, array(), 'email');

  } else {
  	// register_error(elgg_echo('user:username:notfound', array($username)));
    throw new Exception('Could not find the user associated with the email address: '.$email);
  }


}

function send_new_password($user_guid, $conf_code){
  global $CONFIG;

  $user_guid = (int)$user_guid;
  $user = get_entity($user_guid);

  if ($user instanceof ElggUser) {
    $saved_code = $user->getPrivateSetting('passwd_conf_code');

    if ($saved_code && $saved_code == $conf_code) {
      $password = generate_random_cleartext_password();

      if (force_user_password_reset($user_guid, $password)) {
        remove_private_setting($user_guid, 'passwd_conf_code');
        // clean the logins failures
        reset_login_failure_count($user_guid);

        $email_body = "Hi %s,

      Your password has been reset to: %s

      After you've logged in, remember to change your password by going to the 'edit user' section from your profile.";


        $email = elgg_echo($email_body, array($user->name, $password));

        return notify_user($user->guid, $CONFIG->site->guid,
          elgg_echo('email:resetpassword:subject'), $email, array(), 'email');
      }
    }
  }

  return FALSE;
}

function change_password($user_guid, $current_password, $password, $password2){

  $current_password = urldecode($current_password);
  $password = urldecode($password);
  $password2 = urldecode($password2);

  // COPIED FROM /engine/lib/user_settings.php

  if (!$user_guid) {
    $user = elgg_get_logged_in_user_entity();
  } else {
    $user = get_entity($user_guid);
  }

  if ($user && $password) {
    // let admin user change anyone's password without knowing it except his own.
    if (!elgg_is_admin_logged_in() || elgg_is_admin_logged_in() && $user->guid == elgg_get_logged_in_user_guid()) {
      $credentials = array(
        'username' => $user->username,
        'password' => $current_password
      );

      try {
        pam_auth_userpass($credentials);
      } catch (LoginException $e) {
        //register_error(elgg_echo('LoginException:ChangePasswordFailure'));
        throw new Exception('could not verify login.');
        return false;
      }
    }

    try {
      $result = validate_password($password);
    } catch (RegistrationException $e) {
      //register_error($e->getMessage());
      throw new Exception($e->getMessage());
      return false;
    }

    if ($result) {
      if ($password == $password2) {
        $user->salt = generate_random_cleartext_password(); // Reset the salt
        $user->password = generate_user_password($user, $password);
        if ($user->save()) {
          // system_message(elgg_echo('user:password:success'));
          return true;
        } else {
          // register_error(elgg_echo('user:password:fail'));
          throw new Exception('failed to save new password.');
        }
      } else {
        // register_error(elgg_echo('user:password:fail:notsame'));
        throw new Exception('password duplicate verification is not the same.');
      }
    } else {
      // register_error(elgg_echo('user:password:fail:tooshort'));
      throw new Exception('new password is too short.');
    }
  } else {
    // no change
    return null;
  }

  return false;

}

function change_email($email, $user_id){

  $email = urldecode($email);

  // COPIED FROM /engine/lib/user_settings.php

  if (!$user_id) {
    $user = elgg_get_logged_in_user_entity();
  } else {
    $user = get_entity($user_id);
  }

  if (!is_email_address($email)) {
    // register_error(elgg_echo('email:save:fail'));
    throw new Exception('not in proper email format.');
    return false;
  }

  if ($user) {
    if (strcmp($email, $user->email) != 0) {
      if (!get_user_by_email($email)) {
        if ($user->email != $email) {

          $user->email = $email;
          if ($user->save()) {
            // system_message(elgg_echo('email:save:success'));
            return true;
          } else {
            // register_error(elgg_echo('email:save:fail'));
            throw new Exception('failed to save new email address.');

          }
        }
      } else {
        // register_error(elgg_echo('registration:dupeemail'));
        throw new Exception('same email as currently registered email.');
      }
    } else {
      // no change
      return null;
    }
  } else {
    // register_error(elgg_echo('email:save:fail'));
    throw new Exception('could not verify login.');
  }
  return false;
}

function get_profile_type() {

    $dbprefix = elgg_get_config('dbprefix');

    $profile_type = array();

    $results = elgg_get_entities(array(
        'type_subtype_pair'	=>	array('object' => 'custom_profile_type')
    ));

    foreach($results as $result) {

        $label = get_metadata_byname($result->guid, 'metadata_label')->value;

        $profile_type[] = array(
            id => $result->guid,
            label => $label
        );
    }

    return $profile_type;
}

function get_school_list() {

    //$dbprefix = elgg_get_config('dbprefix');

    $results = elgg_get_entities_from_metadata(array(
        "type_subtype_pair" => array('object' => 'custom_profile_field'),
        "metadata_value" => 'school'
    ));

    $result = $results[0];

    $label = get_metadata_byname($result->guid, 'metadata_options')->value;

    $school = explode(',', $label);

    return $school;
}

function search_site($searchTerms){

    $params = array(
    'query' => $searchTerms,
    'offset' => 0,
    'limit' => 100,
    'sort' => 'relevance',
    'order' => 'asc',
    'search_type' => 'all',
    'type' => ELGG_ENTITIES_ANY_VALUE,
    'subtype' => ELGG_ENTITIES_ANY_VALUE,
//  'tag_type' => $tag_type,
    'owner_guid' => ELGG_ENTITIES_ANY_VALUE,
    'container_guid' => ELGG_ENTITIES_ANY_VALUE,
//  'friends' => $friends
    'pagination' => FALSE
    );

    $types = get_registered_entity_types();
    $custom_types = elgg_trigger_plugin_hook('search_types', 'get_types', $params, array());



}
