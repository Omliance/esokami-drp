/**
 * @file
 * Esokami "Terre & Lumiere" — custom behaviors.
 *
 * - Scroll reveal (IntersectionObserver)
 * - FAQ accordion
 */
(function ($, Drupal) {

  'use strict';

  /**
   * Scroll reveal for .ek-reveal and .ek-reveal-group children.
   */
  Drupal.behaviors.esokamiReveal = {
    attach: function (context) {
      if (!('IntersectionObserver' in window)) {
        // Fallback: show everything immediately
        var els = context.querySelectorAll('.ek-reveal, .ek-reveal-group > *');
        for (var i = 0; i < els.length; i++) {
          els[i].classList.add('is-visible');
        }
        return;
      }

      var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-visible');
            observer.unobserve(entry.target);
          }
        });
      }, { threshold: 0.12 });

      // Observe .ek-reveal elements
      var reveals = context.querySelectorAll('.ek-reveal:not(.is-visible)');
      reveals.forEach(function (el) {
        observer.observe(el);
      });

      // Observe .ek-reveal-group (add is-visible to the group itself)
      var groups = context.querySelectorAll('.ek-reveal-group:not(.is-visible)');
      groups.forEach(function (group) {
        observer.observe(group);
      });
    }
  };

  /**
   * FAQ accordion — toggle .is-open on .ek-faq__item.
   */
  Drupal.behaviors.esokamiAccordion = {
    attach: function (context) {
      var items = context.querySelectorAll('.ek-faq__item:not(.ek-faq-processed)');
      items.forEach(function (item) {
        item.classList.add('ek-faq-processed');
        var question = item.querySelector('.ek-faq__q');
        if (!question) return;

        function toggle() {
          var isOpen = item.classList.contains('is-open');
          // Close all siblings
          var siblings = item.parentElement.querySelectorAll('.ek-faq__item.is-open');
          siblings.forEach(function (sib) {
            sib.classList.remove('is-open');
            var q = sib.querySelector('.ek-faq__q');
            if (q) q.setAttribute('aria-expanded', 'false');
          });
          // Toggle current
          if (!isOpen) {
            item.classList.add('is-open');
            question.setAttribute('aria-expanded', 'true');
          }
        }

        question.addEventListener('click', toggle);
      });
    }
  };

})(jQuery, Drupal);
