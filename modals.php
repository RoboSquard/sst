<!-- Modal -->
<div class="modal fade" id="exampleModal3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
  <div class="modal-dialog modal-dialog-slideout modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal sideout large</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<!--========================================================================== C ==========================================================================-->

<!------------------------------------------------------------------------- Chatbox ------------------------------------------------------------------------->
<div class="modal fade" id="chatbot" tabindex="-1" role="dialog" aria-labelledby="modal-toolsLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-slideout" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-toolsLabel">Social Chatbots</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div style="padding: 0rem;" class="modal-body">
	  
              <div class="card-body">
			  
			  <img src="https://suite.social/images/rss/chatbots.jpg" class="mb-4 img-fluid" alt="Social Chatbots">
			  <p>Save leads and provide quick responses to common questions with templates and categories, helping you to save time. Each time someone comments or contacts your business, the bot saves user info and auto-replies based on keyword and accuracy. </p>		  

                <a href="javascript:void(0);" onClick="popup('https://suite.social/member/user/app/chatbot')" class="btn btn-block btn-lg btn-social btn-facebook mb-2">
                    <i class="fa-brands fa-facebook"></i> Facebook Chatbot
                </a>			

                <a href="javascript:void(0);" onClick="popup('https://suite.social/member/user/app/idirect')" class="btn btn-block btn-lg btn-social btn-twitter mb-2">
                    <i class="fa-brands fa-twitter"></i> Instagram Chatbot
                </a>
				
                <a href="javascript:void(0);" onClick="popup('https://suite.social/member/user/app/marketing')" class="btn btn-block btn-lg btn-social btn-twitter mb-2">
                    <i class="fa-brands fa-twitter"></i> Messenger Chatbot
                </a>			

                <a href="javascript:void(0);" onClick="popup('https://suite.social/s/sender-invite')" class="btn btn-block btn-lg btn-social btn-whatsapp mb-2">
                    <i class="fa-brands fa-whatsapp"></i> WhatsApp Chatbot
                </a>					

              </div>
              <!-- /.card-body -->
 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!--========================================================================== E ==========================================================================-->

<!------------------------------------------------------------------------- Add ------------------------------------------------------------------------->

<div class="modal fade" id="modal-add" tabindex="-1" role="dialog" aria-labelledby="modal-postLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-slideout modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-postLabel">Publish Post</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
	  
      <div class="modal-body">
	  
                <div id="accordion">
				
			    <!---------------------------- ACCORDION --------------------------->				
				
                  <div class="card card-info">
                    <div class="card-header">
                      <h4 class="card-title w-100">
                        <a class="d-block w-100" data-toggle="collapse" href="#collapsePost">
                          Add Post
                        </a>
                      </h4>
                    </div>
					
                    <div id="collapsePost" class="collapse show" data-parent="#accordion">

          <div class="card card-primary mt-3">
			  
              <!-- form start -->			  
              <form method="post" id="addPostForm">
                <div class="card-body">	  
	<input type="hidden" name="post_id" value="" id="post_id"/>
    <input type="hidden" name="panel_id">
	  
                  <div class="form-group">
                    <label for="InputPostImage">Post Image</label>
                    <p><input type="url" class="form-control" name="image_url" placeholder="Enter URL"></p>
					<p><a href="javascript:void(0);" onClick="popupImages('images/', this)"  class="btn btn-sm btn-dark btn-block"><i class="fa-solid fa-search"></i> SEARCH IMAGES</a></p>
                  </div>
                  <div class="form-group">
                    <label for="InputPostTitle">Post Title</label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="Enter title">
                  </div>
				
				<div style="display:none" class="form-group">
				<label for="InputPostDate">Post Date<br><small class="text-muted">Used for RSS feed social publishing</small></label>
					<div class="input-group">
                    <input type='text' class="form-control" name="pubDate" id="datetimepicker"/>
					
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
					<input type="url" class="form-control" placeholder="Enter Link" name="link"/>
				  </div>
				  
                  <div class="form-group">
					<label for="InputPostNotes">Post Notes</label>
					<textarea class="form-control" name="notes" rows="4" placeholder="Enter Notes"></textarea>
				  </div>
                </div><!-- /.card-body -->
                <div class="card-footer">
                  <button type="button" class="btn btn-primary" onClick="savePost(this);">Save Post</button>	
                </div>
              </form>
            </div>			  
					  
                    </div>
					
                  </div>
				  
				  <!---------------------------- ACCORDION --------------------------->
				  
                  <div class="card card-info">
                    <div class="card-header">
                      <h4 class="card-title w-100">
                        <a class="d-block w-100" data-toggle="collapse" href="#collapseCHATGPT">
                          CHAT GPT
                        </a>
                      </h4>
                    </div>
                    <div id="collapseCHATGPT" class="collapse" data-parent="#accordion">

					<div class="card card-success collapsed-card mt-3">
					  <div class="card-header">
						<h3 class="card-title">IDEAS</h3>
						<div class="card-tools">
						  <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
						  </button>
						</div>
						<!-- /.card-tools -->
					  </div>
					  <!-- /.card-header -->
					  <div class="card-body p-0" style="display: none;">
					  
					  <?php require_once 'gpt/ideas/index.php'; ?>
					  </div>
					  <!-- /.card-body -->
					</div>

                        <div class="card card-primary mt-3">
                            <div class="card-header">
                                <h3 class="card-title">Writer</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form id="writer_add_form" name="writer_add_form">
                                <div class="card-body">
                                    <input type="text" name="for" value="add" hidden>
                                    <div class="form-group">
                                        <label for="InputKeyword">Primary Keyword*</label>
                                        <input name="aiKeyword" class="form-control" value=""
                                               placeholder="Example: How to earn money from Youtube" required>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label">Choose Use Case*</label>
                                        <select  class="form-control" name="aiType" required>
                                            <option value="blog_idea">Blog Idea &amp; Outline</option>
                                            <option value="blog_writing">Blog Section Writing</option>
                                            <option value="business_idea">Business Ideas</option>
                                            <option value="cover_letter">Cover Letter</option>
                                            <option value="social_ads">Facebook, Twitter, Linkedin Ads</option>
                                            <option value="google_ads">Google Search Ads</option>
                                            <option value="post_idea">Post &amp; Caption Ideas</option>
                                            <option value="product_des">Product Description</option>
                                            <option value="seo_meta">SEO Meta Description</option>
                                            <option value="seo_title">SEO Meta Title</option>
                                            <option value="video_des">Video Description</option>
                                            <option value="video_idea">Video Idea</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label">Number Of Variants*</label>
                                        <select class="form-control" name="aiVariant" required>
                                            <option value="1">1 Variants</option>
                                            <option value="2">2 Variants</option>
                                            <option value="3">3 Variants</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="InputImage">Summarize</label>
                                        <input  type="number"  min="1" max="10"
                                               name="aiSummarize" class="form-control"
                                               placeholder="Enter number of paragrahs">
                                    </div>
                                    <div class="form-group">
                                        <label for="InputImage">Image*</label>
                                        <textarea  class="form-control" name="aiImage" rows="3"
                                                  placeholder="Enter query"  required></textarea>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                <button type="button" class="btn btn-primary" id="writer_add_form_button">Submit</button>
                                    <button type="submit" class="btn btn-success">Schedule</button>
                                </div>
                            </form>
                        </div>

                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Query</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form id="query_form" name="query_form">
                                <div class="card-body">
                                    <input type="text" name="for" value="add" hidden>
                                    <div class="form-group">
                                        <label for="InputTitle">Title*</label>
                                        <input  type="text" name="title"
                                               class="form-control form-control-border"
                                               placeholder="Enter title" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="InputContent">Content*</label>
                                        <textarea
                                                  name="content"
                                                  class="form-control form-control-border" rows="3"
                                                  placeholder="Enter query" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="InputImage">Image*</label>
                                        <textarea
                                                  name="image"
                                                  class="form-control form-control-border" rows="3"
                                                  placeholder="Enter query" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="InputImage">Summarize</label>
                                        <input  type="number" id="quantity" min="1" max="10"
                                               name="summarize" class="form-control form-control-border"
                                               placeholder="Enter number of paragrahs" >
                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                <button type="button" class="btn btn-primary" id="query_form_button">Submit</button>
                                    <button type="submit" class="btn btn-success">Schedule</button>
                                </div>
                            </form>
                        </div>
						
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">All scheduled chats</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                            <table id="scheduled_list" class="table table-bordered table-striped"
                                       style="width:100%">
                                    <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Title</th>
                                        <th>Content</th>
                                        <th>Image</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>

                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>

                    </div>
                  </div>
                  

                
				  <!---------------------------- ACCORDION --------------------------->
				  
                  <div class="card card-info">
                    <div class="card-header">
                      <h4 class="card-title w-100">
                        <a class="d-block w-100" data-toggle="collapse" href="#collapseRSS">
                          Automatic RSS
                        </a>
                      </h4>
                    </div>
                    <div id="collapseRSS" class="collapse" data-parent="#accordion">

		<div class="card card-primary mt-3">
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
                  </div>
				  
				<!---------------------------- ACCORDION --------------------------->
				
                  <div class="card card-info">
                    <div class="card-header">
                      <h4 class="card-title w-100">
                        <a class="d-block w-100" data-toggle="collapse" href="#collapseArticle">
                          Article Creator
                        </a>
                      </h4>
                    </div>
                    <div id="collapseArticle" class="collapse" data-parent="#accordion">

		<div class="card card-primary mt-3">
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
                  </div>				
				
				<!---------------------------- ACCORDION --------------------------->								
									
                </div>
 
      </div>  

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!------------------------------------------------------------------------- Edit ------------------------------------------------------------------------->

