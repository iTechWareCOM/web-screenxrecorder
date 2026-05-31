document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.copy-email').forEach(btn => {
        btn.addEventListener('click', () => {
            const email = atob(btn.dataset.e).split('').reverse().join('');
            const original = btn.textContent;

            const done = () => {
                btn.textContent = '✓ ' + original;
                btn.classList.add('copied');
                setTimeout(() => {
                    btn.textContent = original;
                    btn.classList.remove('copied');
                }, 2000);
            };

            if (navigator.clipboard) {
                navigator.clipboard.writeText(email).then(done).catch(() => fallback(email, done));
            } else {
                fallback(email, done);
            }
        });
    });
});

function fallback(text, cb) {
    const ta = document.createElement('textarea');
    ta.value = text;
    ta.style.cssText = 'position:fixed;top:-9999px;left:-9999px;opacity:0';
    document.body.appendChild(ta);
    ta.select();
    try { document.execCommand('copy'); cb(); } catch (_) {}
    ta.remove();
}
