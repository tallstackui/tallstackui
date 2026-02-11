# Internal Scoped Customization

Components that use other TallStackUI components internally now expose `scope` attributes, enabling developers to customize those internal instances without publishing Blade templates.

## Usage

```php
// In AppServiceProvider::boot()

// Customize the input used inside the color picker
TallStackUi::customize('input', 'form.color.input')
    ->block('input.base', 'rounded-full');

// Customize the floating used inside the date picker
TallStackUi::customize('floating', 'form.date.floating')
    ->block('wrapper', 'shadow-2xl');

// Customize the badge used inside sidebar items
TallStackUi::customize('badge', 'sidebar.item.badge')
    ->block('wrapper.class', 'border-0');
```

## Scope Reference

### Wrapper Components

These scopes affect all form components that use the shared wrapper infrastructure.

| Parent        | Child | Scope                 |
|---------------|-------|-----------------------|
| wrapper.input | label | `wrapper.input.label` |
| wrapper.input | hint  | `wrapper.input.hint`  |
| wrapper.input | error | `wrapper.input.error` |
| wrapper.radio | error | `wrapper.radio.error` |

Components using `wrapper.input`: input, password, currency, date, time, color, tag, textarea, number.
Components using `wrapper.radio`: checkbox, radio, toggle.

### Form: Color

| Child    | Scope                 |
|----------|-----------------------|
| input    | `form.color.input`    |
| floating | `form.color.floating` |

### Form: Password

| Child    | Scope                    |
|----------|--------------------------|
| input    | `form.password.input`    |
| floating | `form.password.floating` |

### Form: Currency

| Child | Scope                 |
|-------|-----------------------|
| input | `form.currency.input` |

### Form: Date

| Child    | Scope                |
|----------|----------------------|
| input    | `form.date.input`    |
| floating | `form.date.floating` |

### Form: Time

| Child    | Scope                |
|----------|----------------------|
| input    | `form.time.input`    |
| floating | `form.time.floating` |
| button   | `form.time.button`   |

### Form: Upload

| Child    | Scope                  |
|----------|------------------------|
| input    | `form.upload.input`    |
| label    | `form.upload.label`    |
| floating | `form.upload.floating` |
| error    | `form.upload.error`    |

### Form: Select Styled

| Child    | Scope                         |
|----------|-------------------------------|
| label    | `form.select-styled.label`    |
| input    | `form.select-styled.input`    |
| floating | `form.select-styled.floating` |
| hint     | `form.select-styled.hint`     |
| error    | `form.select-styled.error`    |

### Form: Select Native

| Child | Scope                      |
|-------|----------------------------|
| label | `form.select-native.label` |
| hint  | `form.select-native.hint`  |
| error | `form.select-native.error` |

### Form: Pin

| Child | Scope            |
|-------|------------------|
| label | `form.pin.label` |
| hint  | `form.pin.hint`  |
| error | `form.pin.error` |

### Table

| Child         | Scope                 |
|---------------|-----------------------|
| select.styled | `table.select-styled` |
| input         | `table.input`         |
| checkbox      | `table.checkbox`      |

### Dialog

| Child           | Scope           |
|-----------------|-----------------|
| button (cancel) | `dialog.button` |

### Dropdown

| Child    | Scope               |
|----------|---------------------|
| floating | `dropdown.floating` |

### Dropdown Submenu

| Child    | Scope                       |
|----------|-----------------------------|
| floating | `dropdown.submenu.floating` |

### Clipboard

| Child | Scope             |
|-------|-------------------|
| label | `clipboard.label` |
| hint  | `clipboard.hint`  |

### Sidebar Item

| Child | Scope                |
|-------|----------------------|
| badge | `sidebar.item.badge` |
