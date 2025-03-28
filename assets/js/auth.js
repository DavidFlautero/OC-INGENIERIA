// auth.js
$(document).ready(function () {
    // Funciones para el slider de inicio de sesión
    window.openLoginSlider = function () {
        document.getElementById("loginSlider").classList.add("open");
        showLoginForm();
    };

    window.closeLoginSlider = function () {
        document.getElementById("loginSlider").classList.remove("open");
    };

    window.showLoginForm = function () {
        document.getElementById("loginForm").style.display = "block";
        document.getElementById("registerForm").style.display = "none";
        document.getElementById("forgotPasswordForm").style.display = "none";
        document.getElementById("verificationStep").style.display = "none"; // Ocultar verificación
    };

    window.showRegisterForm = function () {
        document.getElementById("loginForm").style.display = "none";
        document.getElementById("registerForm").style.display = "block";
        document.getElementById("forgotPasswordForm").style.display = "none";
        document.getElementById("verificationStep").style.display = "none"; // Ocultar verificación
    };

    window.showForgotPasswordForm = function () {
        document.getElementById("loginForm").style.display = "none";
        document.getElementById("registerForm").style.display = "none";
        document.getElementById("forgotPasswordForm").style.display = "block";
        document.getElementById("verificationStep").style.display = "none"; // Ocultar verificación
    };

    window.loginUser = function (event) {
        event.preventDefault();
        const email = document.getElementById("email").value;
        const password = document.getElementById("password").value;

        // Validación básica
        if (!email || !password) {
            alert("Por favor, completa todos los campos.");
            return;
        }

        // Aquí puedes hacer una llamada a tu backend para autenticar al usuario
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
    };

    window.registerUser = function (event) {
        event.preventDefault();

        const name = document.getElementById("regName").value;
        const email = document.getElementById("regEmail").value;
        const phone = document.getElementById("regPhone").value;
        const password = document.getElementById("regPassword").value;
        const confirmPassword = document.getElementById("regConfirmPassword").value;
        const verificationMethod = document.querySelector('input[name="verificationMethod"]:checked').value;

        // Validación básica
        if (!name || !email || !phone || !password || !confirmPassword) {
            alert("Por favor, completa todos los campos.");
            return;
        }

        if (password !== confirmPassword) {
            alert("Las contraseñas no coinciden.");
            return;
        }

        // Verificar si el correo ya existe en la base de datos
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
                // Ocultar el formulario de registro y mostrar el paso de verificación
                document.getElementById("registerForm").style.display = "none";
                document.getElementById("verificationStep").style.display = "block";
                document.getElementById("verificationStep").style.opacity = "1";
                document.getElementById("verificationStep").style.transform = "translateY(0)";

                // Enviar el código de verificación
                sendVerificationCode(email, phone, verificationMethod);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Ocurrió un error al verificar el correo.');
        });
    };

    window.sendVerificationCode = function (email, phone, method) {
        // Aquí puedes hacer una llamada a tu backend para enviar el código
        console.log(`Código enviado por ${method} a ${method === 'email' ? email : phone}`);
    };

    window.validateVerificationCode = function () {
        const code = document.getElementById("verificationCode").value;

        if (!code) {
            alert("Por favor, ingresa el código.");
            return;
        }

        // Enviar el código de verificación al servidor
        fetch('http://localhost/onlinetienda/views/validar_codigo.php', {
            method: 'POST',
            body: JSON.stringify({ code: code }), // Envía el código como JSON
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert("Registro completado exitosamente.");
                closeLoginSlider();
                // Redirigir al perfil
                window.location.href = "perfil.html";
            } else {
                alert(data.message); // Muestra el mensaje de error del servidor
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Ocurrió un error al validar el código.');
        });
    };

    window.resendCode = function () {
        const email = document.getElementById("regEmail").value;
        const phone = document.getElementById("regPhone").value;
        const verificationMethod = document.querySelector('input[name="verificationMethod"]:checked').value;

        // Reenviar el código
        sendVerificationCode(email, phone, verificationMethod);
        alert("Código reenviado.");
    };

    window.forgotPassword = function (event) {
        event.preventDefault();
        const email = document.getElementById("forgotEmail").value;
        console.log("Recuperar contraseña para:", email);
        alert("Correo de recuperación enviado (simulado)");
        closeLoginSlider();
    };
});