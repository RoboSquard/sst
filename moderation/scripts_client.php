<!-- REQUIRED SCRIPTS -->

<!-- jQuery 
<script src="https://ssur.uk/dash/src/plugins/jquery/jquery.min.js"></script>-->
<!-- Bootstrap 4 -->
<script src="https://venlocal.com/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://venlocal.com/plugins/dist/js/adminlte.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?php echo BASE_URL; ?>src/js/dashboard.js"></script>
<!-- bs-custom-file-input -->
<script src="https://venlocal.com/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<!-- Select2 -->
<script src="https://venlocal.com/plugins/select2/js/select2.full.min.js"></script>
<!-- Sweetalert -->
<script src="https://venlocal.com/plugins/sweetalert2/sweetalert2@11.js"></script>
<!-- Get Instagram Images -->
<script src="src/js/jquery.instagramFeed.min.js" ></script>

<script>
$('#recipeCarousel').carousel({
  interval: 10000
})

$('.carousel .carousel-item').each(function(){
    var minPerSlide = 3;
    var next = $(this).next();
    if (!next.length) {
    next = $(this).siblings(':first');
    }
    next.children(':first-child').clone().appendTo($(this));
    
    for (var i=0;i<minPerSlide;i++) {
        next=next.next();
        if (!next.length) {
        	next = $(this).siblings(':first');
      	}
        
        next.children(':first-child').clone().appendTo($(this));
      }
});

</script>

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

//---------- EDIT POST ----------//

$(document).ready(function() {
  $(document).on('click', '.editPost', function() {
	var data = $(this).closest('li');

	data.addClass('editing');

	$('#modal-edit input[name="post_id"]').val(data.find('.section-title a').attr('data-id'));
	$('#modal-edit input[name="image_url"]').val(data.find('.section-thumb img').attr('src'));
	$('#modal-edit input[name="title"]').val(data.find('.section-title a').text());
	$('#modal-edit input[name="pubDate"]').val(data.find('.section-title a').attr('data-date'));
	$('#modal-edit textarea[name="description"]').val(data.find('.section-text').text());
	$('#modal-edit input[name="link"]').val(data.find('.section-title a').attr('href'));
	$('#modal-edit textarea[name="notes"]').val(data.find('.post-notes').text());

	$('#modal-edit').modal('show');
  });

});

//---------- SAVE POST ----------//

function savePost() {
$.ajax({
url: "<?php echo BASE_URL; ?>post.php?action=create",
type: "POST",
data: $('#addPostForm').serialize(),
beforeSend: function(xhr) {
},
success: function(res) {
  res = JSON.parse(res);
  if (res.status) {
	$('#addPostForm')[0].reset();
	Swal.fire({
	  icon: 'success',
	  title: 'Success!',
	  text: 'Post created successfully!',
	  timer: 3000,
	  timerProgressBar: true,
	});
	let newPost = res.data;
	let newItem = $('<li class="dcsns-li dcsns-rss dcsns-feed-0" data-index="0" data-approved="no">' +
	  '<div class="inner">' +
	  '<span class="section-thumb">' +
	  '<a data-image_count="0" href="' + newPost.link + '">' +
	  '<img src="' + newPost.image_url + '" alt="" style="opacity: 1; display: inline;">' +
	  '</a>' +
	  '</span>' +
	  '<span class="section-title">' +
	  '<a href="' + newPost.link + '" data-date="' + newPost.pubDate + '" data-id="undefined">' + newPost.title + '</a>' +
	  '</span>' +
	  '<span class="section-text">' + newPost.description + '</span>' +
	  '<span class="section-share">' +
	  '<div style="display:none" class="actions">' +
	  '<label class="btn btn-block btn-sm btn-flat btn-success" data-val="yes">' +
	  '<input hidden="" type="radio" name="options" id="option2" autocomplete="off">' +
	  '<i class="fa-solid fa-plus"></i> Approve Post</label>' +
	  '</div>' +
	  '<a style="background-color: #9C27B0; background-image: linear-gradient(-45deg, #9C27B0 0%, #03A9F4 100%); padding-right:10px;padding-left:5px;padding-top:7px;padding-bottom:25px" class="editPost" href="javascript:;">' +
	  '<i class="fa-solid fa-plus"></i> EDIT POST' +
	  '</a>' +
	  '<a style="padding-right:10px;padding-left:5px;padding-top:7px;padding-bottom:25px;color:#343a40" href="javascript:void(0)" class="share-linkedin">' +
	  (newPost.hasNotes ? '<i class="fa-solid fa-file-lines"></i> ' : '') +
	  '<i class="fa-solid fa-clock d-none" href="#modal-scheduled" data-toggle="modal"></i> ' +
	  '<i class="fa-solid fa-trash" onclick="deletePost(this, 0)"></i>' +
	  '</a>' +
	  '<div class="post-notes" style="display: none;">' + newPost.notes + '</div>' +
	  '</span>' +
	  '<span class="clear"></span>' +
	  '</div>' +
	  '</li>');
	window.stream.prepend(newItem).isotope('prepended', newItem);
	$('li.dcsns-rss .section-thumb img', window.stream).css('opacity', 0).show().fadeTo(800, 1);
	$('img', window.stream).on('load', function() {
	  window.stream.isotope('layout');
	});

	updateIndexes();
  } else {
	Swal.fire({
	  icon: 'error',
	  title: 'Error',
	  text: res.message,
	  timer: 3000,
	  timerProgressBar: true,
	})
  }
},
error: function(err) {
  //alert('error='+JSON.stringify(err));
},
});
}

