<div class="content">
    <div class="lgray-bg py-5">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-md-8 content-block">
                    @include('layouts.errors')
                    <div class="d-flex justify-content-between">
                        <div>
                            <h1 class="accent-color mb-0" style="font-weight: bold">{{ $project->projectName }}</h1>
                            <h4 class="mb-4">By <span class="bordo_text font_bold">{{ $project->firstname . " " . $project->lastname }}</span></h4>
                        </div>
                        <div class="d-flex flex-column justify-content-start align-items-end pt-2">
                            <h4 class="m-0">Start date: <span class="text-success font_bold">{{ $project->projectStartDate }}</span></h4>
                            <h4 class="m-0">End date: <span class="bordo_text font_bold">{{ $project->projectEndDate }}</span></h4>
                        </div>
                    </div>
                    <h4 class="mb-0">Funding status:
                        <span class="bagde text-white rounded-pill p-1
                            {{ $project->totalInvestment >= $project->requestedFund ? "bg-success" : (\Carbon\Carbon::parse($project->projectEndDate)->isPast() ? "bg-danger" : "bg-primary") }}">
                            {{ $project->totalInvestment >= $project->requestedFund ? "success" : (\Carbon\Carbon::parse($project->projectEndDate)->isPast() ? "overdue" : "in progress") }}
                        </span>
                    </h4>
                    <div class="progress mt-1" style="background-color: #555555">
                        <div class="d-none" id="widthOfBar">{{ $project->totalInvestment/$project->requestedFund*100 }}%</div>
                        <div role="progressbar" class="progress-bar progress-bar-primary font_bold
                             {{ $project->totalInvestment >= $project->requestedFund ? "bg-success" : (\Carbon\Carbon::parse($project->projectEndDate)->isPast() ? "bg-danger" : "") }}"
                             aria-valuenow="{{ $project->totalInvestment/$project->requestedFund*100 }}" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                    <div class="pull-left dark_text">Raised <strong class="text-success" style="font-weight: bold">${{ $project->totalInvestment }}</strong></div>
                    <div class="pull-right dark_text">Goal <strong class="accent-color" style="font-weight: bold">${{ $project->requestedFund }}</strong></div>
                    <div class="spacer-40"></div>
                    <div class="row mb-5">
                        <div class="col-md-5 col-sm-5">
                            <p class="dark_text" style="font-size: 17px">{{ $project->projectDescription }}</p>
                        </div>
                        <div class="col-md-7 col-sm-7">
                            <ul class="list-group mt-0">
                                <li class="list-group-item">Remaining fund:
                                    <span class="badge ms-1 mt-0" style="background-color: #555555">
                                        {{ \App\Http\Controllers\DonationController\DonationController::expectedRemainingAmount($project->idProject) }}$
                                    </span>
                                </li>
                                <li class="list-group-item">Days left to fundraising:
                                    <span class="badge ms-1 mt-0
                                        {{ \Carbon\Carbon::parse($project->projectEndDate)->isPast() || \App\Http\Controllers\HomeController\GeneralController::daysLeft($project->projectEndDate) <= 10 ? "bg-danger" :
                                           (\App\Http\Controllers\HomeController\GeneralController::daysLeft($project->projectEndDate) > 30 || \App\Http\Controllers\DonationController\DonationController::expectedRemainingAmount($project->idProject) <= 0 ? "bg-success" : "bg-warning") }}">
                                        {{ \Carbon\Carbon::parse($project->projectEndDate)->isPast() || \App\Http\Controllers\DonationController\DonationController::expectedRemainingAmount($project->idProject) <= 0 ? 0 :
                                            \App\Http\Controllers\HomeController\GeneralController::daysLeft($project->projectEndDate) }}
                                    </span>
                                </li>
                                @if(\App\Http\Controllers\DonationController\DonationController::isOwnProject($project->idProject))
                                    <li class="list-group-item">Total investors:
                                        <span class="badge ms-1 mt-0" style="background-color: #555555">
                                            {{ \App\Http\Controllers\ProjectsController\OwnProjectController::numberOfDonors($project->idProject) }}
                                        </span>
                                        <a href="#listHeading{{ $project->idProject }}" class="ms-2 text-primary font_bold" onclick="showTable({{$project->idProject}})">See list of investors</a>
                                    </li>
                                @endif
                            </ul>
                            @if(\App\Http\Controllers\DonationController\DonationController::isOwnProject($project->idProject))
                                <a class="btn btn-primary" disabled="disabled" style="background-color: #555555; opacity: 0.8">No donation for own project</a>
                            @elseif(\App\Http\Controllers\DonationController\DonationController::expectedRemainingAmount($project->idProject) <= 0)
                                <a class="btn btn-primary bg-success border-success" disabled="disabled" style="opacity: 0.8">This project is raised!</a>
                            @elseif(\App\Http\Controllers\DonationController\DonationController::isProjectOverdue($project->idProject))
                                <a class="btn btn-primary bg-danger border-danger" disabled="disabled" style="opacity: 0.8">This project is overdue</a>
                            @elseif(\App\Http\Controllers\DonationController\DonationController::hasDonated($project->idProject))
                                <a class="btn btn-primary bg-success border-success" disabled="disabled" style="opacity: 0.8">You have already donated!</a>
                            @else
                                <a onclick="OpenModal({{ $project->idProject }})" class="btn btn-primary">Donate Now</a>
                            @endif
                        </div>
                    </div>
                    @if(\App\Http\Controllers\DonationController\DonationController::isOwnProject($project->idProject))
                        <div id="hiddenTable{{ $project->idProject }}" style="display: none">
                            <h3 class="font_bold" id="listHeading{{ $project->idProject }}" style="color: #4292d4">List of Investors</h3>
                            <p>Click on column to sort data by its category</p>
                            <table class="table table-primary table-striped" id="investorsTable{{ $project->idProject }}">
                                <thead class="table-dark">
                                    <tr class="font_bold">
                                        <th scope="col">#</th>
                                        <th scope="col">Investor's Name</th>
                                        <th scope="col">Investment Fund</th>
                                        <th scope="col">Investment Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach($investments[$project->idProject] as $investment)
                                        <tr>
                                            <th scope="row">{{ ++$i }}</th>
                                            <td>{{ $investment->firstname . " " . $investment->lastname }}</td>
                                            <td>{{ $investment->investmentFund }}$</td>
                                            <td>{{ $investment->investmentDate }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="col-md-3 sidebar-block">
                    <!-- Latest Events -->
                    <div class="widget events-calendar-widget">
                        <h3 class="widgettitle">Other projects</h3>
                        <div class="events-calendar-widget-body">
                            <ul class="events-compact-list">
                                @foreach($random_projects as $random)
                                    <li class="event-list-item">
                                        <a href="{{ route('project', [$random->idProject]) }}">
                                            <span class="event-date">
                                                <span class="date">{{ \Carbon\Carbon::parse($random->projectEndDate)->format('d') }}</span>
                                                <span class="month">{{ \Carbon\Carbon::parse($random->projectEndDate)->format("M") }}</span>
                                                <span class="year">{{ \Carbon\Carbon::parse($random->projectEndDate)->format("Y") }}</span>
                                            </span>
                                        </a>
                                        <div class="event-list-cont">
                                            <span class="font_bold">{{ \Carbon\Carbon::parse($random->projectEndDate)->format("l, H:i") }}</span>
                                            <h4 class="post-title"><a href="{{ route('project', [$random->idProject]) }}">{{ $random->projectName }}</a></h4>
                                            <h5>By <span class="font_bold bordo_text">{{ \App\Http\Controllers\ProjectsController\AnyProjectController::getNameOfOwner($random->idUser) }}</span></h5>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="investmentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="projectName" style="font-weight: bold; color: #42B8D4"></h3>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"
                            style="background-color: #D82E67; width: 40px; height: 40px; border-color: #D82E67; border-top-right-radius: 3px">
                        <span aria-hidden="true" style="font-size: 30px">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h3><span class="accent-color" style="font-weight: bold">Owner: </span><span id="projectOwner"></span></h3>
                    <h4><b style="font-weight: bold"><span class="text-success">Raised</span>/<span class="accent-color">Requested fund</span>:</b> <span id="raised_fund"></span>/<span id="requested_fund"></span>$</h4>
                    <h4><b style="font-weight: bold; color: #D82E67">Expected remaining fund:</b> <span id="remaining_fund"></span>$</h4>
                    <form method="POST" action="{{ route('donation') }}">
                        @csrf
                        <input type="hidden" name="project_id" id="project_id" />
                        <div class="form-group">
                            <h4 class="accent-color mb-0"><label for="investedFund" class="col-form-label">Your amount:</label></h4>
                            <input type="number" min="0.01" step="0.01" class="form-control" name="investedFund" id="investedFund" required="required" />
                        </div>
                        <div class="amount_buttons d-flex justify-content-between mb-3">
                            <button type="button" class="btn btn-secondary amount_option" onclick="IncreaseAmount(1)" style="font-weight: bold">+1$</button>
                            <button type="button" class="btn btn-secondary amount_option" onclick="IncreaseAmount(5)" style="font-weight: bold">+5$</button>
                            <button type="button" class="btn btn-secondary amount_option" onclick="IncreaseAmount(10)" style="font-weight: bold">+10$</button>
                            <button type="button" class="btn btn-secondary amount_option" onclick="IncreaseAmount(50)" style="font-weight: bold">+50$</button>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn text-white" data-bs-dismiss="modal" style="background-color: #D82E67; font-weight: bold">Close</button>
                            <button type="submit" class="btn btn-primary" style="font-weight: bold">Invest</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
