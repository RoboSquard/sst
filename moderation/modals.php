<!--=========================================================================== A ===========================================================================-->

<!---------------------------------------------------------------------------- ADD ---------------------------------------------------------------------------->

<div class="modal fade" id="modal-add" tabindex="-1" role="dialog" aria-labelledby="modal-postLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-slideout" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<h5 class="modal-title" id="modal-postLabel">Add Post</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">×</span>
		</button>
	  </div>
	  <div class="modal-body">
		<form method="post" id="addPostForm">
		  <input type="hidden" name="post_id" value="" id="post_id" />

		  <div class="form-group">
			<label for="InputPostImage">Post Image</label>
			<p><input type="url" class="form-control" name="image_url" placeholder="Enter URL"></p>
			<p><a href="javascript:void(0);" onClick="popupImages('src/images/index.htm', this)" class="btn btn-sm btn-dark btn-block"><i class="fa-solid fa-search"></i> SEARCH IMAGES</a></p>
		  </div>
		  <div class="form-group">
			<label for="InputPostTitle">Post Title</label>
			<input type="text" class="form-control" id="title" name="title" placeholder="Enter title">
		  </div>

		  <div style="display:none" class="form-group">
			<label for="InputPostDate">Post Date<br><small class="text-muted">Used for RSS feed social publishing</small></label>
			<div class="input-group">
			  <input type='text' class="form-control" name="pubDate" id="datetimepicker" />

			  <div class="input-group-append">
				<div class="input-group-text"><i class="fa-solid fa-calendar-days"></i></div>
			  </div>
			</div>

		  </div>

		  <div class="form-group">
			<label for="InputPostContent">Post Content</label>
			<textarea class="form-control" rows="4" placeholder="Enter Content" name="description"></textarea>
		  </div>

		  <div class="form-group">
			<label for="InputPostLink">Post Link</label>
			<input type="url" class="form-control" placeholder="Enter Link" name="link" />
		  </div>

		  <div class="form-group">
			<label for="InputPostNotes">Post Notes</label>
			<textarea class="form-control" name="notes" rows="4" placeholder="Enter Notes"></textarea>
		  </div>
		</form>

	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		<a href="https://venlocal.com/s/screen" class="btn btn-primary">Order free</a>
		<!--<button type="button" class="btn btn-primary" onClick="savePost();">Save Post</button>-->
	  </div>
	</div>
  </div>
</div>

<!------------------------------------------------------------------------- AUTOMATIC ------------------------------------------------------------------------->

<div class="modal fade" id="modal-auto" tabindex="-1" role="dialog" aria-labelledby="modal-postLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-slideout" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<h5 class="modal-title" id="modal-postLabel">Automatic</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">×</span>
		</button>
	  </div>
	  <div class="modal-body">
		<div class="card card-primary">
		  <div class="card-header">
			<h3 class="card-title">Scheduled RSS</h3>
		  </div>
		  <div class="card-body">
			<div id="rssFields2" class="form-group"></div>
		  </div>
		</div>

		<div class="card card-primary">
		  <div class="card-header">
			<h3 class="card-title">Add RSS</h3>
		  </div>
		  <form id="importRssFormnew">
			<div class="card-body">
			  <div class="control-group">
				<label class="control-label" for="links">Links</label>
				<div class="controls">
				  <select name="links" id="links" class="input-medium form-control" title="Link handling">
					<option value="preserve" selected="selected">preserve</option>
					<option value="footnotes">add to footnotes</option>
					<option value="remove">remove</option>
				  </select>
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label" for="rewrite">Rewrite RSS</label>
				<div class="controls">
				  <select name="rewrite" id="rewrite" class="form-control" title="Rewrite rss title & content">
					<option value="1" selected="selected">Rewrite title & content</option>
					<option value="2">Rewrite content</option>
					<option value="3">Rewrite title</option>
					<option value="">Don't rewrite</option>
				  </select>
				</div>
			  </div>
			  <div class="form-group">
				<label for="InputPostSentences">Sentences</label>
				<input type="number" class="form-control item-title" id="sentences" name="sentences" placeholder="Sentences" value="3">
			  </div>

			  <div class="form-group">
				<label for="InputPostSentences">Truncate</label>
				<input type="number" class="form-control item-title" id="truncate" name="truncate" placeholder="truncate" value="50">
			  </div>
			  <div class="form-group">
				<label for="HashTags">HashTags:</label>
				<input type="text" class="form-control input-lg" id="hashtags" name="hashtags" title="hashtags" placeholder="eg #tag1 #tag2">
			  </div>
			  <div class="control-group">
				<label class="control-label" for="imageapi">Image API</label>
				<div class="controls">
				  <select name="imageapi[]" id="imageapi" class="form-control" multiple="multiple" title="Pick Image API">
					<option value="unsplash" selected="selected">unsplash</option>
					<option value="pixbay">pixbay</option>
					<option value="pexeles">pexeles</option>
					<option value="walhaven">walhaven</option>
					<option value="flickr">flickr</option>
					<option value="freepik">freepik</option>
				  </select>
				</div>
			  </div>
			  <div class="form-group">
				<p><label for="Inputrssurl">RSS URL</label></p>
				<input type="text" class="form-control item-title" id="newRSS" name="newRSS" placeholder="Enter RSS URL" required>
			  </div>

			  <div class="mt-4">
				<button type="button" class="btn btn-primary" onclick="importFromRss2(this)">Submit</button>

				<button type="button" class="btn btn-success" onclick="scheduleRss2(this)">Schedule</button>
			  </div>
			</div>
		  </form>

		</div>

	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	  </div>
	</div>
  </div>
</div>

<!------------------------------------------------------------------------- ARTICLES ------------------------------------------------------------------------->

<div class="modal fade" id="modal-creator" tabindex="-1" role="dialog" aria-labelledby="modal-postLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-slideout" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<h5 class="modal-title" id="modal-postLabel">Article Creator</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">×</span>
		</button>
	  </div>
	  <div class="modal-body">

		<div class="card card-primary">
		  <div class="card-header">
			<h3 class="card-title">Scheduled Keywords</h3>
		  </div>
		  <div class="card-body">
			<div id="keywordfields" class="form-group"></div>
		  </div>
		</div>

		<div class="card card-primary">
		  <div class="card-header">
			<h3 class="card-title">Create Articles</h3>
		  </div>
		  <form id="createarticles">
			<div class="card-body">
			  <div class="form-group">
				<label for="keyword">Keyword:</label>
				<input type="text" class="form-control input-lg" id="keyword" name="keyword" title="KEYWORD" placeholder="Enter keyword e.g. smartphone, samsung galaxy, seo tool, etc" required>
			  </div>
			  <div class="form-group">
				<label for="HashTags">HashTags:</label>
				<input type="text" class="form-control input-lg" id="hashtags" name="hashtags" title="hashtags" placeholder="eg #tag1 #tag2">
			  </div>
			  <div class="control-group">
				<label class="control-label" for="lang">Language</label>
				<div class="controls">
				  <select name="lang" id="lang" class="form-control" title="Language" data-content="Select the language!">
					<option value="en" selected="selected">English</option>
					<option value="fr">French</option>
					<option value="de">Germany</option>
					<option value="es">Spanish</option>
					<option value="nl">Netherland</option>
					<option value="cn">Chinese</option>
					<option value="jp">Japanese</option>
					<option value="br">Brazil</option>
					<option value="ru">Russia</option>
					<option value="se">Sweden</option>
					<option value="pl">Polish</option>
					<option value="kr">Korean</option>
					<option value="tr">Turkish</option>
				  </select>
				</div>
			  </div>
			  <div class="form-group">
				<label for="rewrite">Rewrite articles:</label>
				<div class="controls">
				  <select name="rewrite" id="rewrite" class="form-control" title="Rewrite articles" data-content="By default, links within the content are preserved. Change this field if you'd like links removed.">
					<option value="original">keep original</option>
					<option value="unique">make unique</option>
				  </select>
				</div>
			  </div>
			  <div class="form-group">
				<label for="InputPostSentences">Sentences</label>
				<input type="number" class="form-control item-title" id="sentences" name="sentences" placeholder="Sentences" value="3">
			  </div>
			  <div class="form-group">
				<label for="InputPostSentences">Truncate</label>
				<input type="number" class="form-control item-title" id="truncate" name="truncate" placeholder="truncate" value="50">
			  </div>
			  <div class="form-group">
				<label class="control-label" for="numbers">Number of Articles</label>
				<div class="controls">
				  <select name="numbers" id="numbers" class="form-control" title="Number of Articles">
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					<option value="6">6</option>
					<option value="7">7</option>
					<option value="8">8</option>
					<option value="9">9</option>
					<option value="10">10</option>
				  </select>
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label" for="imageapi">Image API</label>
				<div class="controls">
				  <select name="imageapi[]" id="imageapi" class="form-control" multiple="multiple" title="Pick Image API">
					<option value="unsplash" selected="selected">unsplash</option>
					<option value="pixbay">pixbay</option>
					<option value="pexeles">pexeles</option>
					<option value="walhaven">walhaven</option>
					<option value="flickr">flickr</option>
					<option value="freepik">freepik</option>

				  </select>
				</div>
			  </div>

			  <div class="mt-4">
				<button type="submit" class="btn btn-primary" onclick="createarticle(this)">Submit</button>
				<button type="button" class="btn btn-success" onclick="schedulekeyword(this)">Schedule</button>
			  </div>
			</div>
		  </form>

		</div>

	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	  </div>
	</div>
  </div>
</div>

<!--=========================================================================== B ===========================================================================-->

<!------------------------------------------------------------------- BILLBOARD/NOTICEBOARD ------------------------------------------------------------------->

