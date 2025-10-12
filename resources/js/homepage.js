let activeFilters = [];
let selectedValue = [];
let searchInput = [];
let activePrice = { min: null, max: null };
let page=2;
let minPrice=0;
let maxPrice=0;
let debounceTimer;
let topFilter;

// button filter to taas ( featured, student org, marketplace )



// search filter
document.getElementById('searchInput').addEventListener('input', function() {
    clearTimeout(debounceTimer);

    const searchInput = this.value.trim();

    debounceTimer = setTimeout(() => {
        if (searchInput === "") {
            fetchFilteredProducts([], null, null, null, null); 
        } else {
            fetchFilteredProducts(activeFilters,activePrice.min, activePrice.max,selectedValue,searchInput);
        }
    }, 300); 
});
document.getElementById('magnifying').addEventListener('click', function() {
    event.preventDefault();
    const inputEl = document.getElementById("searchInput");
    searchInput = inputEl && inputEl.value ? inputEl.value.trim() : "";
    fetchFilteredProducts(activeFilters,activePrice.min, activePrice.max,selectedValue,searchInput);
});
//select statement filter to ( yung sort by highest etc)
var selectElement = document.getElementById("sort-by");
selectElement.addEventListener("change", function() {
    selectedValue = selectElement.value;
    page=2;
    updateSortFilter();
});
function updateSortFilter() {
    const topFilterBtn = document.querySelector(".mainFilterButtons.current");
    const topFilter = topFilterBtn ? topFilterBtn.dataset.category : null;
    fetchFilteredProducts(activeFilters,activePrice.min, activePrice.max,selectedValue,searchInput,topFilter);
}
// button filter to sa kanan <- 'yung mga colleges etc
document.querySelectorAll(".filter-btn").forEach(button => {
    button.addEventListener("click", () => {
        button.classList.toggle("active"); 
        document.getElementById("sort-by").value = "";
        updateFilters();
    });
});
// price filter
document.querySelectorAll(".input-min").forEach(input => {
    input.addEventListener("input", () => {
        updatePriceFilter(); 
    });
});
document.querySelectorAll(".input-max").forEach(input => {
    input.addEventListener("input", () => {
        updatePriceFilter(); 
    });
});


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

function updateFilters() {
    activeFilters = [];
    document.querySelectorAll(".filter-btn.active").forEach(activeBtn => {
        activeFilters.push(activeBtn.dataset.filter);
    });
    const topFilterBtn = document.querySelector(".mainFilterButtons.current");
    const topFilter = topFilterBtn ? topFilterBtn.dataset.category : null;
    fetchFilteredProducts(activeFilters, activePrice.min, activePrice.max, selectedValue, searchInput, topFilter);
}

// Clear all filters function
function clearAllFilters() {
    // Reset active filters array
    activeFilters = [];
    
    // Reset price filters
    activePrice = { min: null, max: null };
    
    // Reset search input
    searchInput = [];
    
    // Reset sort dropdown
    selectedValue = [];
    
    // Clear all active filter buttons
    document.querySelectorAll(".filter-btn.active").forEach(btn => {
        btn.classList.remove("active");
    });
    
    // Clear price inputs
    const minInput = document.querySelector(".input-min");
    const maxInput = document.querySelector(".input-max");
    if (minInput) minInput.value = "";
    if (maxInput) maxInput.value = "";
    
    // Clear search input
    const searchInputEl = document.getElementById("searchInput");
    if (searchInputEl) searchInputEl.value = "";
    
    // Reset sort dropdown
    const sortDropdown = document.getElementById("sort-by");
    if (sortDropdown) sortDropdown.value = "";
    
    // Fetch products with no filters
    const topFilterBtn = document.querySelector(".mainFilterButtons.current");
    const topFilter = topFilterBtn ? topFilterBtn.dataset.category : null;
    fetchFilteredProducts([], null, null, null, null, topFilter);
}

function fetchFilteredProducts(filters, minPrice, maxPrice,selectedValue,searchInput,topFilter) {
    let url='?page=${page}';
    if (filters && filters.length > 0) {
        url += `&filters=${JSON.stringify(filters)}`;
    }
    if (minPrice !== undefined && maxPrice !== undefined) {
        url += `&price[min]=${minPrice}&price[max]=${maxPrice}`;
    }

    if (selectedValue !== undefined && selectedValue !== null && selectedValue !== '') {
        url += `&sort=${encodeURIComponent(selectedValue)}`;
    }

    if (searchInput !== undefined && searchInput !== null && searchInput !== '') {
        url += `&searching=${encodeURIComponent(searchInput)}`;
    }
    if(topFilter){
        url += `&topFilter=${encodeURIComponent(topFilter)}`;
    }
    console.log(url);
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

// Add event listener for clear filters button
document.addEventListener('DOMContentLoaded', function() {
    const clearFiltersBtn = document.getElementById('clearFilters');
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', clearAllFilters);
    }
});
