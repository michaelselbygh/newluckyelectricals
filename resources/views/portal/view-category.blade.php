@extends('layouts.portal.master')

@section('page-title') Update Category @endsection

@section('content-body')
    <div class="row">
        <div class="col-md-5">
            <div class="row">
                <div class="col-md-8" style="margin-top : 10px;">
                    <h5 class="card-title">Update category, {{ $category['description'] }}</h5>
                </div>
                <div class="col-md-4" style="text-align: right; margin-bottom:5px;">
                    @if (sizeof($category["children"]) == 0 and sizeof($category["products"]) == 0)
                        <form method="POST" action="{{ route('manager.process.category', $category['id']) }}">
                                @csrf
                                <button  data-toggle="tooltip" data-popup="tooltip-custom" data-original-title="Delete Category" style="margin-top: 3px;" class="btn btn-danger btn-sm round">
                                    <i class="ft-trash"></i>
                                </button>
                                <input type="hidden" name="category_action" value="delete"/>
                        </form>
                    @endif
                </div>
            </div>
            @include('portal.success-and-error.message')
            <div class="card">
                <div class="card-content" style="padding:20px;">
                    <form method="POST" action="{{ route('manager.process.category', $category['id']) }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <input id="description" name="description"  class="form-control round {{ $errors->has('description') ? 'form-control is-invalid' : '' }}" placeholder="Enter category description" value="{{ $category['description'] }}" type="text" required>
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
                                            <option value="{{ $parents[$i]['id'] }}" {{ $category['parent']["id"] == $parents[$i]['id'] ? 'selected' : '' }}>
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
                                            Update Category
                                    </button>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="category_action" value="update"/>
                    </form>
                </div>
            </div>
            @if (sizeof($category['children']) > 0)
                <h5 class="card-title">Sub-Categories</h5>
                <div class="card">
                    <div class="card-content" style="padding:20px;">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-striped table-bordered zero-configuration" id="delivery-items">
                                    <thead>
                                        <tr>
                                            <th>Description</th>
                                            <th style="min-width: 100px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i = 0; $i < sizeof($category['children']); $i++)
                                        <tr>
                                                <td>{{ $category['children'][$i]["description"] }}</td>
                                                <td>
                                                    <a href="{{ route('manager.show.category', $category['children'][$i]["id"]) }}">
                                                        <button data-toggle="tooltip" data-popup="tooltip-custom" data-original-title="View {{ $category['children'][$i]["description"] }}"   class="btn btn-info btn-sm round">
                                                            <i class="ft-eye"></i>
                                                        </button>
                                                    </a>
                                                    <button onclick="submitChildDeleteForm('{{ $category['children'][$i]['id'] }}')" data-toggle="tooltip" data-popup="tooltip-custom" data-original-title="Delete Category {{ $category['children'][$i]['description'] }}" class="btn btn-danger btn-sm round">
                                                        <i class="ft-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        @if ($category["level"] == 2)
            <div class="col-md-7">
                <h5 class="card-title">Products under category, {{ $category['description'] }}</h5>
                <div class="card">
                    <div class="card-content" style="padding:20px;">
                        <table class="table table-striped table-bordered zero-configuration" id="delivery-items">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Preview</th>
                                    <th style="min-width: 100px;">Action</th>
    
                                </tr>
                            </thead>
                            <tbody>
                                @for ($i = 0; $i < sizeof($category['children']); $i++)
                                <tr>
                                        <td>{{ $category['products'][$i]["id"] }}</td>
                                        <td>{{ $category['products'][$i]["name"] }}</td>
                                        <td>Product Preview</td>
                                        <td>
                                            <a href="{{ route('manager.show.product', $category['products'][$i]["id"]) }}">
                                                <button data-toggle="tooltip" data-popup="tooltip-custom" data-original-title="View {{ $category['products'][$i]["name"] }}"   class="btn btn-info btn-sm round">
                                                    <i class="ft-eye"></i>
                                                </button>
                                            </a>
                                        </td>
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> 
        @endif
    </div>

    <form id="child-delete-form" method="POST" action="{{ route('manager.process.category', $category['id']) }}">
        @csrf
        <input type="hidden" name="child_id" id="child_id" />
        <input id="category_action" type="hidden" name="category_action"  value="delete_child"/>
    </form>

    <script>
        function submitChildDeleteForm(childID)
        {
            document.getElementById('child_id').value = childID;
            document.getElementById('child-delete-form').submit(); 
        } 
    </script>
@endsection