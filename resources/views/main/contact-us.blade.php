@extends('layouts.main.master')
@section('page-title') Let's get Talking @endsection
@section('page-content')
    <h3 style="text-align: center">Send Us A Message</h3>
    <section class="so-spotlight1" style="min-height: 450px;">

        <div class="container">
            <div class="row">

                <div class="info-contact clearfix">
                    <div class="col-lg-3 col-sm-3 col-xs-12 info-store">
                    </div>
                    <div class="col-lg-6 col-sm-6 col-xs-12 contact-form">
                        @include('main.success-and-error.message')
                        <form action="{{ route("contact-us") }}" method="post" enctype="multipart/form-data" class="form-horizontal">
                            @csrf
                            <fieldset>
                                <div class="form-group required">
                                    <div class="col-sm-12">
                                        <label class="control-label" for="input-name">Your Name</label>
                                        <input type="text" name="name" value="{{ old('name') }}" id="input-name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}">
                                        @if ($errors->has('name'))
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <div class="col-sm-12">
                                        <label class="control-label" for="input-email">E-Mail Address</label>
                                        <input type="text" name="email" value="{{ old('email') }}" id="input-email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}">
                                        @if ($errors->has('email'))
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <div class="col-sm-12">
                                        <label class="control-label" for="input-enquiry">Enquiry</label>
                                        <textarea name="enquiry" rows="10" id="input-enquiry" class="form-control {{ $errors->has('enquiry') ? 'is-invalid' : '' }}">{{ old('enquiry') }}</textarea>
                                        @if ($errors->has('enquiry'))
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->first('enquiry') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </fieldset>
                            <div class="buttons">
                                <div style="text-align:center;">
                                    <button class="btn btn-default buttonGray" type="submit">
                                        <span>Submit</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <br><br>
        </div>  
    </section>

    <br>
@endsection