<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="modal-postLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-slideout modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-postLabel">Edit Post</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
	  <form method="post" id="editPostForm">
	  <input type="hidden" name="post_id" value="" id="post_id"/>
    <input type="hidden" name="panel_id">
    <input type="hidden" name="current_title">
	  
                  <div class="form-group">
                    <label for="InputPostImage">Post Image</label>
                    <input type="url" class="form-control" id="link" name="image_url" placeholder="Enter URL">
					<a href="javascript:void(0);" onClick="popupImages('images/', this)"  class="btn btn-sm btn-dark btn-block"><i class="fa-solid fa-search"></i> SEARCH IMAGES</a>
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
                    <input type='text' class="form-control" name="pubDate" id="datetimepicker"/>
					
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
					<input type="url" class="form-control" placeholder="Enter Link" name="link" id="link"/>
				  </div>
				  
                  <div class="form-group">
					<label for="InputPostNotes">Post Notes</label>
					<textarea class="form-control" name="notes" rows="4" placeholder="Enter Notes"></textarea>
				  </div>
				  </form>			  

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onClick="updatePost(this);">Save changes</button>
      </div>
    </div>
  </div>
</div>

<!------------------------------------------------------------------------- Edit ------------------------------------------------------------------------->

<div class="modal fade" id="modal-post" tabindex="-1" role="dialog" aria-labelledby="modal-postLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-slideout modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-postLabel">Edit Post</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
	  
                  <div class="form-group">
                    <label for="InputPostImage">Post Image</label>
                    <input type="url" class="form-control" id="InputPostImage" placeholder="Enter URL">
                  </div>
                  <div class="form-group">
                    <label for="InputPostTitle">Post Title</label>
                    <input type="text" class="form-control" id="InputPostTitle" placeholder="Enter title">
                  </div>
				  
                  <div class="form-group">
                  <label for="InputPostDate">Post Date<br><small class="text-muted">Used for RSS feed social publishing</small></label>
                    <div class="input-group date" id="reservationdatetime" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" data-target="#reservationdatetime">
                        <div class="input-group-append" data-target="#reservationdatetime" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>				  
				  				  
                  <div class="form-group">
					<label for="InputPostContent">Post Content</label>
					<textarea class="form-control" rows="4" placeholder="Enter Content"></textarea>
				  </div>
                  <div class="form-group">
					<label for="InputPostNotes">Post Notes</label>
					<textarea class="form-control" rows="4" placeholder="Enter Notes"></textarea>
				  </div>			  

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<!------------------------------------------------------------------------- Envato ------------------------------------------------------------------------->
<div class="modal fade" id="envato" tabindex="-1" role="dialog" aria-labelledby="modal-envatoLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-slideout" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-toolsLabel">Envato Authors</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div style="padding: 0rem;" class="modal-body">
	  
              <div class="card-body">
			  
			  <h3>Welcome Envato Author!</h3>
			  
<!-- partial:index.partial.html -->
<div class="e-player-container">
  <input type="checkbox" id="e-playing" />  
  <img class="gif" src="https://suite.social/images/gif/Envato.gif">
  <label class="gif-btn btn-stop" for="e-playing"><span class="icon-stop">&#9632;</span> Stop</label>
  <label class="gif-btn btn-play" for="e-playing">&#9658; Need help? Play Guide</label>
</div>
<!-- partial -->	
	<br>
			  
			  <!--<img src="https://suite.social/images/networks/Envato.jpg" class="mb-4 img-fluid" alt="Envato">-->

				<!-- Text input-->
				<div class="form-group">
					<h5>How much do you expect to earn from your product?</h5>
					<input placeholder="Enter your earning target" name="e-sales" type="number" id="input1" class="form-control" style="background-color: #fff;color: #000;">
				</div>				

				<div id="e-profit" style="display:none;">
				<!-- Text input-->
				<div class="form-group">
					<h5>With Social Promotions, you can grow your sales by:</h5>
					<input type="text" id="input2" readonly class="form-control is-valid">
				</div>	
				</div>
							
<script>
$("#input1").keyup(function(){
    var input1 = parseInt($("#input1").val());
    if(!isNaN(input1)){
        $("#input2").val(input1 * 3);
    }
    else{
        $("#input2").val("");
        alert("Please enter a valid number");
    }
});

