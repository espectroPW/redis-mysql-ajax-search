$(document).ready(function() {
    let searchTimeout;
    const searchInput = $('#search-input');
    const resultsContainer = $('#results');
    
    // Funkcja do wyświetlania ładowania
    function showLoading() {
        resultsContainer.html('<div class="loading">Wyszukiwanie...</div>');
    }
    
    // Funkcja do wyświetlania braku wyników
    function showNoResults() {
        resultsContainer.html('<div class="no-results">Brak wyników wyszukiwania</div>');
    }
    
    // Funkcja do wyświetlania wyników
    function displayResults(results) {
        if (results.length === 0) {
            showNoResults();
            return;
        }
        
        let html = '';
        results.forEach(function(item) {
            html += `
                <div class="result-item">
                    <h3>${item.title}</h3>
                    <p>${item.content}</p>
                </div>
            `;
        });
        
        resultsContainer.html(html);
    }
    
    // Funkcja do wykonywania wyszukiwania
    function performSearch(query) {
        if (!query.trim()) {
            resultsContainer.empty();
            return;
        }
        
        showLoading();
        
        $.ajax({
            url: 'search.php',
            method: 'GET',
            data: { q: query },
            dataType: 'json',
            success: function(response) {
                if (response.error) {
                    resultsContainer.html(`<div class="no-results">Błąd: ${response.error}</div>`);
                } else {
                    displayResults(response.results);
                }
            },
            error: function(xhr, status, error) {
                resultsContainer.html(`<div class="no-results">Wystąpił błąd: ${error}</div>`);
            }
        });
    }
    
    // Nasłuchiwanie zdarzenia wprowadzania tekstu
    searchInput.on('input', function() {
        const query = $(this).val().trim();
        
        // Anuluj poprzednie opóźnione wyszukiwanie
        clearTimeout(searchTimeout);
        
        // Ustaw nowe opóźnione wyszukiwanie (300ms)
        searchTimeout = setTimeout(function() {
            performSearch(query);
        }, 300);
    });
    
    // Nasłuchiwanie zdarzenia naciśnięcia klawisza Enter
    searchInput.on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            clearTimeout(searchTimeout);
            performSearch($(this).val());
        }
    });
});