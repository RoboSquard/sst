<?php
$error_message = '';
$publish_url = '';
if(isset($_GET['id']) && !empty($_GET['id'])){
  $moderation = getWhere('moderations', ['moderation_id' => $_GET['id'], 'status' => 'enabled']);
  if($moderation){
    $url = ORIGIN_URL.$moderation['published_rss_path'];
    $publish_url = ORIGIN_URL.$moderation['published_rss_path'];
    session_start(); 
    $_SESSION['panel_id'] = $moderation['panel_id'];
    $_SESSION['moderation_id'] = $moderation['id'];
  }else{
    header('Location: ' . REDIRECTION_URL, true, $permanent ? 301 : 302);
    exit();
    $error_message = 'Page is temporary unavailable';
  }
 
}else{
  header('Location: ' . REDIRECTION_URL, true, $permanent ? 301 : 302);
  exit();
  $error_message = 'Page is temporary unavailable';
}
?>
<head>
<meta charset="utf-8">
<title>Venlocal - Social Screens</title>

<!-- Meta Data -->
<meta name="title" content="Venlocal - Social Screens">
<meta name="description" content="Turn your brand into a conversation and grow conversions with free Social Screen.">
<meta name="author" content="Venlocal | https://venlocal.com/">
<meta name="keywords" content="chat room, chat, chatroom, dating, Social Screens, earn, games, group chat, hire, icebreaker, jobs, live chat, local, messaging, money, music, payment, private chat, radio player, radio, rent, social network, trade, venues">
<meta name="robots" content="index, follow">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="language" content="English">
<meta name="revisit-after" content="7 days">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />

<!-- Google Plus -->
<!-- Update your html tag to include the itemscope and itemtype attributes. -->
<!-- html itemscope itemtype="//schema.org/{CONTENT_TYPE}" -->
<meta itemprop="name" content="Venlocal - Social Screens">
<meta itemprop="description" content="Turn your brand into a conversation and grow conversions with free Social Screen.">
<meta itemprop="image" content="https://venlocal.com/images/Banner_Screen.jpg">

<!-- Twitter -->
<meta name="twitter:card" content="Venlocal - Social Screens">
<meta name="twitter:site" content="@venlocal">
<meta name="twitter:title" content="Venlocal - Social Screens">
<meta name="twitter:description" content="Turn your brand into a conversation and grow conversions with free Social Screen.">
<meta name="twitter:creator" content="@venlocal">
<meta name="twitter:image:src" content="https://venlocal.com">
<meta name="twitter:player" content="">

<!-- Open Graph General (Facebook & Pinterest) -->
<meta property="og:url" content="https://venlocal.com">
<meta property="og:title" content="Venlocal - Social Screens">
<meta property="og:description" content="Turn your brand into a conversation and grow conversions with free Social Screen.">
<meta property="og:site_name" content="Venlocal">
<meta property="og:image" content="https://venlocal.com/images/Banner_Screen.jpg">
<meta property="og:type" content="product">
<meta property="og:locale" content="en_UK">

<!-- Open Graph Article (Facebook & Pinterest) -->
<meta property="article:section" content="Marketing">
<meta property="article:tag" content="Marketing">

<!-- Mobile Specific Meta -->
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<meta name="HandheldFriendly" content="true" />

<!-- Favicon -->
<link rel="shortcut icon" type="image/x-icon" href="https://venlocal.com/images/favicon/favicon.ico">
<link rel="icon" type="image/png" sizes="32x32" href="https://venlocal.com/images/favicon/icon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="https://venlocal.com/images/favicon/icon-16x16.png">
<link rel="apple-touch-icon" sizes="180x180" href="https://venlocal.com/images/favicon/apple-touch-icon-180x180.png">
<link rel="apple-touch-icon" sizes="72x72" href="https://venlocal.com/images/favicon/apple-touch-icon-72x72.png">
<link rel="apple-touch-icon" sizes="114x114" href="https://venlocal.com/images/favicon/apple-touch-icon-114x114.png">
<link rel="apple-touch-icon" sizes="144x144" href="https://venlocal.com/images/favicon/apple-touch-icon-144x144.png">
<link rel="apple-touch-icon" sizes="256x256" href="https://venlocal.com/images/favicon/apple-touch-icon-256x256.png" />
<meta name="msapplication-TileImage" content="https://venlocal.com/images/favicon/mstile-150x150.png">

<!-- Chrome for Android web app tags -->
<meta name="mobile-web-app-capable" content="yes" />
<link rel="shortcut icon" sizes="256x256" href="https://venlocal.com/images/favicon/apple-touch-icon-256x256.png" />

<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

<!-- Font Awesome -->
<link rel="stylesheet" href="src/plugins/fontawesome-free/css/all.min.css">

<!-- Theme style -->
<link rel="stylesheet" href="https://venlocal.com/plugins/dist/css/adminlte.min.css">

<!-- Social Buttons -->
<link type="text/css" rel="stylesheet" href="src/css/social-buttons.css" /> 

<!-- Social Wall -->
<link type="text/css" rel="stylesheet" href="src/css/dcsns_wall.css" media="all" />
<link type="text/css" rel="stylesheet" href="src/css/style_feed.css" />

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<!--<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>-->
<script type="text/javascript" src="src/js/jquery.social.stream.wall.js"></script>

<style>
	
/**************************************** CARD ****************************************/

.cards-wrapper {
  display: flex;
  justify-content: center;
}

.card img {
  max-width: 100%;
  max-height: 100%;
}

.card {
  box-shadow: 2px 6px 8px 0 rgba(22, 22, 26, 0.18);
  border: none;
  border-radius: 0;
  background-color:#fff;
}

/**************************************** HEADER ****************************************/

.pb-21 {
    padding-bottom: 8.5rem!important;
}

.mt-n22 {
    margin-top: -9rem!important;
}

.bg-primary {
    background-color: #9C27B0 !important;
    background-image: linear-gradient(-45deg, #9C27B0 0%, #03A9F4 100%) !important;
}

.container, .container-lg, .container-md, .container-sm, .container-xl {
    max-width: 100%;
}

/**************************************** CAROUSEL ****************************************/

@media (max-width: 768px) {
    .carousel-inner .carousel-item > div {
        display: none;
    }
    .carousel-inner .carousel-item > div:first-child {
        display: block;
    }
}

.carousel-inner .carousel-item.active,
.carousel-inner .carousel-item-next,
.carousel-inner .carousel-item-prev {
    display: flex;
}

/* display 3 */
@media (min-width: 768px) {
    
    .carousel-inner .carousel-item-right.active,
    .carousel-inner .carousel-item-next {
      transform: translateX(33.333%);
    }
    
    .carousel-inner .carousel-item-left.active, 
    .carousel-inner .carousel-item-prev {
      transform: translateX(-33.333%);
    }
}

.carousel-inner .carousel-item-right,
.carousel-inner .carousel-item-left{ 
  transform: translateX(0);
}

/**************************************** BUTTONS ****************************************/

label:not(.form-check-label):not(.custom-file-label) {
    cursor: pointer !important;
}

/**************************************** STREAM ****************************************/

#wall {
  padding: 0px 0 0 0;
  min-height: 2000px;
}

#wrapper,
#container {
  width: 100%;
  padding: 0;
}

#nav-container {
  margin-bottom: 20px;
}

.stream li .section-title a {
  font-weight: bold;
  font-size: 12pt;
}

.modern.dark .stream li .section-intro {
  display: none;
}

.stream li .section-share a {
  width: 100%;
  height: 18px;
  float: left;
  color: #fff;
  font-weight: bold;
  }

.modern .stream li .section-share {
  margin: 0 0px 0px 0;
  padding: 0;
}

.stream li .section-share {
  float: none;
}

.modern .stream li .inner {
  padding: 0;
  box-shadow: 0 2px 4px rgb(0 0 20 / 8%), 0 1px 2px rgb(0 0 20 / 8%);
  border-radius: 10px;
}

.dcsns ul, .dcsns li {
    border-radius: 10px;
}

.modern.dark .stream li .socicon {
  display: none;
}

.dark-mode a:not(.btn):hover {
    color: #E0E0E0;
}

.modern.dark .stream li {
    background-color: rgba(0, 0, 0, 0.9); /*Background color of stream here */
}

/**************************************** SCROLL ****************************************/

.AutoScroll {
   max-height: 100vw;
   overflow-y: hidden;
}

</style>

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-2YT913FL5H"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-2YT913FL5H');
</script>

<script type="text/javascript">
    (function(c,l,a,r,i,t,y){
        c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
        t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
        y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
    })(window, document, "clarity", "script", "fkepp8sj7h");
</script>

<script type="text/javascript">
jQuery(document).ready(function($) {
  $('#social-stream').dcSocialStream({
	feeds: {
	  rss: {
		id: '<?php echo $url; ?>',
		out: 'intro,thumb,title,text,share'
	  }
	},
	rotate: {
	  delay: 0
	},
	style: {
	  layout: 'modern',
	  colour: 'dark'
	},
	twitterId: 'venlocal',
	control: false,
	filter: false,
	wall: true,
	center: true,
	cache: false,
	order: 'date',
	max: 'limit',
	limit: 1000,
	iconPath: 'src/img/dcsns-dark/',
	imagePath: 'src/img/dcsns-dark/'
  });

});
</script>

</head>