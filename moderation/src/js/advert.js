$(document).ready(function(){
	var $container1 = $('.stream').isotope();
	var $newItems = $('<li rel="1" class="dcsns-li dcsns-facebook dcsns-twitter dcsns-google dcsns-youtube dcsns-flickr dcsns-pinterest dcsns-rss dcsns-tumblr dcsns-instagram dcsns-dribbble dcsns-feed-1" style="position: absolute; left: 560px; top: 714px;"><div style="padding-top: 0px; margin-bottom:-5px" class="inner"><a href="https://venlocal.com/s/social"><img style="max-width: 100%; margin-bottom: 0px" src="https://venlocal.com/images/Banner_Social.jpg"></a></div></li>');
	$('.stream').isotope( 'insert', $newItems );
});	