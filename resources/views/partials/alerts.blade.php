@if(session('success'))
    <div class="alert alert-success" data-auto-dismiss>
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger" data-auto-dismiss>
        <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <div>
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
