'use strict';

//On document ready
(function($) {

    const body  = $('body');
    const table = $('table.lp-list-table');
    
    if( body.hasClass( 'beehive-social-layout' ) && body.hasClass( 'course-item-popup' ) ) {
        body.removeClass('panel-expanded');
        body.addClass('panel-collapsed');
    }

    if(body.hasClass('single-lp_course')) {
        let stickyTimer;
        $(window).on('load resize', function() {
            clearTimeout(stickyTimer);
            stickyTimer = setTimeout(function() {
                const features = $('.single-course-features');
                if(window.innerWidth > 991.98) {
                    features.stick_in_parent({
						offset_top: Number(beehive_data.stick_offset),
					});
                } else {
                    features.trigger('sticky_kit:detach');
                }
            }, 500);
        });

        // Truncate review text
        const reviewContent = $( '.course-reviews-list' );
        reviewContent.find('.review-content').each(function() {
            $(this).shorten({
                showChars: 120,
                moreText: beehive_data.read_more,
                lessText: beehive_data.read_close
            });
        });
    }

    if(table.length) {
        table.wrap('<div class="lp-list-table-wrapper"></div>');
    }

    $('.lp-tab-sections').flexMenu({
		showOnHover: false,
		cutoff: 0,
		popupClass: "dropdown-menu-right",
		linkText: '<i class="uil-ellipsis-h"></i>',
		hOverflow: true,
	});

    if(body.is('.post-type-archive-lp_course', '.post-type-archive')) {
        const LpNav = $('.nav-component-list.course-navbar');
    }

})( jQuery );
