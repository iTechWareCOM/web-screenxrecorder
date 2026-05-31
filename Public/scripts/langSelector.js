const langSelector = document.querySelector('.lang_selector');
const langDialog = document.getElementById('langDialog');
const langDialogClose = document.querySelector('#langDialog .closeButton');

langSelector.addEventListener('click', () => {
    langDialog.showModal();
    langSelector.setAttribute('aria-expanded', 'true');
});

langDialog.addEventListener('close', () => {
    langSelector.setAttribute('aria-expanded', 'false');
});

langDialogClose.addEventListener('click', (e) => {
    e.preventDefault();
    langDialog.close();
});