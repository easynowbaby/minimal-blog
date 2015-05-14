var $background = $('.bg-img');
var firstImage = $background.css('background-image');
var numberOfPictures;
if ($('.bg-img').length) {
  numberOfPictures = Object.keys($('.bg-img').data()).length + 1; 
}
var bodyHeight = getDocHeight();
var trigger = bodyHeight / numberOfPictures;


// /* Parallax effects */
$(window).scroll(function(e){
  scrollBanner();
});

// Calcualte the parallax scrolling
function scrollBanner() {

	var scrollPos = jQuery(this).scrollTop() + $(window).height() * .85;

  for (var i = 0; i <= numberOfPictures; i++) {
    if (scrollPos > trigger * (i) && scrollPos < trigger * (i + 1)) {
      var newImage = $background.data('img' + i);
      if (newImage) {
        $background.css('background-image', 'url(' + newImage + ')');
      } else {
        $background.css('background-image', firstImage);
      }
    }
  }

}

function getDocHeight(){
    return Math.max(
        $(document).height(),
        $(window).height(),
        /* For opera: */
        document.documentElement.clientHeight
    );
}


