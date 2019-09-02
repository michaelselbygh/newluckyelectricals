@extends('layouts.portal.master')

@section('page-title') Update User @endsection

@section('content-body')
    <div class="row">
        <div class="col-md-5">
            
            <div class="row">
                <div class="col-md-8" style="margin-top : 10px;">
                    <h5 class="card-title">Update {{ $user['first_name'] }}'s' Details ( <b>{!! $user['state']['html_description'] !!}</b> )</h5>
                </div>
                <div class="col-md-4" style="text-align: right; margin-bottom:5px;">
                    @switch($user["state"]["id"])
                        @case(1)
                            {{-- Active | Reset Password, Deactivate, Delete--}}
                            <button onclick="submitUserAction('reset_password')" data-toggle="tooltip" data-popup="tooltip-custom" data-original-title="Reset {{ $user['first_name'] }}'s password" style="margin-top: 3px;" class="btn btn-info btn-sm round">
                                <i class="ft-lock"></i>
                            </button>
                            <button onclick="submitUserAction('deactivate')" data-toggle="tooltip" data-popup="tooltip-custom" data-original-title="Deactivate {{ $user['first_name'] }}'s account" style="margin-top: 3px;" class="btn btn-warning btn-sm round">
                                <i class="ft-alert-triangle"></i>
                            </button>
                            <button onclick="submitUserAction('delete')" data-toggle="tooltip" data-popup="tooltip-custom" data-original-title="Delete {{ $user['first_name'] }}'s account" style="margin-top: 3px;" class="btn btn-danger btn-sm round">
                                <i class="ft-trash"></i>
                            </button>
                            @break
                        @case(2)
                            {{-- Inactive | Activate, Delete--}}
                            <button onclick="submitUserAction('activate')" data-toggle="tooltip" data-popup="tooltip-custom" data-original-title="Activate {{ $user['first_name'] }}'s account" style="margin-top: 3px;" class="btn btn-success btn-sm round">
                                <i class="ft-check"></i>
                            </button>
                            <button onclick="submitUserAction('delete')" data-toggle="tooltip" data-popup="tooltip-custom" data-original-title="Delete {{ $user['first_name'] }}'s account" style="margin-top: 3px;" class="btn btn-danger btn-sm round">
                                <i class="ft-trash"></i>
                            </button>
                            @break
                        @default
                        {{-- Deleted | Do nothing --}}
                    @endswitch
                </div>
            </div>
            @include('portal.success-and-error.message')
            <div class="card">
                <div class="card-content" style="padding:20px;">
                    <form method="POST" action="{{ route('manager.process.user', $user['id']) }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="first_name">First Name</label>
                                    <input id="first_name" name="first_name"  class="form-control round {{ $errors->has('first_name') ? 'form-control is-invalid' : '' }}" placeholder="Enter user's first name" value="{{ $user['first_name'] }}" type="text" required>
                                    @if ($errors->has('first_name'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('first_name') }}</strong>
                                        </div>
                                    @endif 
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="last_name">Last Name</label>
                                    <input id="last_name" name="last_name"  class="form-control round {{ $errors->has('last_name') ? 'form-control is-invalid' : '' }}" placeholder="Enter user's last name" value="{{ $user['last_name'] }}" type="text" required>
                                     @if ($errors->has('last_name'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('last_name') }}</strong>
                                        </div>
                                    @endif 
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input id="email" name="email"  class="form-control round {{ $errors->has('email') ? 'form-control is-invalid' : '' }}" placeholder="Enter user's email address" value="{{ $user['email'] }}" type="email" required>
                                     @if ($errors->has('email'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </div>
                                    @endif 
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="role">Role</label>
                                    <select class="form-control {{ $errors->has('role') ? 'form-control is-invalid' : '' }}" name='role' style='border-radius:7px;' required>
                                        @for ($i = sizeof($roles)-1; $i >= 0 ; $i--)
                                            <option value="{{ $roles[$i]['id'] }}" {{ $user['role']["id"] == $roles[$i]['id'] ? 'selected' : '' }}>
                                                {{ $roles[$i]["name"] }}
                                            </option>
                                        @endfor
                                    </select>
                                     @if ($errors->has('role'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('role') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-actions" style="text-align:center;">
                                    <button type="submit" name="update_details" class="btn btn-success">
                                            Update Account
                                    </button>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="user_action" value="update"/>
                    </form>
                </div>
            </div>
            <h5 class="card-title">Role Guide</h5>
            <div class="card">
                <div class="card-content" style="padding:20px;">
                    <div class="row">
                        <div class="col-md-12">
                            @for ($i = 0; $i < sizeof($roles); $i++)
                                <div class="row">
                                    <div class="col-md-4">
                                        <p style="font-weight:500;"> {{ $roles[$i]["name"] }} </p>
                                    </div>
                                    <div class="col-md-8" >
                                        <p>{{ $roles[$i]["description"] }}</p>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="user-action-form" method="POST" action="{{ route("manager.process.user", $user['id']) }}">
        @csrf
        <input type="hidden" name="user_action" id="user_action"/>
    </form>

    <script>
        function submitUserAction(user_do)
        {
            document.getElementById('user_action').value = user_do;
            document.getElementById('user-action-form').submit(); 
        } 
    </script>
@endsection