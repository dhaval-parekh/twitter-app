// JavaScript Document
jQuery(document).ready(function(e) {
	jQuery('.carousel').carousel({
		interval: 5000 //changes the speed
	});
});



//	Site Function 
var site  = {} || site;
site = {
	openDownloadModal : function(){
		jQuery('#modalDownloadTweets').modal('show');
	},
	showTweet: function (id){
		if(typeof(id) == 'undefined' || isNaN(id)){ return false; }
		var request = new Object();
		request.id = id;
		jQuery.ajax({
			url: api_url+'getsingletweet/',
			type:'POST',
			data:request,
			success: function(data,status,xhr){
				if(data.status != 200){
					alert(data.message);
					return false;
				}
				//console.log(data)
			},
			error: function(xhr,status,error){
				console.log(error);
			}
				
		}) 
	},
	getUserTweets: function(screen_name){
		if(typeof(screen_name) == 'undefined' ){ return false; }
		var request = new Object();
		request.screen_name = screen_name;
		request.isHtml = 1;
		jQuery('.user-tweets-slider-container').addClass('loading');
		jQuery.ajax({
			url: api_url+'getusertweets/',
			type:'POST',
			data:request,
			success: function(data,status,xhr){
				jQuery('.user-tweets-slider-container').html(data.payload).removeClass('loading');
				if(data.status != 200){
					alert(data.message);
					return false;
				}
				//console.log(data.payload)
				jQuery('.carousel').carousel({ interval: 5000 });

			},
			error: function(xhr,status,error){
				console.log(error);
				
			}
				
		}) 
	}
}