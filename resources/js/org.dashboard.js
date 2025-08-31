document.addEventListener("DOMContentLoaded", () =>{
    document.querySelectorAll(".btn-delete").forEach(button => {
        button.addEventListener("click", async (e) => {
            const productId = e.target.dataset.id;

            if(!confirm("Are you sure you want to delete this product?")){
                return;
            }

            try{
                const response = await fetch(`/products/${productId}`,{
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
        });
    });
});