$('input[name="e-sales"]').bind('keypress change', function(){
    $('#e-profit').toggle(this.value.length)
})
</script>
			  
                <a id="1g" href="javascript:void(0);" class="btn btn-block btn-lg btn-success mb-2">
                    <i class="fa-solid fa-circle-question"></i> HOW DASHBOARD WORKS?
                </a>
				
				<div id="iframe1g"></div>				  
			  
			  <p>Also with Social Dashboard you can...</p>			  

              <h4>
			  <p><i style="color:#7CB342" class="fa-solid fa-check font-weight-bolder"></i> <span style="color:#7CB342" class="font-weight-bolder">MANAGE...</span > <br>all social media accounts to save time!</p>
			  <p><i style="color:#7CB342" class="fa-solid fa-check font-weight-bolder"></i> <span style="color:#7CB342" class="font-weight-bolder">MARKET...</span > <br>products to grow customers & sales!</p>
			  <p><i style="color:#7CB342" class="fa-solid fa-check font-weight-bolder"></i> <span style="color:#7CB342" class="font-weight-bolder">MESSAGE...</span > <br>customers to engage & resolve issues</p>
			  <p><i style="color:#7CB342" class="fa-solid fa-check font-weight-bolder"></i> <span style="color:#7CB342" class="font-weight-bolder">MONITOR...</span > <br>posts & reviews to maintain your reputation!</p>
			  <p><i style="color:#7CB342" class="fa-solid fa-check font-weight-bolder"></i> <span style="color:#7CB342" class="font-weight-bolder">MERCHANT...</span > <br>on social media to earn more money!</p>
			  </h4>

              </div>
              <!-- /.card-body -->
 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">START FREE</button>
      </div>
    </div>
  </div>
</div>

<!--========================================================================== F ==========================================================================-->

<!----------------------------------------------------------------------- Freelancer ----------------------------------------------------------------------->
<div class="modal fade" id="freelancer" tabindex="-1" role="dialog" aria-labelledby="modal-freelancerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-slideout" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-toolsLabel">Freelancers</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div style="padding: 0rem;" class="modal-body">
	  
              <div class="card-body">
			  
			  <h3>Welcome Freelancer!</h3>
			  <img src="https://suite.social/images/networks/Freelancer.jpg" class="mb-4 img-fluid" alt="Freelancer">

                <a id="1g" href="javascript:void(0);" class="btn btn-block btn-lg btn-success mb-2">
                    <i class="fa-solid fa-circle-question"></i> HOW IT WORKS?
                </a>
				
				<div id="iframe1g"></div>				  
			  
			  <p>With Social Dashboard you can...</p>	

              <h4>
			  <p><i class="fa-solid fa-check text-info font-weight-bolder"></i> <span class="text-info font-weight-bolder">MANAGE...</span > <br>all social media accounts to save time!</p>
			  <p><i class="fa-solid fa-check text-info font-weight-bolder"></i> <span class="text-info font-weight-bolder">MARKET...</span > <br>your services to grow customers & sales!</p>
			  <p><i class="fa-solid fa-check text-info font-weight-bolder"></i> <span class="text-info font-weight-bolder">MESSAGE...</span > <br>clients to engage & resolve issues</p>
			  <p><i class="fa-solid fa-check text-info font-weight-bolder"></i> <span class="text-info font-weight-bolder">MONITOR...</span > <br>jobs or reviews to maintain reputation!</p>
			  <p><i class="fa-solid fa-check text-info font-weight-bolder"></i> <span class="text-info font-weight-bolder">MERCHANT...</span > <br>on social media to earn more money!</p>
			  </h4>

              </div>
              <!-- /.card-body -->
 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">START FREE</button>
      </div>
    </div>
  </div>
</div>

<!--========================================================================== G ==========================================================================-->

<!------------------------------------------------------------------------- GUIDE ------------------------------------------------------------------------->
<div class="modal fade" id="guide" tabindex="-1" role="dialog" aria-labelledby="modal-toolsLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-slideout" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-toolsLabel">How does Social Dashboard work?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div style="padding: 0rem;" class="modal-body">
	  
              <div class="card-body">
			  
                <a id="1ge" href="javascript:void(0);" class="btn btn-block btn-lg btn-success mb-2">
                    <i class="fa-solid fa-circle-question"></i> VIEW THE GUIDE
                </a>
				
				<div id="iframe1ge"></div>			

                <a href="https://suite.social/d/install/index.html" class="btn btn-block btn-lg btn-success mb-2">
                    <i class="fa-solid fa-download"></i> INSTALL ON DESKTOP
                </a>
				
				<hr>
			  
			  <img src="https://suite.social/images/banner/<?php $min=1; $max=15; echo rand($min,$max);?>.jpg" class="mb-4 img-fluid" alt="Social Dashboard">
			  <p>You can also...</p>
			  
              <h4>
			  <p><i class="fa-solid fa-check text-green font-weight-bolder"></i> <span class="text-green font-weight-bolder">MANAGE...</span > <br>all social media accounts to save time!</p>
			  <p><i class="fa-solid fa-check text-green font-weight-bolder"></i> <span class="text-green font-weight-bolder">MARKET...</span > <br>your services to grow customers & sales!</p>
			  <p><i class="fa-solid fa-check text-green font-weight-bolder"></i> <span class="text-green font-weight-bolder">MESSAGE...</span > <br>clients to engage & resolve issues</p>
			  <p><i class="fa-solid fa-check text-green font-weight-bolder"></i> <span class="text-green font-weight-bolder">MONITOR...</span > <br>posts & reviews to maintain reputation!</p>
			  <p><i class="fa-solid fa-check text-green font-weight-bolder"></i> <span class="text-green font-weight-bolder">MERCHANT...</span > <br>on social media to earn more money!</p>
			  </h4>				

              </div>
              <!-- /.card-body -->
 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!--========================================================================== L ==========================================================================-->

