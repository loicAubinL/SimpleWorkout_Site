document.querySelectorAll('.champ-fichier input[type="file"]').forEach((input) => {
    const nom = input.parentElement.querySelector('.nom-fichier');
 
    input.addEventListener('change', () => {
        nom.textContent = input.files.length ? input.files[0].name : nom.dataset.empty;
    });
});
 