<a href='{!!route("backend.consultations.edit", $data->id)!!}' 
   class='btn btn-sm btn-primary mt-1 edit-consultation-btn' 
   data-id="{{ $data->id }}"
   data-bs-toggle="tooltip" 
   title="Modifier la consultation">
    <i class="fas fa-wrench"></i> Modifier
</a>
<a href='{!!route("backend.consultations.destroy", $data->id)!!}' 
   class='btn btn-sm btn-danger mt-1 delete-consultation-btn' 
   data-id="{{ $data->id }}"
   data-token="{{ csrf_token() }}"
   data-bs-toggle="tooltip" 
   title="Supprimer la consultation">
    <i class="fas fa-trash"></i> Supprimer
</a>

