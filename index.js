const container = document.querySelector('.container');
const loginBox = document.querySelector('.form-box.Login');
const registerBox = document.querySelector('.form-box.Register');
const registerLink = document.querySelector('.signUpLink');
const loginLink = document.querySelector('.signInLink');

// Show registration form
registerLink.addEventListener('click', () => {
    loginBox.style.display = 'none'; // Hide login form
    registerBox.style.display = 'block'; // Show registration form
});

// Show login form
loginLink.addEventListener('click', () => {
    registerBox.style.display = 'none'; // Hide registration form
    loginBox.style.display = 'block'; // Show login form
});

// Initial setup: show login form by default
registerBox.style.display = 'none'; // Hide registration form initially
