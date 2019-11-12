<?php
/**
 * Banrepcultural - Modern version of MonoBook with fresh look and many usability
 * improvements.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Skins
 */

/**
 * QuickTemplate subclass for Banrepcultural
 * @ingroup Skins
 */
class BanrepculturalTemplate extends BaseTemplate {

	/**
	 * Outputs the entire contents of the HTML page
	 */
	public function execute() {
		$this->data['namespace_urls'] = $this->data['content_navigation']['namespaces'];
		$this->data['view_urls'] = $this->data['content_navigation']['views'];
		$this->data['action_urls'] = $this->data['content_navigation']['actions'];
		$this->data['variant_urls'] = $this->data['content_navigation']['variants'];

		// Move the watch/unwatch star outside of the collapsed "actions" menu to the main "views" menu
		if ( $this->config->get( 'BanrepculturalUseIconWatch' ) ) {
			$mode = $this->getSkin()->getUser()->isWatched( $this->getSkin()->getRelevantTitle() )
				? 'unwatch'
				: 'watch';

			if ( isset( $this->data['action_urls'][$mode] ) ) {
				$this->data['view_urls'][$mode] = $this->data['action_urls'][$mode];
				unset( $this->data['action_urls'][$mode] );
			}
		}

		// Naming conventions for Mustache parameters:
		// - Prefix "is" for boolean values.
		// - Prefix "msg-" for interface messages.
		// - Prefix "page-" for data relating to the current page (e.g. Title, WikiPage, or OutputPage).
		// - Prefix "html-" for raw HTML (in front of other keys, if applicable).
		// - Conditional values are null if absent.
		$params = [
			'html-headelement' => $this->get( 'headelement', '' ),
			'html-sitenotice' => $this->get( 'sitenotice', null ),
			'html-indicators' => $this->getIndicators(),
			'page-langcode' => $this->getSkin()->getTitle()->getPageViewLanguage()->getHtmlCode(),
			'page-isarticle' => (bool)$this->data['isarticle'],

			// Remember that the string '0' is a valid title.
			// From OutputPage::getPageTitle, via ::setPageTitle().
			'html-title' => $this->get( 'title', '' ),

			'html-prebodyhtml' => $this->get( 'prebodyhtml', '' ),
			'msg-tagline' => $this->getMsg( 'tagline' )->text(),
			// TODO: mediawiki/SkinTemplate should expose langCode and langDir properly.
			'html-userlangattributes' => $this->get( 'userlangattributes', '' ),
			// From OutputPage::getSubtitle()
			'html-subtitle' => $this->get( 'subtitle', '' ),

			// TODO: Use directly Skin::getUndeleteLink() directly.
			// Always returns string, cast to null if empty.
			'html-undelete' => $this->get( 'undelete', null ) ?: null,

			// From Skin::getNewtalks(). Always returns string, cast to null if empty.
			'html-newtalk' => $this->get( 'newtalk', '' ) ?: null,

			'msg-jumptonavigation' => $this->getMsg( 'banrepcultural-jumptonavigation' )->text(),
			'msg-jumptosearch' => $this->getMsg( 'banrepcultural-jumptosearch' )->text(),

			// Result of OutputPage::addHTML calls
			'html-bodycontent' => $this->get( 'bodycontent' ),

			'html-printfooter' => $this->get( 'printfooter', null ),
			'html-catlinks' => $this->get( 'catlinks', '' ),
			'html-dataAfterContent' => $this->get( 'dataAfterContent', '' ),
			// From MWDebug::getHTMLDebugLog (when $wgShowDebug is enabled)
			'html-debuglog' => $this->get( 'debughtml', '' ),
			// From BaseTemplate::getTrail (handles bottom JavaScript)
			'html-printtail' => $this->getTrail(),
		];

		// TODO: Convert the rest to Mustache
		ob_start();

		?>
		<div id="mw-navigation">
			<!--<h2><?php $this->msg( 'navigation-heading' ) ?></h2>-->
			<div id="mw-head">
				<div id="header1-wrapper">
					<div id="header1">
						<div id="header-social-links" class="social-links">
							<a target="_blank" class="facebook-link" href="http://www.banrepcultural.org/redes"></a>
							<a target="_blank" class="youtube-link" href="http://www.banrepcultural.org/redes"></a>
							<a target="_blank" class="instagram-link" href="http://www.banrepcultural.org/redes"></a>
							<a target="_blank" class="twitter-link" href="http://www.banrepcultural.org/redes"></a>
						</div>
						<div id="header-site-links">
							<a target="_blank" id="kids-link" href="http://www.banrepcultural.org/ninos-y-ninas">Niñ@s</a>
							<a target="_blank" id="accessibility-link" href="http://www.banrepcultural.org/accesibilidad">Accesibilidad</a>
							<a target="_blank" id="news-link" href="http://www.banrepcultural.org/noticias">Noticias</a>
							<a target="_blank" id="mail-link" href="http://www.banrepcultural.org/servicios/listas-de-correo">Lista de correos</a>
							<a target="_blank" id="register-link" href="http://www.banrepcultural.org/servicios/asociacion">Hazte socio</a>
						</div>
					</div>
				</div>

				<div id="header2-wrapper">
					<div id="header2">
						<a id="logo" href="<?php echo htmlspecialchars( $this->data['nav_urls']['mainpage']['href'] ); ?>"></a>
					</div>
				</div>

				<div id="header3-wrapper">
					<div id="header3">

						<form id="search-form" action="<?php $this->text( 'wgScript' ); ?>">
							<button type="submit"></button>
							<?php echo $this->makeSearchInput([ 'type' => 'text', 'id' => 'search-field', 'placeholder' => wfMessage( 'banrepcultural-search' ) ]); ?>
							<input type="hidden" name="title" value="<?php $this->text( 'searchtitle' ) ?>" />
						</form>

						<ul id="menu">
							<a id="home-icon" href="<?php echo htmlspecialchars( $this->data['nav_urls']['mainpage']['href'] ); ?>">
								
							</a>

							<?php foreach ( $this->data['sidebar'] as $menu => $items ): ?>
							<li><span><?php echo $menu; ?></span>
								<ul class="submenu">
								<?php foreach ( $items as $key => $value ) echo $this->makeListItem( $key, $value ); ?>
								</ul>
							</li>
							<?php endforeach; ?>

							<li><span><?= wfMessage( 'banrepcultural-tools' ); ?></span>
								<ul class="submenu">
								<?php foreach ( $this->getToolbox() as $key => $value ) echo $this->makeListItem( $key, $value ); ?>
								</ul>
							</li>

							<?php $actions = $this->getActions(); if ( $actions ): ?>
							<li><span><?= wfMessage( 'banrepcultural-actions' ); ?></span>
								<ul class="submenu">
								<?php foreach ( $actions as $key => $value ) echo $this->makeListItem( $key, $value ); ?>
								</ul>
							</li>
							<?php endif; ?>

							<li><span><?= wfMessage( 'banrepcultural-you' ); ?></span>
								<ul class="submenu">
								<?php foreach ( $this->getPersonalTools() as $key => $value ) echo $this->makeListItem( $key, $value ); ?>
								</ul>
							</li>
						</ul>

					</div>
				</div>

			</div>
			<!--div id="mw-panel"></div-->
		</div>
		<?php Hooks::run( 'BanrepculturalBeforeFooter' ); ?>
		<div id="footer-wrapper" role="contentinfo"<?php $this->html( 'userlangattributes' ) ?>>
			<div id="footer">
				<div class="footer-column">
					<a class="footer-icon"></a>
					<ul>
						<li><a target="_blank" href="http://www.banrepcultural.org/acerca-de">Acerca de Banrepcultural</a></li>
						<li><a target="_blank" href="http://www.banrep.gov.co/">Banco de la República</a></li>
						<li><a target="_blank" href="http://www.banrepcultural.org/accesibilidad">Accesibilidad</a></li>
						<li><a target="_blank" href="http://www.banrep.gov.co/es/transparencia-acceso-informacion-publica">Transparencia</a></li>
						<li><a target="_blank" href="http://www.banrepcultural.org/noticias">Noticias</a></li>
						<li><a target="_blank" href="http://www.banrepcultural.org/prensa">Prensa</a></li>
						<li><a target="_blank" href="http://www.banrepcultural.org/servicios/publicaciones">Publicaciones</a></li>
						<li><br>
							<div class="social-links">
								<a target="_blank" class="facebook-link" href="http://www.banrepcultural.org/redes"></a>
								<a target="_blank" class="youtube-link" href="http://www.banrepcultural.org/redes"></a>
								<a target="_blank" class="instagram-link" href="http://www.banrepcultural.org/redes"></a>
								<a target="_blank" class="twitter-link" href="http://www.banrepcultural.org/redes"></a>
							</div>
						</li>
					</ul>
				</div>
				<div class="footer-column">
					<h2>Información de interés y ayuda</h2>
					<div><a href="/en">Versión en inglés</a></div>
					<div><a href="/servicios/preguntas-frecuentes">Preguntas frecuentes</a></div>
					<div><a href="/mapa-del-sitio">Mapa del sitio</a></div>
					<div><a href="/servicios/catalogo-bibliografico-en-linea">Catálogo en línea</a></div>
					<div><a href="/servicios/preguntas-frecuentes">Hazte socio</a></div>
					<div><a href="http://www.banrepcultural.org/boletin-cultural/">Boletín cultural y bibliográfico</a></div>
					<div><a href="/publicaciones">Derechos de Autor</a></div>
					<div><a href="/eform/submit/danos-tu-opinion">¿Encontraste algun error?</a></div>
					<div><a href="/creditos">Créditos</a></div>
				</div>
				<div class="footer-column">
					<h2>Contacto y avisos legales</h2>
					<div><a href="http://www.banrep.gov.co/donde-estamos">Localización física y horarios de atención al público en el territorio nacional</a></div>
					<div><a href="http://www.banrep.gov.co/atencion-ciudadano">Atención al ciudadano</a></div>
					<div><a href="https://atencionalciudadano.banrep.gov.co/siac/ess.do">Formulario electrónico de solicitudes de información</a></div>
					<div><a href="/servicios/listas-de-correo">Listas de correo</a></div>
					<div><a href="mailto: AtencionalCiudadano@banrep.gov.co">Contáctenos: atencionalciudadano@banrep.gov.co</a></div>
					<div><a href="http://www.banrep.gov.co/notificaciones-judiciales">Buzón de notificaciones judiciales</a></div>
					<div><a href="http://www.banrep.gov.co/aviso-legal">Aviso legal</a></div>
					<div><a href="http://www.banrep.gov.co/proteccion-datos-personales">Políticas de protección de datos personales</a></div>
					<div><a href="http://www.banrep.gov.co/politicas-de-seguridad-de-la-informacion">Políticas de seguridad de la información</a></div>
				</div>
				<div id="copyright">© 2017 Banco de la República, Colombia. Todos los derechos reservados.</div>
			</div>
		</div>
		<?php
		$params['html-unported'] = ob_get_contents();
		ob_end_clean();

		// Prepare and output the HTML response
		$templates = new TemplateParser( __DIR__ . '/templates' );
		echo $templates->processTemplate( 'index', $params );
	}

