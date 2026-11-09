<div class="page-title-container">
    <div class="row">
        <div class="col-12 col-sm-6">
            <h1 class="mb-0 pb-0 display-4" id="title">{{ $title }}</h1>
        </div>
        @isset($action)
            <div class="col-12 col-sm-6 d-flex align-items-start justify-content-end">
                {!! $action !!}
            </div>
        @endisset
    </div>
</div>

@foreach (['success' => 'success', 'error' => 'danger', 'warning' => 'warning'] as $key => $tipe)
    @if (session($key))
        <div class="alert alert-{{ $tipe }} alert-dismissible fade show" role="alert">
            {{ session($key) }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
@endforeach
