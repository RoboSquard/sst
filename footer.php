<!--=================================================================== REQUIRED SCRIPTS ===================================================================-->

<!------------------------------ PLUGINS ------------------------------>

<!-- jQuery UI 1.11.4 -->
<script src="src/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>

<!-- Bootstrap 4 -->
<script src="src/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Bootstrap color picker -->
<script src="src/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>

<!-- OverlayScrollbars -->
<script src="src/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>

<!-- Select2 -->
<script src="src/plugins/select2/js/select2.full.min.js"></script>

<!-- DataTables -->
<script src="src/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="src/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="src/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="src/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

<!-- Summernote -->
<script src="src/plugins/summernote/summernote-bs4.min.js"></script>
<script src="src/plugins/sweet-alert/sweetalert2.min.js"></script>

<!------------------------------ ADMIN UI ------------------------------>

<!-- AdminLTE App -->
<script src="src/dist/js/adminlte.min.js"></script>

<!-- AdminLTE dashboard demo --
<script src="src/dist/js/pages/dashboard.js"></script> <!-- FOR DRAGGABLE PANELS BUT SEARCH WILL STOP WORKING -->

<!------------------------------ SETTINGS ------------------------------>

<script src="src/js/jquery.instagramFeed.min.js" ></script>
<script src="src/js/script.js?version=28"></script>
<script src="src/js/secret.js"></script> 
<script type="text/javascript" src="src/js/advert.js"></script>

<!------------------------------ CHAT GPT ------------------------------>
<!--<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js'></script>-->
<script src="gpt/action.js"></script>
<script  src="gpt/ideas/script.js"></script>

<!------------------------------ STEPS ------------------------------>

<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js"></script>
<script src="https://suite.social/src/js/simpleform.min.js" type="text/javascript" ></script>

<script type="text/javascript">
$(".search_form").simpleform({
	speed : 500,
	transition : 'fade',
	progressBar : true,
	showProgressText : true,
	validate: true
});

$("#testform2").simpleform({
	speed : 500,
	transition : 'slide',
	progressBar : true,
	showProgressText : true,
	validate: true
});

$("#testform3").simpleform({
	speed : 500,
	transition : 'none',
	progressBar : true,
	showProgressText : true,
	validate: false
});

function validateForm(formID, Obj){

switch(formID){
	case 'search_form' :
		Obj.validate({
			rules: {
				product: {
					required: true
				},
				offer: {
					required: true
				}
			},
			messages: {
				product: {
					required: "Enter valid product url (this is where people purchase or download)."
				},
				offer: {
					required: "Enter promotion offer, e.g. coupon code with purchase link etc (users will see this after they share on social media)."
				}
			}
			});
	return Obj.valid();
	break;

	case 'testform2' :
		Obj.validate({
			rules: {
				email: {
					required: true,
					email: true
				},
				name: {
					required: true
				},
				spouse_email: {
					required: true,
					email: true
				},
				spouse_name: {
					required: true
				},
				street: {
					required: true
				}
			},
			messages: {
				email: {
					required: "Please enter an email address",
					email: "Not a valid email address"
				},
				name: {
					required: "Please enter your name"
				},
				spouse_email: {
					required: "Please enter an email address",
					email: "Not a valid email address"
				},
				spouse_name: {
					required: "Please enter your spouses name"
				},
				street: {
					required: "Please enter street name"
				}
			}
		});
	return Obj.valid();
	break;
	}
}
</script>

<script>
	
$('select[name=ads]').change(function(){
    $('#your_input').val($(this).val());
});

$("#your_input").on('input', function(key) {
  var value = $(this).val();
  $(this).val(value.replace(/ /g, '-'));
})

$(".your_input").on('input', function(key) {
  var value = $(this).val();
  $(this).val(value.replace(/ /g, '-'));
})

	
$("#r11").on("click", function(){
  $(this).parent().find("a").trigger("click")
})

$("#r12").on("click", function(){
  $(this).parent().find("a").trigger("click")
})

