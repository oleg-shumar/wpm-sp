window.onload = function(){
    document.querySelector('[data-slug="plugin-name-here"] a').addEventListener('click', function(event) {
        event.preventDefault();
        const urlRedirect = document.querySelector('[data-slug="plugin-name-here"] a').getAttribute('href');
        if (confirm('Are you sure you want to save this thing into the database?')) {
            window.location.href = urlRedirect;
        } else {
            console.log('Ohhh, you are so sweet!');
        }
    })
}