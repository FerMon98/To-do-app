function validateForm() {
    var status = document.getElementById("statusSelect").value;
    if (status === "") {
        alert("Please select a status before submitting.");
        return false;
    }
    return true;
}

function toggleEditForm(taskId) {
    const form = document.getElementById('edit-form-' + taskId);
    const isHidden = form.style.display === 'none';
    form.style.display = isHidden ? 'block' : 'none';
}

// Function to open the modal for the correct task
document.querySelectorAll('.edit-btn').forEach(button => {
    button.addEventListener('click', function() {
        const taskId = this.getAttribute('data-task-id');
        const modal = document.querySelector(`.modal[data-modal-id='${taskId}']`);
        modal.style.display = 'block';
    });
});

// Function to close the modal when the close button or cancel button is clicked
document.querySelectorAll('.close, .cancel-btn').forEach(button => {
    button.addEventListener('click', function() {
        const taskId = this.getAttribute('data-modal-id');
        const modal = document.querySelector(`.modal[data-modal-id='${taskId}']`);
        modal.style.display = 'none';
    });
});

//Close modal when user clicks outside the modal content
window.addEventListener('click', function(event) {
    document.querySelectorAll('.modal').forEach(modal => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
});