<div class="modal fade" id="modal-board" tabindex="-1" role="dialog" aria-labelledby="modal-postLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-slideout" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<h5 class="modal-title" id="modal-postLabel">Edit Social Billboard or Noticeboard</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">×</span>
		</button>
	  </div>
	  <div class="modal-body">
	  
	   <div class="alert alert-danger alert-dismissible">
		  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		  <h5><i class="icon fas fa-ban"></i> Alert!</h5>
		  Moderate by approving some posts before you create!
		</div>  
	  
		<form>
		
		  <div class="form-group">
			<label for="InputBackground">Random background keyword</label>
			<input type="text" class="form-control form-control-lg" placeholder="Enter keyword (e.g. business)">
			
			<!--<div class="btn-block btn-group-vertical">
			  <button type="button" class="bg-black btn btn-default">Black</button>
			  <button type="button" class="bg-gray-dark btn btn-default">Gray Dark</button>
			  <button type="button" class="bg-gray-dark btn btn-default">Gray</button>
			  <button type="button" class="bg-light btn btn-default">Light</button>
			  <button type="button" class="bg-indigo btn btn-default">Indigo</button>
			  <button type="button" class="bg-lightblue btn btn-default">Lightblue</button>
			  <button type="button" class="bg-navy btn btn-default">Navy</button>
			  <button type="button" class="bg-purple btn btn-default">Purple</button>
			  <button type="button" class="bg-fuchsia btn btn-default">Fuchsia</button>
			  <button type="button" class="bg-pink btn btn-default">Pink</button>
			  <button type="button" class="bg-maroon btn btn-default">Maroon</button>
			  <button type="button" class="bg-orange btn btn-default">Orange</button>
			  <button type="button" class="bg-lime btn btn-default">Lime</button>
			  <button type="button" class="bg-teal btn btn-default">Teal</button>
			  <button type="button" class="bg-olive btn btn-default">Olive</button>
			 </div>-->

		  </div>
		  
		  <!--<div class="form-group">
			<label for="InputCustomColor">Custom Color</label>
			<input type="text" class="form-control" placeholder="Enter custom color in #hex">
		  </div>-->

		  <div class="form-group">
			<label for="InputHeader">Header Title</label>
			<input type="text" class="form-control form-control-lg" placeholder="Enter title or #hashtag">
		  </div>
		  
		  <div class="form-group">
			<label for="InputColor">Custom Stream Color</label>
			<input type="text" class="form-control form-control-lg" placeholder="Enter #hex value">
		  </div>

		</form>

	  </div>
	  <div class="modal-footer">
		<!--<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>-->
		<a href="public.php" target="_blank" class="btn btn-primary">Public Page</a>
		<a href="board.php" target="_blank" class="btn btn-success">Create Board</a>
	  </div>
	</div>
  </div>
</div>

<!------------------------------------------------------------------------- BLOG ------------------------------------------------------------------------->

<div class="modal fade" id="modal-blog" tabindex="-1" role="dialog" aria-labelledby="modal-postLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-slideout" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<h5 class="modal-title" id="modal-postLabel">Edit Social Blog</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">×</span>
		</button>
	  </div>
	  <div class="modal-body">

	   <div class="alert alert-danger alert-dismissible">
		  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		  <h5><i class="icon fas fa-ban"></i> Alert!</h5>
		  Moderate by approving some posts before you create!
		</div>  
	  	  
		<form>
		
		  <div class="form-group">
			<label for="InputBackground">Random background keyword</label>
			<input type="text" class="form-control form-control-lg" placeholder="Enter keyword (e.g. business)">
		  </div>

		  <div class="form-group">
			<label for="InputHeader">Header Title</label>
			<input type="text" class="form-control form-control-lg" placeholder="Enter title or #hashtag">
		  </div>	
		  
		  <div class="form-group">
			<label for="InputColor">Custom Stream Color</label>
			<input type="text" class="form-control form-control-lg" placeholder="Enter #hex value">
		  </div>
		  
            <div class="contacts">
                <label>Follow Buttons:</label>
                    <div class="form-group multiple-form-group input-group">
                        <div class="input-group-btn input-group-select">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <span class="concept">SOCIAL</span> <span class="caret"></span>
                            </button>
							<div class="dropdown-menu" style="">
							  <a class="dropdown-item" href="#">Blogger</a>
							  <a class="dropdown-item" href="#">Facebook</a>
							  <a class="dropdown-item" href="#">Linkedin</a>
							  <a class="dropdown-item" href="#">Pinterest</a>
							  <a class="dropdown-item" href="#">Reddit</a>
							  <a class="dropdown-item" href="#">Skype</a>
							  <a class="dropdown-item" href="#">Telegram</a>
							  <a class="dropdown-item" href="#">TikTok</a>
							  <a class="dropdown-item" href="#">Tumblr</a>
							  <a class="dropdown-item" href="#">Twitter</a>
							  <a class="dropdown-item" href="#">Venlocal</a>
							  <a class="dropdown-item" href="#">Vk</a>
							  <a class="dropdown-item" href="#">Whatsapp</a>
							  <a class="dropdown-item" href="#">Xing</a>
							  <a class="dropdown-item" href="#">YouTube</a>							  
							</div>
                            <input type="hidden" class="input-group-select-val" name="contacts['type'][]" >
                        </div>
                        <input type="text" name="contacts['value'][]" class="form-control" placeholder="Social profile url">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-success btn-add">+</button>
                        </span>
                    </div>
                </div>

		</form>

	  </div>
	  <div class="modal-footer">
		<!--<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>-->
		<a href="public.php" target="_blank" class="btn btn-primary">Guest Post</a>
		<a href="blog.php" target="_blank" class="btn btn-success">Create Blog</a>
	  </div>
	</div>
  </div>
</div>

<!-------------------------------------------------------------------------- BOOTH -------------------------------------------------------------------------->

<div class="modal fade" id="modal-booth" tabindex="-1" role="dialog" aria-labelledby="modal-postLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-slideout" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<h5 class="modal-title" id="modal-postLabel">Edit Social Booth</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">×</span>
		</button>
	  </div>
	  <div class="modal-body">
	  
	   <div class="alert alert-danger alert-dismissible">
		  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		  <h5><i class="icon fas fa-ban"></i> Alert!</h5>
		  Moderate by approving some posts before you create!
		</div>  
	  
		<form>
		
		  <div class="form-group">
			<label for="InputBackground">Random background keyword</label>
			<input type="text" class="form-control form-control-lg" placeholder="Enter keyword (e.g. business)">
		  </div>
		  
		  <div class="form-group">
			<label for="InputFrame">Branded Frame</label>
			<input type="url" class="form-control form-control-lg" placeholder="Enter picture of frame">
		  </div>

		</form>

	  </div>
	  <div class="modal-footer">
		<!--<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>-->
		<a href="booth/index.php" target="_blank" class="btn btn-primary">Public Booth</a>
		<a href="booth.php" target="_blank" class="btn btn-success">Create Booth</a>
	  </div>
	</div>
  </div>
