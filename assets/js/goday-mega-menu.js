/**
 * GO Day Mega Menu — Frontend Interactions
 *
 * Handles hover/click toggle, .ics calendar download,
 * scroll/escape/click-outside close, and Elementor editor compatibility.
 */
(function () {
	"use strict";

	var CLOSE_DELAY = 200; // ms before menu closes on mouseleave

	/**
	 * Generate and trigger download of an .ics calendar file.
	 */
	function downloadIcs() {
		var lines = [
			"BEGIN:VCALENDAR",
			"VERSION:2.0",
			"PRODID:-//GO Day//goday.world//EN",
			"CALSCALE:GREGORIAN",
			"METHOD:PUBLISH",
			"BEGIN:VEVENT",
			"DTSTART;VALUE=DATE:20260523",
			"DTEND;VALUE=DATE:20260524",
			"SUMMARY:GO Day 2026",
			"DESCRIPTION:Share Jesus with One Person — Pentecost Saturday. Learn more at https://goday.world",
			"URL:https://goday.world",
			"STATUS:CONFIRMED",
			"TRANSP:TRANSPARENT",
			"END:VEVENT",
			"END:VCALENDAR",
		].join("\r\n");

		var blob = new Blob([lines], { type: "text/calendar;charset=utf-8" });
		var url = URL.createObjectURL(blob);
		var a = document.createElement("a");
		a.href = url;
		a.download = "go-day-2026.ics";
		document.body.appendChild(a);
		a.click();
		document.body.removeChild(a);
		URL.revokeObjectURL(url);
	}

	/**
	 * Initialize one instance of the mega menu widget.
	 */
	function initWidget(root) {
		var trigger = root.querySelector(".goday-mm-trigger");
		var panel = root.querySelector(".goday-mm-panel");
		var overlay = root.querySelector(".goday-mm-overlay");

		if (!trigger || !panel) return;

		var closeTimer = null;
		var isOpen = false;

		function open() {
			if (closeTimer) {
				clearTimeout(closeTimer);
				closeTimer = null;
			}
			if (isOpen) return;
			isOpen = true;

			// Position the panel below the site header
			var headerEl =
				root.closest("header") ||
				root.closest(".elementor-location-header") ||
				root.closest("[data-elementor-type='header']");

			if (headerEl) {
				var rect = headerEl.getBoundingClientRect();
				panel.style.top = rect.bottom + "px";
			} else {
				var triggerRect = trigger.getBoundingClientRect();
				panel.style.top = triggerRect.bottom + 8 + "px";
			}

			root.classList.add("is-open");
			trigger.classList.add("is-active");
			trigger.setAttribute("aria-expanded", "true");
			panel.setAttribute("aria-hidden", "false");
		}

		function close() {
			if (!isOpen) return;
			isOpen = false;
			root.classList.remove("is-open");
			trigger.classList.remove("is-active");
			trigger.setAttribute("aria-expanded", "false");
			panel.setAttribute("aria-hidden", "true");
		}

		function scheduleClose() {
			if (closeTimer) clearTimeout(closeTimer);
			closeTimer = setTimeout(close, CLOSE_DELAY);
		}

		function cancelClose() {
			if (closeTimer) {
				clearTimeout(closeTimer);
				closeTimer = null;
			}
		}

		// --- Hover behavior (desktop) ---
		trigger.addEventListener("mouseenter", open);
		trigger.addEventListener("mouseleave", scheduleClose);
		panel.addEventListener("mouseenter", function () {
			cancelClose();
		});
		panel.addEventListener("mouseleave", scheduleClose);

		// --- Click toggle (accessibility + mobile) ---
		trigger.addEventListener("click", function (e) {
			e.preventDefault();
			if (isOpen) {
				close();
			} else {
				open();
			}
		});

		// --- Click-away overlay ---
		if (overlay) {
			overlay.addEventListener("click", close);
		}

		// --- Escape key ---
		document.addEventListener("keydown", function (e) {
			if (e.key === "Escape" && isOpen) {
				close();
			}
		});

		// --- Scroll close ---
		var scrolling = false;
		window.addEventListener(
			"scroll",
			function () {
				if (isOpen && !scrolling) {
					scrolling = true;
					requestAnimationFrame(function () {
						close();
						scrolling = false;
					});
				}
			},
			{ passive: true }
		);

		// --- Calendar download button ---
		var calBtn = root.querySelector('[data-action="calendar"]');
		if (calBtn) {
			calBtn.addEventListener("click", function (e) {
				e.preventDefault();
				downloadIcs();
				close();
			});
		}

		// --- Close on link click ---
		var links = panel.querySelectorAll("a.goday-mm-link");
		for (var i = 0; i < links.length; i++) {
			links[i].addEventListener("click", function () {
				// Let the link navigate, but close menu
				setTimeout(close, 100);
			});
		}
	}

	// --- Elementor frontend hook ---
	if (typeof jQuery !== "undefined" && typeof elementorFrontend !== "undefined") {
		jQuery(window).on("elementor/frontend/init", function () {
			elementorFrontend.hooks.addAction(
				"frontend/element_ready/goday-mega-menu.default",
				function ($scope) {
					initWidget($scope[0]);
				}
			);
		});
	}

	// --- Fallback: init on DOMContentLoaded if outside Elementor ---
	document.addEventListener("DOMContentLoaded", function () {
		var widgets = document.querySelectorAll(".goday-mm-wrapper");
		for (var i = 0; i < widgets.length; i++) {
			// Only init if Elementor didn't already handle it
			if (!widgets[i].dataset.godayMmInit) {
				widgets[i].dataset.godayMmInit = "1";
				initWidget(widgets[i]);
			}
		}
	});
})();