//---------- UPDATE POST ----------//

function updatePost() {
  const postEl = document.querySelector('li.editing');

$.ajax({
url: "<?php echo BASE_URL; ?>post.php?action=update&index=" + postEl.dataset.index,
type: "POST",
data: $('#editPostForm').serialize(),
success: function(res) {
  res = JSON.parse(res);
  if (!res.status) {
	Swal.fire({
	  icon: 'error',
	  title: 'Error',
	  text: res.message ? res.message : 'Post could not be updated',
	  timer: 3000,
	  timerProgressBar: true,
	});
	return;
  }

  // Update post view
  let updatedPost = res.data;
  const img = postEl.querySelector('.section-thumb img');
  const link = postEl.querySelector('.section-thumb a');
  if (img) img.src = updatedPost.image_url;
  if (link) link.href = updatedPost.link;
  postEl.querySelector('.section-title a').textContent = updatedPost.title;
  postEl.querySelector('.section-text').textContent = updatedPost.description;
  postEl.querySelector('.section-title a').href = updatedPost.link;
  postEl.querySelector('.post-notes').textContent = updatedPost.notes;

  const fileIcon = postEl.querySelector('.fa-file-lines');
  if (updatedPost.hasNotes && !fileIcon) {
	let el = postEl.querySelectorAll('.share-linkedin');
	el[1].innerHTML = '<i class="fa-solid fa-file-lines"></i> ' + el[1].innerHTML;
  } else if (!updatedPost.hasNotes && fileIcon) {
	fileIcon.remove();
  }

  Swal.fire({
	icon: 'success',
	title: 'Success!',
	text: 'Post updated successfully!',
	timer: 3000,
	timerProgressBar: true,
  });
}
})
}

// Remove editing class on modal close
$('#modal-edit').on('hidden.bs.modal', function() {
  document.querySelector('li.editing').classList.remove('editing');
})

//---------- DELETE POST ----------//

function deletePost(self, index) {
  Swal.fire({
	title: 'Are you sure?',
	text: "Do you really want to delete this?",
	icon: 'warning',
	showCancelButton: true,
	confirmButtonColor: '#3085d6',
	cancelButtonColor: '#d33',
	cancelButtonText: 'No',
	confirmButtonText: 'Yes, delete it!'
  }).then((result) => {
	if (result.isConfirmed) {
	  $.ajax({
		url: '<?php echo BASE_URL; ?>post.php?action=delete',
		type: 'POST',
		data: {index: index},
		success: function (res) {
		  res = JSON.parse(res);
		  if (res.status) {
			const item = self.closest('li.dcsns-li');
			window.stream.isotope('remove', item).isotope('layout');
			setTimeout(() => {
			  updateIndexes();
			}, 1000);
		  } else {
			Swal.fire({
			  icon: 'error',
			  title: 'Error',
			  text: 'Post could not be deleted!',
			  timer: 3000,
			  timerProgressBar: true,
			})
		  }
		}
	  })
	}
  })
}

//---------- APPROVE POST ----------//

