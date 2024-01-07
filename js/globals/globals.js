import {event} from '../helpers';
import Interaction from './interaction';

window.$modalOpen = (name) => event(`modal:${name}-open`, null, false);
window.$modalClose = (name) => event(`modal:${name}-close`, null, false);

window.$slideOpen = (name) => event(`slide:${name}-open`, null, false);
window.$slideClose = (name) => event(`slide:${name}-close`, null, false);

window.$interaction = (type) => new Interaction(type);