<!------------------------------------------------------------------------- LEADS ------------------------------------------------------------------------->
<div class="modal fade" id="leads" tabindex="-1" role="dialog" aria-labelledby="modal-toolsLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-slideout" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-toolsLabel">How does Social Leads work?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div style="padding: 0rem;" class="modal-body">
	  
              <div class="card-body">
			  
                <a id="1l" href="javascript:void(0);" class="btn btn-block btn-lg btn-success mb-2">
                    <i class="fa-solid fa-circle-question"></i> VIEW THE GUIDE
                </a>
				
				<div id="iframe1l"></div>				

                <a id="2l" href="javascript:void(0);" class="btn btn-block btn-lg btn-success mb-2">
                    <i class="fa-solid fa-download"></i> INSTALL ON DESKTOP
                </a>
				
				<div id="iframe2l"></div>	
				
				<hr>
			  
			  <img src="https://suite.social/images/rss/leads.jpg" class="mb-4 img-fluid" alt="Social Leads">
			  <p>Find Leads & Buyers on Social Media! The tool sorts through social media noise, filters out sellers and spammers then finds people who are most likely to purchase your product or service. Reply to users with your offer.</p>
			  
              <h4>
			  <p><i class="fa-solid fa-check text-green font-weight-bolder"></i> <span class="text-green font-weight-bolder">BY BRAND</span > <br>to offer alternatives</p>
			  <p><i class="fa-solid fa-check text-green font-weight-bolder"></i> <span class="text-green font-weight-bolder">BY HASHTAG...</span > <br>to discover selling opportunities</p>
			  <p><i class="fa-solid fa-check text-green font-weight-bolder"></i> <span class="text-green font-weight-bolder">BY PRODUCT...</span > <br>to offer promotions</p>
			  <p><i class="fa-solid fa-check text-green font-weight-bolder"></i> <span class="text-green font-weight-bolder">BY SERVICE...</span > <br>to offer discounts</p>
			  <p><i class="fa-solid fa-check text-green font-weight-bolder"></i> <span class="text-green font-weight-bolder">BY TOPIC...</span > <br>to upsell your products or services</p>
			  </h4>				

              </div>
              <!-- /.card-body -->
 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!--========================================================================== M ==========================================================================-->

<!------------------------------------------------------------------------- Manage ------------------------------------------------------------------------->
<div class="modal fade" id="manage" tabindex="-1" role="dialog" aria-labelledby="modal-toolsLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-slideout" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-toolsLabel">Social Management</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div style="padding: 0rem;" class="modal-body">
	  
              <div class="card-body">
			  
			  <img src="https://suite.social/images/rss/manage.jpg" class="mb-4 img-fluid" alt="Social Publisher">
			  <p>Post, plan or promote to over 30 social media networks, while you focus on running your business and serving more customers.</p>

                <a id="1m" href="javascript:void(0);" class="btn btn-block btn-lg btn-success mb-2">
                    <i class="fa-solid fa-circle-question"></i> 1. How to add accounts?
                </a>
				
				<div id="iframe1m"></div>				

                <a id="2m" href="javascript:void(0);" class="btn btn-block btn-lg btn-success mb-2">
                    <i class="fa-solid fa-circle-question"></i> 2. How to publish post?
                </a>
				
				<div id="iframe2m"></div>					

                <a id="3m" href="javascript:void(0);" class="btn btn-block btn-lg btn-success mb-2">
                    <i class="fa-solid fa-circle-question"></i> 3. How to schedule posts?
                </a>	
				
				<div id="iframe3m"></div>					
				
                <a id="4m" href="javascript:void(0);" class="btn btn-block btn-lg btn-success mb-2">
                    <i class="fa-solid fa-circle-question"></i> 4. How to plan posts?
                </a>
				
				<div id="iframe4m"></div>	

              </div>
              <!-- /.card-body -->
 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!--========================================================================== P ==========================================================================-->

<!----------------------------------------------------------------------- Producthunt ----------------------------------------------------------------------->
<div class="modal fade" id="producthunt" tabindex="-1" role="dialog" aria-labelledby="modal-producthuntLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-slideout" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-toolsLabel">Product hunters</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div style="padding: 0rem;" class="modal-body">
	  
              <div class="card-body">
			  
			  <h3>Welcome Product hunters!</h3>
			  
<!-- partial:index.partial.html -->
<div class="p-player-container">
  <input type="checkbox" id="p-playing" />  
  <img class="gif" src="https://suite.social/images/gif/Producthunt.gif">
  <label class="gif-btn btn-stop" for="p-playing"><span class="icon-stop">&#9632;</span> Stop</label>
  <label class="gif-btn btn-play" for="p-playing">&#9658; Need help? Play Guide</label>
</div>
<!-- partial -->	
	<br>			  
			  
			  <!--<img src="https://suite.social/images/networks/Producthunt.jpg" class="mb-4 img-fluid" alt="Producthunt">-->
			  
				<!-- Text input-->
				<div class="form-group">
					<h5>How much do you expect to earn from your product?</h5>
					<input placeholder="Enter your earning target" name="p-sales" type="number" id="input3" class="form-control" style="background-color: #fff;color: #000;">
				</div>

				<div id="p-profit" style="display:none;">
				<!-- Text input-->
				<div class="form-group">
					<h5>With Social Promotions, you can grow your sales by:</h5>
					<input type="text" id="input4" readonly class="form-control is-valid">
				</div>	
				</div>
		
<script>
$("#input3").keyup(function(){
    var input1 = parseInt($("#input3").val());
    if(!isNaN(input1)){
        $("#input4").val(input1 * 3);
    }
    else{
        $("#input4").val("");
        alert("Please enter a valid number");
    }
});

$('input[name="p-sales"]').bind('keypress change', function(){
    $('#p-profit').toggle(this.value.length)
})
</script>
			  
                <a id="1g" href="javascript:void(0);" class="btn btn-block btn-lg btn-success mb-2">
                    <i class="fa-solid fa-circle-question"></i> HOW DASHBOARD WORKS?
                </a>
				
				<div id="iframe1g"></div>	
				
			  <p>Also with Social Dashboard you can...</p>

              <h4>
			  <p><i class="fa-solid fa-check text-orange font-weight-bolder"></i> <span class="text-orange font-weight-bolder">MANAGE...</span > <br>all social media accounts to save time!</p>
			  <p><i class="fa-solid fa-check text-orange font-weight-bolder"></i> <span class="text-orange font-weight-bolder">MARKET...</span > <br>products to grow customers & sales!</p>
			  <p><i class="fa-solid fa-check text-orange font-weight-bolder"></i> <span class="text-orange font-weight-bolder">MESSAGE...</span > <br>customers to engage & resolve issues</p>
			  <p><i class="fa-solid fa-check text-orange font-weight-bolder"></i> <span class="text-orange font-weight-bolder">MONITOR...</span > <br>reviews to maintain your reputation!</p>
			  <p><i class="fa-solid fa-check text-orange font-weight-bolder"></i> <span class="text-orange font-weight-bolder">MERCHANT...</span > <br>on social media to earn more money!</p>
			  </h4>

              </div>
              <!-- /.card-body -->
 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">START FREE</button>
      </div>
    </div>
  </div>
</div>


<!------------------------------------------------------------------------- Publish ------------------------------------------------------------------------->

<!-- Modal -->
<div class="modal fade" id="modal-publish" tabindex="-1" role="dialog" aria-labelledby="modal-publishLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-slideout modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-publishLabel">Social Publishing <small>Via Zapier</small></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">

<div class="container">
	
<h4>STEP 1: Create feed</h4>

          <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Publish / Zapier RSS Link</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->			  
              <form>
                <div class="card-body">
				<p><small class="text-muted">Generate RSS link of the panel.</small></p> 
                  <div class="form-group">

				   <div class="input-group">
					  <input type="text" class="form-control">
					  <span class="input-group-append">
						<button type="button" class="btn btn-info btn-flat">CREATE</button>
					  </span>
					</div>

                  </div>				  
                </div>
                <!-- /.card-body -->
              </form>
            </div>
			
