@extends('layouts.admin')

@section('meta')
    <title>Attrition Prediction </title>
    <meta name="description" content="Workday Attendance">
@endsection

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12 page-header">
            <h2 class="page-title">
                {{ __("Attrition Prediction") }}

                <a href="{{ url('/admin/attrition/manual-entry') }}" class="btn btn-outline-primary btn-sm float-right">
                    <i class="fas fa-plus"></i><span class="button-with-icon">{{ __("Predict New Employee Attrition") }}</span>
                </a>
                {{-- <a href="{{ url('/webclock') }}" target="_blank" class="btn btn-outline-success btn-sm mr-2 float-right">
                    <i class="fas fa-clock"></i><span class="button-with-icon">{{ __("Web Clock") }}</span>
                </a> --}}
            </h2>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ url('admin/attendance') }}" method="post" class="form-inline responsive-filter-form needs-validation mb-2" novalidate autocomplete="off" accept-charset="utf-8">
                @csrf
                <div class="form-group">
                    <label for="start" class="mr-2">{{ __("Date Range") }}</label>
                    <input name="start" type="date" class="form-control form-control-sm mr-1" value="" required>
                </div>
                <div class="form-group">
                    <input name="end" type="date" class="form-control form-control-sm mr-1" value="" required>
                </div>
                <div class="form-group">
                    <button class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-filter"></i><span class="button-with-icon">{{ __("Filter") }}</span>
                    </button>
                </div>
            </form>

            <table width="100%" class="table datatables-table custom-table-ui" data-order='[[ 0, "desc" ]]'>
                <thead>
                    <tr>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('Employee') }}</th>
                       
                        <th>{{ __('Status') }} ({{ __("Churn") }}/{{ __("Not Churn") }})</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @isset($attritions)
                    @foreach ($attritions as $data)
                    <tr>
                        <td>{{ $data->created_at }}</td>
                        <td>{{ $data->employee_id }}</td>
                        <td>
                            @php 
                                if($data->churn_status == 0) {
                                   echo "NOT CHURN";
                                } else {
                                  echo "CHURN"; 
                                }
                            @endphp
                        </td>
                   
                        <td class="text-right">
                            <a href="{{ url('/admin/attendance/delete') }}/{{ $data->id }}" class="btn btn-outline-secondary btn-sm btn-rounded"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    @endforeach
                    @endisset
                </tbody>
            </table>
            <small class="text-muted">{{ __("Only 250 recent records will be displayed use Date range filter to get more records") }}</small>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/initiate-datatables.js') }}"></script> 
    <script src="{{ asset('/assets/js/initiate-toast.js') }}"></script>
@endsection