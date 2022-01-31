<footer>
    <div class="py-0">
        <div class="container">
            <div class="row font-size-sm">
                <div class="col-sm-6 order-sm-2 mb-1 mb-sm-0 text-end">
                    <img src="{{ asset('assets/images/logo.png') }}" style="width: 15px; height: 15px"> by Taleh and Mirjalal
                </div>
                <div class="col-sm-6 order-sm-1 text-start">
                    <a class="font-w600" href="{{ route('index') }}">CrowdFunding 1.0</a> &copy; {{ "2021" == date("Y") ? "2021"  : "2021 - ".date("Y") }}
                </div>
            </div>
        </div>
    </div>
</footer>
