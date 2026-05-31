const btn_go_back_up = document.querySelector('.floatButton.go_back_up');

window.addEventListener('scroll', () => {
    btn_go_back_up.classList.toggle('show', window.scrollY > 150);
});

btn_go_back_up.addEventListener('click', (e) => {
    e.preventDefault();
    window.scrollTo({ top: 0, behavior: 'smooth' });
});

btn_go_back_up.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
});