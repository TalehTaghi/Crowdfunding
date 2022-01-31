@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if(session('success'))
    <div class="alert alert-success">
        <p>The operation has been carried out successfully!</p>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger">
        <p>Some unespected error has occured!</p>
    </div>
@endif
@if(session('error_mail'))
    <div class="alert alert-danger">
        <p>Email is not found!</p>
    </div>
@endif
@if(session('error_pass'))
    <div class="alert alert-danger">
        <p>Password is not correct!</p>
    </div>
@endif
@if(session('error_no_project'))
    <div class="alert alert-danger">
        <p>Project is not found!</p>
    </div>
@endif
@if(session('error_own_project'))
    <div class="alert alert-danger">
        <p>You cannot donate to your own project!</p>
    </div>
@endif
@if(session('error_has_donated'))
    <div class="alert alert-danger">
        <p>You cannot donate twice to the same project!</p>
    </div>
@endif
@if(session('error_funds_raised'))
    <div class="alert alert-danger">
        <p>You cannot donate to the project, if the necessary funds have already been raised!</p>
    </div>
@endif
@if(session('error_project_overdue'))
    <div class="alert alert-danger">
        <p>You cannot donate to past project!</p>
    </div>
@endif
@if(session('error_amount_max'))
    <div class="alert alert-danger">
        <p>The expected remaining fund for this project is {{ session('error_amount_max') }}$. You cannot donate amount more than that!</p>
    </div>
@endif
@if(session('error_amount_min'))
    <div class="alert alert-danger">
        <p>The minimum amount that can be fund is 0.01$!</p>
    </div>
@endif
