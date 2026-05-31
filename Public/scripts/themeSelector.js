const darkThemeMq = window.matchMedia("(prefers-color-scheme: dark)");
let storedTheme = localStorage.getItem('theme');
let theme_option = document.querySelectorAll('.theme_option');

/**
 * Aplica el tema claro u oscuro según la preferencia del sistema operativo.
 * Solo actúa si el usuario tiene seleccionado el modo automático; si tiene
 * uno fijado manualmente, esta función no hace nada.
 *
 * @param {MediaQueryList|MediaQueryListEvent} e
 */
function automaticTheme(e) {
    if (localStorage.getItem('theme') === 'system') {  
        if (e.matches) {
            document.documentElement.classList.add('dark');
            document.documentElement.classList.remove('light');
        } else {
            document.documentElement.classList.add('light');
            document.documentElement.classList.remove('dark');
        }
    } 
}

/**
 * Inicializa el selector de tema: lee localStorage, marca la opción activa
 * y registra los listeners de clic. El menú usa transiciones CSS activadas
 * por clases con un setTimeout corto para que el navegador pinte antes de animar.
 */
function initializeThemeSelector() {
    automaticTheme(darkThemeMq);
    darkThemeMq.addEventListener('change', () => automaticTheme(darkThemeMq));

    if (!storedTheme) {
        localStorage.setItem('theme', 'system');
        storedTheme = 'system';
        automaticTheme(darkThemeMq);
    }

    theme_option.forEach((selector) => {
        selector.style.pointerEvents = 'none';
        if (selector.dataset.theme === storedTheme) {
            selector.style.pointerEvents = 'auto';
            selector.classList.add('active');
            if (storedTheme !== 'system') {
                document.documentElement.classList.add(storedTheme);
            }
        }
        selector.addEventListener('click', function() {
            if (!selector.classList.contains('active')) {
                theme_option.forEach((option) => {
                    option.classList.remove('active');
                });
                selector.classList.add('active');
                localStorage.setItem('theme', selector.dataset.theme);

                if (selector.dataset.theme !== 'system') {
                    document.documentElement.classList.remove('light', 'dark');
                    document.documentElement.classList.add(selector.dataset.theme);
                } else {
                    automaticTheme(darkThemeMq);
                }
            }

            if (!selector.classList.contains('show')) {
                menu_theme_show();
                return;
            }
            menu_theme_hidden();
        });
    });

    function menu_theme_show() {
        theme_option.forEach((selector) => {
            selector.classList.add('block'); 
            setTimeout(() => {
                selector.classList.add('move_selector');
                selector.style.pointerEvents = 'none';
                setTimeout(() => {
                    selector.classList.add('show'); 
                    selector.style.pointerEvents = 'auto';
                }, 400);        
            }, 10); 
        });      
    }

    function menu_theme_hidden() {
        theme_option.forEach((selector) => {
            selector.classList.add('hidden');
            selector.style.pointerEvents = 'none';
            setTimeout(() => {
                if (selector.classList.contains('active')) {
                    selector.style.pointerEvents = 'auto';
                }
                selector.classList.remove('show', 'move_selector', 'hidden', 'block');
            }, 400);
        });
    }

    document.addEventListener('click', function(event) {
        let clickedOutside = true;
        let anyElementHasShow = false;
        theme_option.forEach((selector) => {
            if (selector.classList.contains('show')) {
                anyElementHasShow = true;
                if (selector.contains(event.target)) {
                    clickedOutside = false;
                }
            }
        });
        if (!anyElementHasShow) {
            return;
        }
        if (clickedOutside) {
            menu_theme_hidden();
        }
    });
}

initializeThemeSelector();