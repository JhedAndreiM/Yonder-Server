let activeFilters = [];
document.querySelectorAll(".filter-btn").forEach(button => {
    button.addEventListener("click", () => {
        button.classList.toggle("active"); 
        updateFilters();
    });
});
function updateFilters() {
    activeFilters = [];
    document.querySelectorAll(".filter-btn.active").forEach(activeBtn => {
        activeFilters.push(activeBtn.dataset.filter);
    });
    const filtersInput = document.getElementById('filtersInput');
    if (filtersInput) {
        filtersInput.value = JSON.stringify(activeFilters);
    }
}

const form = document.querySelector('form');
form.addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent default form submission (if you want to handle it manually)
    updateFilters(); // Update filters just before submitting the form
    form.submit(); // Manually trigger the form submission
});

// for tabs
document.getElementById("tabBtnDetails").addEventListener("click", function(){
    console.log("clicked one");
    document.getElementById("tab-details").classList.add("active-tab-content");
    document.getElementById("tab-other").classList.remove("active-tab-content");

    this.classList.add("active-tab");
    document.getElementById("tabBtnOther").classList.remove("active-tab");
});

document.getElementById("tabBtnOther").addEventListener("click", function(){
    console.log("clicked two");
    document.getElementById("tab-other").classList.add("active-tab-content");
    document.getElementById("tab-details").classList.remove("active-tab-content");

    this.classList.add("active-tab");
    document.getElementById("tabBtnDetails").classList.remove("active-tab");
});