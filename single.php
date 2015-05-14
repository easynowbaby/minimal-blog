<?php 

require("require.php");

if ($_GET['id']) {		
	$post = Article::find_by_id((int)$_GET['id']);
	if ($post) {
		view('single', ['post' => $post]);
	}
	else {
		//redirect home
		header('location:/');
	}
}
else {
	//redirect home
	header('location:/');
}

