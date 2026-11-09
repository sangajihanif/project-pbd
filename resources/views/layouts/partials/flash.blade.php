@foreach (['success' => 'success', 'error' => 'danger', 'warning' => 'warning'] as $key => $tipe)
    @if (session($key))
        <div class="alert alert-{{ $tipe }} alert-dismissible fade show" role="alert">
            {{ session($key) }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
@endforeach
