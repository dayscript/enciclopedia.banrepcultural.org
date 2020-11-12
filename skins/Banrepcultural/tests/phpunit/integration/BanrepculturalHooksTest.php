<?php
/*
 * @file
 * @ingroup skins
 */

use Banrepcultural\Hooks;

const SKIN_PREFS_SECTION = 'rendering/skin/skin-prefs';

/**
 * Integration tests for Banrepcultural Hooks.
 *
 * @group Banrepcultural
 * @coversDefaultClass \Banrepcultural\Hooks
 */
class BanrepculturalHooksTest extends \MediaWikiTestCase {
	/**
	 * @covers ::onGetPreferences
	 */
	public function testOnGetPreferencesShowPreferencesDisabled() {
		$config = new HashConfig( [
			'BanrepculturalShowSkinPreferences' => false,
		] );
		$this->setService( 'Banrepcultural.Config', $config );

		$prefs = [];
		Hooks::onGetPreferences( $this->getTestUser()->getUser(), $prefs );
		$this->assertSame( $prefs, [], 'No preferences are added.' );
	}

	/**
	 * @covers ::onGetPreferences
	 */
	public function testOnGetPreferencesShowPreferencesEnabledSkinSectionFoundLegacy() {
		$config = new HashConfig( [
			'BanrepculturalShowSkinPreferences' => true,
			// '1' is Legacy.
			'BanrepculturalDefaultSkinVersionForExistingAccounts' => '1',
			'BanrepculturalDefaultSidebarVisibleForAuthorisedUser' => true
		] );
		$this->setService( 'Banrepcultural.Config', $config );

		$prefs = [
			'foo' => [],
			'skin' => [],
			'bar' => []
		];
		Hooks::onGetPreferences( $this->getTestUser()->getUser(), $prefs );
		$this->assertSame(
			$prefs,
			[
				'foo' => [],
				'skin' => [],
				'BanrepculturalSkinVersion' => [
					'type' => 'toggle',
					'label-message' => 'prefs-banrepcultural-enable-banrepcultural-1-label',
					'help-message' => 'prefs-banrepcultural-enable-banrepcultural-1-help',
					'section' => SKIN_PREFS_SECTION,
					// '1' is enabled which means Legacy.
					'default' => '1',
					'hide-if' => [ '!==', 'wpskin', 'banrepcultural' ]
				],
				'BanrepculturalSidebarVisible' => [
					'type' => 'api',
					'default' => true
				],
				'bar' => []
			],
			'Preferences are inserted directly after skin.'
		);
	}

	/**
	 * @covers ::onGetPreferences
	 */
	public function testOnGetPreferencesShowPreferencesEnabledSkinSectionMissingLegacy() {
		$config = new HashConfig( [
			'BanrepculturalShowSkinPreferences' => true,
			// '1' is Legacy.
			'BanrepculturalDefaultSkinVersionForExistingAccounts' => '1',
			'BanrepculturalDefaultSidebarVisibleForAuthorisedUser' => true
		] );
		$this->setService( 'Banrepcultural.Config', $config );

		$prefs = [
			'foo' => [],
			'bar' => []
		];
		Hooks::onGetPreferences( $this->getTestUser()->getUser(), $prefs );
		$this->assertSame(
			$prefs,
			[
				'foo' => [],
				'bar' => [],
				'BanrepculturalSkinVersion' => [
					'type' => 'toggle',
					'label-message' => 'prefs-banrepcultural-enable-banrepcultural-1-label',
					'help-message' => 'prefs-banrepcultural-enable-banrepcultural-1-help',
					'section' => SKIN_PREFS_SECTION,
					// '1' is enabled which means Legacy.
					'default' => '1',
					'hide-if' => [ '!==', 'wpskin', 'banrepcultural' ]
				],
				'BanrepculturalSidebarVisible' => [
					'type' => 'api',
					'default' => true
				],
			],
			'Preferences are appended.'
		);
	}

	/**
	 * @covers ::onGetPreferences
	 */
	public function testOnGetPreferencesShowPreferencesEnabledSkinSectionMissingLatest() {
		$config = new HashConfig( [
			'BanrepculturalShowSkinPreferences' => true,
			// '2' is latest.
			'BanrepculturalDefaultSkinVersionForExistingAccounts' => '2',
			'BanrepculturalDefaultSidebarVisibleForAuthorisedUser' => true
		] );
		$this->setService( 'Banrepcultural.Config', $config );

		$prefs = [
			'foo' => [],
			'bar' => [],
		];
		Hooks::onGetPreferences( $this->getTestUser()->getUser(), $prefs );
		$this->assertSame(
			$prefs,
			[
				'foo' => [],
				'bar' => [],
				'BanrepculturalSkinVersion' => [
					'type' => 'toggle',
					'label-message' => 'prefs-banrepcultural-enable-banrepcultural-1-label',
					'help-message' => 'prefs-banrepcultural-enable-banrepcultural-1-help',
					'section' => SKIN_PREFS_SECTION,
					// '0' is disabled (which means latest).
					'default' => '0',
					'hide-if' => [ '!==', 'wpskin', 'banrepcultural' ]
				],
				'BanrepculturalSidebarVisible' => [
					'type' => 'api',
					'default' => true
				],
			],
			'Legacy skin version is disabled.'
		);
	}

