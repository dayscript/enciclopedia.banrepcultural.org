$(function () {

var banrepcultural = {
	init: function () {
		banrepcultural.bind();
	},
	bind: function () {
		$( '#menu > li' ).mouseover( banrepcultural.showSubmenu ).mouseout( banrepcultural.hideSubmenu );
		$( '#search-form button' ).click( banrepcultural.showSearchInput );
		$( '#search-form input' ).focusout( banrepcultural.hideSearchInput );
	},
	showSubmenu: function () {
		var menu = $( this ),
			submenu = $( '.submenu', menu );
		if ( !submenu.is( ':visible' ) ) {
			submenu.show();
		}
	},
	hideSubmenu: function () {
		var menu = $( this );
		menu.children( '.submenu' ).hide();
	},
	toggleSubmenu: function () {
		var menu = $( this ),
			submenu = $( '.submenu', menu );
		if ( submenu.is( ':visible' ) ) {
			submenu.hide();
		} else {
			submenu.show();
		}
	},
	showSearchInput: function () {
		var searchButton = $( this ),
			searchForm = searchButton.parent(),
			searchInput = $( 'input', searchForm );
		if ( !searchInput.is( ':visible' ) ) {
			searchInput.show().focus();
			searchButton.addClass( 'active' );
			return false;
		}
	},
	hideSearchInput: function () {
		var searchInput = $( this ),
			searchForm = searchInput.parent(),
			searchButton = $( 'button', searchForm );

		searchInput.hide();
		searchButton.removeClass( 'active' );
	}
}

$(banrepcultural.init);
});
