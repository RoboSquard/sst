jQuery(document).ready(function($) {

  $('#social-account').find('[type=submit]').on('click', function(e) {
    e.preventDefault();

    var keyword = $(this).parents('form').find('[type=text]').val();
    var type = $(this).parents('form').find('[type=text]').attr('data-type');
    var feedUrls = getFeedUrl(keyword);

    var feedUrl = feedUrls[type];

    $('.stream-container div').remove();
    $('.stream-container').append('<div id="id-'+parseInt(Math.random()*1000)+'"><span class="loader"></span></div>');
    $('html,body').animate({
            scrollTop: $('.stream-container').offset().top
        }, 1000);

    setTimeout(function() {
      $('.loader').slideUp(500).remove();
    }, 10000);
    
    $('.stream-container div').dcSocialStream({
      feeds: {
        rss: {
          id: feedUrl,
          out: 'intro,thumb,title'
          //url: 'rss.php'
        }
      },
      rotate: {
        delay: 0
      },
      style: {
        layout: 'modern',
        colour: 'dark'
      },
      control: false,
      filter: false,
      wall: true,
      center: true,
      cache: true,
      max: 'limit',
      limit: 100,
      iconPath: 'images/dcsns-dark/',
      imagePath: 'images/dcsns-dark/'
    });
    console.log(feedUrl);

  })
});

