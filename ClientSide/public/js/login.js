document.addEventListener('DOMContentLoaded', function () {
    var loginForm = document.getElementById('login-form');
    loginForm.addEventListener('submit', function(event) {
        event.preventDefault();

        var formData = {
            username: document.getElementById('username').value,
            password: document.getElementById('password').value,
            userType: document.getElementById('user-type').value
        };

        fetch('/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Handle login success
                window.location.href = data.redirectUrl;
            } else {
                // Handle login failure
                alert('Login failed: ' + data.message);
            }
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    });
});
