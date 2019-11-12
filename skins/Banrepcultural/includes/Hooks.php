<?php

namespace Banrepcultural;

use OutputPage;
use SkinTemplate;
use SkinBanrepcultural;

/**
 * Hook handlers for Banrepcultural skin.
 *
 * Hook handler method names should be in the form of:
 *	on<HookName>()
 */
class Hooks {
	/**
	 * BeforePageDisplayMobile hook handler
	 *
	 * Make Banrepcultural responsive when operating in mobile mode (useformat=mobile)
	 *
	 * @see https://www.mediawiki.org/wiki/Extension:MobileFrontend/BeforePageDisplayMobile
	 * @param OutputPage $out
	 * @param SkinTemplate $sk
	 */
	public static function onBeforePageDisplayMobile( OutputPage $out, $sk ) {
		// This makes Banrepcultural behave in responsive mode when MobileFrontend is installed
		if ( $sk instanceof SkinBanrepcultural ) {
			$sk->enableResponsiveMode();
		}
	}
}