<h4>STEP 2: Change status</h4>

<p>Change any post status to <small class="badge badge-warning">Published </small></p>

<hr>
			
<h4>STEP 3: Connect feed</h4>

                <a href="javascript:void(0);" class="btn btn-block btn-lg btn-social btn-facebook" onClick="popuplogin('https://zapier.com/apps/facebook-pages/integrations/rss/39/post-new-rss-items-to-a-facebook-page')">
                    <i class="fa-brands fa-facebook"></i> Facebook Page
                </a>

                <a href="javascript:void(0);" class="btn btn-block btn-lg btn-social btn-facebook" onClick="popuplogin('https://zapier.com/apps/facebook-groups/integrations/rss/234558/post-facebook-group-photos-for-new-items-in-rss-feeds')">
                    <i class="fa-brands fa-facebook-square"></i> Facebook Group
                </a>

                <a href="javascript:void(0);" class="btn btn-block btn-lg btn-social btn-instagram" onClick="popuplogin('https://zapier.com/apps/instagram-for-business/integrations/rss/251723/publish-new-items-in-feeds-to-instagram-for-business')">
                    <i class="fa-brands fa-instagram"></i> Instagram
                </a>	
				
                <a href="javascript:void(0);" class="btn btn-block btn-lg btn-social btn-linkedin" onClick="popuplogin('https://zapier.com/apps/linkedin/integrations/rss/12811/share-new-rss-items-in-your-feed-to-your-company-linkedin-profile')">
                    <i class="fa-brands fa-linkedin"></i> Linkedin Company
                </a>	
				
                <a href="javascript:void(0);" class="btn btn-block btn-lg btn-social btn-linkedin" onClick="popuplogin('https://zapier.com/apps/linkedin/integrations/rss/152/create-share-updates-in-linkedin-for-new-items-in-feed')">
                    <i class="fa-brands fa-linkedin"></i> Linkedin Profile
                </a>
				
                <a href="javascript:void(0);" class="btn btn-block btn-lg btn-social btn-pinterest" onClick="popuplogin('https://zapier.com/apps/pinterest/integrations/rss/11909/add-pins-to-your-pinterest-boards-from-new-rss-feed-items')">
                    <i class="fa-brands fa-pinterest"></i> Pinterest
                </a>
				
                <a href="javascript:void(0);" class="btn btn-block btn-lg btn-social btn-twitter" onClick="popuplogin('https://zapier.com/apps/rss/integrations/twitter/233/tweet-new-rss-feed-items')">
                    <i class="fa-brands fa-twitter"></i> Twitter
                </a>

</div>	

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!------------------------------------------------------------------------ STRATEGY ------------------------------------------------------------------------>
<div class="modal fade" id="strategy" tabindex="-1" role="dialog" aria-labelledby="modal-toolsLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-slideout" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-toolsLabel">How to search a social strategy?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div style="padding: 0rem;" class="modal-body">
	  
              <div class="card-body">
			  
			  <a id="1s" href="javascript:void(0);" class="btn btn-block btn-lg btn-success mb-2">
                    <i class="fa-solid fa-circle-question"></i> VIEW THE GUIDE
                </a>
				
				<div id="iframe1s"></div>
				
				<hr>
			  
			  <img src="https://suite.social/images/rss/strategy.jpg" class="mb-4 img-fluid" alt="Social Strategy">
			  <p>Need ideas to help create a content calendar? Search social media strategies for your business type, industry, or social network.</p>
			
              </div>
              <!-- /.card-body -->
 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!------------------------------------------------------------------------- Status ------------------------------------------------------------------------->

<!-- Modal -->
<div class="modal fade" id="modal-status" tabindex="-1" role="dialog" aria-labelledby="modal-statusLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-slideout modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-statusLabel">Change Status</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">

              <!-- /.card-header -->
              <div class="card-body">
                <input type="text" id="status_panel_id" hidden>
                <input type="text" id="status_panel_title" hidden>
                <input type="text" id="status_current_value" hidden>
                <ul class="todo-list ui-sortable" data-widget="todo-list">
                  <!--<li>
                    <div class="icheck-primary d-inline ml-2">
                      <input type="checkbox" value="" name="todo1" id="todoCheck1">
                      <label for="todoCheck1"></label>
                    </div>
                    <small class="badge badge-success">Add</small>
                  </li>-->				
                  <li id="Approved_list">
                    <div class="icheck-primary d-inline ml-2">
                      <input type="checkbox" value="Approved" name="status_checkbox" id="Approved_checkbox" onchange="changeStatusValue(this)">
                      <label for="Approved_checkbox"></label>
                    </div>
                    <small class="badge badge-warning">Approved</small>
                  </li>				
                  <li id="Closed_list">
                    <div class="icheck-primary d-inline ml-2">
                      <input type="checkbox" value="Closed" name="status_checkbox" id="Closed_checkbox" onchange="changeStatusValue(this)">
                      <label for="Closed_checkbox"></label>
                    </div>
                    <small class="badge badge-light">Closed</small>
                  </li>
                  <li id="Completed_list">
                    <div class="icheck-primary d-inline ml-2">
                      <input type="checkbox" value="Completed" name="status_checkbox" id="Completed_checkbox" onchange="changeStatusValue(this)">
                      <label for="Completed_checkbox"></label>
                    </div>
                    <small class="badge badge-success">Completed</small>
                  </li>
                  <li id="On Hold_list">
                    <div class="icheck-primary d-inline ml-2">
                      <input type="checkbox" value="On Hold" name="status_checkbox" id="On Hold_checkbox" onchange="changeStatusValue(this)">
                      <label for="On Hold_checkbox"></label>
                    </div>
                    <small class="badge badge-warning">On Hold</small>
                  </li>
                  <li id="Open_list">
                    <div class="icheck-primary d-inline ml-2">
                      <input type="checkbox" value="Open" name="status_checkbox" id="Open_checkbox" onchange="changeStatusValue(this)">
                      <label for="Open_checkbox"></label>
                    </div>
                    <small class="badge badge-success">Open</small>
                  </li>
                  <li id="Pending_list">
                    <div class="icheck-primary d-inline ml-2">
                      <input type="checkbox" value="Pending" name="status_checkbox" id="Pending_checkbox" onchange="changeStatusValue(this)">
                      <label for="Pending_checkbox"></label>
                    </div>
                    <small class="badge badge-danger">Pending </small>
                  </li>
                  <li id="Published_list">
                    <div class="icheck-primary d-inline ml-2">
                      <input type="checkbox" value="Published" name="status_checkbox" id="Published_checkbox" onchange="changeStatusValue(this)">
                      <label for="Published_checkbox"></label>
                    </div>
                    <small class="badge badge-warning">Published </small>
                  </li>	
                  <!--<li>
                    <div class="icheck-white d-inline ml-2">
                      <input type="checkbox" value="" name="todo9" id="todoCheck8">
                      <label for="todoCheck9"></label>
                    </div>
                    <small class="badge badge-warning">Scheduled</small>
                  </li>-->			  
                  <li id="Replied_list" >
                    <div class="icheck-primary d-inline ml-2">
                      <input type="checkbox" value="Replied" name="status_checkbox" id="Replied_checkbox" onchange="changeStatusValue(this)">
                      <label for="Replied_checkbox"></label>
                    </div>
                    <small class="badge badge-success">Replied</small>
                  </li>
                </ul>
              </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="savePostStatus()">Save changes</button>
      </div>
    </div>
  </div>
