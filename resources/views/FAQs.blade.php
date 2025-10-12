@php
    $converter = new League\CommonMark\CommonMarkConverter();
    $selectedFaqId = request()->query('faq_id');
@endphp
@extends('Front_layouts.app')

@section('title', 'FAQS')
@section('head')
<link rel="icon" type="image/png" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
      rel="stylesheet"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
      crossorigin="anonymous"
    />
    @vite('resources/css/FAQs.css')
    @vite('resources/js/FAQs.js')
@endsection
@section('content')
    <div class="imgContainer">
      <img class="gradient" src="{{ asset('img/footer.svg') }}" alt="" />
      <div class="overlay-text">Hi, how can we help?</div>
      <div class="searchContainer">
        <input class="" type="text" placeholder="search" />
        <button class="searchButton">
          <img src="{{ asset('img/search-iconWhite.svg') }}" alt="" />
        </button>
        <div class="spinner" style="display:none;"></div>
      </div>
    </div>

    <div class="mainContainer">
    <div class="leftPart">
    @foreach($categories as $category)
    <section>
        <div class="mainCatergory">
            <h2>{{ $category->name }}</h2>
            <img class="arrow {{ $loop->first ? 'rotate' : '' }}" src="{{ asset('img/arrow.svg') }}" alt="" />
        </div>
        <div class="subQuestions" style="display: {{ $loop->first ? 'block' : 'none' }}">
            @foreach($category->faqs as $faq)
            <div class="question" data-id="{{ $faq->id }}"
                @if($selectedFaqId == $faq->id) data-selected="true" @endif>
                <h3 class="{{ $loop->parent->first && $loop->first ? 'active' : '' }}">
                    {{ $faq->question }}
                </h3>
                <p class="answer" style="display: none;">{{ $faq->answer }}</p>
            </div>
            @endforeach
        </div>
    </section>
    @endforeach
    </div>

      <!-- Right Part -->
      <div class="rightPart">
        <h2>What is Yonder?</h2>
        <p>
          Yonder is our university’s very own online marketplace, kind of like our own campus version of an e-commerce site. Here, you can safely buy, sell, or trade items with other students, faculty, and staff. Whether you’re looking for affordable textbooks, pre-loved gadgets, or dorm essentials, Yonder connects you directly with people you can actually bump into on campus.
        </p>
      </div>
    </div>

    <script src="FAQS.js"></script>
    <script>
  // ===========================
// Auto-expand & highlight from ?faq_id=...
// ===========================
document.addEventListener("DOMContentLoaded", () => {
    const selected = document.querySelector(".question[data-selected='true']");
    if (selected) {
        const h3 = selected.querySelector("h3");
        const answer = selected.querySelector(".answer");

        // Expand parent category
        const subQuestions = selected.closest(".subQuestions");
        subQuestions.style.display = "block";

        const arrow = subQuestions.previousElementSibling.querySelector(".arrow");
        arrow.classList.add("rotate");

        // Highlight & show answer in right panel
        document.querySelectorAll(".question h3").forEach(q => q.classList.remove("active"));
        h3.classList.add("active");

        const rightPart = document.querySelector(".rightPart");
        rightPart.innerHTML = `<h2>${h3.textContent}</h2><p>${answer.innerHTML}</p>`;

        // Smooth scroll to the question
        h3.scrollIntoView({ behavior: "smooth", block: "center" });
    }
});
    </script>
@endsection