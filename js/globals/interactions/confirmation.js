import {dispatchEvent, error} from '../../helpers';

/**
 * @param object {Object}
 * @param component {String|Number|Null}
 * @param data {Object}
 */
export default (object, component = null, data) => ({
  confirm() {
    if (component === null) {
      // eslint-disable-next-line max-len
      return error(`You must specify the Livewire component ID to interact with the methods through the [${data.event}] JavaScript API.`);
    }

    // When the component is empty, we will attempt to
    // get the first Livewire component in the page.
    const label = component === '' ? 'first in page' : component;
    const livewire = component === '' ?
        Livewire.first() :
        Livewire.find(component);

    if (!livewire) {
      return error(`The Livewire component [${label}] was not found in the current page.`);
    }

    if (!object.confirm) {
      return error(`The ${data.event} [confirm] does not have the confirm objects.`);
    }

    if (!object.confirm.text) {
      return error(`The ${data.event} [confirm] does not have the text.`);
    }

    if (object.cancel && !object.cancel.text) {
      return error(`The ${data.event} [cancel] does not have the text.`);
    }

    data.type = 'question';
    data.confirm = true;
    data.component = livewire.id;
    data.options = {
      confirm: object.confirm,
      cancel: object.cancel,
    };

    dispatchEvent(data.event, data);
  },
});