</div>

<!------------------------------------------------------------------------- Settings ------------------------------------------------------------------------->
<div class="modal fade" id="modal-settings" tabindex="-1" role="dialog" aria-labelledby="modal-linksLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-slideout" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title" id="modal-linksLabel">Settings: <span id="Panel_Name">Panel</span></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
	  
                <!-- we are adding the accordion ID so Bootstrap's collapse plugin detects it -->
                <div id="accordion">
                  <div class="card card-info">
                    <div class="card-header">
                      <h4 class="card-title w-100">
                        <a class="d-block w-100" data-toggle="collapse" href="#collapseOne">
                          Links
                        </a>
                      </h4>
                    </div>
                    <div id="collapseOne" class="collapse show" data-parent="#accordion">
                      <div class="card-body">

          <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">RSS Link</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->			  
              <form>
                <div class="card-body">
				<p><small class="text-muted">The RSS link of the panel for RSS posting.</small></p> 
                  <div class="form-group">

				   <div class="input-group">
					  <input type="text" class="form-control" id="settings_rss_link">
					  <span class="input-group-append">
						<button type="button" class="btn btn-info btn-flat" id="myTooltip" onclick="copyText()" title=""
                              onmouseout="updateText()">COPY</button>
					  </span>
					</div>

                  </div>
                  <!--<div class="form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                    <label class="form-check-label" for="exampleCheck1">Check me out</label>
                  </div>-->
                </div>
                <!-- /.card-body -->
              </form>
            </div>

          <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Moderation Link</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->			  
              <form>
                <div class="card-body">
				<p><small class="text-muted">Generate Moderation link of the panel for clients to approve posts.</small></p> 
								  
				  <div class="btn-group btn-block">
            <input type="text" id="moderation_panel_id" hidden>
					<button type="button" id="enable-moderation-page" onclick="enableModerationPage()" class="btn btn-success">Enable</button>
					<button type="button" id="disable-moderation-page" onclick="disbaleModerationPage()" class="btn btn-primary">Disable</button>
					<button type="button" id="delete-moderation-page" onclick="deleteModerationPage()" class="btn btn-danger">Delete</button>
				  </div>
				
                  <div class="form-group">
                    <label for="InputModerationPage">Moderation page</label>
				   <div class="input-group">
					  <input type="url" class="form-control" id="InputModerationPage">
					  <span class="input-group-append">
						<button type="button" class="btn btn-info btn-flat" onclick="visitURL('moderation')">VISIT</button>
					  </span>
					</div>					
                  </div>
                  <div class="form-group">
                    <label for="InputPublishingPage">Publishing Page</label>
				   <div class="input-group">
					  <input type="url" class="form-control" id="InputPublishingPage">
					  <span class="input-group-append">
						<button type="button" class="btn btn-info btn-flat" onclick="visitURL('')">VISIT</button>
					  </span>
					</div>
                  </div>
                  <div class="form-group">
                    <label for="InputPublishingRSS">Publishing RSS</label>
				   <div class="input-group">
					  <input type="url" class="form-control" id="InputPublishingRSS">
					  <span class="input-group-append">
						<button type="button" class="btn btn-info btn-flat" id="myTooltip1" onclick="copyPublishedText()" title=""
                              onmouseout="updatePublishedText()">COPY</button>
					  </span>
					</div>
                  </div>

                </div>
                <!-- /.card-body -->
              </form>
            </div>
			
          <!--<div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Publish / Zapier RSS Link</h3>
              </div>		  
              <form>
                <div class="card-body">
				<p><small class="text-muted">Generate Publish / Zapier RSS link of the panel.</small></p> 
                  <div class="form-group">

				   <div class="input-group">
					  <input type="text" class="form-control">
					  <span class="input-group-append">
						<button type="button" class="btn btn-info btn-flat">CREATE</button>
					  </span>
					</div>

                  </div>				  
                </div>
              </form>
            </div>
			
          <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Voting Link</h3>
              </div>		  
              <form>
                <div class="card-body">
				<p><small class="text-muted">Generate voting link of the panel for contests.</small></p> 
				
                  <div class="form-group">

				   <div class="input-group">
					  <input type="text" class="form-control">
					  <span class="input-group-append">
						<button type="button" class="btn btn-info btn-flat">BACKGROUND</button>
					  </span>
					</div>

                  </div>				
                  <div class="form-group">

				   <div class="input-group">
					  <input type="text" class="form-control">
					  <span class="input-group-append">
						<button type="button" class="btn btn-info btn-flat">CREATE</button>
					  </span>
					</div>

                  </div>
					<div class="custom-control custom-checkbox">
					  <input class="custom-control-input" type="checkbox" id="customCheckbox4" value="option1">
					  <label for="customCheckbox4" class="custom-control-label text-muted">Hide share buttons?</label>
					</div>				  
                </div>
              </form>
            </div>
			
          <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Social Wall Link</h3>
              </div>		  
              <form>
                <div class="card-body">
				<p><small class="text-muted">Generate social wall link of the panel to embed on your website.</small></p> 
				
				  <div class="form-group">
					<textarea class="form-control" rows="3" placeholder="Header Content (html supported)"></textarea>
				  </div>	
				  
                  <div class="form-group">

				   <div class="input-group">
					  <input type="text" class="form-control">
					  <span class="input-group-append">
						<button type="button" class="btn btn-info btn-flat">BACKGROUND</button>
					  </span>
					</div>

                  </div>				  
			  
                  <div class="form-group">

				   <div class="input-group">
					  <input type="text" class="form-control">
					  <span class="input-group-append">
						<button type="button" class="btn btn-info btn-flat">CREATE</button>
					  </span>
					</div>

                  </div>
					<div class="custom-control custom-checkbox">
					  <input class="custom-control-input" type="checkbox" id="customCheckbox5" value="option1">
					  <label for="customCheckbox5" class="custom-control-label text-muted">Hide share buttons?</label>
					</div>				  
                </div>
              </form>
            </div>
			
          <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Social Screen Link</h3>
              </div>		  
              <form>
                <div class="card-body">
				<p><small class="text-muted">Generate a auto scrolling social screen link of the panel for events.</small></p> 
				
				  <div class="form-group">
					<textarea class="form-control" rows="3" placeholder="Header Content (html supported)"></textarea>
				  </div>

                  <div class="form-group">

				   <div class="input-group">
					  <input type="text" class="form-control">
					  <span class="input-group-append">
						<button type="button" class="btn btn-info btn-flat">BACKGROUND</button>
					  </span>
					</div>

                  </div>
			  
                  <div class="form-group">

				   <div class="input-group">
					  <input type="text" class="form-control">
					  <span class="input-group-append">
						<button type="button" class="btn btn-info btn-flat">CREATE</button>
					  </span>
					</div>

                  </div>
				  
					<div class="custom-control custom-checkbox">
					  <input class="custom-control-input" type="checkbox" id="customCheckbox6" value="option1">
					  <label for="customCheckbox6" class="custom-control-label text-muted">Hide share buttons?</label>
					</div>
										  
                </div>
              </form>
            </div>
			
          <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Social Magazine Link</h3>
              </div>		  
              <form>
                <div class="card-body">
				<p><small class="text-muted">Generate an RSS flip magazine of the panel to distribute to customers.</small></p> 
				
                  <div class="form-group">

				   <div class="input-group">
					  <input type="text" class="form-control">
					  <span class="input-group-append">
						<button type="button" class="btn btn-info btn-flat">CREATE COVER</button>
					  </span>
					</div>

                  </div>			  
			  
                  <div class="form-group">

				   <div class="input-group">
					  <input type="text" class="form-control">
					  <span class="input-group-append">
						<button type="button" class="btn btn-info btn-flat">CREATE LINK</button>
					  </span>
					</div>

                  </div>
										  
                </div>
              </form>
            </div>-->

                      </div>
                    </div>
                  </div>
                  <div class="card card-info">
                    <div class="card-header">
                      <h4 class="card-title w-100">
                        <a class="d-block w-100" data-toggle="collapse" href="#collapseTwo">
                          Edit
                        </a>
                      </h4>
                    </div>
                    <div id="collapseTwo" class="collapse" data-parent="#accordion">
                      <div class="card-body">

                <!-- Headline -->
                <div class="form-group">
                  <label>Panel Name:</label>
                  <input class="form-control" type="text" value="Panel Name" placeholder="Panel Name">
                </div>
                <!-- /.form group -->
				
                <!-- Color -->
				
				<div class="form-group">
					<label>Default Color:</label>
					<select class="form-control">
					  <option>Blue</option>
					  <option>Teal</option>
					  <option>Green</option>
					  <option>Yellow</option>
					  <option>Red</option>
					  <option>Black</option>
					  <option>White</option>
					</select>
				  </div>				
                <!-- /.form group -->				

                <!-- Color Picker -->
                <div class="form-group">
                  <label>Custom Color:</label>

                  <div class="input-group my-colorpicker2">
                    <input type="text" class="form-control">

                    <div class="input-group-append">
                      <span class="input-group-text"><i class="fas fa-square"></i></span>
                    </div>
                  </div>
                  <!-- /.input group -->
                </div>
                <!-- /.form group -->

                      </div>
                    </div>
                  </div>
                  <div class="card card-info">
                    <div class="card-header">
                      <h4 class="card-title w-100">
                        <a class="d-block w-100" data-toggle="collapse" href="#collapseThree">
                          Notifications
                        </a>
                      </h4>
                    </div>
                    <div id="collapseThree" class="collapse" data-parent="#accordion">
                      <div class="card-body">
					  
          <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Via Email</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->			  
              <form>
                <div class="card-body">
				<p><small class="text-muted">Generate Publish / Zapier RSS link of the panel.</small></p> 

                <!-- Email -->
                <div class="form-group">
                  <label>Your Email:</label>
                  <input class="form-control" type="email" placeholder="Email Address">
                </div>
                <!-- /.form group -->					

                <!-- Email -->
                <div class="form-group">
                  <label>Forward email <small>(e.g. client email)</small></label>
                  <input class="form-control" type="email" placeholder="Email Address">
                </div>
                <!-- /.form group -->

                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Stop</button>
                </div>
              </form>
            </div>	
			
          <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Push or SMS</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->			  
              <form>
                <div class="card-body">
				<p><small class="text-muted">Generate Publish / Zapier RSS link of the panel.</small></p> 
                  <div class="form-group">

				   <div class="input-group">
					  <input type="text" class="form-control">
					  <span class="input-group-append">
						<button type="button" class="btn btn-info btn-flat">CREATE</button>											
					  </span>
					</div>
					
						<br>
						<a href="javascript:void(0);" class="btn btn-block btn-success" onClick="popuplogin('https://zapier.com/apps/rss/integrations/sms/1979/receive-sms-messages-for-new-updates-to-rss-feeds')">
							<i class="fa-solid fa-bell"></i> Push Alerts
						</a>

						<a href="javascript:void(0);" class="btn btn-block btn-success" onClick="popuplogin('https://zapier.com/apps/onesignal/integrations/rss/8120/send-onesignal-push-notifications-for-new-rss-items')">
							<i class="fa-solid fa-comment-sms"></i> SMS Alerts
						</a>

                  </div>				  
                </div>
                <!-- /.card-body -->
              </form>
            </div>

                      </div>
                    </div>
                  </div>
                </div>
 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!--========================================================================== T ==========================================================================-->

