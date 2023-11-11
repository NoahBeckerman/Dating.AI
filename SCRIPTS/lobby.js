document.addEventListener('DOMContentLoaded', function() {
    // Initialize variables
    const searchBar = document.getElementById('searchBar');
    const carouselContainer = document.getElementById('carouselContainer');
    const gridContainer = document.getElementById('gridContainer');
    const personalities = document.querySelectorAll('.s');
  
    // Search bar functionality
    searchBar.addEventListener('input', function() {
      const query = searchBar.value.toLowerCase();
      personalities.forEach(function(personality) {
        const name = personality.querySelector('.card-title').textContent.toLowerCase();
        if (name.includes(query)) {
          personality.style.display = 'block';
        } else {
          personality.style.display = 'none';
        }
      });
    });
  
    // Toggle between grid and carousel views
    // This part can be customized further based on your specific requirements
  });

  document.getElementById('listViewButton').addEventListener('click', function() {
    document.querySelector('.character-container').classList.add('list-view');
    document.querySelector('.character-container').classList.remove('card-view');
});

document.getElementById('cardViewButton').addEventListener('click', function() {
    document.querySelector('.character-container').classList.add('card-view');
    document.querySelector('.character-container').classList.remove('list-view');
});
