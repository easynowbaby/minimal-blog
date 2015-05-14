<?php  
	$arr = $post->to_array();	
	$img = explode(',', $arr['pic_url']);
	$str = '';	
?>

<main id="container" class="single">

	<?php if(sizeof($img) === 0): ?>

		<div class="bg-img" style="background-image: url(assets/img/bg.jpg);"></div>

	<?php endif; ?>

	<?php

		if (sizeof($img) > 0) {

			$str = '';
			$data = '';

			foreach ($img as $key => $value) {
				if ($key == 0) {
					$str = '<div class="bg-img" style="background-image: url(' . $value . ');"';
				}
				else {
					$data .= 'data-img' . $key . '="' . $value . '" ';
				}				
			}

			echo $str . $data . '></div>';
		}		

	?>				

	<article class="content">
		<div class="title">
			<h1><?php echo $arr['name'] ?></h1>
			<span><?php  echo date('j. n. Y', strtotime($arr['date'])); ?></span>		
		</div>
		<div class="body"><p><?php echo nl2br(htmlspecialchars_decode($arr['text'])) ?></p></div>				
	</article>

</main>



