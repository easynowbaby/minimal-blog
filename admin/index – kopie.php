<?php 

require "../require.php";

ActiveRecord\Config::initialize(function($cfg)
{
    $cfg->set_model_directory('../models');    
});

$data['status'] = '';

// submiting new article
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['edit']) && !isset($_POST['changes']) && !isset($_POST['delete']) && !isset($_POST['delete-picture'])) {

	$title = htmlspecialchars($_POST['title']);
	$body = htmlspecialchars($_POST['body']);
	$author = htmlspecialchars($_POST['author']);
	$category = htmlspecialchars($_POST['category']);
	$pics = []; 
	$picdir = rand(1, 1000) . rand(1, 1000);	
	$uploadurl = NULL;	

	if ($_FILES['pic']) {
		array_push($pics, $_FILES['pic']);
	}

	if (!empty($_FILES['slides']['type'][0])) {
		$slides = reArrayFiles($_FILES['slides']);
		foreach ($slides as $key => $value) {
			array_push($pics, $value);
		}
	}
    	

    if ( empty($title) || empty($body) || empty($author) || empty($category)) {
		$data['status'] = 'Please fill all text inputs.';
	} else {  // inputs are not empty

		// upload images if any
		if (isset($_FILES['pic'])) {

			foreach ($pics as $key => $img) {
				
				$arr = uploadImage($img, $picdir);

				if ($uploadurl) {
					$uploadurl .= ',' . $arr['uploadurl'];
				}
				else {
					$uploadurl = $arr['uploadurl'];
				}	

				$data['status'] = $arr['status'];			
			}
		}	

		// then create a new row in the table
		$attributes = array('name' => $title, 'text' => $body, 'author' => $author, 'pic_url' => $uploadurl, 'category' => $category);
		$post = Article::create($attributes);					

		echo 'Article was successfully saved.';
		$data['status'] .= 'Article was successfully saved.';
	}
}


// SAVE CHANGES THAT WERE MADE TO THE DB
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['changes'])) {

	$title = htmlspecialchars($_POST['changes']['name']);
	$body = htmlspecialchars($_POST['changes']['text']);
	$author = htmlspecialchars($_POST['changes']['author']);
	$category = htmlspecialchars($_POST['changes']['category']);
	$id = htmlspecialchars($_POST['changes']['id']);
	
	$post = Article::find($id);
	if ($post->update_attributes(array('name' => $title, 'text' => $body, 'author' => $author, 'category' => $category))) {
		echo "Article was succesfully updated!";
	}
	exit();
		
};

// DELETE ARTICLE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {

	$id = htmlspecialchars($_POST['delete']);	
	
	$post = Article::find($id);
    if ($post->delete()) {
     	echo "The article was succesfully deleted.";
    } 
	exit();		
};

// DELETE PICTURE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete-picture'])) {

	$url = $_POST['delete-picture'];
	$id = $_POST['id'];		

	$article = Article::find($id);
    if ($article->update_attributes(array('pic_url' => $url))) {
     	echo "The picture was succesfully deleted.";
    } 
	exit();		
};

// DISPLAYING DATA OF SELECTED ARTICLE TO THE FORM INPUTS
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit'])) {	

	$data['edit'] = filter_var($_POST["edit"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);		
	$article = Article::find_by_id($data['edit']);
	$arr = $article->to_array();	
	foreach ($arr as $key => $value) {
		$arr[$key] = htmlspecialchars_decode($value);	 	
	 };
	
	$json = json_encode($arr, JSON_UNESCAPED_UNICODE);
	echo $json;
	die();
};

//collecting all articles by id
$articleIds = [];
$allArticles = Article::find('all');

foreach ($allArticles as $article) {
	$arr = $article->to_array();	
	array_push($articleIds, [$arr['id'], $arr['name']]);	
}

$data['listOfArticles'] = array_reverse($articleIds);

viewAdmin([
	'status' => $data['status'],
	'listOfArticles' => $data['listOfArticles']
]);