	/**
	 * Render a series of portals
	 *
	 * @param array $portals
	 */
	protected function renderPortals( array $portals ) {
		// Force the rendering of the following portals
		if ( !isset( $portals['TOOLBOX'] ) ) {
			$portals['TOOLBOX'] = true;
		}
		if ( !isset( $portals['LANGUAGES'] ) ) {
			$portals['LANGUAGES'] = true;
		}
		// Render portals
		foreach ( $portals as $name => $content ) {
			if ( $content === false ) {
				continue;
			}

			// Numeric strings gets an integer when set as key, cast back - T73639
			$name = (string)$name;

			switch ( $name ) {
				case 'SEARCH':
					break;
				case 'TOOLBOX':
					$this->renderPortal( 'tb', $this->getToolbox(), 'toolbox', 'SkinTemplateToolboxEnd' );
					Hooks::run( 'BanrepculturalAfterToolbox' );
					break;
				case 'LANGUAGES':
					if ( $this->data['language_urls'] !== false ) {
						$this->renderPortal( 'lang', $this->data['language_urls'], 'otherlanguages' );
					}
					break;
				default:
					$this->renderPortal( $name, $content );
					break;
			}
		}
	}

	/**
	 * @param string $name
	 * @param array|string $content
	 * @param null|string $msg
	 * @param null|string|array $hook
	 */
	protected function renderPortal( $name, $content, $msg = null, $hook = null ) {
		if ( $msg === null ) {
			$msg = $name;
		}
		$msgObj = $this->getMsg( $msg );
		$labelId = Sanitizer::escapeIdForAttribute( "p-$name-label" );
		?>
		<div class="portal" role="navigation" id="<?php
		echo htmlspecialchars( Sanitizer::escapeIdForAttribute( "p-$name" ) )
		?>"<?php
		echo Linker::tooltip( 'p-' . $name )
		?> aria-labelledby="<?php echo htmlspecialchars( $labelId ) ?>">
			<h3<?php $this->html( 'userlangattributes' ) ?> id="<?php echo htmlspecialchars( $labelId )
				?>"><?php
				echo htmlspecialchars( $msgObj->exists() ? $msgObj->text() : $msg );
				?></h3>
			<div class="body">
				<?php
				if ( is_array( $content ) ) {
				?>
				<ul>
					<?php
					foreach ( $content as $key => $val ) {
						echo $this->makeListItem( $key, $val );
					}
					if ( $hook !== null ) {
						// Avoid PHP 7.1 warning
						$skin = $this;
						Hooks::run( $hook, [ &$skin, true ] );
					}
					?>
				</ul>
				<?php
				} else {
					// Allow raw HTML block to be defined by extensions
					echo $content;
				}

				$this->renderAfterPortlet( $name );
				?>
			</div>
		</div>
	<?php
	}

