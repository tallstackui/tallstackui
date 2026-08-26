# TallStackUI: Upload Editor

> TallStackUI is a TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
> component library providing 80+ Blade components for building modern web interfaces.

An image editor that opens before an image is uploaded, letting the user crop it and rotate it in 90° steps. It is not used on its own: `<x-upload />`, `<x-upload.async />` and the image dialog of `<x-editor />` render it when their editor is enabled, and this page documents what the three share.

## Enabling

```blade
<x-upload wire:model="photo" editor />
<x-upload wire:model="avatar" editor aspect="1:1" />
<x-upload wire:model="photo" editor="crop" />
<x-upload wire:model="photo" editor="rotate" />

<x-upload.async route="uploads.store" editor aspect="16:9" multiple />

<x-editor wire:model="content" upload-property="picture" upload-method="store" upload-editor upload-aspect="4:3" />
```

| Value      | Result                              |
|------------|-------------------------------------|
| `true`     | Crop box and rotation buttons       |
| `"crop"`   | Crop box only                       |
| `"rotate"` | Rotation buttons only               |
| `false`    | No editor, whatever the config says |

`aspect` locks the crop box to a `width:height` ratio (`1:1`, `4:3`, `16:9`). The box starts as the largest centered rectangle of that ratio and every handle keeps it. Without `aspect` the box is free. It has no effect on `"rotate"`.

## Behavior

The editor runs at intake, between picking the file and sending it. What reaches the server is the edited file; the original never leaves the browser. Files already uploaded cannot be edited again.

- Only images open the editor. Documents, and SVGs, go straight to the upload.
- Cancelling, Escape, the backdrop or the close button drop the file: nothing is uploaded.
- With `multiple`, the editor opens once per image, in sequence. Cancelling one removes only that one from the batch.
- The EXIF orientation is applied on load, so a portrait phone photo is shown upright before any rotation.
- Images larger than 4096px on their longest side are downscaled to that on load.
- An animated GIF is flattened to a PNG frame. The output file name follows the resulting format (`photo.gif` becomes `photo.png`; a forced `webp` turns `photo.jpg` into `photo.webp`).
- The dialog is a `modal` with `center="md"`: a bottom sheet below `md`, centered above it.

## Configuration

The same four keys exist under `components.upload`, `components.upload.async` and `components.editor.upload`. The inline attribute always wins over the config.

| Setting | Default | Description                                                                    |
|---------|---------|--------------------------------------------------------------------------------|
| editor  | false   | Opens the editor by default: `false`, `true`, `'crop'` or `'rotate'`           |
| aspect  | null    | Default crop ratio, in the `width:height` format. null = free                  |
| quality | 0.92    | Compression of jpeg and webp output, from 0 to 1. Ignored by png               |
| format  | null    | Forces the output type: `'png'`, `'jpeg'` or `'webp'`. null keeps the original |

## Validation Constraints

- `editor` must be `true`, `false`, `crop` or `rotate`.
- `aspect` must follow the `width:height` format with positive integers, like `16:9`.
- `format` must be null, `png`, `jpeg` or `webp`.

## Translations

The labels live under `messages.upload.editor`: `rotate_left`, `rotate_right` and `reset` feed the tooltips and the `aria-label` of the icon buttons; `cancel` and `apply` are the footer buttons. The modal title is the file name.

## Soft Customization

Soft customization allows you to override default Tailwind CSS classes used by this component at runtime, either through a service provider or scoped per-instance.

### Customization

The component key is `form.upload.editor`, shared by every parent that renders it:

```php
TallStackUi::customize()
    ->form('upload.editor')
    ->block('stage.wrapper', 'your-tailwind-classes');
```

### Available Blocks

| Block Name       | Purpose                                                            |
|------------------|--------------------------------------------------------------------|
| wrapper          | Modal body container                                               |
| name             | File name in the modal title                                       |
| stage.wrapper    | Area the image is fitted into, fixed at 60vh                       |
| stage.frame      | Box sized to the fitted image, holding the canvas and the crop box |
| stage.canvas     | The canvas                                                         |
| crop.box         | Crop box: border and the shadow that dims the outside              |
| crop.grid        | Rule-of-thirds grid inside the box                                 |
| crop.cell        | A single cell of that grid                                         |
| crop.handle.base | Shape and colour shared by the eight handles                       |
| crop.handle.n    | Top handle position and cursor                                     |
| crop.handle.s    | Bottom handle                                                      |
| crop.handle.e    | Right handle                                                       |
| crop.handle.w    | Left handle                                                        |
| crop.handle.ne   | Top-right handle                                                   |
| crop.handle.nw   | Top-left handle                                                    |
| crop.handle.se   | Bottom-right handle                                                |
| crop.handle.sw   | Bottom-left handle                                                 |
| footer.tools     | Group holding the rotation and reset buttons                       |
| footer.tool      | A single icon button                                               |
| footer.tool-icon | Icon inside the button                                             |
| footer.actions   | Group holding Cancel and Apply                                     |

### Internal Scopes

The dialog is a `modal` and the two footer actions are `button` components, reachable through their own scopes:

```php
TallStackUi::customize()
    ->scope('form.upload.editor.modal')
    ->modal()
    ->block('wrapper.fourth')
    ->append('ring-2 ring-primary-500');

TallStackUi::customize()
    ->scope('form.upload.editor.apply')
    ->button()
    ->block('wrapper')
    ->append('uppercase');
```

| Scope                       | Component      |
|-----------------------------|----------------|
| `form.upload.editor.modal`  | `<x-modal />`  |
| `form.upload.editor.cancel` | `<x-button />` |
| `form.upload.editor.apply`  | `<x-button />` |
