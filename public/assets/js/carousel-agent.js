document.addEventListener('DOMContentLoaded', () => {
    const carouselElement = document.getElementById('indicators-carousel');
    const agentSelect = document.getElementById('votes_form_agent');

    const slideElements = carouselElement.querySelectorAll('[data-carousel-item]');
    const indicatorElements = carouselElement.querySelectorAll('[data-carousel-slide-to]');

    const items = Array.from(slideElements).map((el, index) => ({
        position: index,
        el: el
    }));

    const indicators = Array.from(indicatorElements).map((el, index) => ({
        position: index,
        el: el
    }));

    function updateSelectedAgent(position) {
        const activeSlide = items[position]?.el;
        const json = activeSlide?.dataset.agent;

        try {
            const data = JSON.parse(json);
            if (data?.id && agentSelect) {
                agentSelect.value = data.id;
                agentSelect.dispatchEvent(new Event('change'));
            }
        } catch (e) {
            console.warn('Erreur JSON dans data-agent :', json);
        }
    }

    const options = {
        defaultPosition: 0,
        interval: 5000,
        indicators: {
            activeClasses: 'bg-white dark:bg-gray-800',
            inactiveClasses: 'bg-white/50 dark:bg-gray-800/50 hover:bg-white dark:hover:bg-gray-800',
            items: indicators,
        },
        onChange: (instance) => {
            const pos = instance.getActiveItem().position;
            updateSelectedAgent(pos);
        },
    };

    const instanceOptions = {
        id: 'indicators-carousel',
        override: true,
    };

    // ✅ Création de l'instance
    const carousel = new Carousel(carouselElement, items, options, instanceOptions);

    // ✅ Liaison manuelle des boutons Prev / Next
    carouselElement.querySelector('[data-carousel-prev]')?.addEventListener('click', () => {
        carousel.prev();
    });

    carouselElement.querySelector('[data-carousel-next]')?.addEventListener('click', () => {
        carousel.next();
    });

    // Initialisation
    updateSelectedAgent(options.defaultPosition);
});