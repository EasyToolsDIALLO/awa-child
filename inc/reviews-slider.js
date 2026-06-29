(function () {
    'use strict';

    function initSlider(slider) {
        var track = slider.querySelector('.awa-reviews__track');
        var dotsContainer = slider.querySelector('.awa-reviews__dots');
        var cards = slider.querySelectorAll('.awa-review-card');

        if (!track || cards.length <= 1) {
            return;
        }

        var total = cards.length;
        var autoplay = slider.getAttribute('data-autoplay') === 'true';
        var interval = null;
        var autoplayDelay = parseInt(slider.getAttribute('data-delay'), 10) || 4000;
        var currentPage = 0;
        var dots = [];

        function getSlideWidth() {
            var card = cards[0];
            var style = window.getComputedStyle(track);
            var gap = parseFloat(style.gap) || 0;
            return card.offsetWidth + gap;
        }

        function getVisibleCount() {
            var slideWidth = getSlideWidth();
            if (slideWidth <= 0) {
                return 1;
            }
            return Math.max(1, Math.floor(slider.offsetWidth / slideWidth));
        }

        function getPageCount() {
            var visible = getVisibleCount();
            return Math.max(1, Math.ceil(total / visible));
        }

        function getPageIndex(cardIndex) {
            var visible = getVisibleCount();
            return Math.floor(cardIndex / visible);
        }

        function getMaxIndex() {
            return Math.max(0, total - getVisibleCount());
        }

        function buildDots() {
            if (!dotsContainer) {
                return;
            }
            dotsContainer.innerHTML = '';
            dots = [];
            var pageCount = getPageCount();
            for (var i = 0; i < pageCount; i++) {
                var dot = document.createElement('button');
                dot.className = 'awa-reviews__dot';
                dot.type = 'button';
                dot.setAttribute('data-page', i);
                dot.setAttribute('aria-label', 'Aller à la page ' + (i + 1));
                dot.addEventListener('click', function () {
                    var page = parseInt(this.getAttribute('data-page'), 10);
                    goToPage(page);
                    resetAutoplay();
                });
                dotsContainer.appendChild(dot);
                dots.push(dot);
            }
        }

        function updateDots() {
            dots.forEach(function (dot) {
                var page = parseInt(dot.getAttribute('data-page'), 10);
                dot.classList.toggle('is-active', page === currentPage);
            });
        }

        function goToPage(page) {
            var pageCount = getPageCount();
            currentPage = Math.max(0, Math.min(page, pageCount - 1));
            var visible = getVisibleCount();
            var cardIndex = Math.min(currentPage * visible, getMaxIndex());
            var slideWidth = getSlideWidth();
            track.style.transform = 'translateX(-' + (cardIndex * slideWidth) + 'px)';
            updateDots();
        }

        function next() {
            var pageCount = getPageCount();
            if (currentPage >= pageCount - 1) {
                currentPage = 0;
            } else {
                currentPage++;
            }
            goToPage(currentPage);
        }

        function resetAutoplay() {
            if (!autoplay || !interval) {
                return;
            }
            clearInterval(interval);
            interval = setInterval(next, autoplayDelay);
        }

        function refresh() {
            buildDots();
            goToPage(currentPage);
        }

        buildDots();
        goToPage(0);

        window.addEventListener('resize', function () {
            refresh();
        });

        if (autoplay) {
            interval = setInterval(next, autoplayDelay);

            slider.addEventListener('mouseenter', function () {
                clearInterval(interval);
            });

            slider.addEventListener('mouseleave', function () {
                interval = setInterval(next, autoplayDelay);
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.awa-reviews__slider').forEach(initSlider);
    });
})();
