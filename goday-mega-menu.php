<?php
/**
 * Plugin Name: GO Day Mega Menu
 * Plugin URI:  https://goday.world
 * Description: Adds a GO Day mega menu dropdown to any WordPress nav menu item. Create a Custom Link menu item with URL "#goday-mega-menu" and the plugin handles the rest.
 * Version:     1.4.4
 * Author:      PERC Engage
 * Author URI:  https://percengage.com
 * License:     GPL-2.0-or-later
 * Text Domain: goday-mega-menu
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'GODAY_MEGA_MENU_VERSION', '1.4.4' );
define( 'GODAY_MEGA_MENU_URL', plugin_dir_url( __FILE__ ) );
define( 'GODAY_MEGA_MENU_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Enqueue frontend CSS and JS on every page.
 */
add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style(
		'goday-mega-menu',
		GODAY_MEGA_MENU_URL . 'assets/css/goday-mega-menu.css',
		[],
		GODAY_MEGA_MENU_VERSION
	);
	wp_enqueue_script(
		'goday-mega-menu',
		GODAY_MEGA_MENU_URL . 'assets/js/goday-mega-menu.js',
		[],
		GODAY_MEGA_MENU_VERSION,
		true
	);
} );

/**
 * Mark the target menu item.
 *
 * When a menu item has URL "#goday-mega-menu", add a CSS class so the
 * frontend JS can find it, and neutralize the link href.
 */
add_filter( 'wp_nav_menu_objects', function ( $items ) {
	foreach ( $items as $item ) {
		if ( $item->url === '#goday-mega-menu' ) {
			$item->classes[] = 'goday-mm-item';
			$item->url       = '#';
		}
	}
	return $items;
} );

/**
 * Output the mega menu panel HTML in the footer.
 *
 * Hidden by default — JS positions and reveals it when the trigger
 * menu item is hovered or clicked.
 */
