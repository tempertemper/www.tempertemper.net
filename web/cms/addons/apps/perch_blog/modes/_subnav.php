<?php

	$Comments = new PerchBlog_Comments($API);
	$pending_comment_count =$Comments->get_count('PENDING');

	PerchUI::set_subnav([
			[
				'page' => [
						'perch_blog',
						'perch_blog/delete',
						'perch_blog/edit',
						'perch_blog/meta'
				], 
				'label' => 'Posts'
			],
			[
				'page' => [
						'perch_blog/comments',
						'perch_blog/comments/edit'
				], 
				'label' => 'Comments', 
				'badge' => $pending_comment_count, 
				'priv'  => 'perch_blog.comments.moderate'
			],
			[
				'page' => [
						'perch_blog/authors',
						'perch_blog/authors/edit',
						'perch_blog/authors/delete'
				], 
				'label' => 'Authors', 
				'priv'  => 'perch_blog.authors.manage'
			],
			[
				'page' => [
						'perch_blog/sections',
						'perch_blog/sections/edit',
						'perch_blog/sections/delete'
				], 
				'label' => 'Sections', 
				'priv'   => 'perch_blog.sections.manage'
			],
			[
				'page' => [
						'perch_blog/blogs',
						'perch_blog/blogs/edit',
						'perch_blog/blogs/delete'
				], 
				'label'  => 'Blogs', 
				'priv'   => 'perch_blog.blogs.manage', 
				'runway' => true
			],
	], $CurrentUser);