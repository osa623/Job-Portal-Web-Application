function openModal(dietPlan) {
  // Populate modal with diet plan details
  document.getElementById("modalTitle").innerText = dietPlan.meal_plan;
  document.getElementById("modalImage").src = dietPlan.image;
  document.getElementById("modalDescription").innerText = dietPlan.description;
  document.getElementById(
    "modalNotes"
  ).innerHTML = `<li>${dietPlan.notes}</li>`;

  // Show the modal
  document.getElementById("detailModal").style.display = "block";
}

function closeModal() {
  // Hide the modal
  document.getElementById("detailModal").style.display = "none";
}

// Close modal when clicking outside the content
window.onclick = function (event) {
  const modal = document.getElementById("detailModal");
  if (event.target == modal) {
    modal.style.display = "none";
  }
};
