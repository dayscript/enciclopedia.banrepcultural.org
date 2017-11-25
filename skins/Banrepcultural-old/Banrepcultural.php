<?php

class SkinBanrepcultural extends SkinTemplate {

	public $skinname = 'banrepcultural';

	public $template = 'BanrepculturalTemplate';

	static function onBeforePageDisplay( OutputPage &$out, Skin &$skin ) {
		global $wgDefaultSkin;
		if ( $wgDefaultSkin === 'banrepcultural' ) {
			$out->addModuleStyles( 'skins.banrepcultural.styles' );
			$out->addModuleScripts( 'skins.banrepcultural.scripts' );
			$out->addMeta( 'viewport', 'width=device-width' );
		}
	}
}

class BanrepculturalTemplate extends BaseTemplate {

	/**
	 * Merge together the views, actions and variants
	 * and remove the current action, per useless and confusing
	 */
	function getActions() {
		global $mediaWiki;
		$actions = array_merge(
			$this->data['content_navigation']['views'],
			$this->data['content_navigation']['actions'],
			$this->data['content_navigation']['variants']
		);
		$action = $mediaWiki->getAction();
		unset( $actions[ $action ] ); // Remove the current action (doesn't work with Move)
		return $actions;
	}

	/**
	 * Output the page
	 */
	function execute() {
		global $mediaWiki, $wgResourceBasePath;
		$Title = $mediaWiki->getTitle();
		$actions = $this->getActions();
		$images = $wgResourceBasePath . '/skins/Banrepcultural/images/';
		include 'Banrepcultural.phtml';
	}
}