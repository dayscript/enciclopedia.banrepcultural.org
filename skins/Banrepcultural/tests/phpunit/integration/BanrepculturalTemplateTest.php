<?php
namespace MediaWiki\Skins\Banrepcultural\Tests\Integration;

use GlobalVarConfig;
use MediaWikiIntegrationTestCase;
use RequestContext;
use TemplateParser;
use Title;
use BanrepculturalTemplate;
use Wikimedia\TestingAccessWrapper;

/**
 * Class BanrepculturalTemplateTest
 * @package MediaWiki\Skins\Banrepcultural\Tests\Unit
 * @group Banrepcultural
 * @group Skins
 *
 * @coversDefaultClass \BanrepculturalTemplate
 */
class BanrepculturalTemplateTest extends MediaWikiIntegrationTestCase {

	/**
	 * @return \BanrepculturalTemplate
	 */
	private function provideBanrepculturalTemplateObject() {
		$template = new BanrepculturalTemplate(
			GlobalVarConfig::newInstance(),
			new TemplateParser(),
			true
		);
		$template->set( 'skin', new \SkinBanrepcultural() );
		return $template;
	}

	/**
	 * @param string $nodeString an HTML of the node we want to verify
	 * @param string $tag Tag of the element we want to check
	 * @param string $attribute Attribute of the element we want to check
	 * @param string $search Value of the attribute we want to verify
	 * @return bool
	 */
	private function expectNodeAttribute( $nodeString, $tag, $attribute, $search ) {
		$node = new \DOMDocument();
		$node->loadHTML( $nodeString );
		$element = $node->getElementsByTagName( $tag )->item( 0 );
		if ( !$element ) {
			return false;
		}

		$values = explode( ' ', $element->getAttribute( $attribute ) );
		return in_array( $search, $values );
	}

	/**
	 * @covers ::getMenuData
	 */
	public function testMakeListItemRespectsCollapsibleOption() {
		$banrepculturalTemplate = $this->provideBanrepculturalTemplateObject();
		$template = TestingAccessWrapper::newFromObject( $banrepculturalTemplate );
		$listItemClass = 'my_test_class';
		$options = [ 'banrepcultural-collapsible' => true ];
		$item = [ 'class' => $listItemClass ];
		$propsCollapsible = $template->getMenuData(
			'foo',
			[
				'bar' => $item,
			],
			0,
			$options
		);
		$propsNonCollapsible = $template->getMenuData(
			'foo',
			[
				'bar' => $item,
			],
			0,
			[]
		);
		$nonCollapsible = $propsNonCollapsible['html-items'];
		$collapsible = $propsCollapsible['html-items'];

		$this->assertTrue(
			$this->expectNodeAttribute( $collapsible, 'li', 'class', 'collapsible' ),
			'The collapsible element has to have `collapsible` class'
		);
		$this->assertFalse(
			$this->expectNodeAttribute( $nonCollapsible, 'li', 'class', 'collapsible' ),
			'The non-collapsible element should not have `collapsible` class'
		);
		$this->assertTrue(
			$this->expectNodeAttribute( $nonCollapsible, 'li', 'class', $listItemClass ),
			'The non-collapsible element should preserve item class'
		);
	}

	/**
	 * @covers ::getMenuProps
	 */
	public function testGetMenuProps() {
		$title = Title::newFromText( 'SkinTemplateBanrepcultural' );
		$context = RequestContext::getMain();
		$context->setTitle( $title );
		$context->setLanguage( 'fr' );
		$banrepculturalTemplate = $this->provideBanrepculturalTemplateObject();
		// used internally by getPersonalTools
		$banrepculturalTemplate->set( 'personal_urls', [] );
		$this->setMwGlobals( 'wgHooks', [
			'SkinTemplateNavigation' => [
				function ( &$skinTemplate, &$content_navigation ) {
					$content_navigation = [
						'actions' => [],
						'namespaces' => [],
						'variants' => [],
						'views' => [],
					];
				}
			]
		] );
		$openBanrepculturalTemplate = TestingAccessWrapper::newFromObject( $banrepculturalTemplate );

		$props = $openBanrepculturalTemplate->getMenuProps();
		$views = $props['data-page-actions'];
		$namespaces = $props['data-namespace-tabs'];

		$this->assertSame( $views, [
			'id' => 'p-views',
			'label-id' => 'p-views-label',
			'label' => $context->msg( 'views' )->text(),
			'list-classes' => 'banrepcultural-menu-content-list',
			'html-items' => '',
			'is-dropdown' => false,
			'html-tooltip' => '',
			'html-after-portal' => '',
			'class' => 'banrepcultural-menu-empty emptyPortlet banrepcultural-menu banrepcultural-menu-tabs banrepculturalTabs',
		] );

		$variants = $props['data-variants'];
		$actions = $props['data-page-actions-more'];
		$this->assertSame( $namespaces['class'],
			'banrepcultural-menu-empty emptyPortlet banrepcultural-menu banrepcultural-menu-tabs banrepculturalTabs' );
		$this->assertSame( $variants['class'],
			'banrepcultural-menu-empty emptyPortlet banrepcultural-menu banrepcultural-menu-dropdown banrepculturalMenu' );
		$this->assertSame( $actions['class'],
			'banrepcultural-menu-empty emptyPortlet banrepcultural-menu banrepcultural-menu-dropdown banrepculturalMenu' );
		$this->assertSame( $props['data-personal-menu']['class'],
			'banrepcultural-menu-empty emptyPortlet banrepcultural-menu' );
	}

}
