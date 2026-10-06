document.addEventListener('DOMContentLoaded', () => {
    // Animation simple au survol des cartes
    const cards = document.querySelectorAll('.item-card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-5px)';
        });
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0)';
        });
    });
});