<!------------------------------------------------------------------------- Toolkit ------------------------------------------------------------------------->
<div class="modal fade" id="toolkit" tabindex="-1" role="dialog" aria-labelledby="modal-toolsLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-slideout" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-toolsLabel">Social Toolkit</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div style="padding: 0rem;" class="modal-body">
	  
              <div class="card-body">
			  
			  <img src="https://suite.social/images/rss/toolkit.jpg" class="mb-4 img-fluid" alt="Social Toolbox">
			  <p>Save yourself the time and effort of searching or visiting multiple sites. Use converters, generators and senders in one place so you can focus on running your business and serving clients.</p>		  

                <a id="1" href="javascript:void(0);" class="btn btn-block btn-lg btn-social btn-facebook mb-2">
                    <i class="fa-brands fa-facebook"></i> Facebook Tools
                </a>
				
				<div id="iframe1"></div>				

                <a id="2" href="javascript:void(0);" class="btn btn-block btn-lg btn-social btn-twitter mb-2">
                    <i class="fa-brands fa-twitter"></i> Twitter Tools
                </a>
				
				<div id="iframe2"></div>					

                <a id="3" href="javascript:void(0);" class="btn btn-block btn-lg btn-social btn-whatsapp mb-2">
                    <i class="fa-brands fa-whatsapp"></i> WhatsApp Tools
                </a>	
				
				<div id="iframe3"></div>					
				
                <a id="4" href="javascript:void(0);" class="btn btn-block btn-lg btn-social btn-youtube mb-2">
                    <i class="fa-brands fa-youtube"></i> YouTube Tools
                </a>
				
				<div id="iframe4"></div>	

              </div>
              <!-- /.card-body -->
 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!------------------------------------------------------------------------- Training ------------------------------------------------------------------------->
