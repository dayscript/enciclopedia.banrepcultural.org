import mustache from 'mustache';
import { menuTemplate as banrepculturalTabsTemplate } from './Menu.stories.data';
import { namespaceTabsData, pageActionsData } from './MenuTabs.stories.data';
import '../resources/skins.banrepcultural.styles/MenuTabs.less';
import '../resources/skins.banrepcultural.styles/TabWatchstarLink.less';
import '../.storybook/common.less';

export default {
	title: 'MenuTabs'
};

export const pageActionTabs = () => mustache.render( banrepculturalTabsTemplate, pageActionsData );

export const namespaceTabs = () => mustache.render( banrepculturalTabsTemplate, namespaceTabsData );
