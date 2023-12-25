import Dialog from './interactions/dialog';
import Toast from './interactions/toast';
import {dispatchEvent} from '../helpers';

window.$modalOpen = (name) => dispatchEvent(`modal:${name}-open`);
window.$modalClose = (name) => dispatchEvent(`modal:${name}-close`);

window.$slideOpen = (name) => dispatchEvent(`slide:${name}-open`);
window.$slideClose = (name) => dispatchEvent(`slide:${name}-close`);

window.$dialog = (title, description = null) => new Dialog(title, description);
window.$toast = (title, description = null) => new Toast(title, description);