<div class="modal fade" id="training" tabindex="-1" role="dialog" aria-labelledby="modal-trainingLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-slideout" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-toolsLabel">Social Training</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div style="padding: 0rem;" class="modal-body">
	  
              <div class="card-body">

                <a id="5" href="javascript:void(0);" class="btn btn-block btn-lg btn-social btn-instagram mb-2">
                    <i class="fa-brands fa-instagram"></i> Instagram Training
                </a>
				
				<div id="iframe5"></div>				

                <a id="6" href="javascript:void(0);" class="btn btn-block btn-lg btn-social btn-linkedin mb-2">
                    <i class="fa-brands fa-linkedin"></i> Linkedin Training
                </a>
				
				<div id="iframe6"></div>					

                <a id="7" href="javascript:void(0);" class="btn btn-block btn-lg btn-social btn-pinterest mb-2">
                    <i class="fa-brands fa-pinterest"></i> Pinterest Training
                </a>	
				
				<div id="iframe7"></div>					
				
                <a id="8" href="javascript:void(0);" class="btn btn-block btn-lg btn-social btn-wordpress mb-2">
                    <i class="fa-brands fa-tiktok"></i> Tiktok Training
                </a>
				
				<div id="iframe8"></div>	
				
                <a id="9" href="javascript:void(0);" class="btn btn-block btn-lg btn-social btn-twitter mb-2">
                    <i class="fa-brands fa-twitter"></i> Twitter Training
                </a>
				
				<div id="iframe9"></div>	

              </div>
              <!-- /.card-body -->
 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!------------------------------------------------------------------------- Trash ------------------------------------------------------------------------->
<div class="modal fade" id="modal-trash" tabindex="-1" role="dialog" aria-labelledby="modal-trashLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-slideout" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-trashLabel">Trash</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">

                <table id="trash_data" class="table table-bordered table-striped w-100">
                <thead>
                  <tr>
                      <th>Post/Panel</th>
                      <th>Date</th>
                      <th>Action</th>
                  </tr>
                  </thead>
              
                </table>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Delete forever</button>
      </div>
    </div>
  </div>
</div>

<!------------------------------------------------------------------------- Panel Settings ------------------------------------------------------------------------->

<div class="modal fade" id="modal-panel_settings" tabindex="-1" role="dialog" aria-labelledby="modal-postLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-slideout modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body position-relative">
        <div class="loader position-absolute bg-secondary" style="top: 0; padding: .5rem;left: calc(50% - (84.8px / 2)); border-bottom-left-radius: .2rem; border-bottom-right-radius: 0.2rem; transform: translateY(-100%);transition: transform .3s; z-index: 1;">
          <span class="spinner-border spinner-border-sm d-inline-block"></span> Loading...
        </div>
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">RSS Schedule</h3>
          </div>
          <form id="importRssForm">
            <input type="hidden" name="ajax_action" value="save_rss_urls">
            <input type="hidden" name="panel_id" value="">
            <div class="card-body">
              <label>Delete or add feeds</label>
              <div id="rssFields" class="form-group">
                <div class="input-group mb-2">
                  <input type="text" class="form-control new-field" name="urls[]" placeholder="Type valid RSS url">
                  <button type="button" class="add-field btn btn-success btn-sm btn-flat" onclick="addRssField(this)"><i class="fa fa-plus"></i></button>
                  <button type="button" class="rm-field btn btn-danger btn-sm btn-flat d-none" onclick="rmRssField(this)"><i class="fa fa-minus"></i></button>
                </div>
              </div>
              <div class="mt-4">
                <button type="button" class="btn btn-primary" onclick="scheduleRss(this)">Submit</button>
              </div>
              <br>
              <small>Press <span class="fa fa-plus"></span> to add another form field :)</small>
            </div>
          </form>
        </div>
        <div class="network-cards">

        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
      </div>
    </div>
  </div>
</div>


  <!--------------------GPT MODALS-------------------------->
  <div class="modal fade" id="view">
                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                    <div class="modal-content bg-dark">
                        <div class="modal-header">
                            <h4 class="modal-title">View query</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                           <div id="view_html"></div>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>

            <div class="modal fade" id="edit">
                <div class="modal-dialog modal-lg">
                    <form id="edit_query">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Edit query</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <div class="px-3">
                                    <div class="card-body">
                                        <input type="text" name="for" value="update" hidden>
                                        <input type="text" id="InputId" name="Id"  hidden>
                                        <div class="form-group">
                                            <label for="InputTitle">Title*</label>
                                            <input id="InputTitle" type="text" name="title"
                                                   class="form-control form-control-border"
                                                   placeholder="Enter title" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="InputContent">Content*</label>
                                            <textarea id="InputContent"
                                                      name="content"
                                                      class="form-control form-control-border" rows="3"
                                                      placeholder="Enter query" required></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="InputImage">Image*</label>
                                            <textarea id="InputImage"
                                                      name="image"
                                                      class="form-control form-control-border" rows="3"
                                                      placeholder="Enter query" required></textarea>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                    </form>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>

            <div class="modal fade" id="writer_edit">
                <div class="modal-dialog modal-lg">
                    <form id="edit_writer_form">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Edit Writer</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">

                                <div class="px-3">
                                    <div class="card-body">
                                        <input type="text" name="for" value="update" hidden>
                                        <input type="text" id="aiId" name="Id"  hidden>
                                        <div class="form-group">
                                            <label for="InputKeyword">Primary Keyword*</label>
                                            <input id="aiKeyword" name="aiKeyword" class="form-control" value=""
                                                   placeholder="Example: How to earn money from Youtube" required>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label">Choose Use Case*</label>
                                            <select id="aiType" class="form-control" name="aiType" required>
                                                <option value="blog_idea">Blog Idea &amp; Outline</option>
                                                <option value="blog_writing">Blog Section Writing</option>
                                                <option value="business_idea">Business Ideas</option>
                                                <option value="cover_letter">Cover Letter</option>
                                                <option value="social_ads">Facebook, Twitter, Linkedin Ads</option>
                                                <option value="google_ads">Google Search Ads</option>
                                                <option value="post_idea">Post &amp; Caption Ideas</option>
                                                <option value="product_des">Product Description</option>
                                                <option value="seo_meta">SEO Meta Description</option>
                                                <option value="seo_title">SEO Meta Title</option>
                                                <option value="video_des">Video Description</option>
                                                <option value="video_idea">Video Idea</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label">Number Of Variants*</label>
                                            <select id="aiVariant" class="form-control" name="aiVariant" required>
                                                <option value="1">1 Variants</option>
                                                <option value="2">2 Variants</option>
                                                <option value="3">3 Variants</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="InputImage">Summarize</label>
                                            <input  type="number" id="aiSummarize" min="1" max="10"
                                                    name="aiSummarize" class="form-control"
                                                    placeholder="Enter number of paragrahs">
                                        </div>
                                        <div class="form-group">
                                            <label for="InputImage">Image*</label>
                                            <textarea  class="form-control" name="aiImage" rows="3"
                                                       placeholder="Enter query" id="aiImage" required></textarea>
                                        </div>

                                    </div>
                                    <!-- /.card-body -->
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                    </form>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
				  