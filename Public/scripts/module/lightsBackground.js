const lightsContainer = document.querySelector('.lightsContent');
const bodyHeight = document.body.clientHeight;
const numberOfLights = Math.floor(bodyHeight / 300) + 2;
const startPosition = Math.random() < 0.5 ? 0 : 1;
let lightPrints = 0;

/**
 * Rellena el contenedor de luces con elipses SVG decorativas en función
 * de la altura de la página, alternando la posición izquierda/derecha cada ~300px.
 * Un ResizeObserver añade más luces si la página crece dinámicamente.
 */
function initializeLights() {
    while (lightPrints < numberOfLights) {
        generateLight(); 
    }
    function generateLight() {
        const nextY = lightPrints * 300;
        const randomOffset = (Math.random() * 200) - 100;
        const randomY = nextY + randomOffset;

        let leftPosition;
        if ((lightPrints + startPosition) % 2 === 0) {
            const randomLeft = Math.random() * 35;
            leftPosition = `${randomLeft}%`;
        } else {
            const randomLeft = Math.random() * 35 + 65;
            leftPosition = `${randomLeft}%`;
        }

        const light = Math.random() < 0.5
            ? `<svg class="back_light light_1" style="top:${randomY}px; left:${leftPosition};" viewBox="0 0 400 300" fill="none" xmlns="http://www.w3.org/2000/svg"><ellipse cx="200" cy="150" rx="100" ry="50" fill="currentColor"/></svg>`
            : `<svg class="back_light light_2" style="top:${randomY}px; left:${leftPosition};" viewBox="0 0 400 300" fill="none" xmlns="http://www.w3.org/2000/svg"><ellipse cx="200" cy="150" rx="100" ry="50" fill="currentColor"/></svg>`;

        lightsContainer.insertAdjacentHTML('beforeend', light);
        lightPrints++;
    }
    const resizeObserver = new ResizeObserver(() => {
        const newNumberOfLights = Math.floor(document.body.clientHeight / 300) + 1;

        if (newNumberOfLights > lightPrints) {
            for (let i = lightPrints; i < newNumberOfLights; i++) {
                generateLight();
            }
        }
    });
    resizeObserver.observe(document.body);    
}

export default initializeLights;