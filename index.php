<?php 

require ('require.php');

$records_per_page = 5;



if (isset($_GET['cat'])) {	

	//Fetch part of records using SQL LIMIT clause
	$posts = array_reverse(Article::find('all', array('conditions' => array('category = ?', $_GET['cat']))));

	 
	if ($posts) {
		view('index', [
			'posts' => $posts,			
			'category' => $_GET['cat']
		]);
	}
	else {
		echo "category is empty";
	}
}
else {		

	//Fetch part of records using SQL LIMIT clause
	$posts = array_reverse(Article::find("all"));     
	
	// Display them in the view
	view('index', [
		'posts' => $posts
	]);
}
