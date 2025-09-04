document.addEventListener("DOMContentLoaded", function() {

    // =======================================
    // GESTION SIMPLE ET FINALE DU HEADER
    // =======================================
    const topBar = document.getElementById('topBar');
    let hasScrolled = false; // "Mémo" pour le scroll

    function handleScrollEffect() {
        if (!topBar || hasScrolled) {
            return;
        }

        // Dès que l'utilisateur scrolle un peu
        if (window.scrollY > 0) {
            // On fait disparaître la top-bar pour de bon
            topBar.style.display = 'none';
            // On verrouille pour que ça n'arrive qu'une fois
            hasScrolled = true;
        }
    }

    // On attache l'écouteur de scroll
    window.addEventListener('scroll', handleScrollEffect);
    
    // =======================================
    // LOADER DE LA PAGE
    // =======================================
    const loader = document.getElementById('loader');
    if (loader) {
        setTimeout(() => {
            loader.style.display = 'none';
        }, 200);
    }

    // =======================================
    // BOUTON DE RETOUR EN HAUT
    // =======================================

        const scrollToTopBtn = document.getElementById("scrollToTopBtn");

        if (scrollToTopBtn) {
            // Affiche ou cache le bouton en fonction du scroll
            window.addEventListener("scroll", function() {
                if (window.scrollY > 300) {
                    scrollToTopBtn.classList.add("is-visible");
                } else {
                    scrollToTopBtn.classList.remove("is-visible");
                }
            });

            // Gère le clic sur le bouton
            scrollToTopBtn.addEventListener("click", function(e) {
                e.preventDefault(); // Empêche le comportement par défaut du lien (#)
                
                window.scrollTo({
                    top: 0,
                    behavior: "smooth"
                });
            });
        }

    // =======================================
    // ANIMATION DU COMPTEUR DE STATISTIQUES
    // =======================================
    function animateCounter(element) {
        const target = +element.dataset.target;
        const duration = 2000;
        const stepTime = 20;
        const totalSteps = duration / stepTime;
        const increment = target / totalSteps;
        let current = 0;

        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            element.innerText = Math.floor(current);
        }, stepTime);
    }

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counterElement = entry.target;
                animateCounter(counterElement);
                observer.unobserve(counterElement);
            }
        });
    }, {
        threshold: 0.5
    });

    const counters = document.querySelectorAll('.counter');
    counters.forEach(counter => {
        observer.observe(counter);
    });
});


//Fonction pour le boutton en dessous du carousel
// =======================================
// GESTION DE LA SECTION DÉPLIANTE (OPTIMISÉ)
// =======================================
const toggleBtn = document.getElementById('content-toggle-btn');
const contentSection = document.getElementById('content');

if (toggleBtn && contentSection) {
    const toggleIcon = toggleBtn.querySelector('i');

    toggleBtn.addEventListener('click', () => {
        // Vérifie si le contenu est actuellement visible ou non
        const isVisible = contentSection.classList.contains('is-visible');

        if (isVisible) {
            // --- CAS 1 : On va cacher le contenu ---
            // On met la max-height à sa hauteur actuelle avant de la passer à 0
            // pour que la transition de fermeture fonctionne bien.
            contentSection.style.maxHeight = contentSection.scrollHeight + 'px';
            
            // On attend un cycle de rendu pour que la hauteur soit appliquée
            requestAnimationFrame(() => {
                contentSection.style.maxHeight = '0px';
                contentSection.classList.remove('is-visible');
            });

        } else {
            // --- CAS 2 : On va afficher le contenu ---
            // On ajoute la classe pour que les styles (opacity, etc.) s'appliquent
            contentSection.classList.add('is-visible');
            // On applique la hauteur réelle du contenu à max-height.
            // .scrollHeight nous donne la hauteur totale du contenu, même s'il est caché.
            contentSection.style.maxHeight = contentSection.scrollHeight + 'px';
        }

        // On gère l'icône du bouton et l'attribut aria
        const isNowExpanded = contentSection.classList.contains('is-visible');
        toggleBtn.setAttribute('aria-expanded', isNowExpanded);
        if (toggleIcon) {
            toggleIcon.classList.toggle('fa-chevron-down');
            toggleIcon.classList.toggle('fa-chevron-up');
        }
    });

    // Ajout important : on gère le redimensionnement de la fenêtre
    // Si l'utilisateur tourne son téléphone, la hauteur change.
    window.addEventListener('resize', () => {
        if (contentSection.classList.contains('is-visible')) {
            // On réajuste la hauteur si le contenu est déjà ouvert
            contentSection.style.maxHeight = contentSection.scrollHeight + 'px';
        }
    });
}
/*=======================================*/

// =======================================
// FILTRES DE LA PAGE FORMATIONS
// =======================================
const filterContainer = document.querySelector('.filter-bar');
const formationItems = document.querySelectorAll('.formation-item');

if (filterContainer && formationItems.length > 0) {
    filterContainer.addEventListener('click', (e) => {
        if (e.target.classList.contains('filter-btn')) {
            if (filterContainer.querySelector('.active')) {
                filterContainer.querySelector('.active').classList.remove('active');
            }
            e.target.classList.add('active');

            const filterValue = e.target.getAttribute('data-filter');

            formationItems.forEach(item => {
                item.style.transform = 'scale(0.9)';
                item.style.opacity = '0';
                
                setTimeout(() => {
                    item.style.display = 'none';
                }, 200);

                if (item.dataset.category === filterValue || filterValue === 'all') {
                    setTimeout(() => {
                        item.style.display = 'block';
                        requestAnimationFrame(() => {
                           item.style.transform = 'scale(1)';
                           item.style.opacity = '1';
                        });
                    }, 250);
                }
            });
        }
    });
}
/*=======================================*/