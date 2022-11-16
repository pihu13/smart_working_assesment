@extends('admin::layouts.master')
@section('admin::content')

<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">Dashboard</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-12 dashboard-wrap">
        <div class="row">
            <div class="col-6 col-lg-3">
                <div class="card">
                    <div class="card-body widgets p-3 d-flex align-items-center">
                        <div class="bg-gradient-muted widget-icon"> <i class="fal fa-users"></i> </div>
                        <div>
                            <div class="text-value text-primary"><h3>{{ @$customers }}</h3></div>
                            <div class="text-muted text-uppercase font-weight-bold small">Total Doctor's</div>
                        </div>
                    </div>
                    <div class="card-footer px-3 py-2">
                        <a class="btn-block text-muted d-flex justify-content-between align-items-center" href="{{ route("admin.customers.list") }}"> 
                            <span class="small font-weight-bold">View More</span> 
                            <i class="fal fa-chevron-right text-muted"></i> 
                        </a>
                    </div>
                </div>
            </div>
          
           
           


            <div class="col-6 col-lg-3">
                <div class="card">
                    <div class="card-body widgets p-3 d-flex align-items-center">
                        <div class="bg-gradient-muted widget-icon"> <i class="fal fa-users"></i> </div>
                        <div>
                            <div class="text-value text-primary"><h3>{{ $subadmins }}</h3></div>
                            <div class="text-muted text-uppercase font-weight-bold small">Total Enquiry</div>
                        </div>
                    </div>
                    <div class="card-footer px-3 py-2">
                        <a class="btn-block text-muted d-flex justify-content-between align-items-center" href="{{ route("admin.user.enquiries") }}"> 
                            <span class="small font-weight-bold">View More</span> <i class="fal fa-chevron-right text-muted"></i> 
                        </a> 
                    </div>
                </div>
            </div>

        </div>
        
    </div>
</div>

@endsection
@section('js')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type='text/javascript'>
jQuery(document).ready(function () {
    jQuery('input.date-range').daterangepicker({
        "startDate": "01/03/2020",
        "endDate": "01/09/2020",
        opens: 'left'
    });
});

google.charts.load('current', {'packages': ['corechart']});
google.charts.setOnLoadCallback(drawChart);

function drawChart() {
    var data = google.visualization.arrayToDataTable([
        ['option', 'revenue'],
        ['Jan', 7000],
        ['Fab', 1200],
        ['March', 2000],
        ['Apr', 720]
    ]);

    var options = {
        curveType: 'function',
        legend: {position: 'bottom'}
    };

    var chart7 = new google.visualization.LineChart(document.getElementById('revenue-chart'));
    chart7.draw(data, options);
}
</script>
@endsection