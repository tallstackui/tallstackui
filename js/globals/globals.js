import Dialog from './interactions/dialog';
import Toast from './interactions/toast';
import {dispatchEvent} from '../helpers';

window.$modalOpen = (name) => dispatchEvent(`modal:${name}-open`, null, false);
window.$modalClose = (name) => dispatchEvent(`modal:${name}-close`, null, false);

window.$slideOpen = (name) => dispatchEvent(`slide:${name}-open`, null, false);
window.$slideClose = (name) => dispatchEvent(`slide:${name}-close`, null, false);

window.$dialog = (title, description = null) => new Dialog(title, description);
window.$toast = (title, description = null) => new Toast(title, description);
