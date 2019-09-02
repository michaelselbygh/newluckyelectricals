@extends('layouts.portal.master')

@section('page-title') Categories @endsection

@section('content-body')
    <section id="configuration">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <h5 class="card-title">Manage Categories</h5>
                </div>
                @include('portal.success-and-error.message')
                <div class="card">
                    <div class="card-content collapse show">
                        <div class="card-body card-dashboard">
                            <table class="table table-striped table-bordered zero-configuration" id="users">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Description</th>
                                        <th>Parent</th>
                                        <th>Sub-Categories</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody> 
                                    @for($i=0; $i<sizeof($categories); $i++) 
                                        <tr>
                                            <td>{{ $categories[$i]["id"] }}</td>
                                            <td>{{ $categories[$i]["description"] }}</td>
                                            <td>
                                                @if (!is_null($categories[$i]["parent"]))
                                                    {{ $categories[$i]["parent"]["description"] }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if (sizeof($categories[$i]["children"]) > 0)
                                                    {{ $categories[$i]["children"][0]["description"] }}
                                                    @for ($j = 1; $j < sizeof($categories[$i]["children"]); $j++)
                                                        , {{ $categories[$i]["children"][$j]["description"] }}
                                                    @endfor
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('manager.show.category', $categories[$i]["slug"]) }}">
                                                    <button data-toggle="tooltip" data-popup="tooltip-custom" data-original-title="View {{ $categories[$i]["description"] }}"  style="margin-top: 3px;" class="btn btn-info btn-sm round">
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
                [1, 'asc']
            ]
        } );
    })
    </script>
@endsection