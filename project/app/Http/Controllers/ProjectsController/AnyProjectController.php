<?php

namespace App\Http\Controllers\ProjectsController;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DonationController\DonationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class AnyProjectController extends Controller
{
    public function projectView($idProject) {
        $project = DB::table('projects')
                    ->where('projects.idProject', $idProject)
                    ->join('projects_investors', 'projects.idProject', '=', 'projects_investors.idProject')
                    ->join('users', 'projects.idUser', '=', 'users.idUser')
                    ->select('projects.*', 'users.firstname', 'users.lastname',
                            DB::raw("ROUND(SUM(projects_investors.investmentFund), 2) as totalInvestment"))
                    ->groupBy('projects.idProject', 'projects.projectName', 'projects.idUser',
                                    'projects.projectDescription', 'projects.projectEndDate', 'projects.projectStartDate',
                                    'projects.requestedFund', 'users.firstname', 'users.lastname')
                    ->get();

        if ($project->first() && ! DonationController::isOwnProject($project->first()->idProject)) {
            $random_projects = self::randomProjects($idProject);

            if ($random_projects) {
                View::share("projects", $project);
                View::share("random_projects", $random_projects);
                return view('project');
            }
            else {
                return redirect()->back()->with('error', true);
            }
        }
        else {
            return redirect()->back()->with('error_no_project', true);
        }
    }

    public static function randomProjects($idProject) {
        return DB::table('projects')
                ->where('idProject', '!=', $idProject)
                ->where('idUser', '!=', session('user')->idUser)
                ->inRandomOrder()->limit(3)->get();
    }

    public static function getNameOfOwner($idUser) {
        $names = DB::table('users')->where('idUser', $idUser)->select('firstname', 'lastname')->first();
        return $names->firstname . ' ' . $names->lastname;
    }
}