	/**
	 * Render one or more navigations elements by name, automatically reversed by css
	 * when UI is in RTL mode
	 *
	 * @param array $elements
	 */
	protected function renderNavigation( array $elements ) {
		// Render elements
		foreach ( $elements as $name => $element ) {
			switch ( $element ) {
				case 'NAMESPACES':
					?>
					<div id="p-namespaces" role="navigation" class="banrepculturalTabs<?php
					if ( count( $this->data['namespace_urls'] ) == 0 ) {
						echo ' emptyPortlet';
					}
					?>" aria-labelledby="p-namespaces-label">
						<h3 id="p-namespaces-label"><?php $this->msg( 'namespaces' ) ?></h3>
						<ul<?php $this->html( 'userlangattributes' ) ?>>
							<?php
							foreach ( $this->data['namespace_urls'] as $key => $item ) {
								echo $this->makeListItem( $key, $item, [
									'banrepcultural-wrap' => true,
								] );
							}
							?>
						</ul>
					</div>
					<?php
					break;
				case 'VARIANTS':
					?>
					<div id="p-variants" role="navigation" class="banrepculturalMenu<?php
					if ( count( $this->data['variant_urls'] ) == 0 ) {
						echo ' emptyPortlet';
					}
					?>" aria-labelledby="p-variants-label">
						<?php
						// Replace the label with the name of currently chosen variant, if any
						$variantLabel = $this->getMsg( 'variants' )->text();
						foreach ( $this->data['variant_urls'] as $item ) {
							if ( isset( $item['class'] ) && stripos( $item['class'], 'selected' ) !== false ) {
								$variantLabel = $item['text'];
								break;
							}
						}
						?>
						<input type="checkbox" class="banrepculturalMenuCheckbox" aria-labelledby="p-variants-label" />
						<h3 id="p-variants-label">
							<span><?php echo htmlspecialchars( $variantLabel ) ?></span>
						</h3>
						<ul class="menu">
							<?php
							foreach ( $this->data['variant_urls'] as $key => $item ) {
								echo $this->makeListItem( $key, $item );
							}
							?>
						</ul>
					</div>
					<?php
					break;
				case 'VIEWS':
					?>
					<div id="p-views" role="navigation" class="banrepculturalTabs<?php
					if ( count( $this->data['view_urls'] ) == 0 ) {
						echo ' emptyPortlet';
					}
					?>" aria-labelledby="p-views-label">
						<h3 id="p-views-label"><?php $this->msg( 'views' ) ?></h3>
						<ul<?php $this->html( 'userlangattributes' ) ?>>
							<?php
							foreach ( $this->data['view_urls'] as $key => $item ) {
								echo $this->makeListItem( $key, $item, [
									'banrepcultural-wrap' => true,
									'banrepcultural-collapsible' => true,
								] );
							}
							?>
						</ul>
					</div>
					<?php
					break;
				case 'ACTIONS':
					?>
					<div id="p-cactions" role="navigation" class="banrepculturalMenu<?php
					if ( count( $this->data['action_urls'] ) == 0 ) {
						echo ' emptyPortlet';
					}
					?>" aria-labelledby="p-cactions-label">
						<input type="checkbox" class="banrepculturalMenuCheckbox" aria-labelledby="p-cactions-label" />
						<h3 id="p-cactions-label"><span><?php
							$this->msg( 'banrepcultural-more-actions' )
						?></span></h3>
						<ul class="menu"<?php $this->html( 'userlangattributes' ) ?>>
							<?php
							foreach ( $this->data['action_urls'] as $key => $item ) {
								echo $this->makeListItem( $key, $item );
							}
							?>
						</ul>
					</div>
					<?php
					break;
				case 'PERSONAL':
					?>
					<div id="p-personal" role="navigation"<?php
					if ( count( $this->data['personal_urls'] ) == 0 ) {
						echo ' class="emptyPortlet"';
					}
					?> aria-labelledby="p-personal-label">
						<h3 id="p-personal-label"><?php $this->msg( 'personaltools' ) ?></h3>
						<ul<?php $this->html( 'userlangattributes' ) ?>>
							<?php
							$notLoggedIn = '';

							if ( !$this->getSkin()->getUser()->isLoggedIn() &&
								User::groupHasPermission( '*', 'edit' )
							) {
								$notLoggedIn =
									Html::element( 'li',
										[ 'id' => 'pt-anonuserpage' ],
										$this->getMsg( 'notloggedin' )->text()
									);
							}

							$personalTools = $this->getPersonalTools();

							$langSelector = '';
							if ( array_key_exists( 'uls', $personalTools ) ) {
								$langSelector = $this->makeListItem( 'uls', $personalTools[ 'uls' ] );
								unset( $personalTools[ 'uls' ] );
							}

							echo $langSelector;
							echo $notLoggedIn;
							foreach ( $personalTools as $key => $item ) {
								echo $this->makeListItem( $key, $item );
							}
							?>
						</ul>
					</div>
					<?php
					break;
				case 'SEARCH':
					?>
					<div id="p-search" role="search">
						<h3<?php $this->html( 'userlangattributes' ) ?>>
							<label for="searchInput"><?php $this->msg( 'search' ) ?></label>
						</h3>
						<form action="<?php $this->text( 'wgScript' ) ?>" id="searchform">
							<div<?php echo $this->config->get( 'BanrepculturalUseSimpleSearch' ) ? ' id="simpleSearch"' : '' ?>>
								<?php
								echo $this->makeSearchInput( [ 'id' => 'searchInput' ] );
								echo Html::hidden( 'title', $this->get( 'searchtitle' ) );
								/* We construct two buttons (for 'go' and 'fulltext' search modes),
								 * but only one will be visible and actionable at a time (they are
								 * overlaid on top of each other in CSS).
								 * * Browsers will use the 'fulltext' one by default (as it's the
								 *   first in tree-order), which is desirable when they are unable
								 *   to show search suggestions (either due to being broken or
								 *   having JavaScript turned off).
								 * * The mediawiki.searchSuggest module, after doing tests for the
								 *   broken browsers, removes the 'fulltext' button and handles
								 *   'fulltext' search itself; this will reveal the 'go' button and
								 *   cause it to be used.
								 */
								echo $this->makeSearchButton(
									'fulltext',
									[ 'id' => 'mw-searchButton', 'class' => 'searchButton mw-fallbackSearchButton' ]
								);
								echo $this->makeSearchButton(
									'go',
									[ 'id' => 'searchButton', 'class' => 'searchButton' ]
								);
								?>
							</div>
						</form>
					</div>
					<?php

					break;
			}
		}
	}

