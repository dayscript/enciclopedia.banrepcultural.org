import mustache from 'mustache';
import '../resources/skins.banrepcultural.styles/MenuDropdown.less';
import '../.storybook/common.less';
import { banrepculturalMenuTemplate, moreData, variantsData } from './MenuDropdown.stories.data';

export default {
	title: 'MenuDropdown'
};

export const more = () => mustache.render( banrepculturalMenuTemplate, moreData );

export const variants = () => mustache.render( banrepculturalMenuTemplate, variantsData );
