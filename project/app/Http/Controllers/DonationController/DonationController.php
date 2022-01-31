<?php

namespace App\Http\Controllers\DonationController;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DonationController extends Controller
{
    public function getModalInfo(Request $request) {
        $project = DB::table('projects')
                    ->where('projects.idProject', $request->project_id)
                    ->join('projects_investors', 'projects.idProject', '=', 'projects_investors.idProject')
                    ->join('users', 'projects.idUser', '=', 'users.idUser')
                    ->select('projects.projectName', 'users.firstname', 'users.lastname', 'projects.requestedFund',
                                    DB::raw("SUM(projects_investors.investmentFund) as totalInvestment"))
                    ->groupBy('projects.projectName', 'users.firstname', 'users.lastname', 'projects.requestedFund')
                    ->first();

        if ($project) {
            return $project;
        }
        return 0;
    }

    public function donate(Request $request) {
        if (DB::table('projects')->where('idProject', $request->project_id)->first()) {
            $request->validate([
                'investedFund' => 'required',
            ]);

            if ($this->isOwnProject($request->project_id)) {
                return redirect()->back()->with('error_own_project', true);
            }

            if ($this->expectedRemainingAmount($request->project_id) <= 0) {
                return redirect()->back()->with('error_funds_raised', true);
            }

            if ($this->isProjectOverdue($request->project_id)) {
                return redirect()->back()->with('error_project_overdue', true);
            }

            if ($this->hasDonated($request->project_id)) {
                return redirect()->back()->with('error_has_donated', true);
            }

            $expectedRemainingAmount = $this->expectedRemainingAmount($request->project_id);
            if ($request->investedFund <= $expectedRemainingAmount && $request->investedFund >= 0.01) {
                DB::table('projects_investors')->insert([
                    'idUser' => session('user')->idUser,
                    'idProject' => $request->project_id,
                    'investmentFund' => round($request->investedFund, 2),
                    'investmentDate' => Carbon::now()
                ]);

                return redirect()->back()->with('success', true);
            } else if ($request->investedFund < 0.01) {
                return redirect()->back()->with('error_amount_min', true);
            } else {
                return redirect()->back()->with('error_amount_max', $expectedRemainingAmount);
            }
        }
        else {
            return redirect()->back()->with('error_no_project', true);
        }
    }

    public static function isOwnProject($project_id) {
        $project = DB::table('projects')->where('idProject', $project_id)->where('idUser', session('user')->idUser)->first();

        if ($project) {
            return true;
        }
        return false;
    }

    public static function hasDonated($project_id) {
        $investment = DB::table('projects_investors')->where('idProject', $project_id)->where('idUser', session('user')->idUser)->first();

        if ($investment) {
            return true;
        }
        return false;
    }

    public static function isProjectOverdue($project_id) {
        $endDate = DB::table('projects')->where('idProject', $project_id)->first()->projectEndDate;

        return Carbon::parse($endDate)->isPast();
    }

    public static function expectedRemainingAmount($project_id) {
        $project = DB::table('projects')
                    ->where('projects.idProject', $project_id)
                    ->join('projects_investors', 'projects.idProject', '=', 'projects_investors.idProject')
                    ->select('projects.requestedFund', DB::raw("SUM(projects_investors.investmentFund) as totalInvestment"))
                    ->groupBy('projects.requestedFund')
                    ->first();

        if ($project) {
            return $project->requestedFund - $project->totalInvestment;
        }
    }
}
