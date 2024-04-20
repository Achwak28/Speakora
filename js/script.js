let navbar = document.querySelector('.navbar-collapse');
let searchForm = document.querySelector('.navbar-collapse form');
let profile = document.querySelector('.nav-item.dropdown');
let userBtn = document.querySelector('#user-btn');


function showAlert() {
   let alertDiv = document.createElement('div');
   alertDiv.classList.add('alert', 'alert-danger', 'fade', 'show', 'container', 'my-3','text-center');
   alertDiv.setAttribute('role', 'alert');
   alertDiv.innerHTML = 'Please login first <a href="login.php" class="alert-link">login</a>.';
   
   // Insert the alert as the first child of the main element
   document.querySelector('main').insertAdjacentElement('afterbegin', alertDiv);
   

   setTimeout(() => {
       alertDiv.classList.remove('show');
       setTimeout(() => {
           alertDiv.remove();
       }, 300);
   }, 4000); 
}


userBtn.addEventListener('click', () => {
  
    showAlert();
    
  
    profile.classList.toggle('active');
    searchForm.classList.remove('active');
    navbar.classList.remove('active');
});


document.addEventListener('DOMContentLoaded', function() {
   const form = document.querySelector('#register-form');

   form.addEventListener('submit', function(event) {
       event.preventDefault();

       const nameInput = document.getElementById('exampleInputName');
       const emailInput = document.getElementById('exampleInputEmail1');
       const passInput = document.getElementById('exampleInputPassword1');
       const cpassInput = document.getElementById('exampleInputPassword2');
       console.log(nameInput)

       // Your form validation and submission logic goes here
       // For demonstration purposes, let's assume an error occurs
     /*  const errorMessage = 'Error: Email already exists!';
       showErrorMessage(errorMessage);*/
   });
});


// Function to display error message
function showErrorMessage(message) {
   let errorDiv = document.createElement('div');
   errorDiv.classList.add('alert', 'alert-danger', 'fade', 'show', 'container', 'my-3');
   errorDiv.setAttribute('role', 'alert');
   errorDiv.textContent = message;

   // Insert the error message inside the login-form-container div
   const loginFormContainer = document.querySelector('.login-form-container');
   loginFormContainer.appendChild(errorDiv);

   // Hide the error message after 3 seconds
   setTimeout(() => {
       errorDiv.classList.remove('show');
       setTimeout(() => {
           errorDiv.remove();
       }, 300); // Transition duration
   }, 3000); // 3 seconds
}
