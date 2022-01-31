@extends('layouts.master')
@section('content')
    <div class="content">
            <div class="lgray-bg py-5 mb-4">
                @include('layouts.errors')
                <div class="container">
                    <div class="row mb-3">
                        <div class="col-md-5 col-sm-5">
                            <h2 class="block-title">Projects that are open<br><span style="color: #42B8D4; font-weight: bold">for funding</span></h2>
                            <div class="spacer-30"></div>
                        </div>
                        <div class="col-md-7 col-sm-7">
                            <div class="spacer-10"></div>
                            <p class="dark_text">
                                Here are the current projects, owners of which desire that you help them to raise funds for implementing the
                                necessary steps to achieve their objectives. Click on the name or description of project to open its details.
                                You are free to look through projects and choose the ones, that are most interesting
                                for you, and then donate money in order to promote those ideas. But remember: You can fund each project only once! So, choose
                                wisely the amount of money which you are going to fund. <br />
                                P.S.: At this platform people also can fund money for your projects. If you have any projects, you can look at their current statistics
                                by clicking on the button with your name in the top left corner and then selecting "My projects" from the dropdown menu.
                            </p>
                        </div>
                    </div>
                    <div class="main-content">
                        <div class="row">
                            <ul style="list-style: none" class="owl-carousel carousel-fw d-flex flex-wrap m-0">
                                @foreach($projects as $project)
                                    @if((! \Carbon\Carbon::parse($project->projectEndDate)->isPast()) && $project->totalInvestment < $project->requestedFund && $project->idUser != session('user')->idUser)
                                        <li class="item d-flex">
                                            <div class="grid-item cause-grid-item small-business format-standard d-flex">
                                                <div class="grid-item-inner d-flex">
                                                    <div class="grid-item-content">
                                                        <h3 class="post-title" style="font-weight: bold"><a href="{{ route('project', [$project->idProject]) }}">{{ $project->projectName }}</a></h3>
                                                        <h5>{{ $project->firstname . " " . $project->lastname }}</h5>
                                                        <p><a class="description" href="{{ route('project', [$project->idProject]) }}" style="color: #898989">{{ $project->projectDescription }}</a></p>
                                                        <p class="text-sm-center meta-data">Due to:
                                                            <span style="font-weight: bold"
                                                                class="{{ \App\Http\Controllers\HomeController\GeneralController::daysLeft($project->projectEndDate) <= 10 ? "text-danger" :
                                                                         (\App\Http\Controllers\HomeController\GeneralController::daysLeft($project->projectEndDate) <= 30 ? "text-warning" : "text-success") }}">
                                                                {{ $project->projectEndDate }}
                                                            </span>
                                                        </p>
                                                        <div class="meta-data mt-1">Donated <span style="color: #000000">{{ $project->totalInvestment }}</span> / <span class="cause-target">{{ $project->requestedFund }}$</span></div>
                                                    </div>
                                                    @if(\App\Http\Controllers\HomeController\GeneralController::hasAlreadyDonated(session('user')->idUser, $project->idProject))
                                                        <a class="btn btn-primary bg-success border-success" disabled="disabled" style="opacity: 0.75">Already donated!</a>
                                                    @else
                                                        <a onclick="OpenModal({{ $project->idProject }})" class="btn btn-primary">Donate Now</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="lgray-bg py-5 mb-4">
                <div class="container">
                    <div class="row mb-3">
                        <div class="col-md-5 col-sm-5">
                            <h2 class="block-title overdue">Projects that are <br><span style="color: #D82E67; font-weight: bold">overdue</span></h2>
                            <div class="spacer-30"></div>
                        </div>
                        <div class="col-md-7 col-sm-7">
                            <div class="spacer-10"></div>
                            <p class="dark_text">
                                Here are the past projects which failed to raise the needed amount. There's no chance to fund them anymore.
                                You can have a look at their descriptions, end dates and make decision for yourself why they didn't raise the requested fund.
                            </p>
                        </div>
                    </div>
                    <div class="main-content">
                        <div class="row">
                            <ul style="list-style: none" class="owl-carousel carousel-fw d-flex flex-wrap m-0">
                                @foreach($projects as $project)
                                    @if(\Carbon\Carbon::parse($project->projectEndDate)->isPast() && $project->totalInvestment < $project->requestedFund && $project->idUser != session('user')->idUser)
                                        <li class="item d-flex">
                                            <div class="grid-item cause-grid-item small-business format-standard d-flex">
                                                <div class="grid-item-inner d-flex">
                                                    <div class="grid-item-content">
                                                        <h3 class="post-title" style="font-weight: bold"><a href="{{ route('project', [$project->idProject]) }}" class="name_over">{{ $project->projectName }}</a></h3>
                                                        <h5>{{ $project->firstname . " " . $project->lastname }}</h5>
                                                        <p><a class="description_over" href="{{ route('project', [$project->idProject]) }}" style="color: #898989">{{ $project->projectDescription }}</a></p>
                                                        <p class="text-sm-center meta-data">Due to: <span class="text-danger" style="font-weight: bold">{{ $project->projectEndDate }}</span></p>
                                                        <div class="meta-data mt-1">Donated
                                                            <span class="text-danger" style="font-weight: bold">{{ $project->totalInvestment }}</span>
                                                            / <span class="cause-target">{{ $project->requestedFund }}$</span>
                                                        </div>
                                                    </div>
                                                    <a class="btn btn-primary bg-danger border-danger" disabled="disabled" style="opacity: 0.75">Overdue</a>
                                                </div>
                                            </div>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="lgray-bg py-5">
                <div class="container">
                    <div class="row mb-3">
                        <div class="col-md-5 col-sm-5">
                            <h2 class="block-title successfully_raised">Projects that were <br><span style="color: #198754; font-weight: bold">successfully funded</span></h2>
                            <div class="spacer-30"></div>
                        </div>
                        <div class="col-md-7 col-sm-7">
                            <div class="spacer-10"></div>
                            <p class="dark_text">
                                Here are the projects that ended up to be successfully funded. The needed amount of money has been raised for them.
                                You can have a look at their descriptions, end dates and think about how the owners managed to persuade people to fund their projects.
                            </p>
                        </div>
                    </div>
                    <div class="main-content">
                        <div class="row">
                            <ul style="list-style: none" class="owl-carousel carousel-fw d-flex flex-wrap m-0">
                                @foreach($projects as $project)
                                    @if($project->totalInvestment >= $project->requestedFund && $project->idUser != session('user')->idUser)
                                        <li class="item d-flex">
                                            <div class="grid-item cause-grid-item small-business format-standard d-flex">
                                                <div class="grid-item-inner d-flex">
                                                    <div class="grid-item-content">
                                                        <h3 class="post-title" style="font-weight: bold"><a href="#" class="name_success">{{ $project->projectName }}</a></h3>
                                                        <h5>{{ $project->firstname . " " . $project->lastname }}</h5>
                                                        <p><a class="description_success" href="{{ route('project', [$project->idProject]) }}" style="color: #898989">{{ $project->projectDescription }}</a></p>
                                                        <p class="text-sm-center meta-data">Due to: <span class="text-success" style="font-weight: bold">{{ $project->projectEndDate }}</span></p>
                                                        <div class="meta-data mt-1">Donated
                                                            <span class="text-success" style="font-weight: bold">{{ $project->totalInvestment }}</span>
                                                            / <span class="cause-target">{{ $project->requestedFund }}$</span>
                                                        </div>
                                                    </div>
                                                    <a class="btn btn-primary bg-success border-success" disabled="disabled" style="opacity: 0.75">Fund is raised!</a>
                                                </div>
                                            </div>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
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
@endsection