// Approve Post
function approvePost(self, index) {
  if (self.dataset.approved === 'yes') {
    $.ajax({
          url: '<?php echo BASE_URL; ?>post.php?action=unapprove',
          type: 'POST',
          data: {index: index},
          beforeSend: function () {
            self.innerHTML = '<span class="spinner-border spinner-border-sm d-inline-block" role="status" aria-hidden="true"></span> Unapproving...';
          },
          success: function (res) {
            self.setAttribute('data-approved', 'no');
            self.classList.remove('btn-warning');
            self.classList.add('btn-success');
            self.innerHTML = '<i class="fa-solid fa-plus"></i> Approve Post';
          }
        });
    return;
  }

  Swal.fire({
    title: 'When to publish it?',
    html: "<b>Today: </b>Means the post will be published toady.<br><b>Scheduled: </b>Means new posts are published daily.",
    icon: 'info',
    showDenyButton: true,
    showCancelButton: true,
    confirmButtonText: 'PUBLISH NOW',
    denyButtonText: `SCHEDULE IT`,
    confirmButton: 'btn btn-primary'
  }).then((result) => {
    switch (true) {
      case result.isConfirmed: // Publish Now
        $.ajax({
          url: '<?php echo BASE_URL; ?>post.php?action=publish',
          type: 'POST',
          data: {index: index},
          beforeSend: function () {
            self.innerHTML = '<span class="spinner-border spinner-border-sm d-inline-block" role="status" aria-hidden="true"></span> Approving...';
          },
          success: function (res) {
            res = JSON.parse(res);
            if (res.status) {
              
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: res.message? res.message: 'Post could not be published!',
                timer: 3000,
                timerProgressBar: true,
              })
            }

            self.setAttribute('data-approved', 'yes');
            self.classList.remove('btn-success');
            self.classList.add('btn-warning');
            self.innerHTML = '<i class="fa-solid fa-check"></i> APPROVED';
          }
        });
        break;
      case result.isDenied: // Schedule It
        $.ajax({
          url: '<?php echo BASE_URL; ?>cron.php?action=add_item',
          type: 'POST',
          data: {index: index},
          beforeSend: function () {
            self.innerHTML = '<span class="spinner-border spinner-border-sm d-inline-block" role="status" aria-hidden="true"></span> Approving...';
          },
          success: function (res) {
            res = JSON.parse(res);
            if (res.status) {
              let item = res.data;
              self.setAttribute('data-approved', 'yes');
              self.classList.remove('btn-success');
              self.classList.add('btn-warning');
              self.innerHTML = '<i class="fa-solid fa-check"></i> APPROVED';
              self.closest('li.dcsns-li').querySelector('.fa-clock')
                                        .classList
                                        .remove('d-none');
										addQueueItems(res.items, true);
              //addQueueItems([item]);
              Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Post added to queue!',
                timer: 3000,
                timerProgressBar: true,
              })
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: res.message? res.message: 'Post could not be added to queue!',
                timer: 3000,
                timerProgressBar: true,
              })
            }
          }
        });
        break;
    }
  })
}

//---------- IMPORT POST FROM RSS ----------//

function importFromRss() {
  const fields = document.querySelectorAll('#importRssForm input.new-field');
  for (let i = 0; i < fields.length; ++i) {
	fields[i].setAttribute('name', 'urls_to_publish[]');
  }

  $.ajax({
	url: '<?php echo BASE_URL; ?>post.php?action=import_from_rss',
	type: 'POST',
	data: $('#importRssForm').serialize(),
	success: function (res) {
	  res = JSON.parse(res);
	  if (res.status) {
		Swal.fire({
		  icon: 'success',
		  title: 'Success',
		  text: 'Posts from Rss successfully imported!',
		  timer: 3000,
		  timerProgressBar: true,
		}).then((res) => {
		  window.location.reload();
		});
	  } else {
		Swal.fire({
		  icon: 'error',
		  title: 'Error',
		  text: res.message? res.message: 'Could not import from Rss!',
		  timer: 3000,
		  timerProgressBar: true,
		});
	  }
	}
  })
}

//---------- IMPORT POST FROM CSV ----------//

function importFromCsv() {
  $.ajax({
	url: '<?php echo BASE_URL; ?>post.php?action=import_from_csv',
	type: 'POST',
	enctype: 'multipart/form-data',
	processData: false,
	contentType: false,
	cache: false,
	data: new FormData(document.querySelector('#importCsvForm')),
	success: function (res) {
	  res = JSON.parse(res);
	  if (res.status) {
		Swal.fire({
		  icon: 'success',
		  title: 'Success',
		  text: 'Posts from CSV successfully imported',
		  timer: 3000,
		  timerProgressBar: true,
		}).then((res) => {
		  window.location.reload();
		});
	  } else {
		Swal.fire({
		  icon: 'error',
		  title: 'Error',
		  text: res.message? res.message: 'Could not import from CSV!',
		  timer: 3000,
		  timerProgressBar: true,
		});
	  }
	}
  })
}

