<div class="btn-group" role="group">
    <a href="{{ route('backend.consents.show', $data->id) }}" class="btn btn-info btn-sm" title="View">
        <i class="fa fa-eye"></i>
    </a>
    <a href="{{ route('backend.consents.edit', $data->id) }}" class="btn btn-warning btn-sm" title="Edit">
        <i class="fa fa-edit"></i>
    </a>
    <form action="{{ route('backend.consents.destroy', $data->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this consent?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm" title="Delete">
            <i class="fa fa-trash"></i>
        </button>
    </form>
</div>
