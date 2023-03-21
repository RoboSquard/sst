<!-- REQUIRED SCRIPTS -->

<!-- jQuery 
<script src="https://ssur.uk/dash/src/plugins/jquery/jquery.min.js"></script>-->
<!-- Bootstrap 4 -->
<script src="https://venlocal.com/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://venlocal.com/plugins/dist/js/adminlte.min.js"></script>
<!-- Get Instagram Images -->
<script src="src/js/jquery.instagramFeed.min.js" ></script>
<!-- Advert -->
<script src="src/js/advert.js" type="text/javascript" ></script>

<script>
$('select[name=ads]').change(function() {
  $('#your_input').val($(this).val());
});

$("#your_input").on('input', function(key) {
  var value = $(this).val();
  $(this).val(value.replace(/ /g, '-'));
})
</script>

<script type="text/javascript">
// Parse the URL parameter
function getParameterByName(name, url) {
  if (!url) url = window.location.href;
  name = name.replace(/[\[\]]/g, "\\$&");
  var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
	results = regex.exec(url);
  if (!results) return null;
  if (!results[2]) return '';
  return decodeURIComponent(results[2].replace(/\+/g, " "));
}
// Give the parameter a variable name
var dynamicContent = getParameterByName('dc');

$(document).ready(function() {

  // Check if the URL parameter is success
  if (dynamicContent == 'success') {
	$('#success').show();
  }
  // Check if the URL parmeter is empty or not defined, display default content
  else {
	$('#default-content').show();
  }
});
</script>

<script>

function copyFeedUrl(self) {
  const urlInput = document.getElementById('feedUrl');
  navigator.clipboard.writeText(urlInput.value);
  self.textContent = "Copied!";
  setTimeout(() => {
	self.textContent = "Copy";
  }, 3000);
}

// Check for updates
setInterval(() => {
  const postEls = document.querySelectorAll('li.dcsns-li');
  let items = [];
  postEls.forEach((el) => {
	let item = {};
	item.title = el.querySelector('.section-title a').textContent;
	item.description = el.querySelector('.section-text').textContent;
	item.image_url = el.querySelector('.section-thumb img') ?
	  el.querySelector('.section-thumb img').src :
	  '';
	item.pubDate = el.querySelector('.section-title a').dataset.date;
	item.link = el.querySelector('.section-title a').href;
	items.push(item);
  });

  $.ajax({
	url: '<?php echo BASE_URL; ?>post.php?action=check_for_published_posts',
	type: 'POST',
	data: {
	  posts: items
	},
	success: function(res) {
	  res = JSON.parse(res);
	  if (res.status) {
		if (res.data.deletedPosts)
		  deletePosts(res.data.deletedPosts);
		if (res.data.newPosts)
		  insertPosts(res.data.newPosts);
	  }
	}
  })
}, 7000);

function insertPosts(posts) {
  posts.forEach((post) => {
	let newItem = $('<li class="dcsns-li dcsns-rss dcsns-feed-0" data-index="0">' +
	  '<div class="inner">' +
	  '<span class="section-thumb">' +
	  '<a data-image_count="0" href="' + post.link + '">' +
	  '<img src="' + post.image_url + '" alt=""  crossorigin="anonymous"  style="opacity: 1; display: inline;">' +
	  '</a>' +
	  '</span>' +
	  '<span class="section-title">' +
	  '<a href="' + post.link + '" data-date="' + post.pubDate + '" data-id="undefined">' + post.title + '</a>' +
	  '</span>' +
	  '<span class="section-text">' + post.description + '</span>' +
	  '<span class="clear"></span>' +
	  '</div>' +
	  '</li>');
	window.stream.prepend(newItem).isotope('prepended', newItem);
	$('li.dcsns-rss .section-thumb img', window.stream).css('opacity', 0).show().fadeTo(800, 1);
	$('img', window.stream).on('load', function() {
	  window.stream.isotope('layout');
	});
	updateIndexes();
  });
}

function deletePosts(posts) {
  posts.forEach((index) => {
	const el = document.querySelector('li.dcsns-li[data-index="' + index + '"]');
	window.stream.isotope('remove', el).isotope('layout');
	setTimeout(() => {
	  updateIndexes();
	}, 1000);
  });
}

// Update post indexes
function updateIndexes() {
  const items = document.querySelectorAll('li.dcsns-li');
  items.forEach((item, index) => {
	item.dataset.index = index;
	let deleteBtn = item.querySelector('.fa-trash');
	deleteBtn.setAttribute('onclick', 'deletePost(this, ' + index + ')');
  });
}
</script>

<script type="text/javascript" src="src/js/autoscroll.js"></script>
<script type="text/javascript">
  $(".id-2").scroller();
</script>
