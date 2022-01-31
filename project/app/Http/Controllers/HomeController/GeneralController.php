<?php

namespace App\Http\Controllers\HomeController;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class GeneralController extends Controller
{
    public function index() {
        $projects = DB::table('projects')
                    ->join('projects_investors', 'projects.idProject', '=', 'projects_investors.idProject')
                    ->join('users', 'projects.idUser', '=', 'users.idUser')
                    ->select('projects.idProject', 'projects.projectName', 'projects.idUser',
                                    'projects.projectDescription', 'projects.projectEndDate', 'projects.requestedFund',
                                    'users.firstname', 'users.lastname', DB::raw("ROUND(SUM(projects_investors.investmentFund), 2) as totalInvestment"))
                    ->groupBy('projects.idProject', 'projects.projectName', 'projects.idUser',
                                    'projects.projectDescription', 'projects.projectEndDate', 'projects.requestedFund',
                                    'users.firstname', 'users.lastname')
                    ->get();
        View::share("projects", $projects);
        return view('index');
    }

    public static function hasAlreadyDonated($user_id, $project_id) {
        $data = DB::table('projects_investors')
                ->where('idProject', $project_id)
                ->where('idUser', $user_id)->first();

        if ($data) {
            return true;
        }
        return false;
    }

    public static function daysLeft($endDate) {
        return Carbon::parse($endDate)->diffInDays(Carbon::now())+1;
    }
}
