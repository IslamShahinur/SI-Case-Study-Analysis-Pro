/**
 * SI Case Study Analysis Pro - Admin JavaScript
 * 
 * @package SI_CSP
 * @since 1.0.0
 */

(function($) {
    'use strict';

    var SICSPAdmin = {
        
        init: function() {
            this.bindEvents();
            this.initLicensePage();
            this.initAuditDashboard();
            this.initSystemHealth();
            this.initDataManagement();
        },

        bindEvents: function() {
            $(document).on('click', '.si-csp-btn-activate', this.handleLicenseActivation);
            $(document).on('click', '.si-csp-btn-deactivate', this.handleLicenseDeactivation);
            $(document).on('click', '.si-csp-btn-run-audit', this.handleRunAudit);
            $(document).on('click', '.si-csp-btn-extract-data', this.handleExtractData);
            $(document).on('click', '.si-csp-btn-flush-cache', this.handleFlushCache);
            $(document).on('click', '.si-csp-health-action-btn', this.handleHealthAction);
        },

        initLicensePage: function() {
            if (!$('.si-csp-license-page').length) {
                return;
            }
            
            console.log('License page initialized');
        },

        handleLicenseActivation: function(e) {
            e.preventDefault();
            
            var $btn = $(this);
            var $form = $btn.closest('form');
            var licenseKey = $form.find('#si_csp_license_key').val();
            var nonce = $form.find('#si_csp_license_nonce').val();
            
            if (!licenseKey) {
                SICSPAdmin.showNotice('Please enter a license key.', 'error');
                return;
            }
            
            $btn.prop('disabled', true).text('Activating...');
            
            $.ajax({
                url: si_csp_admin.ajax_url,
                type: 'POST',
                data: {
                    action: 'si_csp_activate_license',
                    license_key: licenseKey,
                    nonce: nonce
                },
                success: function(response) {
                    if (response.success) {
                        SICSPAdmin.showNotice(response.data.message, 'success');
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        SICSPAdmin.showNotice(response.data.message || 'Activation failed.', 'error');
                        $btn.prop('disabled', false).text('Activate License');
                    }
                },
                error: function() {
                    SICSPAdmin.showNotice('An error occurred. Please try again.', 'error');
                    $btn.prop('disabled', false).text('Activate License');
                }
            });
        },

        handleLicenseDeactivation: function(e) {
            e.preventDefault();
            
            if (!confirm('Are you sure you want to deactivate your license?')) {
                return;
            }
            
            var $btn = $(this);
            var nonce = $btn.data('nonce');
            
            $btn.prop('disabled', true).text('Deactivating...');
            
            $.ajax({
                url: si_csp_admin.ajax_url,
                type: 'POST',
                data: {
                    action: 'si_csp_deactivate_license',
                    nonce: nonce
                },
                success: function(response) {
                    if (response.success) {
                        SICSPAdmin.showNotice(response.data.message, 'success');
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        SICSPAdmin.showNotice(response.data.message || 'Deactivation failed.', 'error');
                        $btn.prop('disabled', false).text('Deactivate License');
                    }
                }
            });
        },

        initAuditDashboard: function() {
            if (!$('.si-csp-audit-dashboard').length) {
                return;
            }
            
            console.log('Audit dashboard initialized');
        },

        handleRunAudit: function(e) {
            e.preventDefault();
            
            var $btn = $(this);
            var postId = $btn.data('post-id');
            var nonce = $btn.data('nonce');
            
            $btn.prop('disabled', true).text('Running Audit...');
            
            $.ajax({
                url: si_csp_admin.ajax_url,
                type: 'POST',
                data: {
                    action: 'si_csp_run_audit',
                    post_id: postId,
                    nonce: nonce
                },
                success: function(response) {
                    if (response.success) {
                        SICSPAdmin.showNotice('Audit completed successfully.', 'success');
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        SICSPAdmin.showNotice(response.data.message || 'Audit failed.', 'error');
                        $btn.prop('disabled', false).text('Run Audit');
                    }
                }
            });
        },

        initSystemHealth: function() {
            if (!$('.si-csp-system-health').length) {
                return;
            }
            
            console.log('System health page initialized');
        },

        handleHealthAction: function(e) {
            e.preventDefault();
            
            var $btn = $(this);
            var action = $btn.data('action');
            var nonce = $btn.data('nonce');
            
            $btn.prop('disabled', true).text('Processing...');
            
            $.ajax({
                url: si_csp_admin.ajax_url,
                type: 'POST',
                data: {
                    action: 'si_csp_health_action',
                    health_action: action,
                    nonce: nonce
                },
                success: function(response) {
                    if (response.success) {
                        SICSPAdmin.showNotice(response.data.message, 'success');
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        SICSPAdmin.showNotice(response.data.message || 'Action failed.', 'error');
                        $btn.prop('disabled', false).text('Fix');
                    }
                }
            });
        },

        initDataManagement: function() {
            if (!$('.si-csp-data-management').length) {
                return;
            }
            
            console.log('Data management page initialized');
        },

        handleExtractData: function(e) {
            e.preventDefault();
            
            if (!confirm('This will extract data from all Case Studies and Blueprints. Continue?')) {
                return;
            }
            
            var $btn = $(this);
            var nonce = $btn.data('nonce');
            
            $btn.prop('disabled', true).text('Extracting...');
            
            $.ajax({
                url: si_csp_admin.ajax_url,
                type: 'POST',
                data: {
                    action: 'si_csp_extract_all_data',
                    nonce: nonce
                },
                success: function(response) {
                    if (response.success) {
                        SICSPAdmin.showNotice('Data extraction completed. ' + response.data.count + ' items processed.', 'success');
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        SICSPAdmin.showNotice(response.data.message || 'Extraction failed.', 'error');
                        $btn.prop('disabled', false).text('Extract Data');
                    }
                }
            });
        },

        handleFlushCache: function(e) {
            e.preventDefault();
            
            if (!confirm('Are you sure you want to flush all caches?')) {
                return;
            }
            
            var $btn = $(this);
            var nonce = $btn.data('nonce');
            
            $btn.prop('disabled', true).text('Flushing...');
            
            $.ajax({
                url: si_csp_admin.ajax_url,
                type: 'POST',
                data: {
                    action: 'si_csp_flush_cache',
                    nonce: nonce
                },
                success: function(response) {
                    if (response.success) {
                        SICSPAdmin.showNotice('Cache flushed successfully.', 'success');
                        $btn.prop('disabled', false).text('Flush Cache');
                    } else {
                        SICSPAdmin.showNotice(response.data.message || 'Cache flush failed.', 'error');
                        $btn.prop('disabled', false).text('Flush Cache');
                    }
                }
            });
        },

        showNotice: function(message, type) {
            var $notice = $('<div class="notice notice-' + type + ' is-dismissible"><p>' + message + '</p></div>');
            
            $('.si-csp-admin-wrap').prepend($notice);
            
            setTimeout(function() {
                $notice.fadeOut(function() {
                    $(this).remove();
                });
            }, 5000);
        }
    };

    $(document).ready(function() {
        SICSPAdmin.init();
    });

})(jQuery);
