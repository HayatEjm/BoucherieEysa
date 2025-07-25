/* ========================================================================
   JS DÉDIÉ À LA PAGE CLICK & COLLECT (click_collect/index.html.twig)
   ======================================================================== */

document.addEventListener('DOMContentLoaded', function () {
  // Smooth scroll pour les ancres valides
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      const targetId = this.getAttribute('href')

      // Ignorer si href="#" seul
      if (!targetId || targetId === '#') return

      const target = document.querySelector(targetId)

      if (target) {
        e.preventDefault()
        target.scrollIntoView({
          behavior: 'smooth',
          block: 'start'
        })
      }
    })
  })
})

    // Animation des cartes au scroll (optionnel, pour l'UX)
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    document.querySelectorAll('.advantage-card, .pickup-item, .schedule-item').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });

// ========================================================================
// Fin du JS dédié à la page Click & Collect
// ========================================================================