$("#r13").on("click", function(){
  $(this).parent().find("a").trigger("click")
})

$("#r14").on("click", function(){
  $(this).parent().find("a").trigger("click")
})

$("#r15").on("click", function(){
  $(this).parent().find("a").trigger("click")
})

$("#r16").on("click", function(){
  $(this).parent().find("a").trigger("click")
})

</script>

<!------------------------------ SCRIPTS ------------------------------>

<!-- //////////////////////////////////////////////// -->
<!-- REQUIRED JAVASCRIPT -->
<script type="text/javascript">
document.addEventListener("DOMContentLoaded", function(event) {
	
	// no autoplay - no extra class - no prev/next commands
	lc_gif_player('.target_1', false, '', ['move']);
	
	// autoplay - no extra class - no fullscreen command
	lc_gif_player('.target_2', true, '', ['fullscreen']);
	
});
</script>
<!-- //////////////////////////////////////////////// -->

<script type="text/javascript">
// Facebook
$(function(){
    $('#1').click(function(){ 
        if(!$('#1iframe').length) {
                $('#iframe1').html('<iframe id="1iframe" src="https://suite.social/tools/facebook/index.html" width="100%" height="450" frameborder="0" scrolling="no" allowfullscreen data-lazyembed></iframe>');
        }
    });   
});

// Twitter
$(function(){
    $('#2').click(function(){ 
        if(!$('#2iframe').length) {
                $('#iframe2').html('<iframe id="2iframe" src="https://suite.social/tools/twitter/index.html" width="100%" height="450" frameborder="0" scrolling="no" allowfullscreen data-lazyembed></iframe>');
        }
    });  	
});

// Whatsapp
$(function(){
    $('#3').click(function(){ 
        if(!$('#3iframe').length) {
                $('#iframe3').html('<iframe id="3iframe" src="https://suite.social/tools/whatsapp/index.html" width="100%" height="450" frameborder="0" scrolling="no" allowfullscreen data-lazyembed></iframe>');
        }
    });  	
});

// Youtube
$(function(){
    $('#4').click(function(){ 
        if(!$('#4iframe').length) {
                $('#iframe4').html('<iframe id="4iframe" src="https://suite.social/tools/youtube/index.html" width="100%" height="450" frameborder="0" scrolling="no" allowfullscreen data-lazyembed></iframe>');
        }
    });  	
});

// Instagram Training
$(function(){
    $('#5').click(function(){ 
        if(!$('#5iframe').length) {
                $('#iframe5').html('<iframe id="5iframe" src="https://suite.social/training/instagram.htm" width="100%" height="500" frameborder="0" scrolling="no" allowfullscreen data-lazyembed></iframe>');
        }
    });   
});

// Linkedin Training
$(function(){
    $('#6').click(function(){ 
        if(!$('#6iframe').length) {
                $('#iframe6').html('<iframe id="6iframe" src="https://suite.social/training/linkedin.htm" width="100%" height="500" frameborder="0" scrolling="no" allowfullscreen data-lazyembed></iframe>');
        }
    });  	
});

// Pinterest Training
$(function(){
    $('#7').click(function(){ 
        if(!$('#7iframe').length) {
                $('#iframe7').html('<iframe id="7iframe" src="https://suite.social/training/pinterest.htm" width="100%" height="500" frameborder="0" scrolling="no" allowfullscreen data-lazyembed></iframe>');
        }
    });  	
});

// Tiktok Training
$(function(){
    $('#8').click(function(){ 
        if(!$('#8iframe').length) {
                $('#iframe8').html('<iframe id="8iframe" src="https://suite.social/training/tiktok.htm" width="100%" height="500" frameborder="0" scrolling="no" allowfullscreen data-lazyembed></iframe>');
        }
    });  	
});

