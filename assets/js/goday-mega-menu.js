/**
 * GO Day Mega Menu — Frontend Interactions
 *
 * Finds the nav menu item marked with .goday-mm-item (via WP filter on
 * items with URL "#goday-mega-menu") and attaches the mega menu panel
 * that is output in wp_footer.
 */
(function () {
	"use strict";

	var CLOSE_DELAY = 200;

	/**
	 * Generate and trigger download of an .ics calendar file (Apple Calendar).
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

	function getGoogleCalendarUrl() {
		var params = new URLSearchParams({
			action: "TEMPLATE",
			text: "GO Day 2026",
			dates: "20260523/20260524",
			details: "Share Jesus with One Person — Pentecost Saturday. Learn more at https://goday.world",
			sf: "true",
		});
		return "https://calendar.google.com/calendar/render?" + params;
	}

	function getOutlookUrl() {
		var params = new URLSearchParams({
			subject: "GO Day 2026",
			startdt: "2026-05-23",
			enddt: "2026-05-24",
			body: "Share Jesus with One Person — Pentecost Saturday. Learn more at https://goday.world",
			allday: "true",
			path: "/calendar/action/compose",
		});
		return "https://outlook.live.com/calendar/0/action/compose?" + params;
	}

	function getYahooUrl() {
		var params = new URLSearchParams({
			v: "60",
			title: "GO Day 2026",
			st: "20260523",
			et: "20260524",
			desc: "Share Jesus with One Person — Pentecost Saturday. Learn more at https://goday.world",
			in_loc: "",
		});
		return "https://calendar.yahoo.com/?" + params;
	}

	var calendarActions = {
		google: function () { window.open(getGoogleCalendarUrl(), "_blank"); },
		apple: function () { downloadIcs(); },
		outlook: function () { window.open(getOutlookUrl(), "_blank"); },
		yahoo: function () { window.open(getYahooUrl(), "_blank"); },
	};

	function init() {
		// Find the menu <li> that the PHP filter marked with .goday-mm-item
		var menuItem = document.querySelector(".goday-mm-item");
		if (!menuItem) return;

		var trigger = menuItem.querySelector("a");
		var panel = document.getElementById("goday-mm-panel");
		var overlay = document.getElementById("goday-mm-overlay");

		if (!trigger || !panel) return;

		// Prevent the "#" link from scrolling to top
		trigger.addEventListener("click", function (e) {
			e.preventDefault();
		});

		// Add a chevron to the trigger link
		var chevron = document.createElement("span");
		chevron.className = "goday-mm-chevron";
		chevron.innerHTML =
			'<svg width="10" height="6" viewBox="0 0 10 6" fill="none">' +
			'<path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>' +
			"</svg>";
		trigger.appendChild(chevron);

		var closeTimer = null;
		var isOpen = false;

		function positionPanel() {
			// Position panel directly below the header/nav bar
			var headerEl =
				menuItem.closest("header") ||
				menuItem.closest(".elementor-location-header") ||
				menuItem.closest("[data-elementor-type='header']") ||
				menuItem.closest("nav");

			if (headerEl) {
				var rect = headerEl.getBoundingClientRect();
				panel.style.top = rect.bottom + "px";
			} else {
				var triggerRect = trigger.getBoundingClientRect();
				panel.style.top = triggerRect.bottom + 8 + "px";
			}
		}

		function open() {
			if (closeTimer) {
				clearTimeout(closeTimer);
				closeTimer = null;
			}
			if (isOpen) return;
			isOpen = true;

			positionPanel();
			document.body.classList.add("goday-mm-is-open");
			menuItem.classList.add("is-active");
			trigger.setAttribute("aria-expanded", "true");
			panel.setAttribute("aria-hidden", "false");
		}

		function close() {
			if (!isOpen) return;
			isOpen = false;
			document.body.classList.remove("goday-mm-is-open");
			menuItem.classList.remove("is-active");
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

		// --- Hover (desktop) ---
		menuItem.addEventListener("mouseenter", open);
		menuItem.addEventListener("mouseleave", scheduleClose);
		panel.addEventListener("mouseenter", cancelClose);
		panel.addEventListener("mouseleave", scheduleClose);

		// --- Click toggle ---
		trigger.addEventListener("click", function (e) {
			e.preventDefault();
			if (isOpen) close();
			else open();
		});

		// --- Overlay click-away ---
		if (overlay) {
			overlay.addEventListener("click", close);
		}

		// --- Escape key ---
		document.addEventListener("keydown", function (e) {
			if (e.key === "Escape" && isOpen) close();
		});

		// --- Scroll close ---
		var ticking = false;
		window.addEventListener(
			"scroll",
			function () {
				if (isOpen && !ticking) {
					ticking = true;
					requestAnimationFrame(function () {
						close();
						ticking = false;
					});
				}
			},
			{ passive: true }
		);

		// --- Calendar dropdown ---
		var calBtn = panel.querySelector('[data-action="calendar"]');
		var calDropdown = panel.querySelector('.goday-mm-cal-dropdown');

		if (calBtn && calDropdown) {
			calBtn.addEventListener("click", function (e) {
				e.preventDefault();
				e.stopPropagation();
				var isCalOpen = calDropdown.getAttribute("aria-hidden") === "false";
				calDropdown.setAttribute("aria-hidden", isCalOpen ? "true" : "false");
			});

			var calOptions = calDropdown.querySelectorAll('.goday-mm-cal-option');
			for (var j = 0; j < calOptions.length; j++) {
				calOptions[j].addEventListener("click", function (e) {
					e.preventDefault();
					e.stopPropagation();
					var type = this.getAttribute("data-calendar");
					if (calendarActions[type]) {
						calendarActions[type]();
					}
					calDropdown.setAttribute("aria-hidden", "true");
					close();
				});
			}
		}

		// Close calendar dropdown when mega menu closes
		var origClose = close;
		close = function () {
			if (calDropdown) {
				calDropdown.setAttribute("aria-hidden", "true");
			}
			origClose();
		};

		// --- Close panel on link click ---
		var links = panel.querySelectorAll("a.goday-mm-link");
		for (var i = 0; i < links.length; i++) {
			links[i].addEventListener("click", function () {
				setTimeout(close, 100);
			});
		}

		// Mark as initialized
		menuItem.setAttribute("aria-haspopup", "true");
		trigger.setAttribute("aria-expanded", "false");
	}

	// Initialize on DOM ready
	if (document.readyState === "loading") {
		document.addEventListener("DOMContentLoaded", init);
	} else {
		init();
	}
})();
