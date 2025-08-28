// ===========================
// Toggle categories open/close
// ===========================
document.querySelectorAll(".mainCatergory").forEach((category) => {
    category.addEventListener("click", () => {
        const subQuestions = category.nextElementSibling;
        const arrow = category.querySelector(".arrow");
        const isOpen = subQuestions.style.display === "block";

        subQuestions.style.display = isOpen ? "none" : "block";
        arrow.classList.toggle("rotate", !isOpen);
    });
});

// ===========================
// Right panel update on question click
// ===========================
const rightPart = document.querySelector(".rightPart");
const questions = document.querySelectorAll(".question h3");

questions.forEach((q) => {
    q.addEventListener("click", () => {
        // Remove highlight from all
        questions.forEach((item) => item.classList.remove("active"));
        q.classList.add("active");

        const answer = q.nextElementSibling.innerHTML;
        rightPart.innerHTML = `<h2>${q.textContent}</h2><p>${answer}</p>`;
    });
});

// ===========================
// FAQ Search (Enter key or button)
// ===========================
const searchInput = document.querySelector(".searchContainer input");
const searchButton = document.querySelector(".searchButton");

function triggerSearch() {
    const query = searchInput.value.trim();
    if (!query) return;
    rightPart.innerHTML = '<div class="spinner-right"></div>';
    fetch(`/faq/search?q=${encodeURIComponent(query)}`)
        .then((res) => {
            if (!res.ok) throw new Error("Network response was not ok");
            return res.json();
        })
        .then((data) => {
            if (!data.answer) {
                rightPart.innerHTML = `
                    <h2>No Results Found</h2>
                    <p>We couldn’t find an answer for "${query}".</p>
                `;
                return;
            }

            rightPart.innerHTML = `
                <h2>Search Result</h2>
                ${data.answer}
            `;

            if (data.faq_ids && data.faq_ids.length > 0) {
                questions.forEach((q) => q.classList.remove('active'));

                console.log("FAQ IDs returned:", data.faq_ids);
                data.faq_ids.forEach((id) => {
                    const matchedQuestion = document.querySelector(`.question[data-id="${id}"] h3`);
                    if (!matchedQuestion) return;

                    const matchedCategory = matchedQuestion.closest('.subQuestions');
                    const categoryHeader = matchedCategory.previousElementSibling;
                    const arrow = categoryHeader.querySelector('.arrow');

                    if (matchedCategory.style.display !== 'block') {
                        matchedCategory.style.display = 'block';
                        arrow.classList.add('rotate');
                    }

                    matchedQuestion.classList.add('active');
                });


                const firstQuestion = document.querySelector(`.question[data-id="${data.faq_ids[0]}"] h3`);
                if (firstQuestion) firstQuestion.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        })
        .catch((err) => {
            console.error("Search error:", err);
            rightPart.innerHTML = `
                <h2>Error</h2>
                <p>Something went wrong while searching. Please try again later.</p>
            `;
        });
}

searchInput.addEventListener("keydown", (e) => {
    if (e.key === "Enter") {
        e.preventDefault();
        triggerSearch();
    }
});

searchButton.addEventListener("click", (e) => {
    e.preventDefault();
    triggerSearch();
});