// Twitter Training
$(function(){
    $('#9').click(function(){ 
        if(!$('#9iframe').length) {
                $('#iframe9').html('<iframe id="9iframe" src="https://suite.social/training/twitter.htm" width="100%" height="500" frameborder="0" scrolling="no" allowfullscreen data-lazyembed></iframe>');
        }
    });  	
});

// ##### MANAGEMENT ##### //

// How to add accounts?
$(function(){
    $('#1m').click(function(){ 
        if(!$('#1miframe').length) {
                $('#iframe1m').html('<iframe id="1miframe" src="https://suite.social/tools/steps/account.html" width="100%" height="600" frameborder="0" scrolling="no" allowfullscreen data-lazyembed></iframe>');
        }
    });  	
});

// How to publish post?
$(function(){
    $('#2m').click(function(){ 
        if(!$('#2miframe').length) {
                $('#iframe2m').html('<iframe id="2miframe" src="https://suite.social/tools/steps/post.html" width="100%" height="600" frameborder="0" scrolling="no" allowfullscreen data-lazyembed></iframe>');
        }
    });  	
});

// How to schedule posts?
$(function(){
    $('#3m').click(function(){ 
        if(!$('#3miframe').length) {
                $('#iframe3m').html('<iframe id="3miframe" src="https://suite.social/tools/steps/schedule.html" width="100%" height="600" frameborder="0" scrolling="no" allowfullscreen data-lazyembed></iframe>');
        }
    });  	
});

// How to plan posts?
$(function(){
    $('#4m').click(function(){ 
        if(!$('#4miframe').length) {
                $('#iframe4m').html('<iframe id="4miframe" src="https://suite.social/tools/steps/plan.html" width="100%" height="600" frameborder="0" scrolling="no" allowfullscreen data-lazyembed></iframe>');
        }
    });  	
});

// Guide
$(function(){
    $('#1g').click(function(){ 
        if(!$('#1giframe').length) {
                $('#iframe1g').html('<iframe id="1giframe" src="https://suite.social/tools/steps/dash.html" width="100%" height="700" frameborder="0" scrolling="auto" allowfullscreen data-lazyembed></iframe>');
        }
    });  	
});

// Guide
$(function(){
    $('#1ge').click(function(){ 
        if(!$('#1geiframe').length) {
                $('#iframe1ge').html('<iframe id="1geiframe" src="https://suite.social/tools/steps/dash.html" width="100%" height="700" frameborder="0" scrolling="auto" allowfullscreen data-lazyembed></iframe>');
        }
    });  	
});

// Leads
$(function(){
    $('#1l').click(function(){ 
        if(!$('#1liframe').length) {
                $('#iframe1l').html('<iframe id="1liframe" src="https://suite.social/tools/steps/leads.html" width="100%" height="700" frameborder="0" scrolling="auto" allowfullscreen data-lazyembed></iframe>');
        }
    });  	
});

// Strategy
$(function(){
    $('#1s').click(function(){ 
        if(!$('#1siframe').length) {
                $('#iframe1s').html('<iframe id="1siframe" src="https://suite.social/tools/steps/strategy.html" width="100%" height="700" frameborder="0" scrolling="auto" allowfullscreen data-lazyembed></iframe>');
        }
    });  	
});

</script>

<script>
	
// OPEN MODAL ON LINK > https://suite.social/d/#manage
	
$(document).ready(function() {

  if(window.location.href.indexOf('#manage') != -1) {
    $('#manage').modal('show');
  }

});

$(document).ready(function() {

  if(window.location.href.indexOf('#envato') != -1) {
    $('#envato').modal('show');
  }

});

$(document).ready(function() {

  if(window.location.href.indexOf('#producthunt') != -1) {
    $('#producthunt').modal('show');
  }

});

$(document).ready(function() {

  if(window.location.href.indexOf('#freelancer') != -1) {
    $('#freelancer').modal('show');
  }

});
	
</script>

<script>

function popup(url)
{
var w = 1024;
var h = 800;
var title = 'Social';
var left = (screen.width / 2) - (w / 2);
var top = (screen.height / 2) - (h / 2);
window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
}

