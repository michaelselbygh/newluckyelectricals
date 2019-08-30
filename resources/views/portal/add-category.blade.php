@extends('layouts.portal.master')

@section('page-title') Add Category @endsection

@section('content-body')
    <div class="row">
        <div class="col-md-5">
            <div class="row">
                <div class="col-md-8" style="margin-top : 10px;">
                    <h5 class="card-title">Add Category</h5>
                </div>
                <div class="col-md-4" style="text-align: right; margin-bottom:5px;">
                </div>
            </div>
            @include('portal.success-and-error.message')
            <div class="card">
                <div class="card-content" style="padding:20px;">
                    <form method="POST" action="{{ route('manager.process.add.category') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <input id="description" name="description"  class="form-control round {{ $errors->has('description') ? 'form-control is-invalid' : '' }}" placeholder="Enter category description" value="" type="text" required>
                                    @if ($errors->has('description'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('description') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="parent">Parent</label>
                                    <select class="form-control {{ $errors->has('parent') ? 'form-control is-invalid' : '' }}" name='parent' style='border-radius:7px;' required>
                                        <option value="0">
                                            None
                                        </option>
                                        @if (!is_null($parents))
                                        @for ($i = 0; $i < sizeof($parents) ; $i++)
                                            <option value="{{ $parents[$i]['id'] }}" {{ old('parent')== $parents[$i]['id'] ? 'selected' : '' }}>
                                                {{ $parents[$i]["description"] }}
                                            </option>
                                        @endfor
                                        @endif
                                    </select>
                                        @if ($errors->has('parent'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('parent') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-actions" style="text-align:center;">
                                    <button type="submit" name="update_details" class="btn btn-success">
                                            Add Category
                                    </button>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="category_action" value="update"/>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection