<!-- If there are form errors, show these here -->
@if (count($errors) > 0)
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </div>
@endif
