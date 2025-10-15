/**
 * RTL Support for SameCRM
 * =======================
 * JavaScript functions for RTL layout support
 */

// RTL Detection and Management
var RTLSupport = {
    
    // RTL Languages
    rtlLanguages: ['ar', 'fa', 'ur', 'he'],
    
    // Current language
    currentLang: null,
    
    // Initialize RTL support
    init: function() {
        this.currentLang = this.getCurrentLanguage();
        this.applyRTLSupport();
        this.bindEvents();
    },
    
    // Get current language
    getCurrentLanguage: function() {
        // Try to get from HTML lang attribute
        var htmlLang = document.documentElement.getAttribute('lang');
        if (htmlLang) {
            return htmlLang;
        }
        
        // Try to get from NX object
        if (typeof NX !== 'undefined' && NX.system_language) {
            return NX.system_language;
        }
        
        // Default fallback
        return 'en';
    },
    
    // Check if current language is RTL
    isRTL: function() {
        return this.rtlLanguages.indexOf(this.currentLang) !== -1;
    },
    
    // Apply RTL support
    applyRTLSupport: function() {
        if (this.isRTL()) {
            this.addRTLClass();
            this.fixRTLIcons();
            this.fixRTLTables();
            this.fixRTLForms();
            this.fixRTLNavigation();
        }
    },
    
    // Add RTL class to body
    addRTLClass: function() {
        document.body.classList.add('rtl-body');
        document.documentElement.classList.add('rtl-html');
    },
    
    // Fix RTL icons
    fixRTLIcons: function() {
        // Fix chevron icons
        $('.fa-chevron-left').removeClass('fa-chevron-left').addClass('fa-chevron-right');
        $('.fa-chevron-right').removeClass('fa-chevron-right').addClass('fa-chevron-left');
        
        // Fix arrow icons
        $('.fa-arrow-left').removeClass('fa-arrow-left').addClass('fa-arrow-right');
        $('.fa-arrow-right').removeClass('fa-arrow-right').addClass('fa-arrow-left');
        
        // Fix other directional icons
        $('.fa-angle-left').removeClass('fa-angle-left').addClass('fa-angle-right');
        $('.fa-angle-right').removeClass('fa-angle-right').addClass('fa-angle-left');
        
        // Fix caret icons
        $('.fa-caret-left').removeClass('fa-caret-left').addClass('fa-caret-right');
        $('.fa-caret-right').removeClass('fa-caret-right').addClass('fa-caret-left');
        
        // Fix specific SameCRM icons
        $('.sl-icon-arrow-left').removeClass('sl-icon-arrow-left').addClass('sl-icon-arrow-right');
        $('.sl-icon-arrow-right').removeClass('sl-icon-arrow-right').addClass('sl-icon-arrow-left');
        
        // Fix menu icons
        $('.ti-angle-left').removeClass('ti-angle-left').addClass('ti-angle-right');
        $('.ti-angle-right').removeClass('ti-angle-right').addClass('ti-angle-left');
    },
    
    // Fix RTL tables
    fixRTLTables: function() {
        $('table').each(function() {
            $(this).addClass('rtl-table');
        });
    },
    
    // Fix RTL forms
    fixRTLForms: function() {
        $('.form-control').each(function() {
            if ($(this).attr('type') !== 'password') {
                $(this).css('text-align', 'right');
            }
        });
        
        $('.input-group').each(function() {
            $(this).addClass('rtl-input-group');
        });
    },
    
    // Fix RTL navigation
    fixRTLNavigation: function() {
        $('.navbar-nav').addClass('rtl-nav');
        $('.dropdown-menu').addClass('rtl-dropdown');
    },
    
    // Bind events
    bindEvents: function() {
        var self = this;
        
        // Handle dynamic content
        $(document).on('DOMNodeInserted', function(e) {
            if (self.isRTL()) {
                self.fixDynamicContent($(e.target));
            }
        });
        
        // Handle AJAX content
        $(document).ajaxComplete(function() {
            if (self.isRTL()) {
                self.fixRTLIcons();
                self.fixRTLTables();
                self.fixRTLForms();
            }
        });
    },
    
    // Fix dynamic content
    fixDynamicContent: function(element) {
        if (this.isRTL()) {
            element.find('.fa-chevron-left').removeClass('fa-chevron-left').addClass('fa-chevron-right');
            element.find('.fa-chevron-right').removeClass('fa-chevron-right').addClass('fa-chevron-left');
            element.find('.form-control').css('text-align', 'right');
            element.find('table').addClass('rtl-table');
        }
    },
    
    // Switch language direction
    switchDirection: function(lang) {
        this.currentLang = lang;
        if (this.isRTL()) {
            document.documentElement.setAttribute('dir', 'rtl');
            document.documentElement.setAttribute('lang', lang);
            document.body.classList.add('rtl-body');
        } else {
            document.documentElement.setAttribute('dir', 'ltr');
            document.documentElement.setAttribute('lang', lang);
            document.body.classList.remove('rtl-body');
        }
        this.applyRTLSupport();
    },
    
    // Get RTL status
    getRTLStatus: function() {
        return this.isRTL();
    }
};

// Initialize RTL support when DOM is ready
$(document).ready(function() {
    RTLSupport.init();
});

// Export for global use
window.RTLSupport = RTLSupport;
