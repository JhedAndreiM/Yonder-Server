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
  console.log(searchInput);
  fetchFilteredProducts(searchInput);
}

function fetchFilteredProducts(searchInput) {
  let url = baseUrl + '?';

  if (searchInput) url += `searching=${encodeURIComponent(searchInput)}&`;

  const stock = document.getElementById('stockFilter').value;
  if (stock) url += `stock=${encodeURIComponent(stock)}&`;

  const sort = document.getElementById('sortFilter').value;
  if (sort) url += `sort=${encodeURIComponent(sort)}&`;

  fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }})
    .then(r => r.text())
    .then(html => { document.querySelector('.product-grid').innerHTML = html; })
    .catch(err => console.error('Error:', err));
}
