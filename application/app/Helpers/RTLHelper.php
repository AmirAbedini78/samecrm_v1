<?php

namespace App\Helpers;

class RTLHelper
{
    /**
     * RTL Languages
     */
    const RTL_LANGUAGES = ['ar', 'fa', 'ur', 'he'];
    
    /**
     * Check if current locale is RTL
     */
    public static function isRTL($locale = null)
    {
        if ($locale === null) {
            $locale = app()->getLocale();
        }
        
        return in_array($locale, self::RTL_LANGUAGES);
    }
    
    /**
     * Get direction attribute
     */
    public static function getDirection($locale = null)
    {
        return self::isRTL($locale) ? 'rtl' : 'ltr';
    }
    
    /**
     * Get HTML lang attribute
     */
    public static function getLangAttribute($locale = null)
    {
        if ($locale === null) {
            $locale = app()->getLocale();
        }
        
        return $locale;
    }
    
    /**
     * Get RTL class
     */
    public static function getRTLClass($locale = null)
    {
        return self::isRTL($locale) ? 'rtl-layout' : 'ltr-layout';
    }
    
    /**
     * Get text alignment class
     */
    public static function getTextAlignClass($locale = null)
    {
        return self::isRTL($locale) ? 'text-right' : 'text-left';
    }
    
    /**
     * Get margin/padding direction
     */
    public static function getMarginDirection($locale = null)
    {
        return self::isRTL($locale) ? 'margin-right' : 'margin-left';
    }
    
    /**
     * Get padding direction
     */
    public static function getPaddingDirection($locale = null)
    {
        return self::isRTL($locale) ? 'padding-right' : 'padding-left';
    }
    
    /**
     * Get float direction
     */
    public static function getFloatDirection($locale = null)
    {
        return self::isRTL($locale) ? 'float-right' : 'float-left';
    }
    
    /**
     * Get border radius direction
     */
    public static function getBorderRadiusDirection($locale = null)
    {
        return self::isRTL($locale) ? 'border-radius-left' : 'border-radius-right';
    }
    
    /**
     * Get transform direction
     */
    public static function getTransformDirection($locale = null)
    {
        return self::isRTL($locale) ? 'scaleX(-1)' : 'scaleX(1)';
    }
    
    /**
     * Get icon direction
     */
    public static function getIconDirection($icon, $locale = null)
    {
        if (!self::isRTL($locale)) {
            return $icon;
        }
        
        $rtlIcons = [
            'fa-chevron-left' => 'fa-chevron-right',
            'fa-chevron-right' => 'fa-chevron-left',
            'fa-arrow-left' => 'fa-arrow-right',
            'fa-arrow-right' => 'fa-arrow-left',
            'fa-angle-left' => 'fa-angle-right',
            'fa-angle-right' => 'fa-angle-left',
            'fa-caret-left' => 'fa-caret-right',
            'fa-caret-right' => 'fa-caret-left',
        ];
        
        return $rtlIcons[$icon] ?? $icon;
    }
    
    /**
     * Get RTL CSS variables
     */
    public static function getRTLCSSVariables($locale = null)
    {
        if (!self::isRTL($locale)) {
            return '';
        }
        
        return '
            :root {
                --rtl-direction: rtl;
                --rtl-text-align: right;
                --rtl-margin-left: 0;
                --rtl-margin-right: var(--margin-value);
                --rtl-padding-left: 0;
                --rtl-padding-right: var(--padding-value);
                --rtl-float: right;
                --rtl-border-radius-left: 0;
                --rtl-border-radius-right: var(--border-radius-value);
            }
        ';
    }
    
    /**
     * Get RTL JavaScript variables
     */
    public static function getRTLJSVariables($locale = null)
    {
        return [
            'isRTL' => self::isRTL($locale),
            'direction' => self::getDirection($locale),
            'textAlign' => self::isRTL($locale) ? 'right' : 'left',
            'marginDirection' => self::getMarginDirection($locale),
            'paddingDirection' => self::getPaddingDirection($locale),
            'floatDirection' => self::getFloatDirection($locale),
        ];
    }
}
