import mustache from 'mustache';
import '../resources/skins.banrepcultural.styles/SearchBox.less';
import '../.storybook/common.less';
import { searchBoxData, searchBoxTemplate } from './SearchBox.stories.data';

export default {
	title: 'SearchBox'
};

export const simpleSearch = () => mustache.render( searchBoxTemplate, searchBoxData );
