$( function() {
    var div = document.getElementById("scrollEvents");
	var scroll = div.scrollTop;
    // increase the scroll position by 10 px every 10th of a second
    var original = div.scrollTop; //save the original position
    setInterval(function () {
        // make sure it's not at the bottom
        if (div.scrollTop < div.scrollHeight - div.clientHeight) {
                  $("#scrollEvents").animate({scrollTop: scroll + $("#scrollEvents").height() + 5},3000); // move down one image
          scroll += $("#scrollEvents").height() + 5;
        }
        else{ //if it's at the bottom
            $("#scrollEvents").animate({ 
              scrollTop: original
            },1000); // move down one image
          scroll = original;
        }
    }, 5000); // 5 second intervals between change so user has two seconds to look a flyer
});