//---------- UPDATE POST INDEXES ----------//

function updateIndexes() {
  const items = document.querySelectorAll('li.dcsns-li');
  items.forEach((item, index) => {
    item.dataset.index = index;
    let deleteBtn = item.querySelector('.fa-trash');
    deleteBtn.setAttribute('onclick', 'deletePost(this, '+index+')');
    let approveBtn = item.querySelector('.actions > label');
    approveBtn.setAttribute('onclick', 'approvePost(this, '+index+')');
  });
}

//========== QUEUE ITEMS ==========//

// Load queue items
function loadQueueItems() {
  const queEl = document.querySelector('#toDoList');
  $.ajax({
	url: '<?php echo BASE_URL; ?>cron.php?action=get_items',
	success: function (res) {
	  res = JSON.parse(res);
	  addQueueItems(res.data);
	}
  });
}

// Add items to queue
function addQueueItems(items, reRendring = false) {
  const month = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
  const queEl = document.querySelector('#toDoList');
  if (reRendring) queEl.innerHTML = '';
  let itemIndex, itemEl, pubDate;
  items.forEach((item) => {
	itemIndex = $(queEl).sortable("toArray").length + 1;
	pubDate = new Date(item.publishDate);
	itemEl = '<li' + (item.published ? ' class="done"' : '') + ' data-id="' + (itemIndex - 1) + '">' +
	  '<span class="handle">' +
	  '<i class="fas fa-ellipsis-v"></i> ' +
	  '<i class="fas fa-ellipsis-v"></i>' +
	  '</span>' +
	  '<div  class="icheck-primary d-inline ml-2"> ' +
	  '<input type="checkbox" class="publish-que-item" name="todo' + itemIndex + '" id="todoCheck' + itemIndex + '" data-id="' + (itemIndex - 1) + '" ' + (item.published ? 'checked disabled' : 'disabled') + '>' +
	  '<label for="todoCheck' + itemIndex + '"></label>' +
	  '</div>' +
	  '<span class="text">' + textExcerpt(item.title, 33) + '</span>' +
	  '<small class="badge badge-success"><i class="far fa-clock"></i> ' + ordinalSuffixOf(pubDate.getDate()) + ' ' + month[pubDate.getMonth()] + ' '+ pubDate.getFullYear() + '</small>' +
	  '<div class="tools">' +
	  '<i class="fas fa-edit edit-que-item" data-toggle="modal" data-target="#editItemModal" data-id="' + (itemIndex - 1) + '"></i>' +
	  '<i class="fas fa-trash delete-que-item" data-id="' + (itemIndex - 1) + '"></i>' +
	  '</div>' +
	  '</li>';
	queEl.innerHTML += itemEl;
  })

// Refresh Que List
$(queEl).sortable("refresh");

addItemPublishHandler();
addItemEditHandler();
addItemDeleteHandler();
}

// Add handler to que item edit buttons
function addItemPublishHandler() {
  const btns = document.querySelectorAll('.publish-que-item');
  btns.forEach((btn => {
	btn.addEventListener('click', event => publishQueueItem(event));
  }))
}

// Add handler to que item edit buttons
function addItemEditHandler() {
  const btns = document.querySelectorAll('.edit-que-item');
  btns.forEach((btn => {
	btn.addEventListener('click', event => editQueueItem(event));
  }))
}

// Add handler to que item delete buttons
function addItemDeleteHandler() {
  const btns = document.querySelectorAll('.delete-que-item');
  btns.forEach((btn => {
	btn.addEventListener('click', event => deleteQueueItem(event));
  }))
}

// Publish queue item
function publishQueueItem(event) {
  event.preventDefault();
  Swal.fire({
	title: 'Are you sure?',
	text: "Do you really want to publish this?",
	icon: 'warning',
	showCancelButton: true,
	confirmButtonColor: '#3085d6',
	cancelButtonColor: '#d33',
	cancelButtonText: 'No',
	confirmButtonText: 'Yes, publish it!'
  }).then((result) => {
	if (result.isConfirmed) {
	  $.ajax({
		url: '<?php echo BASE_URL; ?>cron.php?action=publish_item',
		type: 'POST',
		data: {
		  index: event.target.dataset.id
		},
		success: function(res) {
		  res = JSON.parse(res);
		  if (res.status) {
			addQueueItems(res.data, true); // Rerender items
		  } else {
			Swal.fire({
			  icon: 'error',
			  title: 'Error',
			  text: res.message ? res.message : 'Post could not be published!',
			  timer: 3000,
			  timerProgressBar: true,
			})
		  }
		}
	  })
	}
  })
}

