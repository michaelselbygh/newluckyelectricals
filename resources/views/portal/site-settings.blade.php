@extends('layouts.portal.master')

@section('page-title') Settings @endsection

@section('content-body')
    <div class="row">
        <div class="col-md-6">
            <h5 class="card-title">Site Settings</h5>
            @include('portal.success-and-error.message')
            <form method="POST" action="{{ route('manager.process.settings') }}">
                <div class="card">
                    <div class="card-content" style="padding:20px;">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="scrolling_text">Scrolling Text</label>
                                    <input id="scrolling_text" name="scrolling_text"  class="form-control round {{ $errors->has('scrolling_text') ? 'form-control is-invalid' : '' }}" placeholder="Enter text that scrolls at the top of main page" value="{{ $settings[0]['value'] }}" type="text">
                                    @if ($errors->has('scrolling_text'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('scrolling_text') }}</strong>
                                        </div>
                                    @endif 
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-actions" style="text-align:center;">
                                    <button type="submit" name="update_settings" class="btn btn-success">
                                            Update Settings
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection