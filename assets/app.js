/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

// Flowbite
import 'flowbite';

// Swiper
import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';

// Exemple init Swiper
// document.addEventListener("DOMContentLoaded", () => {
//     new Swiper(".swiper", {
//         loop: true,
//         pagination: {
//             el: ".swiper-pagination",
//             clickable: true,
//         },
//         navigation: {
//             nextEl: ".swiper-button-next",
//             prevEl: ".swiper-button-prev",
//         },
//     });
// });

console.log('App.js loaded 🎉');