	/**
	 * @inheritDoc
	 */
	public function makeLink( $key, $item, $options = [] ) {
		$html = parent::makeLink( $key, $item, $options );
		// Add an extra wrapper because our CSS is weird
		if ( isset( $options['banrepcultural-wrap'] ) && $options['banrepcultural-wrap'] ) {
			$html = Html::rawElement( 'span', [], $html );
		}
		return $html;
	}

	/**
	 * @inheritDoc
	 */
	public function makeListItem( $key, $item, $options = [] ) {
		// For fancy styling of watch/unwatch star
		if (
			$this->config->get( 'BanrepculturalUseIconWatch' )
			&& ( $key === 'watch' || $key === 'unwatch' )
		) {
			$item['class'] = rtrim( 'icon ' . $item['class'], ' ' );
			$item['primary'] = true;
		}

		// Add CSS class 'collapsible' to links which are not marked as "primary"
		if (
			isset( $options['banrepcultural-collapsible'] ) && $options['banrepcultural-collapsible'] ) {
			$item['class'] = rtrim( 'collapsible ' . $item['class'], ' ' );
		}

		// We don't use this, prevent it from popping up in HTML output
		unset( $item['redundant'] );

		return parent::makeListItem( $key, $item, $options );
	}

	public function getActions() {
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
}
