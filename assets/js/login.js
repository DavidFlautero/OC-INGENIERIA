// login.js
function openLoginSlider() {
    document.getElementById("loginSlider").classList.add("open");
    showLoginForm();
}

function closeLoginSlider() {
    document.getElementById("loginSlider").classList.remove("open");
}

function showLoginForm() {
    document.getElementById("loginForm").style.display = "block";
    document.getElementById("registerForm").style.display = "none";
    document.getElementById("forgotPasswordForm").style.display = "none";
    document.getElementById("verificationStep").style.display = "none";
}

function showRegisterForm() {
    document.getElementById("loginForm").style.display = "none";
    document.getElementById("registerForm").style.display = "block";
    document.getElementById("forgotPasswordForm").style.display = "none";
    document.getElementById("verificationStep").style.display = "none";
}

function showForgotPasswordForm() {
    document.getElementById("loginForm").style.display = "none";
    document.getElementById("registerForm").style.display = "none";
    document.getElementById("forgotPasswordForm").style.display = "block";
    document.getElementById("verificationStep").style.display = "none";
}

function loginUser(event) {
    event.preventDefault();
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    if (!email || !password) {
        alert("Por favor, completa todos los campos.");
        return;
    }

    fetch('http://localhost/onlinetienda/views/login.php', {
        method: 'POST',
        body: JSON.stringify({ email: email, password: password }),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert("Inicio de sesión exitoso.");
            closeLoginSlider();
        } else {
            alert("Correo o contraseña incorrectos.");
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Ocurrió un error al iniciar sesión.');
    });
}

function registerUser(event) {
    event.preventDefault();
    const name = document.getElementById("regName").value;
    const email = document.getElementById("regEmail").value;
    const phone = document.getElementById("regPhone").value;
    const password = document.getElementById("regPassword").value;
    const confirmPassword = document.getElementById("regConfirmPassword").value;

    if (!name || !email || !phone || !password || !confirmPassword) {
        alert("Por favor, completa todos los campos.");
        return;
    }

    if (password !== confirmPassword) {
        alert("Las contraseñas no coinciden.");
        return;
    }

    fetch('http://localhost/onlinetienda/views/verificar_correo.php', {
        method: 'POST',
        body: JSON.stringify({ email: email }),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.exists) {
            alert("El correo ya está registrado.");
        } else {
            document.getElementById("registerForm").style.display = "none";
            document.getElementById("verificationStep").style.display = "block";
            sendVerificationCode(email, phone);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Ocurrió un error al verificar el correo.');
    });
}

function sendVerificationCode(email, phone) {
    // Función para enviar el código de verificación (por SMS o correo).
    console.log("Enviando código de verificación...");
}