</div>

<!--=========================================================================== C ===========================================================================-->

<!-------------------------------------------------------------------------- CONTEST -------------------------------------------------------------------------->

<div class="modal fade" id="modal-contest" tabindex="-1" role="dialog" aria-labelledby="modal-postLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-slideout" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<h5 class="modal-title" id="modal-postLabel">Edit Social Contest</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">×</span>
		</button>
	  </div>
	  <div class="modal-body">
	  
	   <div class="alert alert-danger alert-dismissible">
		  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		  <h5><i class="icon fas fa-ban"></i> Alert!</h5>
		  Moderate by approving some posts before you create!
		</div>  
	    
		<form>
		
		<div class="card direct-chat direct-chat-primary">
              <div class="card-header ui-sortable-handle">
                <h3 class="card-title">Votes</h3>

                <div class="card-tools">
                  <span title="3 Votes" class="badge badge-primary">10</span>
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <!-- Conversations are loaded here -->
								
                <div class="direct-chat-messages">
                  <!-- Message. Default to the left -->
				  
                  <ul class="contacts-list bg-dark">
                    <li>
                      <a href="#">
                        <img class="contacts-list-img rounded" src="https://via.placeholder.com/150/000000/FFFFFF/?text=IMAGE" alt="Image">

                        <div class="contacts-list-info">
                          <span class="contacts-list-name">
                            10 Votes
                            <small class="contacts-list-date float-right">1/1/23</small>
                          </span>
                          <span class="contacts-list-msg">Post title here</span>
                        </div>
                        <!-- /.contacts-list-info -->
                      </a>
                    </li>
                    <!-- End Contact Item -->
                    <li>
                      <a href="#">
                        <img class="contacts-list-img rounded" src="https://via.placeholder.com/150/000000/FFFFFF/?text=IMAGE" alt="Image">

                        <div class="contacts-list-info">
                          <span class="contacts-list-name">
                            9 Votes
                            <small class="contacts-list-date float-right">1/1/23</small>
                          </span>
                          <span class="contacts-list-msg">Post title here</span>
                        </div>
                        <!-- /.contacts-list-info -->
                      </a>
                    </li>
                    <!-- End Contact Item -->
                    <li>
                      <a href="#">
                        <img class="contacts-list-img rounded" src="https://via.placeholder.com/150/000000/FFFFFF/?text=IMAGE" alt="Image">

                        <div class="contacts-list-info">
                          <span class="contacts-list-name">
                            8 Votes
                            <small class="contacts-list-date float-right">1/1/23</small>
                          </span>
                          <span class="contacts-list-msg">Post title here</span>
                        </div>
                        <!-- /.contacts-list-info -->
                      </a>
                    </li>
                    <!-- End Contact Item -->
                    <li>
                      <a href="#">
                        <img class="contacts-list-img rounded" src="https://via.placeholder.com/150/000000/FFFFFF/?text=IMAGE" alt="Image">

                        <div class="contacts-list-info">
                          <span class="contacts-list-name">
                            7 Votes
                            <small class="contacts-list-date float-right">1/1/23</small>
                          </span>
                          <span class="contacts-list-msg">Post title here</span>
                        </div>
                        <!-- /.contacts-list-info -->
                      </a>
                    </li>
                    <!-- End Contact Item -->
                    <li>
                      <a href="#">
                        <img class="contacts-list-img rounded" src="https://via.placeholder.com/150/000000/FFFFFF/?text=IMAGE" alt="Image">

                        <div class="contacts-list-info">
                          <span class="contacts-list-name">
                            6 Votes
                            <small class="contacts-list-date float-right">1/1/23</small>
                          </span>
                          <span class="contacts-list-msg">Post title here</span>
                        </div>
                        <!-- /.contacts-list-info -->
                      </a>
                    </li>
                    <!-- End Contact Item -->
                    <li>
                      <a href="#">
                        <img class="contacts-list-img rounded" src="https://via.placeholder.com/150/000000/FFFFFF/?text=IMAGE" alt="Image">

                        <div class="contacts-list-info">
                          <span class="contacts-list-name">
                            5 Votes
                            <small class="contacts-list-date float-right">1/1/23</small>
                          </span>
                          <span class="contacts-list-msg">Post title here</span>
                        </div>
                        <!-- /.contacts-list-info -->
                      </a>
                    </li>
                    <!-- End Contact Item -->
                  </ul>
                  <!-- /.contacts-list -->	

                </div>
                <!--/.direct-chat-messages-->

              </div>
              <!-- /.card-body -->
            </div>
		
		  <div class="form-group">
			<label for="InputBackground">Random background keyword</label>
			<input type="text" class="form-control form-control-lg" placeholder="Enter keyword (e.g. business)">
		  </div>

		  <div class="form-group">
			<label for="InputHeader">Header Title</label>
			<input type="text" class="form-control form-control-lg" placeholder="Enter title or #hashtag">
		  </div>
		  
		  <div class="form-group">
			<label for="InputColor">Custom Stream Color</label>
			<input type="text" class="form-control form-control-lg" placeholder="Enter #hex value">
		  </div>

		</form>

	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		<a href="contest.php" target="_blank" class="btn btn-primary">Create Contest</a>
	  </div>
	</div>
  </div>
</div>

<!--=========================================================================== D ===========================================================================-->

<!--------------------------------------------------------------------------- DISPLAY --------------------------------------------------------------------------->

