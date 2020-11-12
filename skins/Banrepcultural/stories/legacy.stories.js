import mustache from 'mustache';
import '../resources/skins.banrepcultural.styles/skin-legacy.less';
import legacySkinTemplate from '!!raw-loader!../includes/templates/skin-legacy.mustache';
import {
	LEGACY_TEMPLATE_DATA,
	NAVIGATION_TEMPLATE_DATA,
	TEMPLATE_PARTIALS
} from './skin.stories.data';

export default {
	title: 'Skin (legacy)'
};

export const banrepculturalLegacyLoggedOut = () => mustache.render(
	legacySkinTemplate,
	Object.assign(
		{},
		LEGACY_TEMPLATE_DATA,
		NAVIGATION_TEMPLATE_DATA.loggedOutWithVariants
	),
	TEMPLATE_PARTIALS
);

export const banrepculturalLegacyLoggedIn = () => mustache.render(
	legacySkinTemplate,
	Object.assign(
		{},
		LEGACY_TEMPLATE_DATA,
		NAVIGATION_TEMPLATE_DATA.loggedInWithMoreActions
	),
	TEMPLATE_PARTIALS
);
