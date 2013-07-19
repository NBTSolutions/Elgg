<?php
/**
 * news English language file.
 *
 */

$english = array(
	'news' => 'News',
	'news:blogs' => 'News',
	'news:revisions' => 'Revisions',
	'news:archives' => 'Archives',
	'news:news' => 'news',
	'item:object:news' => 'News',

	'news:title:user_blogs' => '%s\'s News',
	'news:title:all_blogs' => 'All site News',
	'news:title:friends' => 'Friends\' News',

	'news:group' => 'Group news',
	'news:enablenews' => 'Enable group news',
	'news:write' => 'Write a news post',
	
	//Admin
	'news:settings:layout:title' => 'Masonry Tile Layout',
	'news:settings:layout:no' => 'No',
	'news:settings:layout:yes' => 'Yes',
	'news:settings:layout:description' => 'Choose Between a normal list layout or a 2 column grid layout',
    'news:settings:layout:element_limit:label' => 'Maximum number of news objects displayed per page',
	'news:settings:layout:element_limit:description' => 'Choose the maximum number of new objects displayed per page',
	
	// Editing
	'news:add' => 'Add news post',
	'news:edit' => 'Edit news post',
	'news:excerpt' => 'Excerpt',
	'news:body' => 'Body',
	'news:save_status' => 'Last saved: ',
	'news:never' => 'Never',

	// Statuses
	'news:status' => 'Status',
	'news:status:draft' => 'Draft',
	'news:status:published' => 'Published',
	'news:status:unsaved_draft' => 'Unsaved Draft',

	'news:revision' => 'Revision',
	'news:auto_saved_revision' => 'Auto Saved Revision',

	// messages
	'news:message:saved' => 'news post saved.',
	'news:error:cannot_save' => 'Cannot save news post.',
	'news:error:cannot_write_to_container' => 'Insufficient access to save news to group.',
	'news:messages:warning:draft' => 'There is an unsaved draft of this post!',
	'news:edit_revision_notice' => '(Old version)',
	'news:message:deleted_post' => 'news post deleted.',
	'news:error:cannot_delete_post' => 'Cannot delete news post.',
	'news:none' => 'No news posts',
	'news:error:missing:title' => 'Please enter a news title!',
	'news:error:missing:description' => 'Please enter the body of your news!',
	'news:error:cannot_edit_post' => 'This post may not exist or you may not have permissions to edit it.',
	'news:error:revision_not_found' => 'Cannot find this revision.',

	// river
	'river:create:object:news' => '%s published a news post %s',
	'river:comment:object:news' => '%s commented on the news %s',

	// notifications
	'news:newpost' => 'A new news post',
	'news:notification' =>
'
%s published a news post.

%s
%s

View and comment on the news post:
%s
',

	// widget
	'news:widget:description' => 'Display your latest news posts',
	'news:moreNews' => 'More news posts',
	'news:numbertodisplay' => 'Number of news posts to display',
	'news:noNews' => 'No news posts'
);

add_translation('en', $english);
