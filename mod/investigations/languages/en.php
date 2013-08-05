<?php
/**
 * Elgg groups plugin language pack
 *
 * @package ElggGroups
 */

$english = array(

	/**
	 * Menu items and titles
	 */
	'investigation' => "Investigations",
	'investigations:owned' => "Investigation I own",
	'investigations:owned:user' => 'Investigations %s owns',
	'investigations:yours' => "My groups",
	'investigations:user' => "%s's groups",
	'investigations:all' => "All investigations",
	'investigate:add' => "<i class='icon-plus'></i> Create a new investigation",
	'investigations:edit' => "Edit investigation",
	'investigations:delete' => 'Delete Investigation',
	'investigations:membershiprequests' => 'Manage join requests',
	'investigations:membershiprequests:pending' => 'Manage join requests (%s)',
	'investigations:invitations' => 'Investigation invitations',
	'investigations:invitations:pending' => 'Investigation invitations (%s)',

	'investigations:icon' => 'Investigation icon (leave blank to leave unchanged)',
	'investigations:name' => 'Investigation name',
	'investigations:username' => 'Investigation short name (displayed in URLs, alphanumeric characters only)',
	'investigations:description' => 'Description',
	'investigations:briefdescription' => 'Brief description',
	'investigations:interests' => 'Tags',
	'investigations:website' => 'Website',
	'investigations:members' => 'Investigation members',
	'investigations:my_status' => 'My status',
	'investigations:my_status:group_owner' => 'You own this group',
	'investigations:my_status:group_member' => 'You are in this group',
	'investigations:subscribed' => 'Investigation notifications on',
	'investigations:unsubscribed' => 'Investigation notifications off',

	'investigations:members:title' => 'Members of %s',
	'investigations:members:more' => "View all members",
	'investigations:membership' => "Investigation membership permissions",
	'investigations:access' => "Access permissions",
	'investigations:owner' => "Owner",
	'investigations:owner:warning' => "Warning: if you change this value, you will no longer be the owner of this group.",
	'investigations:widget:num_display' => 'Number of groups to display',
	'investigations:widget:membership' => 'Investigation membership',
	'investigations:widgets:description' => 'Display the groups you are a member of on your profile',
	'investigations:noaccess' => 'No access to group',
	'investigations:permissions:error' => 'You do not have the permissions for this',
	'investigations:ingroup' => 'in the group',
	'investigations:cantcreate' => 'You can not create a group. Only admins can.',
	'investigations:cantedit' => 'You can not edit this group',
	'investigations:saved' => 'Investigation saved',
	'investigations:featured' => 'Featured groups',
	'investigations:makeunfeatured' => 'Unfeature',
	'investigations:makefeatured' => 'Make featured',
	'investigations:featuredon' => '%s is now a featured group.',
	'investigations:unfeatured' => '%s has been removed from the featured groups.',
	'investigations:featured_error' => 'Invalid group.',
	'investigations:joinrequest' => 'Request membership',
	'investigations:join' => 'Join group',
	'investigations:leave' => 'Leave group',
	'investigations:invite' => 'Invite friends',
	'investigations:invite:title' => 'Invite friends to this group',
	'investigations:inviteto' => "Invite friends to '%s'",
	'investigations:nofriends' => "You have no friends left who have not been invited to this group.",
	'investigations:nofriendsatall' => 'You have no friends to invite!',
	'investigations:viagroups' => "via groups",
	'investigations:group' => "Group",
	'investigations:search:tags' => "Search for Investigations",
	'investigations:search:title' => "Search for groups tagged with '%s'",
	'investigations:search:none' => "No matching groups were found",
	'investigations:search_in_group' => "Search this investigation",
	'investigations:acl' => "Investigation: %s",

	'investigation_discussion:notification:topic:subject' => 'New group discussion post',
	'investigations:notification' =>
	'%s added a new discussion topic to %s:

	%s
	%s

	View and reply to the investigation_discussion:
	%s
	',

	'investigation_discussion:notification:reply:body' =>
	'%s replied to the discussion topic %s in the group %s:

	%s

	View and reply to the investigation_discussion:
	%s
	',

	'investigations:activity' => "Investigation activity",
	'investigations:enableactivity' => 'Enable investigation activity',
	'investigations:activity:none' => "There is no group activity yet",

	'investigations:notfound' => "Investigation not found",
	'investigations:notfound:details' => "The requested group either does not exist or you do not have access to it",

	'investigations:requests:none' => 'There are no current membership requests.',

	'investigations:invitations:none' => 'There are no current invitations.',

	'item:object:investigationforumtopic' => "Discussion topics",
    'item:object:investigationforumtopic_text' => 'Text Discussion',
    'item:object:investigationforumtopic_image' => 'Image Discussion',
    'item:object:investigationforumtopic_map' => 'Map Discussion',
    'item:object:investigationforumtopic_graph' => 'Graph Discussion',
    'item:object:investigationforumtopic_video' => 'Video Discussion',

	'investigationforumtopic:new' => "Add discussion post",

	'investigations:count' => "groups created",
	'investigations:open' => "open investigation",
	'investigations:closed' => "closed group",
	'investigations:member' => "members",
	'investigations:searchtag' => "Search for groups by tag",

	'investigations:more' => 'More groups',
	'investigations:none' => 'No investigations',


	/*
	 * Access
	 */
	'investigations:access:private' => 'Closed - Users must be invited',
	'investigations:access:public' => 'Open - Any user may join',
	'investigations:access:group' => 'Group members only',
	'investigations:closedgroup' => 'This group has a closed membership.',
	'investigations:closedgroup:request' => 'To ask to be added, click the "request membership" menu link.',
	'investigations:visibility' => 'Who can see this group?',

	/*
	Group tools
	 */
	'investigations:enableforum' => 'Enable investigation discussion',
	'investigations:yes' => 'yes',
	'investigations:no' => 'no',
	'investigations:lastupdated' => 'Last updated %s by %s',
	'investigations:lastcomment' => 'Last comment %s by %s',

	/*
	Group discussion
	 */
	'discussion' => 'Discussion',
	'investigation_discussion:add' => 'Add discussion topic',
	'investigation_discussion:latest' => 'Latest discussion',
	'investigation_discussion:group' => 'Investigation discussion',
	'investigation_discussion:none' => 'No discussion',
	'investigation_discussion:none_yet' => "<p>Be the first to start a discussion about this investigation!</p>",
	'investigation_discussion:reply:title' => 'Reply by %s',

	'investigation_discussion:topic:created' => 'The discussion topic was created.',
	'investigation_discussion:topic:updated' => 'The discussion topic was updated.',
	'investigation_discussion:topic:deleted' => 'Discussion topic has been deleted.',

	'investigation_discussion:topic:notfound' => 'Discussion topic not found',
	'investigation_discussion:error:notsaved' => 'Unable to save this topic',
	'investigation_discussion:error:missing' => 'Both title and message are required fields',
	'investigation_discussion:error:permissions' => 'You do not have permissions to perform this action',
	'investigation_discussion:error:notdeleted' => 'Could not delete the discussion topic',

	'investigation_discussion:reply:deleted' => 'Discussion reply has been deleted.',
	'investigation_discussion:reply:error:notdeleted' => 'Could not delete the discussion reply',

	'reply:this' => 'Reply to this',

	'investigation:replies' => 'Replies',
	'investigations:forum:created' => 'Created %s with %d comments',
	'investigations:forum:created:single' => 'Created %s with %d reply',
	'investigations:forum' => 'Discussion',
	'investigations:addtopic' => 'Add a topic',
	'investigations:forumlatest' => 'Latest discussion',
	'investigations:latestdiscussion' => 'Latest discussion',
	'investigations:newest' => 'Newest',
	'investigations:popular' => 'Popular',
	'groupspost:success' => 'Your reply was succesfully posted',
	'groupspost:failure' => 'There was problem while posting your reply',
	'investigations:alldiscussion' => 'Latest discussion',
	'investigations:edittopic' => 'Edit topic',
	'investigations:topicmessage' => 'Topic message',
	'investigations:topicstatus' => 'Topic status',
	'investigations:reply' => 'Post a comment',
	'investigations:topic' => 'Topic',
	'investigations:posts' => 'Posts',
	'investigations:lastperson' => 'Last person',
	'investigations:when' => 'When',
	'grouptopic:notcreated' => 'No topics have been created.',
	'investigations:topicopen' => 'Open',
	'investigations:topicclosed' => 'Closed',
	'investigations:topicresolved' => 'Resolved',
	'grouptopic:created' => 'Your topic was created.',
	'groupstopic:deleted' => 'The topic has been deleted.',
	'investigations:topicsticky' => 'Sticky',
	'investigations:topicisclosed' => 'This discussion is closed.',
	'investigations:topiccloseddesc' => 'This discussion is closed and is not accepting new comments.',
	'grouptopic:error' => 'Your group topic could not be created. Please try again or contact a system administrator.',
	'investigations:forumpost:edited' => "You have successfully edited the forum post.",
	'investigations:forumpost:error' => "There was a problem editing the forum post.",


	'investigations:privategroup' => 'This group is closed. Requesting membership.',
	'investigations:notitle' => 'Groups must have a title',
	'investigations:cantjoin' => 'Can not join group',
	'investigations:cantleave' => 'Could not leave group',
	'investigations:removeuser' => 'Remove from group',
	'investigations:cantremove' => 'Cannot remove user from group',
	'investigations:removed' => 'Successfully removed %s from group',
	'investigations:addedtogroup' => 'Successfully added the user to the group',
	'investigations:joinrequestnotmade' => 'Could not request to join group',
	'investigations:joinrequestmade' => 'Requested to join group',
	'investigations:joined' => 'Successfully joined group!',
	'investigations:left' => 'Successfully left group',
	'investigations:notowner' => 'Sorry, you are not the owner of this group.',
	'investigations:notmember' => 'Sorry, you are not a member of this group.',
	'investigations:alreadymember' => 'You are already a member of this group!',
	'investigations:userinvited' => 'User has been invited.',
	'investigations:usernotinvited' => 'User could not be invited.',
	'investigations:useralreadyinvited' => 'User has already been invited',
	'investigations:invite:subject' => "%s you have been invited to join %s!",
	'investigations:updated' => "Last reply by %s %s",
	'investigations:started' => "Started by %s",
	'investigations:joinrequest:remove:check' => 'Are you sure you want to remove this join request?',
	'investigations:invite:remove:check' => 'Are you sure you want to remove this invitation?',
	'investigations:invite:body' => "Hi %s,

	%s invited you to join the '%s' group. Click below to view your invitations:

	%s",

	'investigations:welcome:subject' => "Welcome to the %s group!",
	'investigations:welcome:body' => "Hi %s!

	You are now a member of the '%s' group! Click below to begin posting!

	%s",

	'investigations:request:subject' => "%s has requested to join %s",
	'investigations:request:body' => "Hi %s,

	%s has requested to join the '%s' group. Click below to view their profile:

	%s

	or click below to view the group's join requests:

	%s",

	/*
		Forum river items
	 */

	'river:create:object:investigation' => '%s created the investigation %s',
	'river:join:group:default' => '%s joined the group %s',
	'river:create:object:investigationforumtopic' => '%s added a new discussion topic %s',
	'river:reply:object:investigationforumtopic' => '%s replied on the discussion topic %s',
	'river:create:object:observation' => "%s created an %s",
	'investigations:observation' => 'observation',
	'investigations:nowidgets' => 'No widgets have been defined for this group.',


	'investigations:widgets:members:title' => 'Group members',
	'investigations:widgets:members:description' => 'List the members of a group.',
	'investigations:widgets:members:label:displaynum' => 'List the members of a group.',
	'investigations:widgets:members:label:pleaseedit' => 'Please configure this widget.',

	'investigations:widgets:entities:title' => "Objects in group",
	'investigations:widgets:entities:description' => "List the objects saved in this group",
	'investigations:widgets:entities:label:displaynum' => 'List the objects of a group.',
	'investigations:widgets:entities:label:pleaseedit' => 'Please configure this widget.',

	'investigations:forumtopic:edited' => 'Forum topic successfully edited.',

	'investigations:allowhiddengroups' => 'Do you want to allow private (invisible) groups?',
	'investigations:whocancreate' => 'Who can create new groups?',

	/**
	 * Action messages
	 */
	'investigation:deleted' => 'Group and group contents deleted',
	'investigation:notdeleted' => 'Group could not be deleted',

	'investigation:notfound' => 'Could not find the group',
	'grouppost:deleted' => 'Group posting successfully deleted',
	'grouppost:notdeleted' => 'Group posting could not be deleted',
	'groupstopic:deleted' => 'Topic deleted',
	'groupstopic:notdeleted' => 'Topic not deleted',
	'grouptopic:blank' => 'No topic',
	'grouptopic:notfound' => 'Could not find the topic',
	'grouppost:nopost' => 'Empty post',
	'investigations:deletewarning' => "Are you sure you want to delete this group? There is no undo!",

	'investigations:invitekilled' => 'The invite has been deleted.',
	'investigations:joinrequestkilled' => 'The join request has been deleted.',

	// ecml
	'investigations:ecml:discussion' => 'Investigation Discussions',
	'investigations:ecml:groupprofile' => 'Investigation profiles',
);

add_translation("en", $english);
