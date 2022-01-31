<header class="site-header">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="site-logo">
            <a href="{{ route('index') }}" class="d-flex"><img src="{{ asset('assets/images/logo.png') }}" alt="Logo"><h3 class="m-0 ms-1" style="font-weight: bold">CF - CrowdFunding</h3></a>
        </div>
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                <b style="font-weight: bold;">{{ session('user')->firstname . " " . session('user')->lastname }}</b>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                <li><a class="dropdown-item" href="{{ route('ownProjects') }}" style="font-size: 16px; font-weight: bold;">My projects <i class="fas fa-project-diagram"></i> </a></li>
                <li><a class="dropdown-item" href="{{ route('logout') }}" style="font-size: 16px; font-weight: bold;">Log out <i class="fas fa-sign-out-alt"></i></a></li>
            </ul>
        </div>
    </div>
</header>
