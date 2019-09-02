@extends('layouts.portal.master')

@section('page-title') View Product @endsection

@section('content-body')
    <form method="POST" action="{{ route('manager.process.add.product') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-7">
                <div class="row">
                    <div class="col-md-8" style="margin-top : 10px;">
                        <h5 class="card-title">Edit {{ $product['name'] }} Details</h5>
                    </div>
                    <div class="col-md-4" style="text-align: right; margin-bottom:5px;">
                    </div>
                </div>
                @include('portal.success-and-error.message')
                <div class="card">
                    <div class="card-content" style="padding:20px;">
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
                <div class="card" style="">
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
                                                <input class="form-control round" name='variantDescription0' value="None" type="text">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input class="form-control round" name='stock0' value="1" min="1" type="number" >
                                            </div>
                                        </div>
                                    </div>
                                    <input type="file" class="form-control-file" name="variantImages0[]" multiple required>
                                </div>
                                <input type='hidden' id='newSKUCount' name='newSKUCount' Value='1'>
                            </div>
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
            </div>
        </div>
    </form>
    <script>
        var variationCount = document.getElementById("newSKUCount").value;
        $( "#addVariation" ).click(function(){

                var updateString = "<hr><div class='row'><div class='col-md-8'><div class='form-group' style='margin-bottom: 2px;'><label for='name'>Description</label></div></div><div class='col-md-4'><div class='form-group' style='margin-bottom: 2px;'><label for='name'>Quantity</label></div></div></div><div class='row'><div class='col-md-8'><div class='form-group'><input class='form-control round' name='variantDescription"+variationCount+"' value='None' type='text'></div></div><div class='col-md-4'><div class='form-group'><input class='form-control round' name='stock"+variationCount+"' value='1' type='number' ></div></div></div><input type='file' class='form-control-file' name='variantImages"+variationCount+"[]' multiple>";
                
                //populate modal inputs
                $('#variations').append(updateString);
                variationCount++;

                document.getElementById("newSKUCount").value = variationCount;
            
        });

    </script>
@endsection