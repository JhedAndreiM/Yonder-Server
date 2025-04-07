let activeFilters = [];
let page=2;

document.querySelectorAll(".filter-btn").forEach(button => {
    button.addEventListener("click", () => {
        button.classList.toggle("active"); // Highlight selected filters
        updateFilters();
    });
});

function updateFilters() {
    activeFilters = [];
    page = 2
    document.querySelectorAll(".filter-btn.active").forEach(activeBtn => {
        activeFilters.push(activeBtn.dataset.filter);
    });
    fetchFilteredProducts(activeFilters);
    
}

function fetchFilteredProducts(filters) {
    let url='?page=${page}';
    if(filters.length>0){
        url += '&filters='+JSON.stringify(filters);
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
    
    let isLoading = false; // To track if a request is in progress
    let scrollTimeout = null; // To store the timeout ID for debouncing

    if (!container || !productContainer) {
        console.error('Scroll container or product container not found.');
        return;
    }

    container.addEventListener('scroll', () => {
        
        if (container.scrollTop + container.clientHeight >= container.scrollHeight - 5 && !isLoading) {
            
            isLoading = true;

            
            if (scrollTimeout) {
                clearTimeout(scrollTimeout);
            }

           
            scrollTimeout = setTimeout(async () => {
                
                try {
                    const filterParam = activeFilters.length > 0 ? `&filters=${encodeURIComponent(JSON.stringify(activeFilters))}` : '';
                    const response = await fetch(`?page=${page}${filterParam}`, {
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
                        page++; // ✅ Only increment if cards were found
                    }
                } catch (error) {
                    console.error('Error loading more products:', error);
                    isLoading = false; 
                }
            }, 500); 
        }
    });
});