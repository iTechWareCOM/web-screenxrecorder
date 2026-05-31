const openDialogButton = document.querySelector('.floatButton.report');
const contactDialog = document.getElementById('contactDialog');
const closeDialog = document.querySelector('#contactDialog .closeButton');
const captchaQuestion = document.getElementById('captchaQuestion');
const captchaAnswerInput = document.getElementById('captchaAnswer');

let captchaAnswer;

function generateCaptcha() {
    const num1 = Math.floor(Math.random() * 10) + 1;
    const num2 = Math.floor(Math.random() * 10) + 1;
    captchaAnswer = num1 + num2;
    captchaQuestion.textContent = `${num1} + ${num2} = ?`;
}

openDialogButton.addEventListener('click', () => {
    generateCaptcha();
    contactDialog.showModal();
});

closeDialog.addEventListener('click', (e) => {
    e.preventDefault();
    contactDialog.close();
});

document.getElementById('contactForm').addEventListener('submit', (e) => {
    e.preventDefault();

    if (parseInt(captchaAnswerInput.value) === captchaAnswer) {
        const formData = new FormData(e.target);
        fetch('/send-email', {
            method: 'POST',
            body: formData,
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al enviar el correo');
            }
            return response.json();
        })
        .then(data => {
            gtag('event', 'SX_ContactFormSubmit', {});
            document.getElementById("contactDialogSuccess").style.display = 'block';
            captchaAnswerInput.value = '';
            setTimeout(() => {
                document.getElementById("contactDialogSuccess").style.display = 'none'; 
                contactDialog.close();              
            }, 2000);
        })
        .catch(error => {
            console.error(error);
            document.getElementById("contactDialogError").style.display = 'block';
            captchaAnswerInput.value = '';      
            generateCaptcha();   
            setTimeout(() => {
                document.getElementById("contactDialogError").style.display = 'none';
            }, 3000);
        });
    } else {
        document.getElementById("contactDialogError").style.display = 'block';
        captchaAnswerInput.value = '';        
        setTimeout(() => {
            document.getElementById("contactDialogError").style.display = 'none';
            generateCaptcha();  
        }, 3000);
    }
});
