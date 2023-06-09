@extends('layouts.admin')

@section('meta')
    <title>Attrition Prediction </title>
    <meta name="description">
@endsection

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12 page-header">
            <h2 class="page-title">
                {{ __("Predict New Employee Attrition") }}

                <a href="{{ url('/admin/attrition') }}" class="btn btn-outline-primary btn-sm float-right">
                    <i class="fas fa-arrow-left"></i><span class="button-with-icon">{{ __("Return") }}</span>
                </a>
            </h2>
        </div>
    </div>
 

    <div class="card">
        @if(Session::has('success'))

                   <div class="alert alert-success">
                    {{ Session::get( 'success' ) }}
                   </div>
                   @endif

                    @if(Session::has('error'))
                   <div class="alert alert-danger">
                    {{ Session::get( 'error' ) }}
                   </div>
                   @endif
        
        <form action="{{ url('admin/attrition/add-entry') }}" method="post" class="needs-validation" autocomplete="off" novalidate accept-charset="utf-8">
            @csrf
            <div class="card-header"></div>
            <div class="card-body">
                <div class="form-group">
                  <label for="name">{{ __("Employee") }}</label>
                  <select name="name" class="form-control" required>
                    <option value="" disabled selected>Choose...</option>
                    @isset($employee)
                    @foreach ($employee as $data)
                        <option value="{{ $data->id }}" data-reference="{{ $data->id }}">{{ $data->lastname }}, {{ $data->firstname }}</option>
                    @endforeach
                    @endisset
                  </select>
                </div>
                <div class="form-group">
                    <label for="age">{{ __("Employee Age") }} <small class="text-muted"></small></label>
                    <input type="text" name="age" value="" class="form-control text-uppercase" required>
                </div>
                <div class="form-group">
                    <label for="credits">{{ __("Daily Rate") }} </label>
                    <input type="text" name="daily_rate" value="" class="form-control text-uppercase" required>
                </div>
                <div class="form-group">
                    <label for="credits">{{ __("Hourly Rate") }} </label>
                    <input type="text" name="hourly_rate" value="" class="form-control text-uppercase" required>
                </div>
                <div class="form-group">
                    <label for="credits">{{ __("Distance From Home") }} </label>
                    <input type="text" name="distance_from_home" value="" class="form-control text-uppercase" required>
                </div>
                <div class="form-group">
                    <label for="credits">{{ __("Years With Current Manager") }} </label>
                    <input type="text" name="years_with_cm" value="" class="form-control text-uppercase" required>
                </div>
                <div class="form-group">
                    <label for="credits">{{ __("Total Working Years") }} </label>
                    <input type="text" name="total_working_years" value="" class="form-control text-uppercase" required>
                </div>
                <div class="form-group">
                    <label for="credits">{{ __("Percentage Salary Hike") }} </label>
                    <input type="text" name="percentage_salary_hike" value="" class="form-control text-uppercase" required>
                </div>
                <div class="form-group">
                    <label for="credits">{{ __("Number of Companies Worked") }} <small class="text-muted">e.g. "3" (Companies)</small></label>
                    <input type="text" name="no_of_companies" value="" class="form-control text-uppercase" required>
                </div>
                <div class="form-group">
                    <label for="credits">{{ __("Monthly Income") }} </label>
                    <input type="text" name="monthly_income" value="" class="form-control text-uppercase" required>
                </div>
                <div class="form-group">
                    <label for="credits">{{ __("Monthly Rate") }} <small class="text-muted">e.g. "30" (per cent)</small></label>
                    <input type="text" name="monthly_rate" value="" class="form-control text-uppercase" required>
                </div>
               

              
                
            </div>
            <div class="card-footer text-right">
                <input type="hidden" value="" name="reference">
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check-circle"></i><span class="button-with-icon">{{ __("Predict Attrition") }}</span>
                </button>
                <a href="{{ url('/admin/attrition') }}" class="btn btn-secondary">
                    <i class="fas fa-times-circle"></i><span class="button-with-icon">{{ __("Cancel") }}</span>
                </a>
            </div>
        </form>
    </div>
</div>
@endsection


@section('scripts')
    <script src="{{ asset('/assets/js/validate-form.js') }}"></script>
    <script src="{{ asset('/assets/js/get-employee-id.js') }}"></script>
    <script src="{{ asset('/assets/js/initiate-toast.js') }}"></script>
@endsection