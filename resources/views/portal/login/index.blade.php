@extends('layouts.portal.login')

@section('login')
<form class="form-horizontal form-simple" action="{{ route('manager.process.login') }}" method="POST" >
        @csrf
        @include('portal.success-and-error.message')
        <fieldset class="form-group position-relative has-icon-left mb-0">
            <input type="text" name="email" class="form-control form-control-lg input-lg" id="user-name" placeholder="Enter Your Email"
                required>
            <div class="form-control-position">
                <i class="ft-user"></i>
            </div>
        </fieldset>
        <fieldset class="form-group position-relative has-icon-left">
            <input type="password" name="password" class="form-control form-control-lg input-lg" id="user-password"
                placeholder="Enter Your Password" required>
            <div class="form-control-position">
                <i class="la la-key"></i>
            </div>
        </fieldset>
        <div class="form-group row">
        </div>
        <button type="submit" name="login_manager" class="btn btn-info btn-lg btn-block" style="background-color: #197852 !important; border-color: #197852 !important; border-radius: 5px;"><i class="ft-unlock"></i> Login</button>
    </form>
@endsection