function getFeedUrl(keyword)
  {   
    keyword = keyword.toLowerCase();
    return {
      blogger:'https://'+keyword+'.blogspot.com/rss.xml',
      deviantart:'https://backend.deviantart.com/rss.xml?q=boost%3Apopular+'+keyword,
      facebook:'https://ssur.uk/dashboard/rss/?action=display&bridge=Facebook&u='+keyword+'&media_type=all&limit=-1&format=Mrss',
      flickr:'https://api.flickr.com/services/feeds/photos_public.gne?tags='+keyword,
      instagram:'https://ssur.uk/dashboard/rss/?action=display&bridge=Instagram&u='+keyword+'&media_type=all&format=Mrss',	  
	  instagram2:'https://ssur.uk/dashboard/rss/?action=display&bridge=Instagram&h='+keyword+'&media_type=all&format=Mrss',
	  pinterest:'https://pinterest.com/'+keyword+'/feed.rss',
      tumblr:'https://'+keyword+'.tumblr.com/rss',
      youtube:'https://www.youtube.com/feeds/videos.xml?user='+keyword
    };
      
      // Blogger
      $( "#br_buttons" ).html('<input type="text" class="form-control input-lg" value="" placeholder="Copy RSS Feed URL..."/>');

      // DeviantArt Gallery
      var dagname=$('#dagname').val();
      $( "#dag_buttons" ).html('<input type="text" class="form-control input-lg" value="https://backend.deviantart.com/rss.xml?q=gallery%3A'+dagname.toLowerCase()+'"placeholder="Copy RSS Feed URL..."/>');

      // DeviantArt Search
      var dasname=$('#dasname').val();
      $( "#das_buttons" ).html('<input type="text" class="form-control input-lg" value="https://backend.deviantart.com/rss.xml?q=boost%3Apopular+'+dasname.toLowerCase()+'"placeholder="Copy RSS Feed URL..."/>');

      // Etsy
      var eyname=$('#eyname').val();
      $( "#ey_buttons" ).html('<input type="text" class="form-control input-lg" value="https://www.etsy.com/shop/'+eyname.toLowerCase()+'/rss" placeholder="Copy RSS Feed URL..."/>');

      // Facebook
      var fbname=$('#fbname').val();
      $( "#fb_buttons" ).html('<input type="text" class="form-control input-lg" value="https://ssur.uk/dashboard/rss/?action=display&bridge=Facebook&u='+fbname.toLowerCase()+'&media_type=all&limit=-1&format=Mrss" placeholder="Copy RSS Feed URL..."/>');

      // Flickr
      var frname=$('#frname').val();
      $( "#fr_buttons" ).html('<input type="text" class="form-control input-lg" value="https://api.flickr.com/services/feeds/photos_public.gne?tags='+frname.toLowerCase()+'" placeholder="Copy RSS Feed URL..."/>');
              
      // Google News
      var gnname=$('#gnname').val();
      $( "#gn_buttons" ).html('<input type="text" class="form-control input-lg" value="https://news.google.com/news/rss/search/section/q/'+gnname.toLowerCase()+'/'+gnname.toLowerCase()+'?hl=en&gl=GB&ned=us" placeholder="Copy RSS Feed URL..."/>');
      
      //Instagram Tag
      var imname=$('#imname').val();
      $( "#im_buttons" ).html('<input type="text" class="form-control input-lg" value="https://ssur.uk/dashboard/rss/?action=display&bridge=Instagram&h='+imname.toLowerCase()+'&media_type=all&format=Mrss" placeholder="Copy RSS Feed URL..."/>');

      //Instagram User
      var imuname=$('#imuname').val();
      $( "#imu_buttons" ).html('<input type="text" class="form-control input-lg" value="https://ssur.uk/dashboard/rss/?action=display&bridge=Instagram&u='+imuname.toLowerCase()+'&media_type=all&format=Mrss" placeholder="Copy RSS Feed URL..."/>');

      // Medium
      var mmname=$('#mmname').val();
      $( "#mm_buttons" ).html('<input type="text" class="form-control input-lg" value="https://medium.com/feed/@'+mmname.toLowerCase()+'" placeholder="Copy RSS Feed URL..."/>');

      // Pinterest
      var ptname=$('#ptname').val();
      $( "#pt_buttons" ).html('<input type="text" class="form-control input-lg" value="https://pinterest.com/'+ptname.toLowerCase()+'/feed.rss" placeholder="Copy RSS Feed URL..."/>');

      // Reddit
      var rtname=$('#rtname').val();
      $( "#rt_buttons" ).html('<input type="text" class="form-control input-lg" value="https://www.reddit.com/r/'+rtname.toLowerCase()+'.json" placeholder="Copy RSS Feed URL..."/>');

      // Tumblr
      var trname=$('#trname').val();
      $( "#tr_buttons" ).html('<input type="text" class="form-control input-lg" value="https://'+trname.toLowerCase()+'.tumblr.com/rss" placeholder="Copy RSS Feed URL..."/>');

      // Twitter Keyword
      var tkname=$('#tkname').val();
      $( "#tk_buttons" ).html('<input type="text" class="form-control input-lg" value="https://ssur.uk/dashboard/rss/?action=display&bridge=Twitter&context=By+keyword+or+hashtag&q='+tkname.toLowerCase()+'&format=Mrss" placeholder="Copy RSS Feed URL..."/>');

      // Twitter Tag
      var thname=$('#thname').val();
      $( "#th_buttons" ).html('<input type="text" class="form-control input-lg" value="https://ssur.uk/dashboard/rss/?action=display&bridge=Twitter&context=By+keyword+or+hashtag&q=%23'+thname.toLowerCase()+'&format=Mrss" placeholder="Copy RSS Feed URL..."/>');

      // Twitter User
      var tuname=$('#tuname').val();
      $( "#tu_buttons" ).html('<input type="text" class="form-control input-lg" value="https://ssur.uk/dashboard/rss/?action=display&bridge=Twitter&context=By+username&u='+tuname.toLowerCase()+'&format=Mrss" placeholder="Copy RSS Feed URL..."/>');
    
      // Vimeo Channel
      var vcname=$('#vcname').val();
      $( "#vc_buttons" ).html('<input type="text" class="form-control input-lg" value="https://vimeo.com/channels/'+vcname.toLowerCase()+'/videos/rss" placeholder="Copy RSS Feed URL..."/>');
      
      // Vimeo Likes
      var vlname=$('#vlname').val();
      $( "#vl_buttons" ).html('<input type="text" class="form-control input-lg" value="https://vimeo.com/'+vlname.toLowerCase()+'/likes/rss" placeholder="Copy RSS Feed URL..."/>');
  
      // Vimeo Search
      var vsname=$('#vsname').val();
      $( "#vs_buttons" ).html('<input type="text" class="form-control input-lg" value="https://vimeo.com/'+vsname.toLowerCase()+'/videos/rss" placeholder="Copy RSS Feed URL..."/>');

      // Wordpress
      var wsname=$('#wsname').val();
      $( "#ws_buttons" ).html('<input type="text" class="form-control input-lg" value="https://'+wsname.toLowerCase()+'.wordpress.com/feed/" placeholder="Copy RSS Feed URL..."/>');

      // YouTube Channel
      var ycname=$('#ycname').val();
      $( "#yc_buttons" ).html('<input type="text" class="form-control input-lg" value="https://ssur.uk/dashboard/rss/?action=display&bridge=Youtube&c='+ycname.toLowerCase()+'&duration_min=&duration_max=&format=Mrss" placeholder="Copy RSS Feed URL..."/>');
      
      // YouTube Playlist
      var ypname=$('#ypname').val();
      $( "#yp_buttons" ).html('<input type="text" class="form-control input-lg" value="https://ssur.uk/dashboard/rss/?action=display&bridge=Youtube&p='+ypname.toLowerCase()+'&duration_min=&duration_max=&format=Mrss" placeholder="Copy RSS Feed URL..."/>');

      // YouTube User 
      var yuname=$('#yuname').val();
      $( "#yu_buttons" ).html('<input type="text" class="form-control input-lg" value="https://www.youtube.com/feeds/videos.xml?user='+yuname.toLowerCase()+'" placeholder="Copy RSS Feed URL..."/>');      

      // YouTube Search
      var ysname=$('#ysname').val();
      $( "#ys_buttons" ).html('<input type="text" class="form-control input-lg" value="https://ssur.uk/dashboard/rss/?action=display&bridge=Youtube&s='+ysname.toLowerCase()+'&pa=5&duration_min=&duration_max=&format=Mrss" placeholder="Copy RSS Feed URL..."/>');     
    
    }
    
function getCookie(name) {
  var value = "; " + document.cookie;
  var parts = value.split("; " + name + "=");
  if (parts.length == 2) return parts.pop().split(";").shift();
}
    $(document).ready(function(){

        $('#feed_link').submit(function(e){
            var stream_url = $('#rss_link').val();
            document.cookie = "default_rss_feed="+stream_url;
            
        });

        $(document).on('click', '.stream input[type=text]', function() {
          $(this)[0].focus();
        })

    });