<a href='{!!route("backend.consultations.edit", $data->id)!!}' 
   class='btn btn-sm btn-primary mt-1 edit-btn' 
   data-id="{{ $data->id }}"
   data-bs-toggle="tooltip" 
   title="Edit Consultation">
    <i class="fas fa-wrench"></i> Edit
</a>
<a href='{!!route("backend.consultations.destroy", $data->id)!!}' 
   class='btn btn-sm btn-danger mt-1 delete-btn' 
   data-id="{{ $data->id }}"
   data-token="{{ csrf_token() }}"
   data-bs-toggle="tooltip" 
   title="Delete Consultation">
    <i class="fas fa-trash"></i> Delete
</a>

