$(function() {
	var banrepculturalMobileElements = false,
	function setupBanrepculturalMobile() {
		if ( !banrepculturalMobileElements ) {
			
			$('#mw-navigation').insertAfter('#mw-page-base');

			banrepculturalMobileElements = true;
		}
	}
	console.log('mobile');
	$( window ).on( 'resize', setupBanrepculturalMobile );
	setupBanrepculturalMobile();
});