$("#your_input").on('input', function(key) {
  var value = $(this).val();
  $(this).val(value.replace(/ /g, '-'));
})

$(function () {
  // Summernote
  $('.textarea').summernote()
  $('#advert').summernote('code', '')
  $('#banner').summernote('code', '')
})

var page = 'index';
var ipaddress = '';
var projectId = 0;
$(function () {
  //Initialize Select2 Elements
  $('.select2').select2()

  //Initialize Select2 Elements
  $('.select2bs4').select2({
	theme: 'bootstrap4'
  })
})

</script>

<script>

//Colorpicker
$('.my-colorpicker1').colorpicker()
//color picker with addon
$('.my-colorpicker2').colorpicker()

$('.my-colorpicker2').on('colorpickerChange', function(event) {
  $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
})

$("#your_input").on('input', function(key) {
  var value = $(this).val();
  $(this).val(value.replace(/ /g, '-'));
})

</script>

<script>
	
$(function () {
  $("#example1").DataTable({
     "responsive": true, "lengthChange": false, "autoWidth": false,
     "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
  //}).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
  $('#example2').DataTable({
     "paging": true,
     "lengthChange": false,
     "searching": false,
     "ordering": true,
     "info": true,
     "autoWidth": false,
     "responsive": true,
});

// STREAM

$(document).on('click', '.section-thumb a', function (e) {
	e.preventDefault();
	itemCount = $(this).data('image_count');
	$('.title-'+itemCount+' a' ).click()
	return false;
});

$(document).on('click', '.section-title a', function (e) {
	e.preventDefault();
	let streamName = $('.pName').val();
	let postLink   = $(this).attr('href'); 
	let postTitle  = $(this).text();

	let postId = funhash(postLink+streamName);
	});

	return false;
});

$(document).on('click', '.section-share a', function (e) {
	e.preventDefault();
	let streamName  = $('.pName').val();
	let className   = $(this).attr('class');
	let postLink    = ''; 
	let postTitle   = '';
	let sharelink   = ''; 
	let newShareUrl = ''; 
	let shareUrl    = new URL($(this).attr('href'));
	const params    = new URLSearchParams(shareUrl.search);  
	

	switch(className)
	{
	case 'share-tumblr':
		postTitle   = params.get('b');
		postId      = funhash(postLink+streamName);
		sharelink   = url+"share/content.php?b=";
		newShareUrl = "share/content.php?b="+encodeURIComponent(postTitle);
	break;
	case 'share-whatsapp':
		postLink    = params.get('text');
		postId      = funhash(postLink+streamName);
		sharelink   = url+"/link/share.php?id=";
		newShareUrl = "https://api.whatsapp.com/send?text="+encodeURIComponent(sharelink);
		break;					
}


	$.ajax({
		url: 'saveShare.php' ,
		method: 'post' ,
		data: {
			action    : 'saveShare' ,
			streamName: streamName,
			postLink  : postLink,
			postTitle : postTitle,
			postId    : postId
		} ,
		success: function (res) {
			var res = jQuery.parseJSON(res);
			if (res.status == 'success') {
				openPopupWindow(newShareUrl);
				return false;
			}
			else if(res.status == 'error'){
				Swal.fire(
					'Post Share Failed!',
					'Unable to Share the post!',
					'error'
				);
			}
		} ,
		error: function (e) {
			console.log(e);
			return false;
		}
	});

	return false;
});

function openPopupWindow(linkToOpen){
	var w = 800;
	var h = 600;
	var title = 'Social login';
	var left = (screen.width / 2) - (w / 2);
	var top = (screen.height / 2) - (h / 2);
	window.open(linkToOpen, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
	return false;
} 

var funhash = function(s) {
	for(var i = 0, h = 0xdeadbeef; i < s.length; i++)
		h = Math.imul(h ^ s.charCodeAt(i), 2654435761);
	return (h ^ h >>> 16) >>> 0;
};
</script>
