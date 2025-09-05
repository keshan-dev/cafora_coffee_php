
const signUpButton = document.getElementById('signUpButton');
const signInButton = document.getElementById('signInButton');
const signUpForm  = document.getElementById('signup');
const signInForm  = document.getElementById('signIn');

if (signUpButton){
  signUpButton.addEventListener('click', () => {
    signInForm.style.display = 'none';
    signUpForm.style.display = 'block';
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
}
if (signInButton){
  signInButton.addEventListener('click', () => {
    signUpForm.style.display = 'none';
    signInForm.style.display = 'block';
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
}
