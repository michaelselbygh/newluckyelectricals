@extends('layouts.portal.master')

@section('page-title') Dashboard @endsection

@section('content-body')
    <div class="row">
        <div class="col-md-8">
            <h5 class="card-title">Quick Access</h5>
            @include('portal.success-and-error.message')
            <div class="card" style="min-height: 450px">
                <div class="card-content" style="padding:20px;">
                    <div class="row">
                        <div class="col-md-3" style="text-align:center;">
                            <div class="card pull-up" style="background-color:#197852; text-align:center; width:150px; height:120px; display:inline-block; margin-bottom:5rem;">
                                <a href="{{ route('manager.show.products') }}">
                                <div class="card-content">
                                    <i class="la la-gift" style="font-size: 60px; margin-top: 15px; color: #fff;"></i>
                                    <h6 style="color:white; padding-bottom:10px;">Products</h6>
                                </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-3" style="text-align:center;">
                            <div class="card pull-up" style="background-color:#197852; text-align:center; width:150px; height:120px; display:inline-block; margin-bottom:5rem;">
                                <a href="{{ route('manager.show.categories') }}">
                                <div class="card-content">
                                    <i class="la la-sitemap" style="font-size: 60px; margin-top: 15px; color: #fff;"></i>
                                    <h6 style="color:white; padding-bottom:10px;">Categories</h6>
                                </div>
                                </a>
                            </div>
                        </div>
                        @if (Auth::user()->role <= 2)
                            <div class="col-md-3" style="text-align:center;">
                                <div class="card pull-up" style="background-color:#197852; text-align:center; width:150px; height:120px; display:inline-block; margin-bottom:5rem;">
                                    <a href="{{ route('manager.show.users') }}">
                                    <div class="card-content">
                                        <i class="la la-users" style="font-size: 60px; margin-top: 15px; color: #fff;"></i>
                                        <h6 style="color:white; padding-bottom:10px;">Users</h6>
                                    </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3" style="text-align:center;">
                                <div class="card pull-up" style="background-color:#197852; text-align:center; width:150px; height:120px; display:inline-block; margin-bottom:5rem;">
                                    <a href="{{ route("manager.show.settings") }}">
                                    <div class="card-content">
                                        <i class="la la-gears" style="font-size: 60px; margin-top: 15px; color: #fff;"></i>
                                        <h6 style="color:white; padding-bottom:10px;">Site Settings</h6>
                                    </div>
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection