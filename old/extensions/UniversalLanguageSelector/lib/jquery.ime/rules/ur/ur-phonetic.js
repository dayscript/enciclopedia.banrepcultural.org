( function ( $ ) {
	'use strict';

	var urPhonetic = {
		id: 'ur-phonetic',
		name: 'صوتی',
		description: 'Phonetic keyboard for Urdu script',
		date: '2013-02-18',
		URL: 'http://cvs.savannah.gnu.org/viewvc/m17n-contrib/im/ur-phonetic.mim?root=m17n&view=markup',
		author: 'Parag Nemade',
		license: 'GPLv3',
		version: '1.0',
		patterns: [
			[ '!', '!' ],
			[ '\\#', '/' ],
			/* Yeh with hamza above */ [ '\\$', 'ئ' ],
			[ '\\(', ')' ],
			[ '\\)', '(' ],
			[ '\\_" "ّ' ],
			/* Alef with hamza above */ [ '\\-', 'أ' ],
			/* Alef with madda above */ [ '\\+', 'آ' ],
			/* Waw with hamza above */ [ '\\=', 'ؤ' ],
			[ 'Q', 'ْ' ],
			[ 'q', 'ق' ],
			[ 'W', 'ﷺ' ],
			[ 'w', 'و' ],
			[ 'E', '¥' ],
			[ 'e', 'ع' ],
			[ 'R', 'ڑ' ],
			[ 'r', 'ر' ],
			[ 'T', 'ٹ' ],
			[ 't', 'ت' ],
			[ 'Y', '\u0601' ],
			[ 'y', 'ے' ],
			[ 'U', '،' ],
			[ 'u', 'ء' ],
			[ 'I', 'ٰ' ],
			[ 'i', 'ی' ],
			/* Teh marbuta goal */ [ 'O', 'ۃ' ],
			[ 'o', 'ہ' ],
			[ 'P', 'ُ' ],
			[ 'p', 'پ' ],
			[ '\\{', 'ٰ' ],
			[ '\\[', ']' ],
			[ '\\}', 'ٰٰ' ],
			[ '\\]', '[' ],
			[ 'A', 'ٓ' ],
			[ 'a', 'ا' ],
			[ 'S', 'ص' ],
			[ 's', 'س' ],
			[ 'D', 'ڈ' ],
			[ 'd', 'د' ],
			[ 'F', '' ],
			[ 'f', 'ف' ],
			[ 'G', 'غ' ],
			[ 'g', 'گ' ],
			[ 'H', 'ح' ],
			[ 'h', 'ھ' ],
			[ 'J', 'ض' ],
			[ 'j', 'ج' ],
			[ 'K', 'خ' ],
			[ 'k', 'ک' ],
			[ 'L', '\u0613' ],
			[ 'l', 'ل' ],
			[ ':', ':' ],
			/* Arabic semicolon */ [ ';', '؛' ],
			[ '\"', '؎' ],
			[ '\'', 'ٰ' ],
			[ '\\|', 'ؔ' ],
			[ '\\', '؎' ],
			[ '\\~', 'ً' ],
			[ '\\`', 'ٍ' ],
			[ 'Z', 'ذ' ],
			[ 'z', 'ز' ],
			[ 'X', 'ژ' ],
			[ 'x', 'ش' ],
			[ 'C', 'ث' ],
			[ 'c', 'چ' ],
			[ 'V', 'ظ' ],
			[ 'v', 'ط' ],
			[ 'B', 'ؒ' ],
			[ 'b', 'ب' ],
			[ 'N', 'ں' ],
			[ 'n', 'ن' ],
			[ 'M', '' ],
			[ 'm', 'م' ],
			[ '\\<', 'ِ' ],
			[ ',', '،' ],
			[ '\\>', 'َ' ],
			/* Arabic full stop */ [ '\\.', '۔' ],
			/* Arabic question mark */ [ '\\?', '؟' ],
			[ '\\^', 'ۖ' ],
			[ '\\&', 'ٔ' ],
			[ '\\*', 'ٌ' ] ]
	};

	$.ime.register( urPhonetic );
}( jQuery ) );