	/**
	 * @covers ::onPreferencesFormPreSave
	 */
	public function testOnPreferencesFormPreSaveBanrepculturalEnabledLegacyNewPreference() {
		$formData = [
			'skin' => 'banrepcultural',
			// True is Legacy.
			'BanrepculturalSkinVersion' => true,
		];
		$form = $this->createMock( HTMLForm::class );
		$user = $this->createMock( \User::class );
		$user->expects( $this->once() )
			->method( 'setOption' )
			// '1' is Legacy.
			->with( 'BanrepculturalSkinVersion', '1' );
		$result = true;
		$oldPreferences = [];

		Hooks::onPreferencesFormPreSave( $formData, $form, $user, $result, $oldPreferences );
	}

	/**
	 * @covers ::onPreferencesFormPreSave
	 */
	public function testOnPreferencesFormPreSaveBanrepculturalEnabledLatestNewPreference() {
		$formData = [
			'skin' => 'banrepcultural',
			// False is latest.
			'BanrepculturalSkinVersion' => false,
		];
		$form = $this->createMock( HTMLForm::class );
		$user = $this->createMock( \User::class );
		$user->expects( $this->once() )
			->method( 'setOption' )
			// '2' is latest.
			->with( 'BanrepculturalSkinVersion', '2' );
		$result = true;
		$oldPreferences = [];

		Hooks::onPreferencesFormPreSave( $formData, $form, $user, $result, $oldPreferences );
	}

	/**
	 * @covers ::onPreferencesFormPreSave
	 */
	public function testOnPreferencesFormPreSaveBanrepculturalEnabledNoNewPreference() {
		$formData = [
			'skin' => 'banrepcultural',
		];
		$form = $this->createMock( HTMLForm::class );
		$user = $this->createMock( \User::class );
		$user->expects( $this->never() )
			->method( 'setOption' );
		$result = true;
		$oldPreferences = [];

		Hooks::onPreferencesFormPreSave( $formData, $form, $user, $result, $oldPreferences );
	}

	/**
	 * @covers ::onPreferencesFormPreSave
	 */
	public function testOnPreferencesFormPreSaveBanrepculturalDisabledNoOldPreference() {
		$formData = [
			// False is latest.
			'BanrepculturalSkinVersion' => false,
		];
		$form = $this->createMock( HTMLForm::class );
		$user = $this->createMock( \User::class );
		$user->expects( $this->never() )
			->method( 'setOption' );
		$result = true;
		$oldPreferences = [];

		Hooks::onPreferencesFormPreSave( $formData, $form, $user, $result, $oldPreferences );
	}

	/**
	 * @covers ::onPreferencesFormPreSave
	 */
	public function testOnPreferencesFormPreSaveBanrepculturalDisabledOldPreference() {
		$formData = [
			// False is latest.
			'BanrepculturalSkinVersion' => false,
		];
		$form = $this->createMock( HTMLForm::class );
		$user = $this->createMock( \User::class );
		$user->expects( $this->once() )
			->method( 'setOption' )
			->with( 'BanrepculturalSkinVersion', 'old' );
		$result = true;
		$oldPreferences = [
			'BanrepculturalSkinVersion' => 'old',
		];

		Hooks::onPreferencesFormPreSave( $formData, $form, $user, $result, $oldPreferences );
	}

	/**
	 * @covers ::onLocalUserCreated
	 */
	public function testOnLocalUserCreatedLegacy() {
		$config = new HashConfig( [
			// '1' is Legacy.
			'BanrepculturalDefaultSkinVersionForNewAccounts' => '1',
		] );
		$this->setService( 'Banrepcultural.Config', $config );

		$user = $this->createMock( \User::class );
		$user->expects( $this->once() )
		->method( 'setOption' )
			// '1' is Legacy.
			->with( 'BanrepculturalSkinVersion', '1' );
		$isAutoCreated = false;
		Hooks::onLocalUserCreated( $user, $isAutoCreated );
	}

	/**
	 * @covers ::onLocalUserCreated
	 */
	public function testOnLocalUserCreatedLatest() {
		$config = new HashConfig( [
			// '2' is latest.
			'BanrepculturalDefaultSkinVersionForNewAccounts' => '2',
		] );
		$this->setService( 'Banrepcultural.Config', $config );

		$user = $this->createMock( \User::class );
		$user->expects( $this->once() )
		->method( 'setOption' )
			// '2' is latest.
			->with( 'BanrepculturalSkinVersion', '2' );
		$isAutoCreated = false;
		Hooks::onLocalUserCreated( $user, $isAutoCreated );
	}

	/**
	 * @covers ::onSkinTemplateNavigation
	 */
	public function testOnSkinTemplateNavigation() {
		$this->setMwGlobals( [
			'wgBanrepculturalUseIconWatch' => true
		] );
		$skin = new SkinBanrepcultural();
		$contentNavWatch = [
			'actions' => [
				'watch' => [ 'class' => 'watch' ],
			]
		];
		$contentNavUnWatch = [
			'actions' => [
				'move' => [ 'class' => 'move' ],
				'unwatch' => [],
			],
		];

		Hooks::onSkinTemplateNavigation( $skin, $contentNavUnWatch );
		Hooks::onSkinTemplateNavigation( $skin, $contentNavWatch );

		$this->assertTrue(
			strpos( $contentNavWatch['views']['watch']['class'], 'icon' ) !== false,
			'Watch list items require an "icon" class'
		);
		$this->assertTrue(
			strpos( $contentNavUnWatch['views']['unwatch']['class'], 'icon' ) !== false,
			'Unwatch list items require an "icon" class'
		);
		$this->assertFalse(
			strpos( $contentNavUnWatch['actions']['move']['class'], 'icon' ) !== false,
			'List item other than watch or unwatch should not have an "icon" class'
		);
	}
}
