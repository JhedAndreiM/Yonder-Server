let activeFilters = [];
let selectedValue = [];
let searchInput = [];
let activePrice = { min: null, max: null };
let page=2;
let minPrice=0;
let maxPrice=0;

const filterBtn = document.getElementById('filter-btn');
  const dropdownWrapper = document.getElementById('sort-dropdown');

  filterBtn.addEventListener('click', () => {
    dropdownWrapper.classList.toggle('show-on-mobile');
  });

  document.addEventListener('click', (e) => {
    if (
      !dropdownWrapper.contains(e.target) &&
      !filterBtn.contains(e.target)
    ) {
      dropdownWrapper.classList.remove('show-on-mobile');
    }
  });
  
  window.addEventListener('scroll', () => {
    filterOptions.classList.add('hidden');
  });

// search filter
document.getElementById('btnSub').addEventListener('click', function() {
    event.preventDefault();
    const inputEl = document.getElementById("searchInput");
    searchInput = inputEl && inputEl.value ? inputEl.value.trim() : "";
    fetchFilteredProducts(activeFilters,activePrice.min, activePrice.max,selectedValue,searchInput);
});
document.getElementById('magnifying').addEventListener('click', function() {
    event.preventDefault();
    const inputEl = document.getElementById("searchInput");
    searchInput = inputEl && inputEl.value ? inputEl.value.trim() : "";
    fetchFilteredProducts(activeFilters,activePrice.min, activePrice.max,selectedValue,searchInput);
});
//select statement filter to
var selectElement = document.getElementById("sort-by");
selectElement.addEventListener("change", function() {
    selectedValue = selectElement.value;
    page=2;
    updateSortFilter();
});
function updateSortFilter() {
    fetchFilteredProducts(activeFilters,activePrice.min, activePrice.max,selectedValue,searchInput);
}


// side button filter
document.querySelectorAll(".filter-btn").forEach(button => {
    button.addEventListener("click", () => {
        button.classList.toggle("active"); 
        document.getElementById("sort-by").value = "";
        page=2;
        updateFilters();
    });
});

document.querySelectorAll(".input-min").forEach(input => {
    input.addEventListener("input", () => {
        updatePriceFilter(); 
        page=2;
        document.getElementById("sort-by").value = "";
    });
});
document.querySelectorAll(".input-max").forEach(input => {
    input.addEventListener("input", () => {
        updatePriceFilter(); 
        page=2;
        document.getElementById("sort-by").value = "";
    });
});


function updateFilters() {
    activeFilters = [];
    page = 2
    document.querySelectorAll(".filter-btn.active").forEach(activeBtn => {
        activeFilters.push(activeBtn.dataset.filter);
    });
    fetchFilteredProducts(activeFilters,activePrice.min, activePrice.max,selectedValue,searchInput);
}

function updatePriceFilter() {
    const minInput = document.querySelector(".input-min");
    const maxInput = document.querySelector(".input-max");

    
    if (minInput && maxInput) {
        
        activePrice.min = parseFloat(minInput.value) || null;  
        activePrice.max = parseFloat(maxInput.value) || null;  
    }
    if(activePrice.min===0||activePrice.min===null){
        activePrice.min+=1;

    }
    fetchFilteredProducts(activeFilters,activePrice.min, activePrice.max,selectedValue,searchInput);
}


// dito yung mga method pangsend sa pageController

//search filter



//side buttons filter
function fetchFilteredProducts(filters, minPrice, maxPrice,selectedValue,searchInput) {
    let url='?page=${page}';
    if (filters && filters.length > 0) {
        url += `&filters=${JSON.stringify(filters)}`;
    }
    if (minPrice !== null && maxPrice !== null) {
        url += '&price[min]='+minPrice+'&price[max]='+maxPrice;
        
    }
    if  (selectedValue!== null) {
        url += '&sort='+selectedValue;
        
    }
    if  (searchInput!== null) {
        url += '&searching='+searchInput;
        
    }
    fetch(url,{
        headers:{
            'X-Requested-With':'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(data => {
        const productContainer=document.getElementById('product-container');
        productContainer.innerHTML = data;
    })
    .catch(error=>{
        console.error('Error fetching filtered products:', error);
    })
}

//pang load to ng product pagka scroll
document.addEventListener('DOMContentLoaded', function () {
    page = 2;
    const container = document.getElementById('scroll-container');
    const productContainer = document.getElementById('product-container');
    
    let isLoading = false; 
    let scrollTimeout = null; 
    if (!container || !productContainer) {
        console.error('Scroll container or product container not found.');
        return;
    }

    container.addEventListener('scroll', () => {
        dropdownWrapper.classList.remove('show-on-mobile');
        if (container.scrollTop + container.clientHeight >= container.scrollHeight - 5 && !isLoading) {
            
            isLoading = true;

            
            if (scrollTimeout) {
                clearTimeout(scrollTimeout);
            }

           
            scrollTimeout = setTimeout(async () => {
                
                try {
                    const filterParam = activeFilters.length > 0 ? `&filters=${encodeURIComponent(JSON.stringify(activeFilters))}` : '';
                    const sortParam = selectedValue ? `&sort=${selectedValue}` : '';
                    const searchParam = searchInput ? `&searching=${searchInput}` : '';
                    const response = await fetch(`?page=${page}${filterParam}${sortParam}${searchParam}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });

                    const data = await response.text();
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(data, 'text/html');
                    const newCards = doc.querySelectorAll('.card');
                    newCards.forEach(card => productContainer.appendChild(card));

                    isLoading = false; 
                    if (newCards.length > 0) {
                        newCards.forEach(card => productContainer.appendChild(card));
                        page++; 
                    }
                } catch (error) {
                    console.error('Error loading more products:', error);
                    isLoading = false; 
                }
            }, 500); 
        }
    });
});
