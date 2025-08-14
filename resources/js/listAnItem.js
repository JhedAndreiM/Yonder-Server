const toggleVariant = document.getElementById("toggleVariant");
const variantsDiv = document.getElementById("variants");
const optionsContainer = document.getElementById("optionsContainer");
const addOptionBtn = document.getElementById("addOptionBtn");
const tagInput = document.getElementById("tagInput");
const tagsContainer = document.getElementById("tagsContainer");
const supplierButtons = document.querySelectorAll(".supplier-btn");
const organizationSelect = document.getElementById("organizationSelect");
const collegeButtons = document.querySelectorAll(".college-btn");
const predefinedTagButtons = document.querySelectorAll(".tagsButton .button");
const hiddenInput = document.getElementById('supplier_type');
const organizationField = document.getElementById("organization_id");
const addedTags = new Set();

document.addEventListener('DOMContentLoaded', function () {

  const toggleVariant   = document.getElementById('toggleVariant');
  const variantsDiv     = document.getElementById('variants');
  const variantNameEl   = document.getElementById('variantName');
  const optionsContainer= document.getElementById('optionsContainer');
  const addOptionBtn    = document.getElementById('addOptionBtn');
  const variantsHidden  = document.getElementById('variants_json');
  const form = document.getElementById('createListingForm'); 
  const stockInput = document.getElementById('stock-input'); 

  // Show/hide variant block
  toggleVariant.addEventListener('change', () => {
    variantsDiv.style.display = toggleVariant.checked ? 'block' : 'none';
    if (!toggleVariant.checked) {
      variantsHidden.value = ''; 
      stockInput.readOnly = false;
      const rows = optionsContainer.querySelectorAll('.option-row');
      rows.forEach((row, index) => {
      if (index === 0) {
        const nameInput = row.querySelector('.option-input');
        const stockInput = row.querySelector('.option-input-stock');
        if (nameInput) nameInput.value = '';
        if (stockInput) stockInput.value = '';
        if (variantNameEl) variantNameEl.value = '';
      } else {
      row.remove();
    }
  });
        
    }
    else{
      stockInput.readOnly = true;
    }
  });

  optionsContainer.addEventListener('input', (e) => {
  if (e.target.classList.contains('option-input-stock')) {
      updateTotalStock();
  }
  });
  // computes the total stock from each input
  function updateTotalStock() {
  const variantStocks = optionsContainer.querySelectorAll('.option-input-stock');
  let total = 0;
  variantStocks.forEach(input => {
    const val = parseInt(input.value, 10);
    if (!isNaN(val)) total += val;
  });
  stockInput.value = total; 
  }

  // Add option rows
  const MAX_OPTIONS = 10;
  addOptionBtn.addEventListener('click', function () {
    const currentOptions = optionsContainer.querySelectorAll('.option-row').length;
    if (currentOptions >= MAX_OPTIONS) {
      alert(`You can only add up to ${MAX_OPTIONS} options.`);
      return;
    }
    const optionRow = document.createElement('div');
    optionRow.className = 'option-row';

    const input = document.createElement('input');
    input.type = 'text';
    input.className = 'option-input';
    input.placeholder = `Option ${currentOptions + 1}`;

    const inputStock = document.createElement('input');
    inputStock.type = 'number';
    inputStock.className = 'option-input-stock';
    inputStock.placeholder = `Stock ${currentOptions + 1}`;
    inputStock.min = "1";

    const removeBtn = document.createElement('button');
    removeBtn.type = 'button';
    removeBtn.className = 'remove-btn';
    removeBtn.textContent = '✖';
    removeBtn.addEventListener('click', () => {
      optionRow.remove();
      updateTotalStock();        
    });

    optionRow.appendChild(input);
    optionRow.appendChild(inputStock);
    optionRow.appendChild(removeBtn);
    optionsContainer.appendChild(optionRow);
  });

  

  // Serialize before submit
  form.addEventListener('submit', function () {
    if (!toggleVariant.checked) {
      variantsHidden.value = '';
      return;
    }
    const name = variantNameEl.value.trim();
    const optionInputs = optionsContainer.querySelectorAll('.option-input');
    const optionInputStocks = optionsContainer.querySelectorAll('.option-input-stock');
    const options = [];
    const optionStocks = [];

    optionInputs.forEach(input => {
      const v = input.value.trim();
      if (v !== '') options.push(v);
    });
    optionInputStocks.forEach(input => {
      const v = input.value.trim();
      if (v !== '') optionStocks.push(v);
    });

    // If user turned on variant but entered nothing, send empty
    if (!name && options.length === 0) {
      variantsHidden.value = '';
      return;
    }

    const payload = { name, options, optionStocks };
    variantsHidden.value = JSON.stringify(payload);
  });
});



