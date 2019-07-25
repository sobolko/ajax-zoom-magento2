require(['jquery'], function($) {$(function() {
	console.log('xo');
	if (jQuery.blueimpFP && jQuery.blueimpFP.fileupload){
		console.log('xo2');
		jQuery.widget('blueimpFP.fileupload', jQuery.blueimpFP.fileupload, {
			processActions: {
				resize: function(data, options){
					console.log('xo3');
					return data;
				}
			}
		});
	}
});});