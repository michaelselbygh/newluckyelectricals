@extends('layouts.portal.master')

@section('page-title') My Account @endsection

@section('content-body')
    <div class="row">
        <div class="col-md-4">
            <h5 class="card-title">My Account Details</h5>
            <div class="card">
                <div class="card-content" style="padding:20px;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="first_name">First Name</label>
                                <input id="first_name" name="first_name"  class="form-control round" placeholder="Enter your first name" value="{{ $user['first_name'] }}" type="text" disabled> 
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="last_name">Last Name</label>
                                <input id="last_name" name="last_name"  class="form-control round" placeholder="Enter your last name" value="{{ $user['last_name'] }}" type="text" disabled> 
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input id="email" name="email"  class="form-control round" placeholder="Enter email address" value="{{ $user['email'] }}" type="text" disabled> 
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="role">Role</label>
                            <input id="role" name="role"  class="form-control round" value="{{ $user['role']['description'] }}" type="text" disabled> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <h5 class="card-title">Change Password</h5>
            <div class="card">
                <div class="card-content" style="padding:20px;">
                    @include('portal.success-and-error.message')
                    <form method="POST" action="{{ route('manager.process.account') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="current_password">Current Password</label>
                                    <input id="current_password" name="current_password"  class="form-control round {{ $errors->has('current_password') ? 'form-control is-invalid' : '' }}" placeholder="Enter current password" value="" type="password" required> 
                                    @if ($errors->has('current_password'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('current_password') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="new_password">New Password</label>
                                    <input id="new_password" name="new_password"  class="form-control round {{ $errors->has('new_password') ? 'form-control is-invalid' : '' }}" placeholder="Enter new password" value="" type="password" required> 
                                    @if ($errors->has('new_password'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('new_password') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-actions" style="text-align:center;">
                                    <button type="submit" name="update_password" class="btn btn-success">
                                            Update Password
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection