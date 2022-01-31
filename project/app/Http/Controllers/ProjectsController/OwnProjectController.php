<?php

namespace App\Http\Controllers\ProjectsController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class OwnProjectController extends Controller
{
    public function ownProjectsView() {
        $ownProjects = DB::table('projects')
                        ->where('projects.idUser', session('user')->idUser)
                        ->join('projects_investors', 'projects.idProject', '=', 'projects_investors.idProject')
                        ->join('users', 'projects.idUser', '=', 'users.idUser')
                        ->select('projects.*', 'users.firstname', 'users.lastname',
                            DB::raw("ROUND(SUM(projects_investors.investmentFund), 2) as totalInvestment"))
                        ->groupBy('projects.idProject', 'projects.projectName', 'projects.idUser',
                            'projects.projectDescription', 'projects.projectEndDate', 'projects.projectStartDate',
                            'projects.requestedFund', 'users.firstname', 'users.lastname')
                        ->get();

        if ($ownProjects->first()) {
            $random_projects = AnyProjectController::randomProjects(null);

            if ($random_projects) {
                $investmentsData = array();
                foreach ($ownProjects as $project) {
                    $investments = DB::table('projects_investors')
                        ->where('idProject', $project->idProject)
                        ->join('users', 'projects_investors.idUser', '=', 'users.idUser')
                        ->select('projects_investors.*', 'users.firstname', 'users.lastname')
                        ->orderBy('investmentDate')->get();

                    $investmentsData[$project->idProject] = $investments;
                }

                if ($investments) {
                    View::share("projects", $ownProjects);
                    View::share("random_projects", $random_projects);
                    View::share("investments", $investmentsData);
                    return view('project');
                }
                else {
                    return redirect()->back()->with('error', true);
                }
            }
            else {
                return redirect()->back()->with('error', true);
            }
        }
        else {
            View::share("projects", "no_own_projects");
            return view('project');
        }
    }

    public static function numberOfDonors($idProject) {
        return DB::table('projects_investors')->where('idProject', $idProject)->get()->count();
    }
}
