/**
 * SI Case Study Analysis Pro - Frontend JavaScript
 * 
 * @package SI_CSP
 * @since 1.0.0
 */

(function($) {
    'use strict';

    var SICSPFrontend = {
        
        init: function() {
            this.bindEvents();
            this.initCaseStudyCards();
            this.initAuditBadges();
        },

        bindEvents: function() {
            $(document).on('click', '.si-csp-card-action', this.handleCardAction);
        },

        initCaseStudyCards: function() {
            if (!$('.si-csp-case-study-card').length) {
                return;
            }
            
            console.log('Case study cards initialized');
        },

        initAuditBadges: function() {
            if (!$('.si-csp-audit-badge').length) {
                return;
            }
            
            console.log('Audit badges initialized');
        },

        handleCardAction: function(e) {
            e.preventDefault();
            
            var $link = $(this);
            var targetUrl = $link.attr('href');
            
            if (targetUrl && targetUrl !== '#') {
                window.location.href = targetUrl;
            }
        }
    };

    $(document).ready(function() {
        SICSPFrontend.init();
    });

})(jQuery);
