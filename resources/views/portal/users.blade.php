@extends('layouts.portal.master')

@section('page-title') Users @endsection

@section('content-body')
    <section id="configuration">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <h5 class="card-title">Manage Users</h5>
                </div>
                @include('portal.success-and-error.message')
                <div class="card">
                    <div class="card-content collapse show">
                        <div class="card-body card-dashboard">
                            <table class="table table-striped table-bordered zero-configuration" id="users">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>State</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody> 
                                    @for($i=0; $i<sizeof($users); $i++) 
                                        <tr>
                                            <td>{{ $users[$i]["id"] }}</td>
                                            <td>{{ $users[$i]["first_name"]." ".$users[$i]["last_name"] }}</td>
                                            <td>{{ $users[$i]["email"] }}</td>
                                            <td>{{ $users[$i]["role"]["name"]." - ".$users[$i]["role"]["description"]  }}</td>
                                            <td><b>{!! $users[$i]["state"]["html_description"] !!}</b></td>
                                            <td>
                                                <a href="{{ route('manager.show.user', $users[$i]["id"]) }}">
                                                    <button data-toggle="tooltip" data-popup="tooltip-custom" data-original-title="View {{ $users[$i]["first_name"] }}"  style="margin-top: 3px;" class="btn btn-info btn-sm round">
                                                        <i class="ft-eye"></i>
                                                    </button>
                                                </a>
                                            </td>
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
        $('#users').dataTable( {
            "order": [
                [0, 'asc']
            ]
        } );
    })
    </script>
@endsection