<?php

namespace App\Http\Controllers\admin;

use DB;
use Carbon\Carbon;
use App\Classes\Table;
use App\Classes\Permission;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;
use App\Attrition;
use GuzzleHttp\Client;

class AttritionController extends Controller
{
    
    public function index() 
    {
        // if (permission::permitted('attendance')=='fail'){ return redirect()->route('denied'); }
        
        $attritions = table::attritions()->orderBy('id', 'desc')->get();

        $time_format = table::settings()->value("time_format");
        
        return view('admin.attrition', ['attritions' => $attritions, 'time_format' => $time_format]);
    }

    public function add()
    {
        if (permission::permitted('attendance')=='fail'){ return redirect()->route('denied'); }

        $employee = table::people()->get();

        $time_format = table::settings()->value("time_format");

        return view('admin.attrition-add', ['employee' => $employee, 'time_format' => $time_format]);
    }

    public function entry(Request $request)
    {
    
        $client = new Client(); //GuzzleHttp\Client
        $url = "http://127.0.0.1:5000/prediction";
        $churn = new Attrition();
      
        $age = request('age');
        $dailyRate=request('daily_rate');
        $distanceFromHome = request('distance_from_home');
        $yearsWithCurrManager = request('years_with_cm');
        $totalWorkingYears = request('total_working_years');
        $hourlyRate = request('hourly_rate');
        $percentSalaryHike = request('percentage_salary_hike');
        $numCompaniesWorked = request('no_of_companies');
        $monthlyIncome = request('monthly_income');
        $monthlyRate = request('monthly_rate');
        
        $params = [
            "Age"=> $age,
            "DailyRate"=>  $dailyRate,
            "DistanceFromHome"=> $distanceFromHome,
            "YearsWithCurrManager"=>  $yearsWithCurrManager,
            "TotalWorkingYears "=> $totalWorkingYears,
            "HourlyRate"=> $hourlyRate,
            "PercentSalaryHike"=> $percentSalaryHike,
            "NumCompaniesWorked"=>  $numCompaniesWorked,
            "MonthlyIncome"=>$monthlyIncome,
            "MonthlyRate"=> $monthlyRate
        ];
        // $churn_params = json_encode($params);
        $headers = [
            
            'Accept' => 'application/json'
        ];
        // $header_params = json_encode($headers);

        $response = $client->request('POST', $url, [
            'headers' => $headers,
            'json'=>$params,
            'verify'  => false,
        ]);

        $responseBody = json_decode($response->getBody(),true);
        // $data =json_decode($responseBody);

        $result = $responseBody['prediction'];
        
        $churn_result = intval($result);
       
        $churn->employee_id=request('name');
        $churn->churn_status=$churn_result;
        $churn->save();
        if($churn_result == 0){

            return redirect()->back()->with('success', 'Employee will not leave the organization');   
            // return redirect()-back()->with('success','Customer is predicted not to churn');
        }else if($churn_result == 1){
            return redirect()-back()->with('success','Employee is forecasted to leave');
        }else{
            return redirect()-back()->with('error','Could not predict attrition!! Try again later..');
        }
    }

    public function delete($id, Request $request)
    {
        if (permission::permitted('attendance-delete')=='fail'){ return redirect()->route('denied'); }

        $id = $request->id;

        table::attendance()->where('id', $id)->delete();

        return redirect('admin/attrition')->with('success', trans("Attendance is successfully deleted"));
    }

    public function filter(Request $request)
    {
        if (permission::permitted('attendance')=='fail'){ return redirect()->route('denied'); }
        
        $v = $request->validate([
            'start' => 'required|max:255',
            'end' => 'required|max:255'
        ]);
        
        $start = $request->start;
        
        $end = $request->end;
        
        $time_format = table::settings()->value("time_format");
        
        $attendance = table::attendance()->whereBetween('date', ["$start", "$end"])->get();
        
        return view('admin.attrition', ['attendance' => $attendance, 'time_format' => $time_format]);
    }
}