// Edit queue item
function editQueueItem(event) {
  $.ajax({
	url: '<?php echo BASE_URL; ?>cron.php?action=get_item',
	type: 'POST',
	data: {index: event.target.dataset.id},
	success: function (res) {
	  res = JSON.parse(res);
	  if (res.status) {
		let item = res.data;
		$('#editItemForm .item-index').val(event.target.dataset.id);
		$('#editItemForm .item-publish-date').val(item.publishDate);
		$('#editItemForm .item-image-url').val(item.image_url);
		$('#editItemForm .item-title').val(item.title);
		$('#editItemForm .item-description').val(item.description);
		$('#editItemForm .item-link').val(item.link);
	  } else {
		Swal.fire({
		  icon: 'error',
		  title: 'Error',
		  text: 'Post could not be loaded!',
		  timer: 3000,
		  timerProgressBar: true,
		})
	  }
	}
  })
}

// Update queue item
function updateQueueItem(event) {
  $.ajax({
	url: '<?php echo BASE_URL; ?>cron.php?action=update_item',
	type: 'POST',
	data: $('#editItemForm').serialize(),
	success: function(res) {
	  res = JSON.parse(res);
	  if (res.status) {
		addQueueItems(res.data, true); // Rerender items
		Swal.fire({
		  icon: 'success',
		  title: 'Success',
		  text: 'Post update successfully!',
		  timer: 3000,
		  timerProgressBar: true,
		})
	  } else {
		Swal.fire({
		  icon: 'error',
		  title: 'Error',
		  text: 'Post could not be updated!',
		  timer: 3000,
		  timerProgressBar: true,
		})
	  }
	}
  })
}

// Delete queue item
function deleteQueueItem(event) {
  Swal.fire({
	title: 'Are you sure?',
	text: "Do you really want to delete this?",
	icon: 'warning',
	showCancelButton: true,
	confirmButtonColor: '#3085d6',
	cancelButtonColor: '#d33',
	cancelButtonText: 'No',
	confirmButtonText: 'Yes, delete it!'
  }).then((result) => {
	if (result.isConfirmed) {
	  $.ajax({
		url: '<?php echo BASE_URL; ?>cron.php?action=delete_item',
		type: 'POST',
		data: {index: event.target.dataset.id},
		success: function (res) {
		  res = JSON.parse(res);
		  if (res.status) {
			const li = event.target.closest('li');
			$(li).fadeOut();
			setTimeout(() => {
			  li.remove();
			  addQueueItems(res.data, true); // Rerender items
			}, 1000);
		  } else {
			Swal.fire({
			  icon: 'error',
			  title: 'Error',
			  text: 'Post could not be deleted!',
			  timer: 3000,
			  timerProgressBar: true,
			})
		  }
		}
	  })
	}
  })
}

// Get text excerpt
function textExcerpt(text, length) {
  if (text.length > length)
	return text.substr(0, length - 3) + '...';
  else
	return text;
}

function ordinalSuffixOf(i) {
  var j = i % 10,
	k = i % 100;
  if (j == 1 && k != 11) {
	return i + "st";
  }
  if (j == 2 && k != 12) {
	return i + "nd";
  }
  if (j == 3 && k != 13) {
	return i + "rd";
  }
  return i + "th";
}

$(document).ready(function() {
  loadQueueItems();

  $('#itemPublishDate').datetimepicker({
	timepicker: false,
	format: 'Y-m-d',
	minDate: '0'
  });

  $('#toDoList').on('sortupdate', function(event, ui) {
	const items = document.querySelectorAll('#toDoList li');
	let currentIndex, newIndex;

	for (let i = 0; i < items.length; ++i) {
	  if ($(items[i]).is(ui.item)) {
		currentIndex = items[i].dataset.id;
		newIndex = i;
		break;
	  }
	}

	$.ajax({
	  url: '<?php echo BASE_URL; ?>cron.php?action=update_item_position',
	  type: 'POST',
	  data: {
		index: currentIndex,
		new_index: newIndex
	  },
	  success: function(res) {
		res = JSON.parse(res);
		if (res.status) {
		  addQueueItems(res.data, true); // Rerender items
		} else {
		  Swal.fire({
			icon: 'error',
			title: 'Error',
			text: 'Item position could not be updated!',
			timer: 3000,
			timerProgressBar: true,
		  })
		}
	  }
	})
  })
});

