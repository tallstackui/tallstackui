import upload from '../src/Components/Form/Upload/alpine';
import uploadAsync from '../src/Components/Form/Upload/Async/alpine';

document.addEventListener('alpine:init', () => {
  Alpine.data('tallstackui_formUpload', upload);
  Alpine.data('tallstackui_formUploadAsync', uploadAsync);
});
