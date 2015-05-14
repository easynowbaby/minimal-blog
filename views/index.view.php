<div class="homepage">

	<aside>
		<div class="info">
			<h1><a href="<?php echo ROOT ?>">Blog</a></h1>
			<h2>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iusto id asperiores rerum expedita dolores aliquid.<h2>
		</div>
	</aside>

	<main id="content">

		<?php 

			if (isset($category)) {
				echo '<h2 class="category">Články v kategorii ' . $category . '</h2>';
			}

		?>	

		<?php foreach ($posts as $post) : ?> 

			<?php  
				$arr = $post->to_array();				
			?>	
			
			<article>
				<a href="single.php?id=<?php echo $arr['id'] ?>"><h3><?php echo $arr['name']; ?></h3></a>
				<span><?php  echo date('j. n. Y', strtotime($arr['date'])); ?></span>
				<div class="body"><p><?php echo htmlspecialchars_decode(getExcerpt($arr['text'])); ?></p></div>
				<h4>V kategorii <a href="index.php?cat=<?php echo $arr['category'] ?>"><?php echo $arr['category']; ?></a></h4>
				<hr>		
			</article>

		<?php endforeach; ?>

	</main>

</div>




