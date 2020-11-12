<?php

namespace Banrepcultural;

use ExtensionRegistry;
use HTMLForm;
use MediaWiki\MediaWikiServices;
use OutputPage;
use RequestContext;
use Skin;
use SkinTemplate;
use SkinBanrepcultural;
use User;

/**
 * Presentation hook handlers for Banrepcultural skin.
 *
 * Hook handler method names should be in the form of:
 *	on<HookName>()
 */
class Hooks {
	/**
	 * BeforePageDisplayMobile hook handler
	 *
	 * Make Legacy Banrepcultural responsive when $wgBanrepculturalResponsive = true
	 *
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/BeforePageDisplay
	 * @param OutputPage $out
	 * @param SkinTemplate $sk
	 */
	public static function onBeforePageDisplay( OutputPage $out, $sk ) {
		if ( !$sk instanceof SkinBanrepcultural ) {
			return;
		}

		$skinVersionLookup = new SkinVersionLookup(
			$out->getRequest(), $sk->getUser(), self::getServiceConfig()
		);

		$mobile = false;
		if ( ExtensionRegistry::getInstance()->isLoaded( 'MobileFrontend' ) ) {

			$mobFrontContext = MediaWikiServices::getInstance()->getService( 'MobileFrontend.Context' );
			$mobile = $mobFrontContext->shouldDisplayMobileView();
		}

		if ( $skinVersionLookup->isLegacy()
			&& ( $mobile || $sk->getConfig()->get( 'BanrepculturalResponsive' ) )
		) {
			$out->addMeta( 'viewport', 'width=device-width, initial-scale=1' );
			$out->addModuleStyles( 'skins.banrepcultural.styles.responsive' );
		}
	}

	/**
	 * Add icon class to an existing navigation item inside a menu hook.
	 * See self::onSkinTemplateNavigation.
	 * @param array $item
	 * @return array
	 */
	private static function navigationLinkToIcon( array $item ) {
		if ( !isset( $item['class'] ) ) {
			$item['class'] = '';
		}
		$item['class'] = rtrim( 'icon ' . $item['class'], ' ' );
		return $item;
	}

	/**
	 * Upgrades Banrepcultural's watch action to a watchstar.
	 *
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/SkinTemplateNavigation
	 * @param SkinTemplate $sk
	 * @param array &$content_navigation
	 */
	public static function onSkinTemplateNavigation( $sk, &$content_navigation ) {
		if (
			$sk->getSkinName() === 'banrepcultural' &&
			$sk->getConfig()->get( 'BanrepculturalUseIconWatch' )
		) {
			$key = null;
			if ( isset( $content_navigation['actions']['watch'] ) ) {
				$key = 'watch';
			}
			if ( isset( $content_navigation['actions']['unwatch'] ) ) {
				$key = 'unwatch';
			}

			// Promote watch link from actions to views and add an icon
			if ( $key !== null ) {
				$content_navigation['views'][$key] = self::navigationLinkToIcon(
					$content_navigation['actions'][$key]
				);
				unset( $content_navigation['actions'][$key] );
			}
		}
	}

	/**
	 * Add Banrepcultural preferences to the user's Special:Preferences page directly underneath skins.
	 *
	 * @param User $user User whose preferences are being modified.
	 * @param array[] &$prefs Preferences description array, to be fed to a HTMLForm object.
	 */
	public static function onGetPreferences( User $user, array &$prefs ) {
		if ( !self::getConfig( Constants::CONFIG_KEY_SHOW_SKIN_PREFERENCES ) ) {
			// Do not add Banrepcultural skin specific preferences.
			return;
		}

		$skinVersionLookup = new SkinVersionLookup(
			RequestContext::getMain()->getRequest(), $user, self::getServiceConfig()
		);

		// Preferences to add.
		$banrepculturalPrefs = [
			Constants::PREF_KEY_SKIN_VERSION => [
				'type' => 'toggle',
				// The checkbox title.
				'label-message' => 'prefs-banrepcultural-enable-banrepcultural-1-label',
				// Show a little informational snippet underneath the checkbox.
				'help-message' => 'prefs-banrepcultural-enable-banrepcultural-1-help',
				// The tab location and title of the section to insert the checkbox. The bit after the slash
				// indicates that a prefs-skin-prefs string will be provided.
				'section' => 'rendering/skin/skin-prefs',
				// Convert the preference string to a boolean presentation.
				'default' => $skinVersionLookup->isLegacy() ? '1' : '0',
				// Only show this section when the Banrepcultural skin is checked. The JavaScript client also uses
				// this state to determine whether to show or hide the whole section.
				'hide-if' => [ '!==', 'wpskin', Constants::SKIN_NAME ]
			],
			Constants::PREF_KEY_SIDEBAR_VISIBLE => [
				'type' => 'api',
				'default' => self::getConfig( Constants::CONFIG_KEY_DEFAULT_SIDEBAR_VISIBLE_FOR_AUTHORISED_USER )
			],
		];

		// Seek the skin preference section to add Banrepcultural preferences just below it.
		$skinSectionIndex = array_search( 'skin', array_keys( $prefs ) );
		if ( $skinSectionIndex !== false ) {
			// Skin preference section found. Inject Banrepcultural skin-specific preferences just below it.
			// This pattern can be found in Popups too. See T246162.
			$banrepculturalSectionIndex = $skinSectionIndex + 1;
			$prefs = array_slice( $prefs, 0, $banrepculturalSectionIndex, true )
				+ $banrepculturalPrefs
				+ array_slice( $prefs, $banrepculturalSectionIndex, null, true );
		} else {
			// Skin preference section not found. Just append Banrepcultural skin-specific preferences.
			$prefs += $banrepculturalPrefs;
		}
	}

