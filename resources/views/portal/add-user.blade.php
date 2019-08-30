@extends('layouts.portal.master')

@section('page-title') Add User @endsection

@section('content-body')
    <div class="row">
        <div class="col-md-5">
            <h5 class="card-title">New Account Details</h5>
            @include('portal.success-and-error.message')
            <div class="card">
                <div class="card-content" style="padding:20px;">
                    <form method="POST" action="{{ route('manager.process.add.user') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="first_name">First Name</label>
                                <input id="first_name" name="first_name"  class="form-control round {{ $errors->has('first_name') ? 'form-control is-invalid' : '' }}" placeholder="Enter user's first name" value="{{ old('first_name') }}" type="text" required>
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
                                    <input id="last_name" name="last_name"  class="form-control round {{ $errors->has('last_name') ? 'form-control is-invalid' : '' }}" placeholder="Enter user's last name" value="{{ old('last_name') }}" type="text" required>
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
                                    <input id="email" name="email"  class="form-control round {{ $errors->has('email') ? 'form-control is-invalid' : '' }}" placeholder="Enter user's email address" value="{{ old('email') }}" type="email" required>
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
                                            <option value="{{ $roles[$i]['id'] }}" {{ old('role') == $roles[$i]['id'] ? 'selected' : '' }}>
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
                                    <button type="submit" name="add_user" class="btn btn-success">
                                            Create Account
                                    </button>
                                </div>
                            </div>
                        </div>
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
@endsection