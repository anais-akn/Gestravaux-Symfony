document.addEventListener('DOMContentLoaded', () => {
    const forms = document.querySelectorAll('.form-valider-devis');
    
    forms.forEach(form => {
        form.addEventListener('submit', (e) => {
            const confirmed = confirm("En choisissant ce devis, tous les autres devis de ce chantier seront marqués comme 'Annulés'. Continuer ?");
            if (!confirmed) {
                e.preventDefault();
            }
        });
    });
});