<div class="modal fade" id="modal-display" tabindex="-1" role="dialog" aria-labelledby="modal-postLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-slideout" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<h5 class="modal-title" id="modal-postLabel">Edit Social Display (Kiosk, Wall or Table)</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">×</span>
		</button>
	  </div>
	  <div class="modal-body">
	  
	   <div class="alert alert-danger alert-dismissible">
		  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		  <h5><i class="icon fas fa-ban"></i> Alert!</h5>
		  Moderate by approving some posts before you create!
		</div>  
		
		<form>
		
		  <div class="form-group">
			<label for="InputBackground">Random background keyword</label>
			<input type="text" class="form-control form-control-lg" placeholder="Enter keyword (e.g. business)">
		  </div>

		  <div class="form-group">
			<label for="InputHeader">Header Title</label>
			<input type="text" class="form-control form-control-lg" placeholder="Enter title or #hashtag">
		  </div>
		  
		  <div class="form-group">
			<label for="InputColor">Custom Stream Color</label>
			<input type="text" class="form-control form-control-lg" placeholder="Enter #hex value">
		  </div>

		</form>

	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		<a href="display.php" target="_blank" class="btn btn-primary">Create Display</a>
	  </div>
	</div>
  </div>
</div>

<!--=========================================================================== E ===========================================================================-->

<!--------------------------------------------------------------------------- EDIT --------------------------------------------------------------------------->

<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="modal-postLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-slideout" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<h5 class="modal-title" id="modal-postLabel">Edit Post</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">×</span>
		</button>
	  </div>
	  <div class="modal-body">
		<form method="post" id="editPostForm">
		  <input type="hidden" name="post_id" value="" id="post_id" />

		  <div class="form-group">
			<label for="InputPostImage">Post Image</label>
			<p><input type="url" class="form-control" id="link" name="image_url" placeholder="Enter URL"></p>
			<p><a href="javascript:void(0);" onClick="popupImages('src/images/index.htm', this)" class="btn btn-sm btn-dark btn-block"><i class="fa-solid fa-search"></i> SEARCH IMAGES</a></p>
		  </div>
		  <div class="form-group">
			<label for="InputPostTitle">Post Title</label>
			<input type="text" class="form-control" id="title" name="title" placeholder="Enter title">
		  </div>

		  <!--<div class="form-group">
			  
				<div class="input-group date" id="reservationdatetime" data-target-input="nearest">
					<input type="text" class="form-control datetimepicker-input" data-target="#reservationdatetime" name="pubDate">
					<div class="input-group-append" data-target="#reservationdatetime" data-toggle="datetimepicker">
						<div class="input-group-text"><i class="fa-solid fa-calendar-days"></i></div>
					</div>
				</div>
			</div>-->

		  <div style="display:none" class="form-group">
			<label for="InputPostDate">Post Date<br><small class="text-muted">Used for RSS feed social publishing</small></label>
			<div class="input-group">
			  <input type='text' class="form-control" name="pubDate" id="datetimepicker" />

			  <div class="input-group-append" id="dateButton">
				<div class="input-group-text"><i class="fa-solid fa-calendar-days"></i></div>
			  </div>
			</div>

		  </div>

		  <div class="form-group">
			<label for="InputPostContent">Post Content</label>
			<textarea class="form-control" rows="4" placeholder="Enter Content" id="description" name="description"></textarea>
		  </div>

		  <div class="form-group">
			<label for="InputPostLink">Post Link</label>
			<input type="url" class="form-control" placeholder="Enter Link" name="link" id="link" />
		  </div>

		  <div class="form-group">
			<label for="InputPostNotes">Post Notes</label>
			<textarea class="form-control" name="notes" rows="4" placeholder="Enter Notes"></textarea>
		  </div>
		</form>

	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		<a onclick="updatePost()" class="btn btn-primary">Save Changes</a>
		<!--<button type="button" class="btn btn-primary" onClick="updatePost();">Save changes</button>-->
	  </div>
	</div>
  </div>
</div>

<!------------------------------------------------------------------------- EDIT QUEUE ------------------------------------------------------------------------->

<div class="modal fade" id="editItemModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<h5 class="modal-title" id="exampleModalLongTitle">Edit Item</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">&times;</span>
		</button>
	  </div>
	  <div class="modal-body">
		<form method="post" id="editItemForm">
		  <input type="hidden" class="item-index" name="index">
		  <div class="form-group">
			<label for="itemPublishDate">Publish Date</label>
			<input type="text" class="form-control item-publish-date" placeholder="Publish Date" name="publish_date" id="itemPublishDate" />
		  </div>
		  <div class="form-group">
			<label for="InputPostImage">Post Image</label>
			<input type="url" class="form-control item-image-url" id="link" name="image_url" placeholder="Enter URL">
			<p><a href="javascript:void(0);" onClick="popupImages('src/images/index.htm', this)" class="btn btn-sm btn-dark btn-block"><i class="fa-solid fa-search"></i> SEARCH IMAGES</a></p>
		  </div>
		  <div class="form-group">
			<label for="InputPostTitle">Post Title</label>
			<input type="text" class="form-control item-title" id="title" name="title" placeholder="Enter title">
		  </div>
		  <div class="form-group">
			<label for="InputPostContent">Post Content</label>
			<textarea class="form-control item-description" rows="4" placeholder="Enter Content" id="description" name="description"></textarea>
		  </div>

		  <div class="form-group">
			<label for="InputPostLink">Post Link</label>
			<input type="url" class="form-control item-link" placeholder="Enter Link" name="link" id="link" />
		  </div>
		</form>
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		<button type="button" class="btn btn-primary" onclick="updateQueueItem(event)">Save changes</button>
	  </div>
	</div>
  </div>
