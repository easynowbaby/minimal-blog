/******************
*******************
*
*  AJAX
*
*******************
*******************/

var input = document.getElementById("pic");
var formdata = false;

// CACHING VARIABLES
var $inputTitle = $('.create input[name="title"]');
var $inputAuthor = $('.create input[name="author"]');
var $inputCategory = $('.create input[name="category"]');
var $inputText = $('.create textarea');
var $makeChanges = $('#make-changes');
var $deletePost = $('#delete-post');
createFormData();
displayExistingArticles();



// CREATE ARTICLE & UPLOAD IMAGES
$(function(){
  $(document).on('click', '#create-post', function(e){
    e.preventDefault();    
    formdata.append("title", $('.create input[name="title"]').val());
    formdata.append("body", $('.create textarea').val());
    formdata.append("author", $('.create input[name="author"]').val());
    formdata.append("category", $('.create input[name="category"]').val());      
    $.ajax({
      url: '../admin/index.php',
      type: 'post',
      processData: false,
      contentType: false,      
      data: formdata,
      success: function(data, status) {        
        $('#status').html(data).addClass('success');
        setTimeout(function(){
          $('#status').removeClass('success');
        }, 1000);
        clearInputs()
        displayExistingArticles();
        createFormData();
      },
      error: function(xhr, desc, err) {
        console.log(xhr);
        console.log("Details: " + desc + "\nError:" + err);
      }
    }); // end ajax call
  });
});

// SAVE CHANGES THAT WERE MADE TO THE DB
$(function(){
  $(document).on('click', '#make-changes', function(e){
    e.preventDefault();
    var self = $(this);     
    var id = parseInt($(this).attr('data-id'));    
    if (!id) {
      $('#status').html('Please select an article to edit it.').addClass('error');
      setTimeout(function(){
        $('#status').removeClass('error');
      }, 1000);
      return;
    }
    var json = {
      'id' : id,
      'title': $('.create input[name="title"]').val(),
      'author': $('.create input[name="author"]').val(),
      'category': $('.create input[name="category"]').val(),
      'body' : $('.create textarea').val()
    };       
    formdata.append("changes", JSON.stringify(json));    
    $.ajax({
      url: '../admin/index.php',
      type: 'post',
      processData: false,
      contentType: false, 
      data: formdata,
      success: function(data, status) {        
        $('#status').html(data).addClass('success');
        setTimeout(function(){
          $('#status').removeClass('success');
        }, 1000);
        clearInputs();
        $('.delete-picture').remove();
        displayExistingArticles();
        $('#make-changes').attr('data-id', '');
        $('#delete-post').attr('data-id', '');
        createFormData();        
        //makeNewArticle();
      },
      error: function(xhr, desc, err) {
        console.log(xhr);
        console.log("Details: " + desc + "\nError:" + err);
      }
    }); // end ajax call
  });
});

// PREPARE IMAGES FOR UPLOAD 
$(function(){
  input.addEventListener("change", function (evt) {
    var len = this.files.length, img, reader, file;     
    for (var i = 0; i < len; i++ ) {
      file = this.files[i]; 
      if (!!file.type.match(/image.*/)) {
        if ( window.FileReader ) {
          reader = new FileReader();
          reader.onloadend = function (e) { 
            showUploadedItem(e.target.result);
          };
          reader.readAsDataURL(file);
        }
        if (formdata) {
          formdata.append("pic[]", file);
        }        
      } 
    }      
  }, false);
});

// SHOW DATA OF ARTICLE BEING EDITED IN FORM INPUTS
$(function(){
  $('#edit-post').on('click', function(e){
    e.preventDefault();
    // remove old pictures
    $('.delete-picture').remove();
    var data = $('.edit select').val();  
    if ($('#make-changes').length && $('#delete-post').length) {
      $('#make-changes').attr('data-id', data);
      $('#delete-post').attr('data-id', data);   
    }
    $.ajax({
      url: '../admin/index.php',
      type: 'post',
      data: {'edit': data},
      success: function(data, status) {
      	var json = jQuery.parseJSON( data );      	
      	$('.create input[name="title"]').val(json.name);
      	$('.create input[name="author"]').val(json.author);
      	$('.create input[name="category"]').val(json.category);
      	$('.create textarea').val(json.text).text();
      	$('#create-post-li').hide();        
      	if (!$('#make-changes').length) {
      		$('.create .buttons').append('<li><input id="make-changes" type="submit" value="Save Changes" ></li>');
      		$('.create .buttons').append('<li><input id="delete-post" type="submit" value="Delete Post"></li>');
          $('.create .buttons').append('<li><input id="back-to-make-post" type="submit" value="Make New"></li>');
      	}
      	$('#make-changes, #delete-post').attr('data-id', json.id);        
        if (!$('.uploaded-pictures').length) {
          $('.create .uploads').append('<ul class="uploaded-pictures"></ul>');
          $('.create .uploaded-pictures').append('<p>Currently uploaded pictures. Press button to delete: </p>');
        }
        if (json.pic_url) {
          listPictures(json.pic_url);
        }      
      },
      error: function(xhr, desc, err) {
        console.log(xhr);
        console.log("Details: " + desc + "\nError:" + err);
      }
    }); // end ajax call
  });
});

// DELETE ARTICLE
$(function(){
  $(document).on('click','#delete-post', function(e){
    e.preventDefault();
    var id = parseInt($(this).attr('data-id'));   
    if (!id) {
      $('#status').html('Please select an article to edit it.').addClass('error');
      setTimeout(function(){
        $('#status').removeClass('error');
      }, 1000);
      return;
    }
    if (confirm("Do you really want to delete this post?")) {    	 
	    $.ajax({
	      url: '../admin/index.php',
	      type: 'post',
	      data: {'delete': id},
	      success: function(data, status) {	      
	        $('#status').html(data).addClass('success');
          setTimeout(function(){
            $('#status').removeClass('success');
          }, 1000);
          clearInputs();
          $('.delete-picture').remove();          
          displayExistingArticles();
          $('#make-changes').attr('data-id', '');
          $('#delete-post').attr('data-id', '');
	      },
	      error: function(xhr, desc, err) {
	        console.log(xhr);
	        console.log("Details: " + desc + "\nError:" + err);
	      }
    	});  // end ajax call  
    } 
  });
});

// DELETE PICTURE
$(function(){
  $(document).on('click','.delete-picture button', function(e){
    e.preventDefault();    
    var self = $(this);
    if (confirm("Do you really want to delete this picture?")) {
      // remove deleted url and deal with commas - ugly as fuck but you gotta do what you gotta do
      var url = $(this).data('url');
      var allUrls = $('#make-changes').data('pics');      
      var replaced = allUrls.replace(url,'');        
      var trailingCom = replaced.replace(/^\,|\,$/g, "");
      var doubleCom = trailingCom.replace(/(\,)\1h/, ",h");      
      $.ajax({
        url: '../admin/index.php',
        type: 'post',
        data: {
          'delete-picture': doubleCom,
          'id' : $('#make-changes').data('id')
        },
        success: function(data, status) {
          $('#status').html(data).addClass('success');
          setTimeout(function(){
            $('#status').removeClass('success');
          }, 1000);
          self.fadeOut();
        },
        error: function(xhr, desc, err) {
          console.log(xhr);
          console.log("Details: " + desc + "\nError:" + err);
        }
      });  // end ajax call  
    } 
  });
});

// DISPLAY EXISTING ARTICLES IN UL
function displayExistingArticles() {
  $('#edit option').remove();
  $.ajax({
      url: '../admin/index.php',
      type: 'post',
      data: 'fetch-editable',
      success: function(json, status) {      
        var json = JSON.parse(json);
        for (key in json) {        
          $('#edit').append('<option value="' + json[key]['id'] + '">' + json[key]['name'] + '</option>');
        }      
      },
      error: function(xhr, desc, err) {
        console.log(xhr);
        console.log("Details: " + desc + "\nError:" + err);
      }
    });  // end ajax call      
}

$(document).on('click', '#back-to-make-post', function(e){ 
 e.preventDefault(); 
 makeNewArticle(); 
})


// HELPER FUNCTIONS
function createFormData() {
  if (window.FormData) {
    formdata = new FormData();
    if ($('#upload-picture').length) {
      $('#upload-picture').hide();
    }
  }
}

function showUploadedItem (source) {
  var list = document.getElementById("image-list"),
      li   = document.createElement("li"),
      img  = document.createElement("img");
  img.src = source;
  li.appendChild(img);
  list.appendChild(li);
}

function listPictures(str) {  
  var arrRev = str.split(',');
  var arr = arrRev.reverse();
  $('#make-changes').attr('data-pics', arr);
  for (var i = arr.length - 1; i >= 0; i--) {
    var split = arr[i].split('/');
    $('.create .uploaded-pictures').append('<li class="delete-picture"><button data-url="' + arr[i] + '">' + split.pop() + '</button></li>')
  }
}

function clearInputs() {
  $inputTitle.val('');
  $inputAuthor.val('');
  $inputCategory.val('');
  $inputText.val('');
}

function makeNewArticle() {
  clearInputs();  
  $('#create-post-li').show();
  $('.buttons li').not('#create-post-li').remove();
  $('.uploaded-pictures').remove();
}