add_action( 'wp_footer', function () {
	$img_base = esc_url( GODAY_MEGA_MENU_URL . 'assets/images/' );
	?>
	<!-- GO Day Mega Menu Panel -->
	<div id="goday-mm-panel" class="goday-mm-panel" aria-hidden="true">
		<div class="goday-mm-grid">

			<!-- Column 1: Come Join Us -->
			<div class="goday-mm-col goday-mm-col--1">
				<a href="https://goday.world" target="_blank" rel="noopener noreferrer" class="goday-mm-card" style="--delay: 0.05s">
					<img src="<?php echo $img_base; ?>hero-bg.webp"
					     alt="Come join us for GO Day"
					     class="goday-mm-card__img"
					     loading="lazy" />
					<div class="goday-mm-card__overlay"></div>
					<div class="goday-mm-card__text" style="--delay: 0.15s">
						<h3 class="goday-mm-card__title">Come Join Us</h3>
						<p class="goday-mm-card__sub">A Global Event</p>
					</div>
				</a>
				<a href="https://goday.world" target="_blank" rel="noopener noreferrer" class="goday-mm-info" style="--delay: 0.2s">
					<?php goday_mega_menu_render_logo(); ?>
					<p class="goday-mm-info__headline">Share Jesus with One Person</p>
					<p class="goday-mm-info__date">
						<span class="goday-mm-info__red">Pentecost Saturday</span> &ndash; May 23, 2026
					</p>
				</a>
			</div>

			<!-- Divider 1 -->
			<div class="goday-mm-divider" style="--delay: 0.08s" aria-hidden="true"></div>

			<!-- Column 2: Leaders & Pastors -->
			<div class="goday-mm-col goday-mm-col--2">
				<a href="https://goday.world/pastors" target="_blank" rel="noopener noreferrer" class="goday-mm-card" style="--delay: 0.12s">
					<img src="<?php echo $img_base; ?>youth-leader.jpg"
					     alt="Leaders and Pastors"
					     class="goday-mm-card__img"
					     loading="lazy" />
					<div class="goday-mm-card__overlay"></div>
					<div class="goday-mm-card__text" style="--delay: 0.22s">
						<h3 class="goday-mm-card__title">Leaders &amp; Pastors</h3>
						<p class="goday-mm-card__sub">We Want to Support You</p>
					</div>
				</a>
				<a href="https://goday.world/pastors" target="_blank" rel="noopener noreferrer" class="goday-mm-info" style="--delay: 0.28s">
					<?php goday_mega_menu_render_logo(); ?>
					<p class="goday-mm-info__headline">Leaders &amp; Pastors</p>
				</a>
			</div>

			<!-- Divider 2 -->
			<div class="goday-mm-divider" style="--delay: 0.08s" aria-hidden="true"></div>

			<!-- Column 3: Quick Links -->
			<div class="goday-mm-col goday-mm-col--3">
				<div class="goday-mm-links">
					<div class="goday-mm-calendar-wrap">
						<button class="goday-mm-link" data-action="calendar" style="--delay: 0.18s">
							<h4 class="goday-mm-link__title">Set Your Calendar</h4>
							<p class="goday-mm-link__sub">May 23, 2026</p>
						</button>
						<div class="goday-mm-cal-dropdown" aria-hidden="true">
							<button class="goday-mm-cal-option" data-calendar="google">
								<svg viewBox="0 0 24 24" class="goday-mm-cal-icon" fill="none">
									<path d="M18 3h-1V2h-2v1H9V2H7v1H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2zm0 16H6V8h12v11z" fill="#4285F4"/>
									<rect x="8" y="10" width="3" height="2" rx="0.5" fill="#EA4335"/>
									<rect x="13" y="10" width="3" height="2" rx="0.5" fill="#FBBC04"/>
									<rect x="8" y="14" width="3" height="2" rx="0.5" fill="#34A853"/>
									<rect x="13" y="14" width="3" height="2" rx="0.5" fill="#4285F4"/>
								</svg>
								<span>Google Calendar</span>
							</button>
							<button class="goday-mm-cal-option" data-calendar="apple">
								<svg viewBox="0 0 24 24" class="goday-mm-cal-icon" fill="currentColor">
									<path d="M17.05 20.28c-.98.95-2.05.8-3.08.36-1.09-.46-2.09-.48-3.24 0-1.44.62-2.2.44-3.06-.36C2.79 15.25 3.51 7.59 9.05 7.31c1.35.07 2.29.74 3.08.8 1.18-.24 2.31-.93 3.57-.84 1.51.12 2.65.72 3.4 1.8-3.12 1.87-2.38 5.98.48 7.13-.57 1.5-1.31 2.99-2.53 4.09zM12.03 7.25c-.15-2.23 1.66-4.07 3.74-4.25.29 2.58-2.34 4.5-3.74 4.25z"/>
								</svg>
								<span>Apple Calendar</span>
							</button>
							<button class="goday-mm-cal-option" data-calendar="outlook">
								<svg viewBox="0 0 24 24" class="goday-mm-cal-icon" fill="none">
									<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z" fill="#0078D4"/>
									<path d="M12 6c-1.66 0-3 1.57-3 3.5S10.34 13 12 13s3-1.57 3-3.5S13.66 6 12 6zm0 5.5c-.55 0-1-.67-1-1.5s.45-1.5 1-1.5 1 .67 1 1.5-.45 1.5-1 1.5z" fill="white"/>
									<path d="M7 15h10v1.5a1.5 1.5 0 0 1-1.5 1.5h-7A1.5 1.5 0 0 1 7 16.5V15z" fill="white" opacity="0.8"/>
								</svg>
								<span>Outlook</span>
							</button>
							<button class="goday-mm-cal-option" data-calendar="yahoo">
								<svg viewBox="0 0 24 24" class="goday-mm-cal-icon" fill="none">
									<circle cx="12" cy="12" r="10" fill="#6001D2"/>
									<path d="M7 7l3.5 5.5V17h3v-4.5L17 7h-3l-2 3.5L10 7H7z" fill="white"/>
								</svg>
								<span>Yahoo Calendar</span>
							</button>
						</div>
					</div>
					<a href="https://goday.world/#invite" target="_blank" rel="noopener noreferrer"
					   class="goday-mm-link" style="--delay: 0.24s">
						<h4 class="goday-mm-link__title">Invite a Friend</h4>
						<p class="goday-mm-link__sub">Bring several if you can</p>
					</a>
					<a href="https://gomovement.world/who-we-are"
					   class="goday-mm-link" style="--delay: 0.3s">
						<h4 class="goday-mm-link__title">Who We Are</h4>
						<p class="goday-mm-link__sub">Mobilizing Christians to share their faith globally</p>
					</a>
					<a href="https://gomovement.world/news-stories"
					   class="goday-mm-link" style="--delay: 0.36s">
						<h4 class="goday-mm-link__title">News &amp; Stories</h4>
						<p class="goday-mm-link__sub">Testimonies and updates from around the world</p>
					</a>
					<a href="https://gomovement.world/resources"
					   class="goday-mm-link" style="--delay: 0.42s">
						<h4 class="goday-mm-link__title">Resources</h4>
						<p class="goday-mm-link__sub">Tools and guides to help you share your faith</p>
					</a>
				</div>
				<div class="goday-mm-snippet" style="--delay: 0.45s">
					<p><a href="https://goday.world" target="_blank" rel="noopener noreferrer">GO Day</a> is a global movement where millions of believers share the love
					and message of Jesus with one person through a simple conversation.</p>
				</div>
			</div>

		</div>
	</div>
	<!-- GO Day Mega Menu Overlay -->
	<div id="goday-mm-overlay" class="goday-mm-overlay" aria-hidden="true"></div>
	<?php
} );