</div>

<!------------------------------------------------------------------------- EMBED ------------------------------------------------------------------------->

<div class="modal fade" id="modal-embed" tabindex="-1" role="dialog" aria-labelledby="modal-postLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-slideout" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<h5 class="modal-title" id="modal-postLabel">Social Media Embed</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">×</span>
		</button>
	  </div>
	  <div class="modal-body">
	  
	   <div class="alert alert-danger alert-dismissible">
		  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		  <h5><i class="icon fas fa-ban"></i> Alert!</h5>
		  Moderate by approving some posts before you create!
		</div>  
		
		  <div class="form-group">
			<label for="InputEmbed">Copy embed code for site</label>
			
<textarea class="form-control" rows="6" placeholder="Copy embed">
<iframe src="<?php echo BASE_URL; ?>embed.php" style="border: 0" width="100%" height="400px" scrolling="auto" frameborder="0"></iframe>				
</textarea>

		  </div>

	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		<a href="embed.php" target="_blank" class="btn btn-success">View Embed</a>
	  </div>
	</div>
  </div>
</div>

<!--=========================================================================== G ===========================================================================-->

<!-------------------------------------------------------------------------- GALLERY -------------------------------------------------------------------------->

<div class="modal fade" id="modal-gallery" tabindex="-1" role="dialog" aria-labelledby="modal-postLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-slideout" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<h5 class="modal-title" id="modal-postLabel">Edit Social Gallery</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">×</span>
		</button>
	  </div>
	  <div class="modal-body">
	  
	   <div class="alert alert-danger alert-dismissible">
		  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		  <h5><i class="icon fas fa-ban"></i> Alert!</h5>
		  Moderate by approving some posts before you create!
		</div>  
		
		<form>
		
		  <div class="form-group">
			<label for="InputBackground">Random background keyword</label>
			<input type="text" class="form-control form-control-lg" placeholder="Enter keyword (e.g. business)">
		  </div>
		  
		  <div class="form-group">
			<label for="InputColor">Custom Stream Color</label>
			<input type="text" class="form-control form-control-lg" placeholder="Enter #hex value">
		  </div>

		</form>

	  </div>
	  <div class="modal-footer">
		<!--<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>-->
		<a href="public.php" target="_blank" class="btn btn-primary">Guest Post</a>
		<a href="gallery.php" target="_blank" class="btn btn-success">Create Gallery</a>
	  </div>
	</div>
  </div>
</div>

<!--=========================================================================== I ===========================================================================-->

<!-------------------------------------------------------------------------- IMPORT -------------------------------------------------------------------------->

<div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="modal-postLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-slideout" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<h5 class="modal-title" id="modal-postLabel">Import RSS or CSV</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">×</span>
		</button>
	  </div>
	  <div class="modal-body">

		<div class="card card-primary">
		  <div class="card-header">
			<h3 class="card-title">Import RSS</h3>
		  </div>
		  <!-- /.card-header -->
		  <!-- form start -->
		  <!-- <form id="importRssForm">
			<div class="card-body">
			<p><small class="text-muted">Import valid RSS feed to display on stream.</small></p> 
			  <div class="form-group">

			   <div class="input-group">
				  <input type="text" class="form-control" name="rss_feed_url">
				  <span class="input-group-append">
					<button type="button" class="btn btn-info btn-flat" onclick="importFromRss()">IMPORT</button>
				  </span>
				</div>

			  </div>			  
			</div> -->
		  <!-- /.card-body -->
		  <!-- </form> -->

		  <form id="importRssForm">
			<div class="card-body">
			  <label>Add Rss Urls</label>
			  <div id="rssFields" class="form-group">
				<div class="input-group mb-2">
				  <input type="text" class="form-control new-field" name="urls[]" placeholder="Type something">
				  <button type="button" class="add-field btn btn-success btn-sm btn-flat" onclick="addRssField(this)"><i class="fa fa-plus"></i></button>
				  <button type="button" class="rm-field btn btn-danger btn-sm btn-flat d-none" onclick="rmRssField(this)"><i class="fa fa-minus"></i></button>
				  <button type="button" class="btn btn-default btn-sm btn-flat d-none"><i class="fa fa-clock"></i></button>
				</div>
			  </div>
			  <div class="mt-4">
				<button type="button" class="btn btn-primary" onclick="importFromRss(this)">Submit</button>
				<button type="button" class="btn btn-success" onclick="scheduleRss(this)">Schedule</button>
			  </div>
			  <br>
			  <small>Press <span class="fa fa-plus"></span> to add another form field :)</small>
			</div>
		  </form>
		</div>

		<div class="card card-primary">
		  <div class="card-header">
			<h3 class="card-title">Upload CSV</h3>
		  </div>
		  <!-- /.card-header -->
		  <!-- form start -->
		  <form action="post.php?action=import_from_csv" id="importCsvForm" method="post" enctype="multipart/form-data">
			<div class="card-body">
			  <p><small class="text-muted">Upload valid CSV to display on stream. <a href="example.csv"><u>See example.</u></a></small></p>
			  <div class="form-group">

				<div class="input-group mb-2">
				  <div class="custom-file">
					<input type="file" class="custom-file-input" name="csv_file" id="exampleInputFile">
					<label class="custom-file-label" for="exampleInputFile">Choose file</label>
				  </div>
				</div>
				<div class="input-group-append">
				  <button type="button" class="btn btn-info" onclick="importFromCsv()">Upload</button>
				</div>

			  </div>
			</div>
			<!-- /.card-body -->
		  </form>
		</div>

	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		<!-- <button type="button" class="btn btn-primary" onClick="saveChanges();">Save changes</button> -->
	  </div>
	</div>
  </div>