// Check for updates
setInterval(() => {
  const postEls = document.querySelectorAll('li.dcsns-li');
  let items = [];
  postEls.forEach((el) => {
    let item = {};
    item.title = el.querySelector('.section-title a').textContent;
    item.description = el.querySelector('.section-text').textContent;
    item.notes = el.querySelector('.post-notes').textContent;
    item.image_url = el.querySelector('.section-thumb img')? 
                    el.querySelector('.section-thumb img').src:
                    '';
    item.pubDate = el.querySelector('.section-title a').dataset.date;
    item.link = el.querySelector('.section-title a').href;
    item.approve = el.dataset.approved;
    if (item.notes === '') item.hasNotes = 0;
    else item.hasNotes = 1;
    items.push(item);
  });

  const queueItemEls = document.querySelectorAll('#toDoList li');
  let queueItems = [];
  queueItemEls.forEach((el) => {
    let item = {};
    const checkbox = el.querySelector('.publish-que-item[type="checkbox"]');
    if (checkbox) item.published = checkbox.checked;
    queueItems.push(item);
  });

  const rssFieldsInput = Array.from(document.querySelectorAll('[name="urls[]"]:not(.new-field)'));
  const rssUrls = rssFieldsInput.map((input) => {
    if (input.value) return input.value;
  }).filter(url => url !== undefined);
  
  $.ajax({
      url: '<?php echo BASE_URL; ?>post.php?action=check_for_updates',
      type: 'POST',
      data: {posts: items, 'queue_items': queueItems, 'rss_urls': rssUrls},
      success: function (res) {
        res = JSON.parse(res);
        if (res.status) {
          if (res.data.deletedPosts)
            deletePosts(res.data.deletedPosts);
          if (res.data.newPosts)
            insertPosts(res.data.newPosts);
          if (res.data.updatedPosts)
            updatePosts(res.data.updatedPosts);
          if (res.data.updatedQueueItems && res.data.updatedQueueItems.length > 0)
            addQueueItems(res.data.updatedQueueItems, true);
          if (res.data.updatedRssUrls)
            displayRssUrls(res.data.updatedRssUrls, true);
        }
      }
    })
}, 7000);

