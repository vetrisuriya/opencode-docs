/**
 * Component: Team Leaders
 * Click a card (image or name) to slide down a profile panel under its row.
 * Works with several [data-tl] sections on the same page.
 */
(function () {
  'use strict';

  // Link icons. The type comes from data-type on each .tl-soc link.
  var ICONS = {
    linkedin: '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M4.98 3.5a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5zM3 9.75h4V21H3zM9.5 9.75h3.8v1.6h.05c.53-1 1.82-2 3.75-2 4 0 4.75 2.6 4.75 6V21h-4v-4.9c0-1.2 0-2.7-1.65-2.7s-1.9 1.3-1.9 2.6V21h-4z"/></svg>',
    x: '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.24 2.25h3.31l-7.23 8.26 8.5 11.24H16.17l-5.21-6.82-5.97 6.82H1.68l7.73-8.84L1.25 2.25H8.08l4.71 6.23zm-1.16 17.52h1.83L7.08 4.13H5.12z"/></svg>',
    instagram: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r=".8" fill="currentColor"/></svg>',
    facebook: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>',
    youtube: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"/><path d="m9.75 15.02 5.75-3.27-5.75-3.27z"/></svg>',
    github: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"/></svg>',
    mail: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>',
    web: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a14 14 0 0 1 0 18M12 3a14 14 0 0 0 0 18"/></svg>'
  };

  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  function init(root) {
    var grid = root.querySelector('.tl__grid');
    if (!grid) return;

    var cards = Array.prototype.slice.call(grid.querySelectorAll('.tl-card'));
    var panel = null, box = null, activeCard = null, anchor = null;

    // last card in the same visual row as `card`
    function rowEnd(card) {
      var top = card.offsetTop, last = card;
      cards.forEach(function (c) { if (c.offsetTop === top) last = c; });
      return last;
    }

    function fill(card) {
      box.innerHTML = card.querySelector('.tl-card__details').innerHTML +
        '<button class="tl-panel__close" type="button" aria-label="Close profile">&times;</button>';
      Array.prototype.forEach.call(box.querySelectorAll('.tl-soc'), function (a) {
        a.insertAdjacentHTML('afterbegin', ICONS[a.getAttribute('data-type')] || ICONS.web);
      });
      // arrow under the active card
      var g = panel.getBoundingClientRect(), c = card.getBoundingClientRect();
      panel.style.setProperty('--tl-arrow-x', (c.left + c.width / 2 - g.left) + 'px');
    }

    function markActive(card) {
      if (activeCard) {
        activeCard.classList.remove('is-open');
        activeCard.querySelector('.tl-card__toggle').setAttribute('aria-expanded', 'false');
      }
      activeCard = card;
      if (card) {
        card.classList.add('is-open');
        card.querySelector('.tl-card__toggle').setAttribute('aria-expanded', 'true');
      }
    }

    function closePanel(animated) {
      if (!panel) return;
      var p = panel;
      panel = box = anchor = null;
      markActive(null);
      if (animated && !reduce) {
        p.classList.remove('open');
        setTimeout(function () { p.remove(); }, 480);
      } else {
        p.remove();
      }
    }

    function openPanel(card) {
      var end = rowEnd(card);

      // same row: swap the content, no re-animation
      if (panel && anchor === end) {
        markActive(card);
        fill(card);
        return;
      }
      closePanel(false);

      panel = document.createElement('div');
      panel.className = 'tl-panel';
      panel.setAttribute('role', 'region');
      panel.innerHTML = '<div class="tl-panel__clip"><div class="tl-panel__box"></div></div>';
      box = panel.querySelector('.tl-panel__box');
      anchor = end;
      end.after(panel);
      markActive(card);
      fill(card);

      panel.getBoundingClientRect(); // force reflow so the slide animates
      panel.classList.add('open');
      if (!reduce) {
        setTimeout(function () {
          if (panel) panel.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
        }, 480);
      }
    }

    cards.forEach(function (card) {
      card.querySelector('.tl-card__toggle').addEventListener('click', function () {
        if (activeCard === card) closePanel(true);
        else openPanel(card);
      });
    });

    grid.addEventListener('click', function (e) {
      if (e.target.closest('.tl-panel__close')) {
        var btn = activeCard && activeCard.querySelector('.tl-card__toggle');
        closePanel(true);
        if (btn) btn.focus();
      }
    });

    root.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && panel) {
        var btn = activeCard.querySelector('.tl-card__toggle');
        closePanel(true);
        btn.focus();
      }
    });

    // column count changes on resize, so close to avoid a wrong position
    var lastW = window.innerWidth;
    window.addEventListener('resize', function () {
      if (window.innerWidth !== lastW) { lastW = window.innerWidth; closePanel(false); }
    });
  }

  function boot() {
    Array.prototype.forEach.call(document.querySelectorAll('[data-tl]'), init);
  }

  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', boot);
  else boot();
})();