</div>

<!--=========================================================================== J ===========================================================================-->

<!---------------------------------------------------------------------------- JOBS ---------------------------------------------------------------------------->

<div class="modal fade" id="modal-jobs" tabindex="-1" role="dialog" aria-labelledby="modal-postLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-slideout" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<h5 class="modal-title" id="modal-postLabel">Edit Social Job Board</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">×</span>
		</button>
	  </div>
	  <div class="modal-body">
	  
	   <div class="alert alert-danger alert-dismissible">
		  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		  <h5><i class="icon fas fa-ban"></i> Alert!</h5>
		  Moderate by approving some posts before you create!
		</div>  
		
		<form>
		
		  <div class="form-group">
			<label for="InputBackground">Random background keyword</label>
			<input type="text" class="form-control form-control-lg" placeholder="Enter keyword (e.g. business)">
		  </div>
		  
		  <div class="form-group">
			<label for="InputColor">Custom Stream Color</label>
			<input type="text" class="form-control form-control-lg" placeholder="Enter #hex value">
		  </div>		  

		</form>

	  </div>
	  <div class="modal-footer">
		<!--<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>-->
		<a href="public.php" target="_blank" class="btn btn-primary">Client Job</a>
		<a href="jobs.php" target="_blank" class="btn btn-success">Create Job Board</a>
	  </div>
	</div>
  </div>
</div>

<!--=========================================================================== M ===========================================================================-->

<!---------------------------------------------------------------------------- MENU ---------------------------------------------------------------------------->

<div class="modal fade" id="modal-menu" tabindex="-1" role="dialog" aria-labelledby="modal-postLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-slideout" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<h5 class="modal-title" id="modal-postLabel">Edit Social Menu / Portfolio</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">×</span>
		</button>
	  </div>
	  <div class="modal-body">
	  
	   <div class="alert alert-danger alert-dismissible">
		  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		  <h5><i class="icon fas fa-ban"></i> Alert!</h5>
		  Moderate by approving some posts before you create!
		</div>  
		
		<form>
		
		  <div class="form-group">
			<label for="InputMenuCategories">Menu Categories</label>
			<p><small>Add category name to each post title so category filter works (e.g. Drinks - Product name)</small></p>
			<textarea class="form-control" rows="4" placeholder="Enter menu categories on single lines"></textarea>
		  </div>
		
		  <div class="form-group">
			<label for="InputBackground">Random background keyword</label>
			<input type="text" class="form-control form-control-lg" placeholder="Enter keyword (e.g. business)">
		  </div>

		  <div class="form-group">
			<label for="InputHeader">Header Title</label>
			<input type="text" class="form-control form-control-lg" placeholder="Enter title or #hashtag">
		  </div>  

		  <div class="form-group">
			<label for="InputColor">Custom Stream Color</label>
			<input type="text" class="form-control form-control-lg" placeholder="Enter #hex value">
		  </div>

		</form>

	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		<a href="menu.php" target="_blank" class="btn btn-success">Create Menu</a>
	  </div>
	</div>
  </div>
</div>

<!--=========================================================================== P ===========================================================================-->

<!----------------------------------------------------------------------- PROMO/REWARD ------------------------------------------------------------------------>

<div class="modal fade" id="modal-promo" tabindex="-1" role="dialog" aria-labelledby="modal-postLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-slideout" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<h5 class="modal-title" id="modal-postLabel">Edit Social Promo/Reward</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">×</span>
		</button>
	  </div>
	  <div class="modal-body">
	  
	   <div class="alert alert-danger alert-dismissible">
		  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		  <h5><i class="icon fas fa-ban"></i> Alert!</h5>
		  Moderate by approving some posts before you create!
		</div>  
		
		<form>
		
		  <div class="form-group">
			<label for="InputBackground">Random background keyword</label>
			<input type="text" class="form-control form-control-lg" placeholder="Enter keyword (e.g. business)">
		  </div>
		  
		  <div class="form-group">
			<label for="InputColor">Custom Stream Color</label>
			<input type="text" class="form-control form-control-lg" placeholder="Enter #hex value">
		  </div>		  

		</form>

	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		<a href="promo.php" target="_blank" class="btn btn-success">Create Promo or Reward</a>
	  </div>
	</div>
  </div>
</div>

<!--=========================================================================== R ===========================================================================-->

<!-------------------------------------------------------------------------- REVIEWS -------------------------------------------------------------------------->

<div class="modal fade" id="modal-reviews" tabindex="-1" role="dialog" aria-labelledby="modal-postLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-slideout modal" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<h5 class="modal-title" id="modal-postLabel">Edit Social Reviews</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">×</span>
		</button>
	  </div>
	  <div class="modal-body">
	  
	   <div class="alert alert-danger alert-dismissible">
		  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		  <h5><i class="icon fas fa-ban"></i> Alert!</h5>
		  Moderate by approving some posts before you create!
		</div>  
		
		<form>
		
		  <div class="form-group">
			<label for="InputBackground">Random background keyword</label>
			<input type="text" class="form-control form-control-lg" placeholder="Enter keyword (e.g. business)">
		  </div>

		  <div class="form-group">
			<label for="InputHeader">Header Title</label>
			<input type="text" class="form-control form-control-lg" placeholder="Enter title or #hashtag">
		  </div>
		  
		  <div class="form-group">
			<label for="InputColor">Custom Stream Color</label>
			<input type="text" class="form-control form-control-lg" placeholder="Enter #hex value">
		  </div>

		</form>

	  </div>
	  <div class="modal-footer">
		<!--<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>-->
		<a href="public.php" target="_blank" class="btn btn-primary">Public Page</a>
		<a href="reviews.php" target="_blank" class="btn btn-success">Create Reviews</a>
	  </div>
	</div>
  </div>
