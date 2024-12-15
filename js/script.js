console.log("script.js loaded!");
//JavaScript toggle menu
function toggleMenu() {
    const menu = document.getElementById('mobile-menu')
    menu.classList.toggle('hidden')
}

// Get the button and form elements
const toggleButton = document.getElementById('toggleButton');
const postForm = document.getElementById('postForm');

// Add event listener to toggle visibility
toggleButton.addEventListener('click', () => {
    postForm.classList.toggle('hidden');
});