// Insert Posts
function insertPosts(posts) {
  posts.forEach((post) => {
	let newItem = $('<li class="dcsns-li dcsns-rss dcsns-feed-0" data-index="0">' +
	  '<div class="inner">' +
	  '<span class="section-thumb">' +
	  '<a data-image_count="0" href="' + post.link + '">' +
	  '<img src="' + post.image_url + '" alt="" style="opacity: 1; display: inline;">' +
	  '</a>' +
	  '</span>' +
	  '<span class="section-title">' +
	  '<a href="' + post.link + '" data-date="' + post.pubDate + '" data-id="undefined">' + post.title + '</a>' +
	  '</span>' +
	  '<span class="section-text">' + post.description + '</span>' +
	  '<span class="section-share">' +
	  '<div style="display:none" class="actions">' +
	  '<label class="btn btn-block btn-sm btn-flat btn-success" data-val="yes">' +
	  '<input hidden="" type="radio" name="options" id="option2" autocomplete="off">' +
	  '<i class="fa-solid fa-plus"></i> Approve Post</label>' +
	  '</div>' +
	  '<a style="background-color: #9C27B0; background-image: linear-gradient(-45deg, #9C27B0 0%, #03A9F4 100%);padding-right:10px;padding-left:5px;padding-top:7px;padding-bottom:25px" class="editPost" href="javascript:;">' +
	  '<i class="fa-solid fa-plus"></i> EDIT POST' +
	  '</a>' +
	  '<a style="padding-right:10px;padding-left:5px;padding-top:7px;padding-bottom:25px;color:#343a40" href="javascript:void(0)" class="share-linkedin">' +
	  (post.hasNotes ? '<i class="fa-solid fa-file-lines"></i> ' : '') +
	  '<i class="fa-solid fa-clock ' + (!post.scheduled ? 'd-none' : '') + '" href="#modal-scheduled" data-toggle="modal"></i> ' +
	  '<i class="fa-solid fa-trash" onclick="deletePost(this, 0)"></i>' +
	  '</a>' +
	  '<div class="post-notes" style="display: none;">' + post.notes + '</div>' +
	  '</span>' +
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

// Update posts
function updatePosts(posts) {
  posts.forEach((post) => {
    const postEl = document.querySelector('li.dcsns-li[data-index="'+post.index+'"]');
    // Update post view
    const img = postEl.querySelector('.section-thumb img');
    const link = postEl.querySelector('.section-thumb a');
    if (img) img.src = post.image_url;
    if (link) link.href = post.link;
    postEl.querySelector('.section-title a').textContent = post.title;
    postEl.querySelector('.section-text').textContent = post.description;
    postEl.querySelector('.section-title a').href = post.link;
    postEl.querySelector('.post-notes').textContent = post.notes;
    postEl.dataset.approved = post.approve;

    const fileIcon = postEl.querySelector('.fa-file-lines');
    if (post.hasNotes && !fileIcon) {
      let el = postEl.querySelectorAll('.share-linkedin');
      el[1].innerHTML = '<i class="fa-solid fa-file-lines"></i> ' + el[1].innerHTML;
    } else if(!post.hasNotes && fileIcon) {
      fileIcon.remove();
    }
  });
}

// Delete posts
function deletePosts(posts) {
  posts.forEach((index) => {
	const el = document.querySelector('li.dcsns-li[data-index="' + index + '"]');
	window.stream.isotope('remove', el).isotope('layout');
	setTimeout(() => {
	  updateIndexes();
	}, 1000);
  });
}

// Add RSS
const rssFields = document.querySelector('#rssFields');
function addRssField(self) {
  self.classList.add('d-none');
  self.parentNode.querySelector('.rm-field').classList.remove('d-none');
  let html = '<div class="input-group mb-2">' + 
              '<input type="text" class="form-control new-field" name="urls[]">' +
              '<button type="button" class="add-field btn btn-success btn-sm btn-flat" onclick="addRssField(this)"><i class="fa fa-plus"></i></button>' +
              '<button type="button" class="rm-field btn btn-danger btn-sm btn-flat d-none" onclick="rmRssField(this)"><i class="fa fa-minus"></i></button>' +
              '<button type="button" class="btn btn-default btn-sm btn-flat d-none"><i class="fa fa-clock"></i></button>' +
              '</div>';
  $(rssFields).append(html);
}

function rmRssField(self) {
  self.parentNode.remove();
}

// Schedule RSS
function scheduleRss(self) {
  $.ajax({
	url: '<?php echo BASE_URL; ?>cron.php?action=schedule_rss',
	type: 'POST',
	data: $('#importRssForm').serialize(),
	beforeSend: function() {
	  $(self).html('<span class="spinner-border spinner-border-sm d-inline-block"></span> Scheduling...')
	},
	success: function(res) {
	  res = JSON.parse(res);
	  $(self).html('Schedule');
	  if (res.status) {
		displayRssUrls(res.data);
	  } else {
		Swal.fire({
		  icon: 'error',
		  title: 'Error',
		  text: res.message ? res.message : 'Could not schedule Rss!',
		  timer: 3000,
		  timerProgressBar: true,
		});
	  }
	}
  })
}

// Load RSS
function loadRssUrls() {
  $.ajax({
    url: '<?php echo BASE_URL; ?>cron.php?action=get_rss_urls',
    type: 'GET',
    success: function (res) {
      res = JSON.parse(res);
      if (res.status) {
        displayRssUrls(res.data);
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: res.message? res.message: 'Could not Load Rss Urls!',
          timer: 3000,
          timerProgressBar: true,
        });
      }
    }
  })
}

// Delete RSS
function deleteRssUrl(self) {
  let url = self.dataset.url;

  $.ajax({
    url: '<?php echo BASE_URL; ?>cron.php?action=delete_rss_url',
    type: 'POST',
    data: {url: url},
    beforeSend: function () {
      $(self).html('<span class="spinner-border spinner-border-sm d-inline-block"></span>')
    },
    success: function (res) {
      res = JSON.parse(res);
      if (res.status) {
        $(self.parentNode).fadeOut();
        setTimeout(() => {
          self.parentNode.remove();
        }, 3000);
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: res.message? res.message: 'Could not delete rss url!',
          timer: 3000,
          timerProgressBar: true,
        });
      }
    }
  })
}

//========== RSS ITEMS ==========//