document.addEventListener('DOMContentLoaded', function () {
  const presetTagBtns = document.querySelectorAll('#presetTags .tag-btn');
  const tagInputEl    = document.getElementById('tagInput');
  const tagsContainer = document.getElementById('tagsContainer');
  const tagsHidden    = document.getElementById('tags_json');

  // single source of truth
  let selectedTags = []; // [{id: "3", name:"Books"}  or {id:null, name:"Custom"}]

  function normalizeName(str) {
    return str.trim().toLowerCase();
  }

  function hasTag(name) {
    const n = normalizeName(name);
    return selectedTags.some(t => normalizeName(t.name) === n);
  }

  function addTagObj(tagObj) {
    if (!tagObj.name) return;
    if (hasTag(tagObj.name)) return;
    selectedTags.push(tagObj);
    renderTags();
  }

  function removeTag(name) {
    const n = normalizeName(name);
    selectedTags = selectedTags.filter(t => normalizeName(t.name) !== n);
    renderTags();
  }
  function renderTags() {
    // clear UI
    tagsContainer.innerHTML = '';

    // rebuild chips
    selectedTags.forEach(tag => {
      const chip = document.createElement('div');
      chip.className = 'tag-chip';

      const txt = document.createElement('span');
      txt.textContent = tag.name;

      const rm = document.createElement('button');
      rm.type = 'button';
      rm.className = 'remove-tag';
      rm.textContent = '×';
      rm.addEventListener('click', () => removeTag(tag.name));

      chip.appendChild(txt);
      chip.appendChild(rm);
      tagsContainer.appendChild(chip);
    });

    // sync hidden
    tagsHidden.value = JSON.stringify(selectedTags);
    // optional debug:
    // console.log('tags_json =', tagsHidden.value);

    // update visual state of preset buttons
    document.querySelectorAll('.tag-btn').forEach(btn => {
  const btnName = btn.dataset.name || btn.textContent.trim();
  btn.classList.toggle('selected', hasTag(btnName));
  });
  }

  // preset click
  document.addEventListener('click', function (e) {
  const btn = e.target.closest('.tag-btn');
  if (!btn) return;
  console.log('clicked');
  const name = (btn.dataset.name || btn.textContent).trim();
  if (hasTag(name)) {
    removeTag(name);
  } else {
    addTagObj({
      id: btn.dataset.id || null,
      name
    });
  }
});

  tagInputEl.addEventListener('keydown', e => {
    if (e.key === 'Enter') {
      e.preventDefault();
      const name = tagInputEl.value.trim();
      if (!name) return;
      addTagObj({ id: null, name });
      tagInputEl.value = '';
    }
  });

  renderTags();
});

supplierButtons.forEach((button) => {
  button.addEventListener("click", () => {
    supplierButtons.forEach((btn) => btn.classList.remove("active"));
    button.classList.add("active");

    organizationSelect.style.display =
      button.dataset.type === "student-org" ? "block" : "none";
    if (button.dataset.type === 'student-org') {
        organizationField.required = true;
    } else {
        organizationField.required = false;
    }
       hiddenInput.value = button.dataset.type;
  });
});

const tradeOrSellBtn = document.querySelectorAll('.filter-btn');
const tradeOrSellInput = document.getElementById('tradeOrSell');

tradeOrSellBtn.forEach(button => {
  button.addEventListener("click", () => {
    tradeOrSellBtn.forEach(btn => btn.classList.remove('active'));

    tradeOrSellInput.value = button.dataset.filter;
  });
});

const productQualityBtn = document.querySelectorAll('.filter-btn-quality');
const productQualityInput = document.getElementById('productQuality');

productQualityBtn.forEach(button => {
  button.addEventListener("click", () => {
    productQualityBtn.forEach(btn => btn.classList.remove('active'));

    productQualityInput.value = button.dataset.filter;
  })
})


document.querySelectorAll('.filter-btn').forEach(button=>{
  button.addEventListener("click", ()=>{
    button.classList.toggle("active");
  })
});
document.querySelectorAll('.filter-btn-quality').forEach(button=>{
  button.addEventListener("click", ()=>{
    button.classList.toggle("active");
  })
});

// collegeButtons.forEach((button) => {
//   button.addEventListener("click", () => {
//     button.classList.toggle("active")});
//     console.log('logged!');
// });

predefinedTagButtons.forEach((button) => {
  button.addEventListener("click", () => {
    button.classList.toggle("active");
  });
});


// Colleges
document.addEventListener('DOMContentLoaded', function () {
  const collegeBtns    = document.querySelectorAll('.college-btn');
  const collegesHidden = document.getElementById('colleges_json');

  function syncColleges() {
    const ids = [];
    collegeBtns.forEach(btn => {
      if (btn.classList.contains('active')) {
        const id = btn.dataset.code.toLowerCase();
          ids.push(id);
      }
    });
    collegesHidden.value = JSON.stringify(ids);
  }

  collegeBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      btn.classList.toggle('active');
      syncColleges();
    });
  });

  syncColleges();
});


// for tabs
document.getElementById("tabBtnDetails").addEventListener("click", function(){
    console.log("clicked one");
    document.getElementById("tab-details").classList.add("active-tab-content");
    document.getElementById("tabBtnDetails").classList.add("active-button");
    document.getElementById("tab-other").classList.remove("active-tab-content");
    document.getElementById("tabBtnOther").classList.remove("active-button");

    this.classList.add("active-tab");
    document.getElementById("tabBtnOther").classList.remove("active-tab");
});

document.getElementById("tabBtnOther").addEventListener("click", function(){
    console.log("clicked two");
    document.getElementById("tab-other").classList.add("active-tab-content");
    document.getElementById("tabBtnOther").classList.add("active-button");
    document.getElementById("tab-details").classList.remove("active-tab-content");
    document.getElementById("tabBtnDetails").classList.remove("active-button");

    this.classList.add("active-tab");
    document.getElementById("tabBtnDetails").classList.remove("active-tab");
});