	/**
	 * Hook executed on user's Special:Preferences form save. This is used to convert the boolean
	 * presentation of skin version to a version string. That is, a single preference change by the
	 * user may trigger two writes: a boolean followed by a string.
	 *
	 * @param array $formData Form data submitted by user
	 * @param HTMLForm $form A preferences form
	 * @param User $user Logged-in user
	 * @param bool &$result Variable defining is form save successful
	 * @param array $oldPreferences
	 */
	public static function onPreferencesFormPreSave(
		array $formData,
		HTMLForm $form,
		User $user,
		&$result,
		$oldPreferences
	) {
		$preference = null;
		$isBanrepculturalEnabled = ( $formData[ 'skin' ] ?? '' ) === Constants::SKIN_NAME;
		if ( $isBanrepculturalEnabled && array_key_exists( Constants::PREF_KEY_SKIN_VERSION, $formData ) ) {
			// A preference was set. However, Special:Preferences converts the result to a boolean when a
			// version name string is wanted instead. Convert the boolean to a version string in case the
			// preference display is changed to a list later (e.g., a "_new_ new Banrepcultural" / '3' or
			// 'alpha').
			$preference = $formData[ Constants::PREF_KEY_SKIN_VERSION ] ?
				Constants::SKIN_VERSION_LEGACY :
				Constants::SKIN_VERSION_LATEST;
		} elseif ( array_key_exists( Constants::PREF_KEY_SKIN_VERSION, $oldPreferences ) ) {
			// The setting was cleared. However, this is likely because a different skin was chosen and
			// the skin version preference was hidden.
			$preference = $oldPreferences[ Constants::PREF_KEY_SKIN_VERSION ];
		}
		if ( $preference !== null ) {
			$user->setOption( Constants::PREF_KEY_SKIN_VERSION, $preference );
		}
	}

	/**
	 * Called one time when initializing a users preferences for a newly created account.
	 *
	 * @param User $user Newly created user object.
	 * @param bool $isAutoCreated
	 */
	public static function onLocalUserCreated( User $user, $isAutoCreated ) {
		$default = self::getConfig( Constants::CONFIG_KEY_DEFAULT_SKIN_VERSION_FOR_NEW_ACCOUNTS );
		// Permanently set the default preference. The user can later change this preference, however,
		// self::onLocalUserCreated() will not be executed for that account again.
		$user->setOption( Constants::PREF_KEY_SKIN_VERSION, $default );
	}

	/**
	 * Called when OutputPage::headElement is creating the body tag to allow skins
	 * and extensions to add attributes they might need to the body of the page.
	 *
	 * @param OutputPage $out
	 * @param Skin $sk
	 * @param string[] &$bodyAttrs
	 */
	public static function onOutputPageBodyAttributes( OutputPage $out, Skin $sk, &$bodyAttrs ) {
		$skinVersionLookup = new SkinVersionLookup(
			$out->getRequest(), $sk->getUser(), self::getServiceConfig()
		);

		if ( $skinVersionLookup->isLegacy() ) {
			$bodyAttrs['class'] .= ' skin-banrepcultural-legacy';
			return;
		}

		if ( self::getConfig( Constants::CONFIG_KEY_LAYOUT_MAX_WIDTH ) ) {
			$bodyAttrs['class'] .= ' skin-banrepcultural-max-width';
		}
	}

	/**
	 * Get a configuration variable such as `Constants::CONFIG_KEY_SHOW_SKIN_PREFERENCES`.
	 *
	 * @param string $name Name of configuration option.
	 * @return mixed Value configured.
	 * @throws \ConfigException
	 */
	private static function getConfig( $name ) {
		return self::getServiceConfig()->get( $name );
	}

	/**
	 * @return \Config
	 */
	private static function getServiceConfig() {
		return MediaWikiServices::getInstance()->getService( Constants::SERVICE_CONFIG );
	}
}