/**
 * Render the inline GO DAY logo SVG.
 */
function goday_mega_menu_render_logo() {
	?>
	<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 364.74 74.42" class="goday-mm-logo" aria-label="GO Day" role="img">
		<g fill="#D90000">
			<path d="M35.37,59.69c-2.04-.34-4.08-.59-6.05-1.26-19.7-6.72-18.13-38.28,2.25-43.17,8.06-1.93,17.88-.47,22.63,6.75.99,1.51,1.59,3.01,2.38,4.61h16c-2.36-13.12-12.96-22.63-25.7-25.56C25.68-3.82,4.4,8.52.65,30.33c-2.11,12.28.84,24.48,9.86,33.26,4.43.05,8.97.05,13.38-.59,3.93-.57,7.87-1.66,11.48-3.3Z"/>
			<path d="M73.4,32.96h-37.37v13.5c.12.04,20.96,0,20.96,0-4.03,10.68-14.17,17.79-24.83,20.91-4.46,1.31-9.04,1.95-13.68,2.24,12.27,6.88,29.95,7.01,40.16-3.59.04-.04.07-.08.11-.12l2.14-2.7.81,9.59h11.7v-39.81Z"/>
			<path d="M112.95.02c-33.2-.05-49.88,40.05-26.51,63.42,13.95,13.95,36.97,14.61,51.58,1.28C163.21,41.74,146.98.07,112.95.02ZM118.9,59.23c-22.73,6.19-36.84-22.23-21.22-38.51,10.27-10.71,27.67-7.6,34.43,5.42,6.38,12.3.59,29.33-13.21,33.09Z"/>
		</g>
		<g fill="currentColor">
			<path d="M237.99,35.65c0-7.05-1.55-13.24-4.65-18.57-3.1-5.33-7.38-9.49-12.84-12.49C215.05,1.6,208.73.1,201.55.1h-24.59v74.22h24.46c7.22,0,13.57-1.49,19.05-4.46,5.48-2.97,9.77-7.14,12.87-12.49,3.1-5.35,4.65-11.53,4.65-18.54v-3.19ZM221.62,38.84c0,4.84-.69,8.93-2.07,12.26-1.38,3.33-3.56,5.85-6.53,7.55-2.97,1.7-6.84,2.55-11.59,2.55h-8.09V13.29h8.22c4.67,0,8.48.84,11.44,2.52,2.95,1.68,5.13,4.16,6.53,7.45,1.4,3.29,2.1,7.38,2.1,12.26v3.31Z"/>
			<path d="M276.63.1h-11.02l-29.75,74.22h16.44l5.81-15.35h30.14l5.84,15.35h16.44L280.84.1h-4.21ZM262.74,46.74l10.39-27.46,10.46,27.46h-20.85Z"/>
			<polygon points="364.74 .1 346.9 .1 330.19 33.28 313.52 .1 295.68 .1 321.8 47.65 321.8 74.32 338.18 74.32 338.18 48.35 364.74 .1"/>
		</g>
	</svg>
	<?php
}