function displayRssUrls(urls) {
  let html = '';
  urls.forEach((url) => {
	html += '<div class="input-group mb-2">' +
	  '<input type="text" class="form-control" name="urls[]" value="' + url + '">' +
	  '<button type="button" class="add-field btn btn-success btn-sm btn-flat d-none" onclick="addRssField(this)"><i class="fa fa-plus"></i></button>' +
	  '<button type="button" class="rm-field btn btn-danger btn-sm btn-flat" onclick="deleteRssUrl(this)" data-url="' + url + '"><i class="fa fa-minus"></i></button>' +
	  '<button type="button" class="btn btn-default btn-sm btn-flat"><i class="fa fa-clock"></i></button>' +
	  '</div>';
  });

  html += '<div class="input-group mb-2">' +
	'<input type="text" class="form-control new-field" name="urls[]">' +
	'<button type="button" class="add-field btn btn-success btn-sm btn-flat" onclick="addRssField(this)"><i class="fa fa-plus"></i></button>' +
	'<button type="button" class="rm-field btn btn-danger btn-sm btn-flat d-none" onclick="rmRssField(this)"><i class="fa fa-minus"></i></button>' +
	'<button type="button" class="btn btn-default btn-sm btn-flat d-none"><i class="fa fa-clock"></i></button>' +
	'</div>';

  $(rssFields).html(html);
}

$(document).ready(function () {
  loadRssUrls();
})
</script>

<script>
	
(function ($) {
$(function () {

var addFormGroup = function (event) {
	event.preventDefault();

	var $formGroup = $(this).closest('.form-group');
	var $multipleFormGroup = $formGroup.closest('.multiple-form-group');
	var $formGroupClone = $formGroup.clone();

	$(this)
		.toggleClass('btn-success btn-add btn-danger btn-remove')
		.html('–');

	$formGroupClone.find('input').val('');
	$formGroupClone.find('.concept').text('Facebook');
	$formGroupClone.insertAfter($formGroup);

	var $lastFormGroupLast = $multipleFormGroup.find('.form-group:last');
	if ($multipleFormGroup.data('max') <= countFormGroup($multipleFormGroup)) {
		$lastFormGroupLast.find('.btn-add').attr('disabled', true);
	}
};

var removeFormGroup = function (event) {
	event.preventDefault();

	var $formGroup = $(this).closest('.form-group');
	var $multipleFormGroup = $formGroup.closest('.multiple-form-group');

	var $lastFormGroupLast = $multipleFormGroup.find('.form-group:last');
	if ($multipleFormGroup.data('max') >= countFormGroup($multipleFormGroup)) {
		$lastFormGroupLast.find('.btn-add').attr('disabled', false);
	}

	$formGroup.remove();
};

var selectFormGroup = function (event) {
	event.preventDefault();

	var $selectGroup = $(this).closest('.input-group-select');
	var param = $(this).attr("href").replace("#","");
	var concept = $(this).text();

	$selectGroup.find('.concept').text(concept);
	$selectGroup.find('.input-group-select-val').val(param);

}

var countFormGroup = function ($form) {
	return $form.find('.form-group').length;
};

$(document).on('click', '.btn-add', addFormGroup);
$(document).on('click', '.btn-remove', removeFormGroup);
$(document).on('click', '.dropdown-menu a', selectFormGroup);

});
})(jQuery);

</script>


<link href="https://venlocal.com/plugins/jquery-datetimepicker/jquery.datetimepicker.min.css" rel="stylesheet" />
<script src="https://venlocal.com/plugins/jquery-datetimepicker/jquery-ui.min.js"></script>
<script src="https://venlocal.com/plugins/jquery-datetimepicker/jquery.datetimepicker.full.min.js"></script>

<script>
$(document).ready(function() {
  $('#datetimepicker').datetimepicker({
	format: 'D, d M Y H:i:s',
  });
  $('#dateButton').click(function() {
	$('#datetimepicker').datetimepicker('show');
  });
});
</script>

<script type="text/javascript">
function popupImages(url, searchImageBtn) {
  var w = 1024;
  var h = 768;
  var title = 'Images';
  var left = (screen.width / 2) - (w / 2);
  var top = (screen.height / 2) - (h / 2);
  var popupWindow = window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
  addMessageListener(searchImageBtn);
}

function popup(url)
{
var w = 1024;
var h = 768;
var title = 'Social Tool';
var left = (screen.width / 2) - (w / 2);
var top = (screen.height / 2) - (h / 2);
window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
}

function addMessageListener(searchImageBtn) {
  window.addEventListener('message', (event) => importImageUrl(event, searchImageBtn));
}

function importImageUrl(event, searchImageBtn) {
  if (event.data.imageUrl) {
	let imageUrl = event.data.imageUrl;
	const parentForm = searchImageBtn.closest('form');
	const textBox = parentForm.querySelector('[name="image_url"]');
	textBox.value = imageUrl;
  }
}
</script>
