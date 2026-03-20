/**
 * GO Day Mega Menu — Frontend Interactions
 *
 * Finds the nav menu item with href "#goday-mega-menu" and attaches the
 * mega menu panel. Works with standard WordPress menus (via PHP filter)
 * and Elementor Menu widgets (via JS detection + capture-phase click).
 */
(function () {
	"use strict";

	var CLOSE_DELAY = 200;
	var initialized = false;

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

	var MOBILE_BREAKPOINT = 1024;

	function isMobile() {
		return window.innerWidth <= MOBILE_BREAKPOINT;
	}

	// Selector that matches the Go Day trigger link in any menu system
	var TRIGGER_SELECTOR = 'a[href="#goday-mega-menu"], a[href*="#goday-mega-menu"], .goday-mm-item a';

	// --- Capture-phase click handler ---
	// Registered on the document IMMEDIATELY so it fires before Elementor's
	// handlers, even if Elementor hasn't rendered the menu yet.
	document.addEventListener("click", function (e) {
		var link = e.target.closest(TRIGGER_SELECTOR);
		if (!link) return;
		e.preventDefault();
		e.stopImmediatePropagation();

		// On mobile, just go to goday.world
		if (isMobile()) {
			window.open("https://goday.world", "_blank");
			return;
		}

		// If not yet initialized, try now
		if (!initialized) init();
		if (!initialized) return;

		// Toggle
		if (isOpen) close();
		else open();
	}, true); // <-- capture phase

	// --- Document-level hover handler ---
	// Opens on trigger hover. Only closes when mouse leaves the panel.
	// This avoids flicker caused by the panel overlapping the trigger area.
	var hoverOpenTimer = null;

	document.addEventListener("mouseover", function (e) {
		if (isMobile()) return;

		// Mouse entered the trigger
		var link = e.target.closest(TRIGGER_SELECTOR);
		if (link) {
			if (!initialized) init();
			if (!initialized) return;
			cancelClose();
			// Debounce the open to prevent flicker
			if (!isOpen && !hoverOpenTimer) {
				hoverOpenTimer = setTimeout(function () {
					hoverOpenTimer = null;
					// Re-check that mouse is still over the trigger
					open();
				}, 80);
			}
			return;
		}

		// Mouse entered the panel — cancel any pending close
		if (e.target.closest('#goday-mm-panel')) {
			cancelClose();
			if (hoverOpenTimer) {
				clearTimeout(hoverOpenTimer);
				hoverOpenTimer = null;
			}
		}
	});

	document.addEventListener("mouseout", function (e) {
		// Cancel open if mouse leaves trigger before debounce fires
		var link = e.target.closest(TRIGGER_SELECTOR);
		if (link && hoverOpenTimer) {
			var related = e.relatedTarget;
			if (!related || !(related.closest && related.closest(TRIGGER_SELECTOR))) {
				clearTimeout(hoverOpenTimer);
				hoverOpenTimer = null;
			}
		}

		if (!isOpen) return;

		// Only close when leaving the panel to somewhere that isn't the trigger
		var fromPanel = e.target.closest('#goday-mm-panel');
		if (!fromPanel) return;

		var related = e.relatedTarget;
		if (related && related.closest && related.closest(TRIGGER_SELECTOR)) return;

		scheduleClose();
	});

	var panel = null;
	var overlay = null;
	var menuItem = null;
	var trigger = null;
	var closeTimer = null;
	var isOpen = false;

	function init() {
		if (initialized) return true;

		console.log("[GO Day MM] init() running...");

		// First try the PHP-marked class (standard WP menus)
		menuItem = document.querySelector(".goday-mm-item");

		if (menuItem) {
			console.log("[GO Day MM] Found .goday-mm-item (WP menu)");
			trigger = menuItem.querySelector("a");
		} else {
			// Fallback: find any link pointing to #goday-mega-menu (Elementor, etc.)
			trigger = document.querySelector('a[href="#goday-mega-menu"], a[href*="#goday-mega-menu"]');
			console.log("[GO Day MM] Fallback search result:", trigger);
			if (trigger) {
				menuItem = trigger.closest("li") || trigger.parentElement;
				menuItem.classList.add("goday-mm-item");

				// Remove Elementor's anchor class so it stops treating this as a scroll link
				var anchorParent = trigger.closest(".e-anchor");
				if (anchorParent) {
					anchorParent.classList.remove("e-anchor");
				}

				console.log("[GO Day MM] menuItem:", menuItem.tagName, menuItem.className);
			}
		}

		if (!menuItem || !trigger) {
			console.log("[GO Day MM] No trigger found, will retry...");
			return false;
		}

		panel = document.getElementById("goday-mm-panel");
		overlay = document.getElementById("goday-mm-overlay");

		console.log("[GO Day MM] panel:", panel, "overlay:", overlay);
		if (!panel) {
			console.log("[GO Day MM] No panel found!");
			return false;
		}

		// Neutralize the anchor link
		trigger.setAttribute("href", "#");

		// Add a chevron to the trigger link
		var chevron = document.createElement("span");
		chevron.className = "goday-mm-chevron";
		chevron.innerHTML =
			'<svg width="10" height="6" viewBox="0 0 10 6" fill="none">' +
			'<path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>' +
			"</svg>";
		trigger.appendChild(chevron);

		// --- Hover (desktop) ---
		menuItem.addEventListener("mouseenter", open);
		menuItem.addEventListener("mouseleave", scheduleClose);
		panel.addEventListener("mouseenter", cancelClose);
		panel.addEventListener("mouseleave", scheduleClose);

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
		initialized = true;
		console.log("[GO Day MM] Initialized successfully!");
		return true;
	}

	function positionPanel() {
		if (!menuItem || !panel) return;
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

		// Also close calendar dropdown
		if (panel) {
			var calDropdown = panel.querySelector('.goday-mm-cal-dropdown');
			if (calDropdown) {
				calDropdown.setAttribute("aria-hidden", "true");
			}
		}
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

	// Retry init until Elementor renders the menu
	function tryInit(attempts) {
		if (init()) return;
		if (attempts > 0) {
			setTimeout(function () { tryInit(attempts - 1); }, 300);
		}
	}

	if (document.readyState === "loading") {
		document.addEventListener("DOMContentLoaded", function () { tryInit(30); });
	} else {
		tryInit(30);
	}
})();
