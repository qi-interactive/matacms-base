window.matacms.infinitePager = window.matacms.infinitePager || {};
matacms.infinitePager.content;
matacms.infinitePager.nextPageUrl;
matacms.infinitePager.isLoading = false;

matacms.infinitePager.init = function(opts) {

	matacms.infinitePager.nextPageUrl = $('#'+opts.listViewId).children('ul.pagination').find('li.next a').attr('href');

	$('#'+opts.pjax.id).on('pjax:end', function(e, contents, options) {
		$('#'+opts.listViewId).prepend(matacms.infinitePager.content);
		window.top.mata.simpleTheme.ajaxLoader.stop();	
		matacms.infinitePager.isLoading = false;
	});
	
	$('#'+opts.pjax.id).on('pjax:beforeReplace', function(e, contents, options) {
		matacms.infinitePager.content = $('#'+opts.pjax.id).contents().children('div[data-key]');
		matacms.infinitePager.nextPageUrl = $('#'+opts.pjax.id).contents().children('ul.pagination').find('li.next a').attr('href');
		e.stopPropagation();
		return false;
	});

	$('body').on("scroll", function() {
    	if($(opts.itemSelector, '#'+opts.listViewId).length > 2) {
    		var hT = $(opts.itemSelector, '#'+opts.listViewId).eq(-2).offset().top,
			hH = $(opts.itemSelector, '#'+opts.listViewId).eq(-2).outerHeight(),
			wH = $('body').height(),
			wS = $(this).scrollTop()+(hH);

			if (wS > (hT+hH-wH) && matacms.infinitePager.nextPageUrl != undefined && !matacms.infinitePager.isLoading){
				$('#'+opts.listViewId).children('ul.pagination').find('li.next a').trigger('click');
				matacms.infinitePager.isLoading = true;
				window.top.mata.simpleTheme.ajaxLoader.run();	
			}
    	}
		
	});
	
}
