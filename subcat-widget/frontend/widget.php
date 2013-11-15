<?php
	$params['exclude'] = array_slice($params['exclude'], 1);

	wp_list_categories(array(
		'hide_empty' => $params['hide_empty'],
		'show_count' => $params['show_counts'],
		'depth' => $params['depth'],
		'hierarchical' => 1,
		'child_of' => $params['category_id'],
		'exclude' => $params['exclude'],
		'title_li' => '',
	));