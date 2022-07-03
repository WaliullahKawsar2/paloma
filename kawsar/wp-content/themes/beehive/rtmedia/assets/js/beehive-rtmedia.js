'use strict';

(function($) {
    
    const body = $('body.beehive');

    if(body.hasClass('activity') || body.hasClass('group-home')) {
        const activityForm = $('#bp-nouveau-activity-form'),
        uploadBtn          = $('#rtmedia-add-media-button-post-update'),
        activity           = $('#activity-stream'),
        observationTarget  = activity[0],
        observationConfig  = {
            childList: true, subtree: true
        }

        // Set maxlength to activity form textarea
        activityForm.on('click', function() {
            const textArea  = $(this).find('#whats-new');
            const maxLength = beehive_data.activity_max;
            textArea.attr('maxlength', maxLength);
            textArea.on('keyup', function() {
                if(!$('#bp-nouveau-activity-form .char-count').length) {
                    $(this).after('<span class="char-count mute"></span>');
                }
                const charCount= $('#bp-nouveau-activity-form .char-count');
                const length    = $(this).val().length;
                const remaining = maxLength-length;
                charCount.text(remaining);
            });
        });

        // Insert attachment text to upload button
        if(uploadBtn.length) {
            uploadBtn.append('<span class="button-text">'+ beehive_data.attachment_text +'</span>');
        }

        // Observer the dom and do tasks
        let mutationObserver = new MutationObserver((mutationsList, observer) => {
            for(let mutation of mutationsList) {
                if(mutation.type == 'childList') {
                    if(mutation.addedNodes.length) {
                        for(let node of mutation.addedNodes) {
                            if(node.nodeType == 1) {
                                if(node.classList.contains('activity-list')) {
                                    for(let childNode of node.children) {
                                        if(childNode.classList.contains('activity-item')) {
                                            rtm_url_view();
                                            truncate_activity_text();
                                            rtm_media_view();
                                        }
                                    }
                                }
                                if(node.classList.contains('activity-item')) {
                                    rtm_url_view();
                                    truncate_activity_text();
                                    rtm_media_view();
                                }
                            }
                        }
                    }
                }
            }
        });
        if(observationTarget) {
            let $activityItems = $('.activity-item', observationTarget);

            if($activityItems.length) {
                truncate_activity_text();
                rtm_media_view();
            }
            mutationObserver.observe(observationTarget, observationConfig);
        }
        // end task

        // truncate activity texts
        function truncate_activity_text() {
            $('li.activity_update, li.rtmedia_update').each(function() {
                if($(this).hasClass('text-rendered')) {
                    return;
                }
                const activityInner = $(this).find('.activity-inner');
                const rtmContainer  = activityInner.find('.rtmedia-activity-container');
                const rtmText       = rtmContainer.find('.rtmedia-activity-text');
                if(!rtmContainer.length && !rtmText.length) {
                    activityInner.wrapInner('<div class="activity-inner-text"></div>');
                } else {
                    rtmText.addClass('activity-inner-text');
                }
                const truncate     = $(this).find('.activity-inner-text');
                const embedWrapper = truncate.find('.jetpack-video-wrapper');
                truncate.find('.jetpack-video-wrapper').remove();
                if(truncate.length ) {
                    truncate.shorten({
                        showChars: 230,
                        moreText: beehive_data.read_more.toLowerCase(),
                        lessText: beehive_data.read_close
                    });
                }
                embedWrapper.appendTo(truncate);
                $(this).addClass('text-rendered');
            });
        }

        // Make the medias in the activity nicer
        function rtm_media_view() {
            $('.activity-item .rtm-activity-media-list').each(function() {
                if($(this).hasClass('rtm-activity-list-rendered')) {
                    return;
                }
                if( 1 == beehive_data.rtm_is_masonry ) {
                    if($(this).hasClass('rtm-activity-video-list') || $(this).hasClass('rtm-activity-mixed-list')) {
                        const video = $(this).find('.rtmedia-list-item video');
                        video.each(function() {
                            $(this).attr('preload', true);
                        });
                        $(this).addClass('rtm-activity-list-rendered');
                    }
                } else {
                    if($(this).hasClass('rtm-activity-photo-list')) {
                        const visibleItems = 4;
                        if( $(this).children().length > visibleItems ) {
                            const item = $(this).find('.rtmedia-list-item');
                            item.filter(function(index) {
                                let currentItem = index + 1;
                                return currentItem == visibleItems;
                            }).addClass('more').end().filter(function(index){
                                let currentItem = index + 1;
                                return currentItem > visibleItems;
                            }).hide();
                            $(this).addClass('rtm-activity-list-rendered');
                        }
                    } else if($(this).hasClass('rtm-activity-video-list') || $(this).hasClass('rtm-activity-music-list') || $(this).hasClass('rtm-activity-mixed-list')) {
                        if( $(this).children().length > 1 ) {
                            const video = $(this).find('.rtmedia-list-item video');
                            video.each(function() {
                                $(this).attr('preload', true);
                            });
                            $(this).wrap('<div class="rtm-activity-slider-container"></div>');
                            $(this).addClass('swiper-wrapper');
                            $(this).find('.rtmedia-list-item').addClass('swiper-slide');
                            $(this).after('<div class="swiper-pagination"></div>');
                            new Swiper('.rtm-activity-slider-container', {
                                pagination: {
                                    el: '.swiper-pagination',
                                    clickable: true,
                                },
                            });
                            $(this).addClass('rtm-activity-list-rendered');
                        } else {
                            const video = $(this).find('.rtmedia-list-item video');
                            video.each(function() {
                                $(this).attr('preload', true);
                            });
                            $(this).addClass('rtm-activity-list-rendered');
                        }
                    }
                }
            });
        }

        // Url preview rtmedia addon.
        function rtm_url_view () {
            const activtyItem = $( 'li.activity_update, li.rtmedia_update' );
            activtyItem.each(function() {
                if($(this).hasClass('url-preview-rendered')) {
                    return;
                }
                const rtmFinalLink = $(this).find('.rtmp_final_link');
                if (!rtmFinalLink.length) {
                    return;
                }
                if ( rtmFinalLink.find('iframe').length ) {
                    rtmFinalLink.addClass('has-iframe');
                }
                rtmFinalLink.insertAfter($(this).find('.activity-header'));
                $(this).addClass('url-preview-rendered');
            });
        }
    }

    // Trigger load more click
    if( body.hasClass( 'beehive-media' ) ) {
        const uploaderDiv = $('.rtmedia-uploader-div');
        if( ! uploaderDiv.length ) {
            $(window).scroll( function () {
                const rtmGalleryWrapper = $('.rtmedia_gallery_wrapper');
                const loadMoreBtn       = rtmGalleryWrapper.find( '.rtm-load-more a.show-it' );
                if( loadMoreBtn.length && 'block' == loadMoreBtn.css('display') ) {
                    const pos           = loadMoreBtn.offset(),
                          offset        = pos.top - 50;
                    if ( $(window).scrollTop() + $(window).height() > offset) {
                        loadMoreBtn.trigger( 'click' );
                    }
                }
            });
        }
    }

    if(beehive_data.rtm_is_masonry) {
        let masonaryTimerId = null;
        jQuery( document ).ajaxComplete( function( event, xhr, settings ) {
            var get_action = get_parameter( 'action', settings.data );
            if ( ( 'activity_filter' === get_action || 'post_update' === get_action || 'get_single_activity_content' === get_action || 'activity_get_older_updates' === get_action ) && typeof rtmedia_masonry_layout != 'undefined' && rtmedia_masonry_layout == 'true' && typeof rtmedia_masonry_layout_activity != 'undefined' && rtmedia_masonry_layout_activity == 'true' ) {
                $('#activity-stream .rtmedia-activity-container .rtmedia-list img').off('load');
                if(masonaryTimerId) clearTimeout(masonaryTimerId);
                masonaryTimerId = setTimeout(() => {
                    let imageTimerId = null;
                    $('#activity-stream .rtmedia-activity-container .rtmedia-list img').on('load', function() {
                        if(imageTimerId) clearTimeout(imageTimerId);
                        imageTimerId = setTimeout(() => {
                            rtmedia_activity_masonry();
                        }, 1000)
                    })
                }, 1000)
            }
        } );
    }
})( jQuery );