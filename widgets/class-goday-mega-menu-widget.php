<?php
namespace GoDayMegaMenu\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class GoDayMegaMenuWidget extends Widget_Base {

	public function get_name(): string {
		return 'goday-mega-menu';
	}

	public function get_title(): string {
		return __( 'GO Day Mega Menu', 'goday-mega-menu' );
	}

	public function get_icon(): string {
		return 'eicon-menu-bar';
	}

	public function get_categories(): array {
		return [ 'goday' ];
	}

	public function get_keywords(): array {
		return [ 'menu', 'mega', 'goday', 'navigation' ];
	}

	public function get_script_depends(): array {
		return [ 'goday-mega-menu' ];
	}

	public function get_style_depends(): array {
		return [ 'goday-mega-menu' ];
	}

	protected function register_controls(): void {

		$this->start_controls_section( 'section_content', [
			'label' => __( 'Trigger', 'goday-mega-menu' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'trigger_text', [
			'label'   => __( 'Trigger Label', 'goday-mega-menu' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'Go Day',
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'section_style', [
			'label' => __( 'Style', 'goday-mega-menu' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'trigger_color', [
			'label'     => __( 'Trigger Text Color', 'goday-mega-menu' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [
				'{{WRAPPER}} .goday-mm-trigger' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_section();
	}

	protected function render(): void {
		$settings     = $this->get_settings_for_display();
		$trigger_text = $settings['trigger_text'];
		$widget_id    = $this->get_id();
		$img_base     = GODAY_MEGA_MENU_URL . 'assets/images/';
		?>
		<div class="goday-mm-wrapper" data-goday-mm="<?php echo esc_attr( $widget_id ); ?>">
			<!-- Trigger button -->
			<button class="goday-mm-trigger"
			        aria-expanded="false"
			        aria-controls="goday-mm-panel-<?php echo esc_attr( $widget_id ); ?>">
				<span class="goday-mm-trigger__text"><?php echo esc_html( $trigger_text ); ?></span>
				<svg class="goday-mm-trigger__chevron" width="10" height="6" viewBox="0 0 10 6" fill="none">
					<path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
			</button>

			<!-- Click-away overlay -->
			<div class="goday-mm-overlay" aria-hidden="true"></div>

			<!-- Mega menu panel -->
			<div id="goday-mm-panel-<?php echo esc_attr( $widget_id ); ?>"
			     class="goday-mm-panel"
			     aria-hidden="true">
				<div class="goday-mm-grid">

					<!-- Column 1: Come Join Us -->
					<div class="goday-mm-col goday-mm-col--1">
						<div class="goday-mm-card" style="--delay: 0.05s">
							<img src="<?php echo esc_url( $img_base . 'hero-bg.webp' ); ?>"
							     alt="Come join us for GO Day"
							     class="goday-mm-card__img"
							     loading="lazy" />
							<div class="goday-mm-card__overlay"></div>
							<div class="goday-mm-card__text" style="--delay: 0.15s">
								<h3 class="goday-mm-card__title">Come Join Us</h3>
								<p class="goday-mm-card__sub">A Global Event</p>
							</div>
						</div>
						<div class="goday-mm-info" style="--delay: 0.2s">
							<?php $this->render_goday_logo(); ?>
							<p class="goday-mm-info__headline">Share Jesus with One Person</p>
							<p class="goday-mm-info__date">
								<span class="goday-mm-info__red">Pentecost Saturday</span> &ndash; Saturday, May 23, 2026
							</p>
						</div>
					</div>

					<!-- Divider 1 -->
					<div class="goday-mm-divider" style="--delay: 0.08s" aria-hidden="true"></div>

					<!-- Column 2: Leaders & Pastors -->
					<div class="goday-mm-col goday-mm-col--2">
						<div class="goday-mm-card" style="--delay: 0.12s">
							<img src="<?php echo esc_url( $img_base . 'youth-leader.jpg' ); ?>"
							     alt="Leaders and Pastors"
							     class="goday-mm-card__img"
							     loading="lazy" />
							<div class="goday-mm-card__overlay"></div>
							<div class="goday-mm-card__text" style="--delay: 0.22s">
								<h3 class="goday-mm-card__title">Leaders &amp; Pastors</h3>
								<p class="goday-mm-card__sub">We Want to Support You</p>
							</div>
						</div>
						<div class="goday-mm-info" style="--delay: 0.28s">
							<?php $this->render_goday_logo(); ?>
							<p class="goday-mm-info__headline">Leaders &amp; Pastors</p>
							<p class="goday-mm-info__soon">Coming soon</p>
						</div>
					</div>

					<!-- Divider 2 -->
					<div class="goday-mm-divider" style="--delay: 0.08s" aria-hidden="true"></div>

					<!-- Column 3: Quick Links -->
					<div class="goday-mm-col goday-mm-col--3">
						<div class="goday-mm-links">
							<button class="goday-mm-link" data-action="calendar" style="--delay: 0.18s">
								<h4 class="goday-mm-link__title">Set Your Calendar</h4>
								<p class="goday-mm-link__sub">May 23, 2026</p>
							</button>
							<a href="https://goday.world" target="_blank" rel="noopener noreferrer"
							   class="goday-mm-link" style="--delay: 0.24s">
								<h4 class="goday-mm-link__title">Invite a Friend</h4>
								<p class="goday-mm-link__sub">Bring several if you can</p>
							</a>
							<a href="https://gomovement.world/who-we-are" target="_blank" rel="noopener noreferrer"
							   class="goday-mm-link" style="--delay: 0.3s">
								<h4 class="goday-mm-link__title">Who We Are</h4>
								<p class="goday-mm-link__sub">Mobilizing Christians to share their faith globally</p>
							</a>
							<a href="https://gomovement.world/news-stories" target="_blank" rel="noopener noreferrer"
							   class="goday-mm-link" style="--delay: 0.36s">
								<h4 class="goday-mm-link__title">News &amp; Stories</h4>
								<p class="goday-mm-link__sub">Testimonies and updates from around the world</p>
							</a>
							<a href="https://gomovement.world/resources" target="_blank" rel="noopener noreferrer"
							   class="goday-mm-link" style="--delay: 0.42s">
								<h4 class="goday-mm-link__title">Resources</h4>
								<p class="goday-mm-link__sub">Tools and guides to help you share your faith</p>
							</a>
						</div>
						<div class="goday-mm-snippet" style="--delay: 0.45s">
							<p>GO Day is a global movement where millions of believers share the love
							and message of Jesus with one person through a simple conversation.</p>
						</div>
					</div>

				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render the inline GO DAY logo SVG.
	 */
	private function render_goday_logo(): void {
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

	protected function content_template(): void {
		?>
		<div class="goday-mm-wrapper">
			<button class="goday-mm-trigger">
				<span class="goday-mm-trigger__text">{{{ settings.trigger_text }}}</span>
				<svg class="goday-mm-trigger__chevron" width="10" height="6" viewBox="0 0 10 6" fill="none">
					<path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
			</button>
			<div class="goday-mm-panel" style="display:none;">
				<p style="padding:2em;color:#999;text-align:center;">[Mega menu preview — save and view on frontend]</p>
			</div>
		</div>
		<?php
	}
}
