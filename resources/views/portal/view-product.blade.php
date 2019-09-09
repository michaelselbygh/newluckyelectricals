@extends('layouts.portal.master')

@section('page-title') {{ $product['name'] }} @endsection

@section('content-body')
    <div class="row">
        <div class="col-md-7">
            <div class="row">
                <div class="col-md-8" style="margin-top : 10px;">
                    <h5 class="card-title">Edit {{ $product['name'] }} Details</h5>
                </div>
                <div class="col-md-4" style="text-align: right; margin-bottom:5px;">
                    <form method="POST" action="{{ route('manager.process.product', $product['slug']) }}" enctype="multipart/form-data">
                        @csrf
                        <button type="submit" data-toggle="tooltip" data-popup="tooltip-custom" data-original-title="Delete {{ $product['name'] }}"  style="margin-top: 3px;" class="btn btn-danger btn-sm round">
                            <i class="ft-trash"></i>
                        </button>
                        <input type="hidden" name="product_action" value="delete_product"/>
                    </form>
                </div>
            </div>
            @include('portal.success-and-error.message')
            <div class="card">
                <div class="card-content" style="padding:20px;">
                    <form method="POST" action="{{ route('manager.process.product', $product['slug']) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input id="name" name="name"  class="form-control round {{ $errors->has('name') ? 'form-control is-invalid' : '' }}" placeholder="Enter product name" value="{{ $product['name'] }}" type="text" required>
                                    @if ($errors->has('name'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description">Description (Optional)</label>
                                    <textarea id="description" name="description" class="form-control round {{ $errors->has('description') ? 'form-control is-invalid' : '' }}" placeholder="Enter Product Description">{{ $product['description'] }}</textarea>
                                    @if ($errors->has('description'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('description') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="features">Highlighted Features</label>
                                    <textarea id="features" name="features" class="form-control round {{ $errors->has('features') ? 'form-control is-invalid' : '' }}" placeholder="Enter Product Highlighted Features" required>{{ $product['features'] }}</textarea>
                                    @if ($errors->has('features'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('features') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category">Category</label>
                                    <select class="form-control {{ $errors->has('category') ? 'form-control is-invalid' : '' }}" name='category' style='border-radius:7px;' required>
                                        <option value="">
                                            None
                                        </option>
                                        @if (!is_null($product["category_options"]))
                                            @for ($i = 0; $i < sizeof($product["category_options"]) ; $i++)
                                                <option value="{{ $product["category_options"][$i]['id'] }}" {{ $product['category']['id'] == $product["category_options"][$i]['id'] ? 'selected' : '' }}>
                                                    {{ $product["category_options"][$i]["description"] }}
                                                </option>
                                            @endfor
                                        @endif
                                    </select>
                                        @if ($errors->has('category'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('category') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tags">Tags (Optional but helps in Search)</label>
                                    <input id="tags" tags="tags"  class="form-control round {{ $errors->has('tags') ? 'form-control is-invalid' : '' }}" placeholder="Enter product tags" value="{{ $product['tags'] }}" type="text">
                                    @if ($errors->has('tags'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('tags') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-actions" style="text-align:center;">
                                    <input type="hidden" name="product_action" value="update_details" />
                                    <button type="submit" name="add_product" class="btn btn-success">
                                            Update Product Details
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="row">
                <div class="col-md-6" style="margin-top: 10px;">
                    <h5 class="card-title">Stock</h5>
                </div>
                <div class="col-md-6" style="text-align:right; margin-bottom: 5px;">
                    <button type="button" data-toggle="tooltip" id="addVariation" data-popup="tooltip-custom" data-original-title="Add Variation" style="margin-top: 3px;" class="btn btn-info btn-sm round">
                        <i class="ft-plus"></i>
                    </button>
                </div>
            </div>
            <form method="POST" action="{{ route('manager.process.product', $product['slug']) }}" enctype="multipart/form-data">
                @csrf
                <div class="card hidescroll">
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <div class="form-body">
                                <div id="variations">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group" style="margin-bottom: 2px;">
                                                <label for="name">Description</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group" style="margin-bottom: 2px;">
                                                <label for="name">Quantity</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <input class="form-control round" name='variantDescription0' value="{{ $product['skus'][0]['description'] }}" type="text" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input class="form-control round" name='stock0' value="{{ $product['skus'][0]['stock_left'] }}" min="0" type="number" >
                                            </div>
                                        </div>
                                        <input type='hidden' name='sku0' value="{{ $product["skus"][0]["id"] }}">
                                    </div>
                                    <table id="" class="table table-hover table-xl mb-0">
                                        <thead>
                                            <tr>
                                                <th class="border-top-0">Image ID</th>
                                                <th class="border-top-0">Image Preview</th>
                                                <th class="border-top-0">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @for($i=0; $i < sizeof($product["skus"][0]["images"]); $i++) 
                                                <tr>
                                                    <td>{{ $product["skus"][0]["images"][$i]["id"] }}</td>
                                                    <td>
                                                        <ul class="list-unstyled users-list m-0">
                                                            <li data-toggle="tooltip" data-popup="tooltip-custom" data-original-title="{{ $product["skus"][0]["images"][$i]["path"].".jpg" }}" class="avatar avatar-sm pull-up">
                                                                <img class="media-object rounded-circle no-border-top-radius no-border-bottom-radius"
                                                                src="{{ url("app/assets/img/products/thumbnail/".$product["skus"][0]["images"][$i]["path"].".jpg") }}"
                                                                alt="{{ $product["skus"][0]["images"][$i]["path"].".jpg" }}">
                                                            </li>
                                                        </ul>
                                                    </td>
                                                    <td>
                                                        {{-- View and delete --}}
                                                        <a target="_blank" href="{{ url("app/assets/img/products/main/".$product["skus"][0]["images"][$i]["path"].".jpg") }}">
                                                            <button type="button" data-toggle="tooltip" data-popup="tooltip-custom" data-original-title="View {{ $product["skus"][0]["images"][$i]["path"].".jpg" }}"  style="margin-top: 3px;" class="btn btn-info btn-sm round">
                                                                <i class="ft-eye"></i>
                                                            </button>
                                                        </a>
                                                        @if (sizeof($product["skus"][0]["images"]) > 1)
                                                            <button onclick="submitImageDelete({{ $product['skus'][0]['images'][$i]['id'] }})" type="button" data-toggle="tooltip" data-popup="tooltip-custom" data-original-title="Delete {{ $product["skus"][0]["images"][$i]["path"].".jpg" }}"  style="margin-top: 3px;" class="btn btn-danger btn-sm round">
                                                                <i class="ft-trash"></i>
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                    <input type="file" class="form-control-file" name="variantImages0[]" multiple>

                                    @for ($j = 1; $j < sizeof($product['skus']); $j++)
                                        <hr style="border-top: 2px solid #197852;">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group" style="margin-bottom: 2px;">
                                                    <label for="name">Description</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group" style="margin-bottom: 2px;">
                                                    <label for="name">Quantity</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <input class="form-control round" name='variantDescription{{$j}}' value="{{ $product['skus'][$j]['description'] }}" type="text" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <input class="form-control round" name='stock{{$j}}' value="{{ $product['skus'][$j]['stock_left'] }}" min="0" type="number" >
                                                </div>
                                            </div>
                                            <input type='hidden' name='sku{{$j}}' value="{{ $product["skus"][$j]["id"] }}">
                                        </div>
                                        <table id="" class="table table-hover table-xl mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="border-top-0">Image ID</th>
                                                    <th class="border-top-0">Image Preview</th>
                                                    <th class="border-top-0">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @for($i=0; $i<sizeof($product["skus"][$j]["images"]); $i++) 
                                                    <tr>
                                                        <td>{{ $product["skus"][$j]["images"][$i]["id"] }}</td>
                                                        <td>
                                                            <ul class="list-unstyled users-list m-0">
                                                                <li data-toggle="tooltip" data-popup="tooltip-custom" data-original-title="{{ $product["skus"][$j]["images"][$i]["path"].".jpg" }}" class="avatar avatar-sm pull-up">
                                                                    <img class="media-object rounded-circle no-border-top-radius no-border-bottom-radius"
                                                                    src="{{ url("app/assets/img/products/thumbnail/".$product["skus"][$j]["images"][$i]["path"].".jpg") }}"
                                                                    alt="{{ $product["skus"][$j]["images"][$i]["path"].".jpg" }}">
                                                                </li>
                                                            </ul>
                                                        </td>
                                                        <td>
                                                            {{-- View and delete --}}
                                                            <a target="_blank" href="{{ url("app/assets/img/products/main/".$product["skus"][$j]["images"][$i]["path"].".jpg") }}">
                                                                <button type="button" data-toggle="tooltip" data-popup="tooltip-custom" data-original-title="View {{ $product["skus"][$j]["images"][$i]["path"].".jpg" }}"  style="margin-top: 3px;" class="btn btn-info btn-sm round">
                                                                    <i class="ft-eye"></i>
                                                                </button>
                                                            </a>
                                                            @if (sizeof($product["skus"][$j]["images"]) > 1)
                                                            <button onclick="submitImageDelete({{ $product['skus'][$j]['images'][$i]['id'] }})" type="button" data-toggle="tooltip" data-popup="tooltip-custom" data-original-title="Delete {{ $product["skus"][$j]["images"][$i]["path"].".jpg" }}"  style="margin-top: 3px;" class="btn btn-danger btn-sm round">
                                                                <i class="ft-trash"></i>
                                                            </button>
                                                        @endif
                                                        </td>
                                                    </tr>
                                                @endfor
                                            </tbody>
                                        </table>
                                        <input type="file" class="form-control-file" name="variantImages{{$j}}[]" multiple>
                                    @endfor
                                </div>
                                <input type='hidden' id='oldSKUCount' name='oldSKUCount' Value='{{$j}}'>
                                <input type='hidden' id='newSKUCount' name='newSKUCount' Value='{{$j}}'>
                            </div>
                            <input type="hidden" name="product_action" value="update_stock" />
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-actions" style="text-align:center;">
                            <button type="submit" name="add_product" class="btn btn-success">
                                    Update Product Stock
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <form id="image-delete-form" method="POST" action="{{ route('manager.process.product', $product['slug']) }}">
        @csrf
        <input type="hidden" name="image_id" id="image_id"/>
        <input type="hidden" name="product_action" value="delete_stock_image"/>
    </form>
    <script>
        var variationCount = document.getElementById("oldSKUCount").value;
        $( "#addVariation" ).click(function(){

                var updateString = "<hr style='border-top: 2px solid #197852;'><div class='row'><div class='col-md-8'><div class='form-group' style='margin-bottom: 2px;'><label for='name'>Description</label></div></div><div class='col-md-4'><div class='form-group' style='margin-bottom: 2px;'><label for='name'>Quantity</label></div></div></div><div class='row'><div class='col-md-8'><div class='form-group'><input class='form-control round' name='variantDescription"+variationCount+"' value='None' type='text'></div></div><div class='col-md-4'><div class='form-group'><input class='form-control round' name='stock"+variationCount+"' value='1' type='number' ></div></div></div><input type='file' class='form-control-file' name='variantImages"+variationCount+"[]' multiple>";
                
                //populate modal inputs
                $('#variations').append(updateString);
                variationCount++;

                document.getElementById("newSKUCount").value = variationCount;
            
        });

        function submitImageDelete(imageID)
        {
            document.getElementById('image_id').value = imageID;
            document.getElementById('image-delete-form').submit(); 
        } 

    </script>
@endsection