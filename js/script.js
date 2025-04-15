console.log("script.js loaded!");
//JavaScript toggle menu
function toggleMenu() {
    const menu = document.getElementById('mobile-menu')
    menu.classList.toggle('hidden')
}

// Get the post button and form elements
const togglePost = document.getElementById('togglePost');
const postForm = document.getElementById('postForm');

// Add event listener to toggle visibility (Form Post)
togglePost.addEventListener('click', () => {
    postForm.classList.toggle('hidden');
});




