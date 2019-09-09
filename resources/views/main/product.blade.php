@extends('layouts.main.product')
@section('page-title'){{ $product["name"] }}
    @if (strtolower($product['sku']['description']) != "none")
        - {{ $product['sku']['description'] }}
    @endif
@endsection
@section('page-image'){{ url('app/assets/img/products/main/'.$product['sku']['images'][0]['path'].'.jpg') }}@endsection
@section('page-content')
    <section class="so-spotlight1" style="min-height: 450px;">

        <div class="container">
            <div class="row">
                <!--Middle Part Start-->
                <div id="content" class="col-md-12 col-sm-12">
                    
                    <div class="product-view row">
                        <div class="left-content-product col-lg-12 col-xs-12">
                            <div class="row">
                                <div class="content-product-left  col-sm-5 col-xs-12 ">
                                    <div class="large-image">
                                        <img itemprop="image" class="product-image-zoom" src="{{ url('app/assets/img/products/main/'.$product['sku']['images'][0]['path'].'.jpg') }}" data-zoom-image="{{ url('app/assets/img/products/main/'.$product['sku']['images'][0]['path'].'.jpg') }}" title="{{ $product["name"] }}" alt="{{ $product["name"] }}">
                                    </div>
                                    @if (sizeof($product["sku"]["images"]) > 1)
                                        <div id="thumb-slider" class="owl-theme owl-loaded owl-drag full_slider" style="{{ sizeof($product["sku"]["images"]) <= 4 ? 'padding-top: 0px' : '' }}">
                                            @for ($i = 0; $i < sizeof($product["sku"]["images"]); $i++)
                                                <a data-index="{{$i}}" class="img thumbnail" data-image="{{ url('app/assets/img/products/main/'.$product['sku']['images'][$i]['path'].'.jpg') }}" title="{{ $product["name"] }}">
                                                    <img src="{{ url('app/assets/img/products/thumbnail/'.$product['sku']['images'][$i]['path'].'.jpg') }}" title="{{ $product["name"] }}" alt="{{ $product["name"] }}">
                                                </a>
                                            @endfor
                                        </div>
                                    @endif
                                </div>
    
                                <div class="content-product-right col-sm-7 col-xs-12">
                                    <div class="title-product">
                                        <h2>
                                            {{ $product["name"] }}
                                            @if (strtolower($product['sku']['description']) != "none")
                                                - {{ $product['sku']['description'] }}
                                            @endif
                                        </h2>
                                    </div>
                                    <div>
                                        @if (trim($product['description']) != "")
                                            {{ $product['description'] }}
                                            <br><br>
                                        @endif
                                    </div>
                                    <div class="product-box-desc">
                                        <div class="inner-box-desc">
                                            @for ($i = 0; $i < sizeof($product['features']); $i++)
                                                <div class="price-tax">{{ $product['features'][$i] }}</div>
                                            @endfor
                                            <br>
                                            @if ($product['sku']['stock_left'] > 0)
                                            <div class="reward" style="color: green"><span style="color: black">Availability:</span> In Stock</div>
                                            @else
                                                <div class="reward" style="color: red"><span style="color: black">Availability:</span> Out of Stock</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div id="product">
                                        @if (sizeof($product["skus"]) > 1)
                                            <h4>Other Available Options</h4>
                                            <div id="radioBtn" class="btn-group">
                                                @for ($i = 0; $i < sizeof($product['skus']); $i++)
                                                    @if($product['skus'][$i]['stock_left'] > 0 AND $product['skus'][$i]['id'] != $product['sku']['id'])
                                                        <a href="{{ route('product', ['product_slug' => $product['slug'], 'sku_slug' => $product['skus'][$i]['slug']]) }}" class="btn rbtn btn-sm notActive" data-toggle="ProductSKU" data-title="{{ $product['skus'][$i]['id'] }}">
                                                            {{ $product['skus'][$i]['description'] }}
                                                        </a>
                                                    @endif
                                                @endfor
                                            </div>
                                        @endif
                                        @if (sizeof($product['related']) > 0)
                                            <div style="padding-top: 20px;">
                                                <a href="{{ route('category', $product['category']['slug']) }}">
                                                    <button type="button" class="button_grey filter_reset" >
                                                        Show more products in {{ $product['category']['description'] }}
                                                    </button>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>  
    </section>
@endsection