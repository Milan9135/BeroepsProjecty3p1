// Function to create and insert the navbar into the page
function loadNavbar() {
    fetch('objects/navbar.php')
        .then(response => response.text())
        .then(data => {
            document.getElementById('navbar').innerHTML = data;
        })
        .catch(error => console.error('Error loading navbar:', error));
}

// Run this function when the page is fully loaded
window.onload = loadNavbar;

// Use where you want navbar to be inserted
{/* <div id="navbar">
    <nav class="navbar">
        <a id="placeholder" href="">a</a>
        <style>
            #placeholder {
                opacity: 0;
    }
        </style>
    </nav>
</div>  */}

// insert in head
// <script src="navbar.js"></script>