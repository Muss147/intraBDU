import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');


// Import SwiperJS
import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay } from 'swiper/modules';

Swiper.use([Navigation, Pagination, Autoplay]);

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
    const swiper = new Swiper('.swiper', {
        loop: true,
            autoplay: {
            delay: 4000,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev'
        },
    });
    
    const track = document.getElementById("carousel-track");

    setInterval(() => {
        const slides = Array.from(track.children);

        // Faire tourner les éléments dans le DOM (slide[0] passe en dernier)
        const first = slides.shift();
        track.appendChild(first);

        // Mise à jour des styles après un petit délai pour la transition
        setTimeout(() => {
        slides.concat(first).forEach((slide, index) => {
            // Réinitialiser toutes les slides
            slide.classList.remove("w-40", "h-40", "border-[#2b62aa]");
            slide.classList.add("w-32", "h-32", "border-[#6ca942]");

            // Slide du milieu (index 1) est mise en valeur
            if (index === 1) {
            slide.classList.remove("w-32", "h-32", "border-[#6ca942]");
            slide.classList.add("w-40", "h-40", "border-[#2b62aa]");
            }
        });
        }, 50);
    }, 3000);
});