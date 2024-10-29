/* Buttons */    
const homeBtn = document.getElementById('homeBtn');
const salesBtn = document.getElementById('salesBtn'); 
const patientsBtn = document.getElementById('patientsBtn');
const inventoryBtn = document.getElementById('inventoryBtn');

/* sections */
const homeSection = document.getElementById('home');
const salesSection = document.getElementById('sales');
const patientsSection = document.getElementById('patients');
const inventorySection = document.getElementById('inventory');

homeBtn.addEventListener('click', () => {
    homeSection.style.display = "block";
    salesSection.style.display = "none";
    patientsSection.style.display = "none";
    inventorySection.style.display = "none";
});

salesBtn.addEventListener('click', () => { 
    homeSection.style.display = "none";
    salesSection.style.display = "block";
    patientsSection.style.display = "none";
    inventorySection.style.display = "none";
});

patientsBtn.addEventListener('click', () => {
    homeSection.style.display = "none";
    salesSection.style.display = "none";
    patientsSection.style.display = "block";
    inventorySection.style.display = "none";
});

inventoryBtn.addEventListener('click', () => {
    homeSection.style.display = "none";
    salesSection.style.display = "none";
    patientsSection.style.display = "none";
    inventorySection.style.display = "block";
});


/* ---------------------------- */

// JavaScript to dynamically populate modal and handle form confirmation
document.getElementById('openModalBtn').addEventListener('click', function() {
    // Get the values from the form
    let name = document.getElementById('name').value;
    let price = document.getElementById('price').value;
    let quantity = document.getElementById('quantity').value;
    let category = document.getElementById('category').value;
    let expiry = document.getElementById('expiry').value;
    let manufacturer = document.getElementById('manufacturer').value;


    // Populate the modal with these values
    document.getElementById('modalName').innerText = name;
    document.getElementById('modalPrice').innerText = price;
    document.getElementById('modalQuantity').innerText = quantity;
    document.getElementById('modalCategory').innerText = category;
    document.getElementById('modalExpiry').innerText = expiry;
    document.getElementById('modalManufacturer').innerText = manufacturer;

    // Show the modal
    document.getElementById('confirmModal').style.display = 'block';
});

// Confirm button functionality
document.getElementById('confirmBtn').addEventListener('click', function() {
    // Submit the form
    document.getElementById('medicineForm').submit();
});

// Cancel button functionality
document.getElementById('cancelBtn').addEventListener('click', function() {
    // Hide the modal
    document.getElementById('confirmModal').style.display = 'none';
});

// Close the modal if the user clicks outside the modal content
window.onclick = function(event) {
    let modal = document.getElementById('confirmModal');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
}    

    function validateEmail() {
    const emailInput = document.getElementById("email");
    const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;

    if (!emailPattern.test(emailInput.value)) {
        emailInput.classList.add("errorBorder");  
        return false;  
    } else {
        emailInput.classList.remove("errorBorder");  
    }
    return true;
}


/* ------------------------log out confimation------------------- */

    