// Consultation page specific JavaScript
// The Vue component is registered in vue/app.js

// Handle edit button clicks
document.addEventListener('click', function(e) {
  if (e.target.closest('.edit-btn')) {
    const btn = e.target.closest('.edit-btn')
    const id = btn.getAttribute('data-id')
    
    // Trigger offcanvas with edit
    const offcanvasEl = document.getElementById('form-offcanvas')
    if (offcanvasEl) {
      // Trigger custom event for crud_change_id
      window.dispatchEvent(new CustomEvent('crud_change_id', { detail: { form_id: parseInt(id) } }))
      
      const bsOffcanvas = new bootstrap.Offcanvas(offcanvasEl)
      bsOffcanvas.show()
    }
    
    e.preventDefault()
    return false
  }
  
  // Handle delete button clicks
  if (e.target.closest('.delete-btn')) {
    const btn = e.target.closest('.delete-btn')
    const id = btn.getAttribute('data-id')
    const token = btn.getAttribute('data-token')
    const url = btn.getAttribute('href')
    
    if (confirm('Are you sure you want to delete this consultation?')) {
      fetch(url, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': token,
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.status) {
          window.successSnackbar(data.message)
          renderedDataTable.ajax.reload(null, false)
        } else {
          window.errorSnackbar(data.message || 'An error occurred')
        }
      })
      .catch(error => {
        console.error('Error:', error)
        window.errorSnackbar('An error occurred while deleting')
      })
    }
    
    e.preventDefault()
    return false
  }
})

