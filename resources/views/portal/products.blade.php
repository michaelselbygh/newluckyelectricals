@extends('layouts.portal.master')

@section('page-title') Products @endsection

@section('content-body')
    <section id="configuration">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <h5 class="card-title">Manage Products</h5>
                </div>
                @include('portal.success-and-error.message')
                <div class="card">
                    <div class="card-content collapse show">
                        <div class="card-body card-dashboard">
                            <table class="table table-striped table-bordered zero-configuration" id="products">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Variations</th>
                                        <th>Category</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody> 
                                    @for($i=0; $i<sizeof($products); $i++) 
                                        <tr>
                                            <td>{{ $products[$i]["id"] }}</td>
                                            <td>{{ $products[$i]["name"] }}</td>
                                            <td>
                                                <ul class="list-unstyled users-list m-0">
                                                    @for ($j = 0; $j < sizeof($products[$i]["skus"]); $j++)
                                                        <li data-toggle="tooltip" data-popup="tooltip-custom" data-original-title="{{ $products[$i]["skus"][$j]["description"] }}" class="avatar avatar-sm pull-up">
                                                            <img class="media-object rounded-circle no-border-top-radius no-border-bottom-radius"
                                                            src="{{ url("app/assets/img/products/thumbnail/".$products[$i]["skus"][$j]["images"][0]["path"].".jpg") }}"
                                                            alt="{{ $products[$i]["skus"][$j]["description"] }}">
                                                        </li>
                                                    @endfor
                                                </ul>
                                            </td>
                                            <td>{{ $products[$i]["category"]["description"] }}</td>
                                            <td>
                                                <a href="{{ route('manager.show.product', $products[$i]["slug"]) }}">
                                                    <button data-toggle="tooltip" data-popup="tooltip-custom" data-original-title="View {{ $products[$i]["name"] }}"  style="margin-top: 3px;" class="btn btn-info btn-sm round">
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
        $('#products').dataTable( {
            "order": [
                [1, 'asc']
            ]
        } );
    })
    </script>
@endsection