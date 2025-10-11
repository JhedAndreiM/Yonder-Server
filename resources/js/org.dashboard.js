document.addEventListener("DOMContentLoaded", () => {
    initializeDeleteButtons();
    initializeSortFilter();
});

function initializeDeleteButtons() {
    document.querySelectorAll(".btn-delete").forEach(button => {
        button.addEventListener("click", handleDeleteProduct);
    });
}

async function handleDeleteProduct(e) {
    const productId = e.target.dataset.id;

    if(!confirm("Are you sure you want to delete this product?")){
        return;
    }

    try {
        const response = await fetch(`/products/${productId}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                "Accept": "application/json"
            }
        });
        if(response.ok){
            e.target.closest(".card").remove();
            alert("Product deleted successfully.");
        } else {
            const data = await response.json();
            alert(data.message || "Failed to delete product.");
        }
    }
    catch (error) {
        console.error(error);
        alert("Something went wrong.");
    }
}

function initializeSortFilter() {
    const selectElement = document.getElementById('sort-by');
    if (!selectElement) return;

    selectElement.addEventListener("change", function() {
        selectElement.disabled = true;
        fetchSortedProducts(selectElement.value);
    });
}
    function fetchSortedProducts(selectedValue) {
        let url = '/organization/dashboard';
        if (selectedValue) {
            url += `?sort=${encodeURIComponent(selectedValue)}`;
        }

        fetch(url, {
            headers: { 
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        })
        .then(response => response.text())
        .then(data => {
            const productContainer = document.getElementById('card-container');
            productContainer.innerHTML = data;
            // Re-initialize delete buttons after content update
            initializeDeleteButtons();
        })
        .catch(error => console.error('Error fetching filtered products:', error))
        .finally(() => {
            // Re-enable the select element
            const selectElement = document.getElementById('sort-by');
            if (selectElement) {
                selectElement.disabled = false;
            }
        });
    }