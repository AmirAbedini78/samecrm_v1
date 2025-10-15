# RTL Support for SameCRM

This document describes the RTL (Right-to-Left) support implementation for SameCRM.

## Overview

RTL support has been added to SameCRM to support languages that are written from right to left, including:
- Arabic (العربية)
- Persian/Farsi (فارسی)
- Urdu (اردو)
- Hebrew (עברית)

## Implementation Details

### 1. HTML Structure Changes

The main layout file (`application/resources/views/layout/wrapper.blade.php`) has been modified to:
- Set the `dir` attribute based on the current locale
- Add RTL-specific CSS classes
- Include RTL helper functions

### 2. CSS Support

#### RTL CSS File
- **File**: `public/css/rtl.css`
- **Purpose**: Contains all RTL-specific styles
- **Features**:
  - Text alignment adjustments
  - Margin and padding direction changes
  - Icon direction fixes
  - Form element adjustments
  - Navigation fixes
  - Table and list adjustments

#### SCSS Integration
- **File**: `public/scss/app.scss`
- **Changes**: Added RTL layout support to the main SCSS file

### 3. JavaScript Support

#### RTL JavaScript File
- **File**: `public/js/rtl.js`
- **Purpose**: Dynamic RTL support and icon direction fixes
- **Features**:
  - Automatic RTL detection
  - Dynamic content handling
  - Icon direction switching
  - AJAX content support

### 4. Language Files

#### Arabic
- **Directory**: `application/resources/lang/arabic/`
- **Files**: `lang.php`, `validation.php`
- **Content**: Complete Arabic translations

#### Persian
- **Directory**: `application/resources/lang/persian/`
- **Files**: `lang.php`
- **Content**: Complete Persian translations

#### Urdu
- **Directory**: `application/resources/lang/urdu/`
- **Files**: `lang.php`
- **Content**: Complete Urdu translations

### 5. Helper Classes

#### RTLHelper
- **File**: `application/app/Helpers/RTLHelper.php`
- **Purpose**: PHP helper class for RTL operations
- **Features**:
  - RTL language detection
  - Direction attribute generation
  - CSS class generation
  - Icon direction handling

#### RTLMiddleware
- **File**: `application/app/Http/Middleware/RTLMiddleware.php`
- **Purpose**: Middleware to inject RTL variables into views
- **Features**:
  - Automatic RTL variable injection
  - View sharing of RTL data

### 6. Blade Helpers

#### RTL Helper View
- **File**: `application/resources/views/helpers/rtl.blade.php`
- **Purpose**: Blade template for RTL variables and CSS
- **Features**:
  - CSS variable injection
  - JavaScript variable setup
  - Dynamic RTL detection

## Usage

### 1. Setting RTL Language

To use RTL support, set the application locale to one of the RTL languages:

```php
// In config/app.php or dynamically
app()->setLocale('ar'); // Arabic
app()->setLocale('fa'); // Persian
app()->setLocale('ur'); // Urdu
app()->setLocale('he'); // Hebrew
```

### 2. Testing RTL

A test page has been created to verify RTL functionality:
- **URL**: `/test-rtl`
- **Features**: Tests all RTL components and layouts

### 3. Language Switching

Users can switch between languages using the language switcher:
- English (LTR)
- Arabic (RTL)
- Persian (RTL)
- Urdu (RTL)
- Hebrew (RTL)

## Technical Details

### RTL Detection

The system automatically detects RTL languages using:
```php
$rtlLanguages = ['ar', 'fa', 'ur', 'he'];
$isRTL = in_array($locale, $rtlLanguages);
```

### CSS Classes

RTL-specific CSS classes are automatically applied:
- `.rtl-layout` - Main RTL container
- `.rtl-body` - Body RTL styling
- `.rtl-text` - RTL text alignment
- `.rtl-margin-left` - RTL margin adjustments
- `.rtl-padding-left` - RTL padding adjustments

### JavaScript Variables

RTL JavaScript variables are available globally:
```javascript
window.RTL = {
    isRTL: true/false,
    direction: 'rtl'/'ltr',
    textAlign: 'right'/'left',
    marginDirection: 'margin-right'/'margin-left',
    paddingDirection: 'padding-right'/'padding-left',
    floatDirection: 'float-right'/'float-left',
    locale: 'current-locale'
};
```

## Browser Support

RTL support is compatible with:
- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+
- Internet Explorer 11+

## Performance Considerations

- RTL CSS is only loaded for RTL languages
- RTL JavaScript is only loaded for RTL languages
- Minimal performance impact on LTR layouts
- Efficient CSS selectors for RTL overrides

## Maintenance

### Adding New RTL Languages

1. Add the language code to `RTLHelper::RTL_LANGUAGES`
2. Create language files in `application/resources/lang/{language}/`
3. Update the HTML `dir` attribute logic
4. Test the new language implementation

### Updating RTL Styles

1. Modify `public/css/rtl.css` for global RTL changes
2. Update `public/scss/app.scss` for SCSS integration
3. Test changes across all RTL languages
4. Verify LTR layouts remain unaffected

## Troubleshooting

### Common Issues

1. **Icons not flipping**: Check if the icon is in the RTL icon mapping
2. **Text alignment issues**: Verify RTL CSS is loaded
3. **Form elements misaligned**: Check RTL form styles
4. **Navigation problems**: Verify RTL navigation CSS

### Debug Mode

Enable RTL debug mode by adding to your view:
```php
@if(config('app.debug'))
    <div class="rtl-debug">
        RTL: {{ RTLHelper::isRTL() ? 'Yes' : 'No' }}
        Direction: {{ RTLHelper::getDirection() }}
    </div>
@endif
```

## Future Enhancements

- [ ] RTL support for more languages
- [ ] RTL-specific themes
- [ ] RTL animation support
- [ ] RTL print styles
- [ ] RTL email templates

## Support

For RTL-related issues:
1. Check the test page at `/test-rtl`
2. Verify language files are complete
3. Check browser console for JavaScript errors
4. Verify CSS is loading correctly

## Changelog

### Version 1.0.0
- Initial RTL support implementation
- Arabic, Persian, Urdu, and Hebrew language support
- RTL CSS and JavaScript framework
- Test page and documentation
