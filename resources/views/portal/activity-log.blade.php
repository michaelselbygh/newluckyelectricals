@extends('layouts.portal.master')

@section('page-title') Activity Log @endsection

@section('content-body')
    <section id="configuration">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-md-6"  style="margin-top:20px;">
                        <h5 class="card-title">Activity Log - {{ date('l jS \of F Y') }} as at {{ date("g:i a") }}</h5>
                    </div>
                    <div class="col-md-6" style="text-align: right; margin-bottom:10px;">
                        <form method="POST" action="{{ route('manager.process.activity-log') }}">
                            @csrf
                            <button class="btn btn-info" type="submit">
                                Generate Export and Clear Log
                            </button>
                        </form>
                    </div>
                </div>
                @include('portal.success-and-error.message')
                <div class="card">
                    <div class="card-content collapse show">
                        <div class="card-body card-dashboard">
                            <table class="table table-striped table-bordered zero-configuration" id="activity">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Type</th>
                                        <th>Causer Type</th>
                                        <th>Causer ID</th>
                                        <th>Subject</th>
                                        <th>Description</th>
                                        <th>Time</th>
                                    </tr>
                                </thead>
                                <tbody> 
                                    @for($i=0; $i<sizeof($activity); $i++) 
                                        <tr>
                                            <td>{{ $activity[$i]["id"] }}</td>
                                            <td>{{ $activity[$i]["log_name"] }}</td>
                                            <td>{{ $activity[$i]["causer_type"] }}</td>
                                            <td>{{ $activity[$i]["causer_id"] }}</td>
                                            <td>{{ $activity[$i]["subject_type"] }}</td>
                                            <td>{{ $activity[$i]["description"] }}</td>
                                            <td>{{ $activity[$i]["created_at"] }}</td>
                                        </tr>
                                    @endfor
                                </tbody>
                                <tfoot>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <script>
    $(document).ready(function(){
        $('#activity').dataTable( {
            "order": [
                [0, 'desc']
            ]
        } );
    })
    </script>
@endsection