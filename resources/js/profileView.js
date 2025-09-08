let debounceTimer;
let userId = document.getElementById('searchInput').dataset.userId;
let baseUrl = `/profile/id/${userId}`;

document.getElementById('searchInput').addEventListener('input', debounceSearch);
document.getElementById('stockFilter').addEventListener('change', triggerFilter);
document.getElementById('sortFilter').addEventListener('change', triggerFilter);

function debounceSearch() {
  clearTimeout(debounceTimer);
  const searchInput = this.value.trim();

  debounceTimer = setTimeout(() => {
    fetchFilteredProducts(searchInput);
  }, 300);
}

function triggerFilter() {
  const searchInput = document.getElementById('searchInput').value.trim();
  fetchFilteredProducts(searchInput);
}

function fetchFilteredProducts(searchInput) {
  let url = baseUrl + '?';

  // append search
  if (searchInput) {
    url += `searching=${encodeURIComponent(searchInput)}&`;
  }

  // append stock filter
  const stock = document.getElementById('stockFilter').value;
  if (stock) {
    url += `stock=${stock}&`;
  }

  // append sort filter
  const sort = document.getElementById('sortFilter').value;
  if (sort) {
    url += `sort=${sort}&`;
  }

  fetch(url, {
    headers: {
      'X-Requested-With': 'XMLHttpRequest'
    }
  })
  .then(response => response.text())
  .then(data => {
    document.querySelector('.product-grid').innerHTML = data;
  })
  .catch(error => console.error('Error:', error));
}
