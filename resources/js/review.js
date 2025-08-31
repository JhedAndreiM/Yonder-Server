let debounceTimer;
document.getElementById('search-input').addEventListener('input', function(){
    clearTimeout(debounceTimer);

    const searchInput = this.value.trim();

    debounceTimer = setTimeout(()=>{
        if(searchInput !== ""){
            console.log('went here');
            fetchDetails(searchInput);
        }
        else{
            fetchDetails(null);
        }
    }, 300)
});

function fetchDetails(searchInput = null){
    let url = `/Reviews?searching=${encodeURIComponent(searchInput || '')}`;

    fetch(url,{
        headers:{
            'X-Requested-With':'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(data => {
        const productContainer=document.getElementById('reviews-container');
        productContainer.innerHTML = data;
    })
    .catch(error=>{
        console.error('Error fetching filtered products:', error);
    })
}