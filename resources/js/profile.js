document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("uniqueConfirmModal");
    const yesBtn = document.getElementById("uniqueConfirmYes");
    const noBtn = document.getElementById("uniqueConfirmNo");
    const messageEl = document.getElementById("uniqueConfirmMessage");
    const messageHead = document.getElementById("uniqueHeaderMessage");
    const imageWrapper = document.getElementById("imageWrapper");
    const ConfirmYes = document.getElementById("uniqueConfirmYes");
    

    let currentForm = null;
    let currentSubmitter = null;

    // Event delegation for all forms
    document.body.addEventListener("submit", function (e) {
        const form = e.target;
        const submitter = e.submitter;

        if (!submitter) return;

        if (
            submitter.classList.contains("cancelButton") ||
            submitter.classList.contains("confirmCOD") ||
            submitter.classList.contains("confirmOrder") ||
            submitter.classList.contains("confirmPayment") ||
            submitter.classList.contains("removeImage") ||
            submitter.classList.contains("lbl_gcash_receipt") ||
            submitter.classList.contains("orderReceived")
        ) {
            e.preventDefault();
            currentForm = form;
            currentSubmitter = submitter;

            if (submitter.classList.contains("cancelButton")) {
                document.getElementById("uniqueModalIcon").src = "/imgModal/cancelLogo.svg";
                document.getElementById("uniqueModalHeader").style.backgroundColor = "#BE1A1A";
                imageWrapper.style.boxShadow = "0 1px 0 rgba(190, 26, 26, 0.6)"; 
                messageHead.textContent = "Confirm Cancel?";
                messageHead.style.color ="#BE1A1A";
                messageEl.textContent = "Are you sure you want to cancel this order? This action cannot be undone.";
                ConfirmYes.style.backgroundColor ="#BE1A1A";
                yesBtn.textContent = "Cancel Order";
                noBtn.textContent = "Close";
            } else if (submitter.classList.contains("confirmCOD")) {
                document.getElementById("uniqueModalIcon").src = "/imgModal/confirmationLogo.svg";
                document.getElementById("uniqueModalHeader").style.backgroundColor = "#5196F0";
                imageWrapper.style.boxShadow = "0 1px 0 rgba(81, 150, 240, 0.6)";
                messageHead.style.color ="#5196F0";
                messageHead.textContent = "Confirm COD?";
                ConfirmYes.style.backgroundColor ="#5196F0";
                messageEl.textContent = "Confirm Cash on Delivery Order?";
                yesBtn.textContent = "Confirm";
            } else if (submitter.classList.contains("confirmOrder")) {
                document.getElementById("uniqueModalIcon").src = "/imgModal/confirmationLogo.svg";
                document.getElementById("uniqueModalHeader").style.backgroundColor = "#5196F0";
                imageWrapper.style.boxShadow = "0 1px 0 rgba(81, 150, 240, 0.6)";
                messageHead.textContent = "Confirm Order?";
                messageHead.style.color ="#5196F0";
                messageEl.textContent = "Are you sure you want to confirm this order? This action cannot be undone.";
                ConfirmYes.style.backgroundColor ="#5196F0";
                yesBtn.textContent = "Confirm";
            } else if (submitter.classList.contains("confirmPayment")) {
                document.getElementById("uniqueModalIcon").src = "/imgModal/confirmationLogo.svg";
                document.getElementById("uniqueModalHeader").style.backgroundColor = "#5196F0";
                imageWrapper.style.boxShadow = "0 1px 0 rgba(81, 150, 240, 0.6)";
                messageHead.textContent = "Confirm Payment?";
                messageHead.style.color ="#5196F0";
                messageEl.textContent = "Are you sure you want to confirm this order payment? This action cannot be undone.";
                ConfirmYes.style.backgroundColor ="#5196F0";
                yesBtn.textContent = "Confirm";
            } else if (submitter.classList.contains("removeImage")) {
                document.getElementById("uniqueModalIcon").src = "/imgModal/cancelLogo.svg";
                document.getElementById("uniqueModalHeader").style.backgroundColor = "#BE1A1A";
                imageWrapper.style.boxShadow = "0 1px 0 rgba(190, 26, 26, 0.6)"; 
                messageHead.textContent = "Confirm Remove Image?";
                messageHead.style.color ="#BE1A1A";
                messageEl.textContent = "Are you sure you want to remove this image? This action cannot be undone.";
                ConfirmYes.style.backgroundColor ="#BE1A1A";
                yesBtn.textContent = "Remove Image";
                noBtn.textContent = "Close";
            }
            else if (submitter.classList.contains("lbl_gcash_receipt")) {
                messageEl.textContent = "Confirm Submit?";
            }
            else if (submitter.classList.contains("orderReceived")) {
                document.getElementById("uniqueModalIcon").src = "/imgModal/confirmationLogo.svg";
                document.getElementById("uniqueModalHeader").style.backgroundColor = "#5196F0";
                imageWrapper.style.boxShadow = "0 1px 0 rgba(81, 150, 240, 0.6)";
                messageHead.textContent = "Confirm Order?";
                messageHead.style.color ="#5196F0";
                messageEl.textContent = "Are you sure you want to confirm this order? This action cannot be undone.";
                ConfirmYes.style.backgroundColor ="#5196F0";
                yesBtn.textContent = "Confirm";
            }

            modal.style.display = "flex";
        }
    });

    yesBtn.addEventListener("click", function () {
        modal.style.display = "none";
        if (!currentForm || !currentSubmitter) return;

        const formData = new FormData(currentForm);

        fetch(currentForm.action, {
            method: currentForm.method,
            body: formData,
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
            },
        })
            .then((response) => response.json())
            .then((data) => {
                console.log("Success:", data);

                if (data.success) {
                    const bar = document.createElement("div");
                    bar.className = "success-bar";
                    bar.innerHTML = `
                        <span>${data.message || "Action successful!"}</span>
                        <img src="/imgModal/barCheckLogo.svg" alt="success" class="success-icon" />
                    `;

                    document.body.appendChild(bar);

                    // Show animation
                    requestAnimationFrame(() => {
                        bar.classList.add("show");
                    });

                    // Hide after 3 seconds
                    setTimeout(() => {
                        bar.classList.remove("show");
                        setTimeout(() => bar.remove(), 400);
                    }, 5000);
                    console.log(currentSubmitter.classList);
                    if (currentSubmitter.classList.contains("cancelButton")) {
                        currentForm.closest(".card")?.remove();
                    } 
                    else if (currentSubmitter.classList.contains("confirmCOD")) {
                        currentForm.closest(".card")?.remove();
                    } 
                    else if (currentSubmitter.classList.contains("confirmOrder")) {
                        const card = currentForm.closest(".card");
                        const filters = "pending";
                        console.log(card.dataset.id);
                        fetch(`/cart/${card.dataset.id}/card?filter=${encodeURIComponent(filters)}`) 
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                const temp = document.createElement("div");
                                temp.innerHTML = data.html.trim();
                                const newCard = temp.querySelector(".card");
                                if (newCard) card.replaceWith(newCard);
                            }
                        })
                        .catch(err => console.error("Error refreshing card:", err));
                    } 
                    else if (currentSubmitter.classList.contains("confirmPayment")) {
                        currentForm.closest(".card")?.remove();
                    } 
                    else if (currentSubmitter.classList.contains("removeImage")) {
                    const card = currentForm.closest(".card");
                    const filters = "pending";
                    console.log(card.dataset.id);
                    fetch(`/cart/${card.dataset.id}/card?filter=${encodeURIComponent(filters)}`, {
                        method: "GET",
                        headers: {
                            "X-Requested-With": "XMLHttpRequest"
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            const temp = document.createElement("div");
                            temp.innerHTML = data.html.trim();
                            const newCard = temp.querySelector(".card");
                            if (newCard) card.replaceWith(newCard);
                        }
                    })
                    .catch(err => console.error("Error refreshing card:", err));
                    }
                    else if (currentSubmitter.classList.contains("orderReceived")) {
                    const card = currentForm.closest(".card");
                    const filters = "receive";
                    console.log(card.dataset.id);
                    console.log(filters);
                    fetch(`/cart/${card.dataset.id}/card?filter=${encodeURIComponent(filters)}`, {
                        method: "GET",
                        headers: {
                            "X-Requested-With": "XMLHttpRequest"
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            const temp = document.createElement("div");
                            temp.innerHTML = data.html.trim();
                            const newCard = temp.querySelector(".card");
                            if (newCard) card.replaceWith(newCard);
                        }
                    })
                    .catch(err => console.error("Error refreshing card:", err));
                    }
                }
                currentForm = null;
                currentSubmitter = null;
            })
            .catch((error) => {
                console.error("Error:", error);
                currentForm = null;
                currentSubmitter = null;
            });
    });

    noBtn.addEventListener("click", function () {
        modal.style.display = "none";
        currentForm = null;
        currentSubmitter = null;
    });
});
