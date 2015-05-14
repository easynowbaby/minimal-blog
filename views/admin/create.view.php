<!doctype html>
<html lang="cs">
<head>
	<title>Blog</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- CSS -->	
	<link rel="stylesheet" href="../assets/css/normalize.css">
	<link rel="stylesheet" href="../assets/css/main.css">

</head>
<body class="admin">	
	
	<main>

		<h1>Create A New Post</h1>

		<form action="" method="post" class="edit">

			<h2>...or select an old post to edit it</h2>
			<select name="edit" id="edit"></select>
			<input id="edit-post" type="submit" value="Edit post">

		</form>	

		<form class="create" action="" method="post" enctype='multipart/form-data'>

			<ul class="inputs">
				<li>
					<label for="title">Title: </label> <br>
					<input name="title" id="title" type="text" value="">
				</li>

				<li>
					<label for="author">Author: </label><br>
					<input type="text" name="author" id="author">
				</li>

				<li>
					<label for="category">Category: </label><br>
					<input type="text" name="category" id="category">
				</li>

				<li>
					<label for="body">Body: </label><br>
					<textarea name="body" id="body"></textarea>
				</li>
			</ul>

			<?php if ( isset($status) ) : ?>
				<p id="status"><?= $status; ?></p>
			<?php endif; ?>

			<ul class="buttons">				
				<li id="create-post-li">
					<input id="create-post" type="submit" value="Save post">
				</li>
			</ul>

			<ul class="uploads">
				<li>
					<label for="pic">Upload background picture(s):</label><br>
					<input type="hidden" name="MAX_FILE_SIZE" value="2000000">
					<input type="file" name="pic[]" id="pic" accept="image/*" multiple />
					<!-- <input type="file" name="images" id="images" multiple /> -->
      				<button type="submit" id="upload-picture">Upload Files!</button>
      				<ul id="image-list"></ul>
				</li>				
				
			</ul>

		</form>

	</main>

<script src="https://code.jquery.com/jquery-1.11.2.min.js"></script>
<script src='../assets/js/admin.js'></script>
</body>

</html>