</div>

<!--=========================================================================== S ===========================================================================-->

<!------------------------------------------------------------------------- SCHEDULED ------------------------------------------------------------------------->

<div class="modal fade" id="modal-scheduled" tabindex="-1" role="dialog" aria-labelledby="modal-postLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-slideout" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<h5 class="modal-title" id="modal-postLabel">Scheduled Posts</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">×</span>
		</button>
	  </div>
	  <div class="modal-body">
		<!-- TO DO List -->
		<ul id="toDoList" class="todo-list" data-widget="todo-list"></ul>
		<!-- /.card -->
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		<!-- <button type="button" class="btn btn-primary" onClick="saveChanges();">Save changes</button> -->
	  </div>
	</div>
  </div>
</div>

<!------------------------------------------------------------------------- SCREEN ------------------------------------------------------------------------->

<div class="modal fade" id="modal-screen" tabindex="-1" role="dialog" aria-labelledby="modal-postLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-slideout" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<h5 class="modal-title" id="modal-postLabel">Edit Social Screen</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">×</span>
		</button>
	  </div>
	  <div class="modal-body">
	  
	   <div class="alert alert-danger alert-dismissible">
		  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		  <h5><i class="icon fas fa-ban"></i> Alert!</h5>
		  Moderate by approving some posts before you create!
		</div>  
		
		<form>
		
		  <div class="form-group">
			<label for="InputBackground">Random background keyword</label>
			<input type="text" class="form-control form-control-lg" placeholder="Enter keyword (e.g. business)">
		  </div>

		  <div class="form-group">
			<label for="InputHeader">Header Title</label>
			<input type="text" class="form-control form-control-lg" placeholder="Enter title or #hashtag">
		  </div>
		  
		  <div class="form-group">
			<label for="InputColor">Custom Stream Color</label>
			<input type="text" class="form-control form-control-lg" placeholder="Enter #hex value">
		  </div>

		</form>

	  </div>
	  <div class="modal-footer">
		<!--<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>-->
		<a href="public.php" target="_blank" class="btn btn-primary">Public Page</a>
		<a href="screen.php" target="_blank" class="btn btn-success">Create Screen</a>
	  </div>
	</div>
  </div>
</div>

<!-------------------------------------------------------------------------- SHOP -------------------------------------------------------------------------->

<div class="modal fade" id="modal-shop" tabindex="-1" role="dialog" aria-labelledby="modal-postLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-slideout modal" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<h5 class="modal-title" id="modal-postLabel">Edit Social Shop</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">×</span>
		</button>
	  </div>
	  <div class="modal-body">
	  
	   <div class="alert alert-danger alert-dismissible">
		  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		  <h5><i class="icon fas fa-ban"></i> Alert!</h5>
		  Moderate by approving some posts before you create!
		</div>  
		
		<form>
		
		  <div class="form-group">
			<label for="InputBackground">Random background keyword</label>
			<input type="text" class="form-control form-control-lg" placeholder="Enter keyword (e.g. business)">
		  </div>

		  <div class="form-group">
			<label for="InputHeader">Header Title</label>
			<input type="text" class="form-control form-control-lg" placeholder="Enter title or #hashtag">
		  </div>
		  
		  <div class="form-group">
			<label for="InputColor">Custom Stream Color</label>
			<input type="text" class="form-control form-control-lg" placeholder="Enter #hex value">
		  </div>

		</form>

	  </div>
	  <div class="modal-footer">
		<!--<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>-->
		<a href="public.php" target="_blank" class="btn btn-primary">Public Page</a>
		<a href="shop.php" target="_blank" class="btn btn-success">Create Shop</a>
	  </div>
	</div>
  </div>
</div>

<!--=========================================================================== W ===========================================================================-->

<!--------------------------------------------------------------------------- WALL --------------------------------------------------------------------------->

<div class="modal fade" id="modal-wall" tabindex="-1" role="dialog" aria-labelledby="modal-postLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-slideout" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<h5 class="modal-title" id="modal-postLabel">Edit Social Wall</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">×</span>
		</button>
	  </div>
	  <div class="modal-body">
	  
	   <div class="alert alert-danger alert-dismissible">
		  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		  <h5><i class="icon fas fa-ban"></i> Alert!</h5>
		  Moderate by approving some posts before you create!
		</div>  
		
		<form>
		
		  <div class="form-group">
			<label for="InputBackground">Random background keyword</label>
			<input type="text" class="form-control form-control-lg" placeholder="Enter keyword (e.g. business)">
		  </div>

		  <div class="form-group">
			<label for="InputHeader">Header Title</label>
			<input type="text" class="form-control form-control-lg" placeholder="Enter title or #hashtag">
		  </div>
		  
		  <div class="form-group">
			<label for="InputColor">Custom Stream Color</label>
			<input type="text" class="form-control form-control-lg" placeholder="Enter #hex value">
		  </div>

		</form>

	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		<a href="wall.php" target="_blank" class="btn btn-primary">Create Wall</a>
	  </div>
	</div>
  </div>
</div>







<a href="'+q+'" data-date="'+item.publishedDate+'" data-id="'+item.id+'">







