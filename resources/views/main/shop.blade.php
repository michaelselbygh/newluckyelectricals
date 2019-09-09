@extends('layouts.main.master')
@section('page-title') Shop @endsection
@section('page-content')
    <section class="so-spotlight1" style="min-height: 450px;">
        <div class="container">
            <div class="row">
                <div id="content" class="col-md-9 col-sm-8">
                    <div class="module titleLine">
                        <h3 class="modtitle">
                            @if (isset($settings["search"]))
                                Search Results containing "{{ $settings["search"] }}"
                                @if (isset($settings["shop-category"]))
                                     in {{ $settings["shop-category"] }}
                                @endif
                            @else
                                Shop  
                                @if (isset($settings["shop-category"]))
                                    {{ $settings["shop-category"] }}
                                @endif
                            @endif
                        </h3>
                        @if (sizeof($products) > 0)
                            <div class="products-list row grid">
                                @for ($i = 0; $i < sizeof($products); $i++)
                                    <div class="product-layout col-md-3 col-sm-6 col-xs-12 ">
                                        <a href="{{ route('product', $products[$i]['slug']) }}">
                                            <div class="product-item-container" style="margin-top:0px; min-height: 260px;">
                                                <div class="left-block">
                                                    <div class="product-image-container lazy second_img ">
                                                        <img style="border-radius: 5px;" data-src="{{ url("app/assets/img/products/thumbnail/".$products[$i]['images'][0]['path'].".jpg") }}" src="data:image/gif;"  alt="Apple Cinema 30&quot;" class="img-responsive"/>
                                                    </div>
                                                </div>
                                                <div class="right-block">
                                                    <div class="caption" style="color:black; text-align: center">
                                                        {{ $products[$i]['name'] }}
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endfor     
                            </div>
                        @else
                            <div style="padding: 70px 0; text-align: center;">
                                <div>No products found.</div>
                                <a href="{{ route('shop') }}">
                                    <button type="button" class="button_grey filter_reset" >Back to Shop</button>
                                </a>
                            </div>
                        @endif
                    </div>
                     <!--Pagination Start-->
                     <div style="width: 100%; text-align: center;">
                        {!! $products->render() !!}
                    </div>
                    <!--Pagination End--> 
                </div>
                <aside class="col-sm-4 col-md-3" id="column-left">
                    <div class="module latest-product titleLine">
                        <h3 class="modtitle">Filter </h3>
                        <div class="modcontent ">
                            <form class="type_2" method="POST" action="{{ route('shop.filter') }}" autocomplete="off">
                                @csrf
                                <div class="table_layout filter-shopby">
                                    <div class="table_row">
                                        <div class="table_cell" style="z-index: 103;">
                                            <legend>Search</legend>
                                            <input class="form-control" type="text" value="{{ isset($settings['search']) ? $settings['search'] : '' }}" size="50" autocomplete="off" placeholder="e.g. Light bulbs or PVC" name="search" required>
                                        </div>
                                        @if (!isset($settings["shop-category"]))
                                            <div class="table_cell">
                                                <fieldset>
                                                    <legend>Category</legend>
                                                    <ul class="checkboxes_list">
                                                        @for ($i = 0; $i < sizeof($categories); $i++)
                                                            <li>
                                                                <input type="checkbox" name="category[]" value="{{ $categories[$i]["id"] }}" id="category_{{$i}}" {{ (isset($settings['search-category']) and in_array($categories[$i]["id"], $settings['search-category'])) ? ' checked' : '' }}>
                                                                <label for="category_{{$i}}">{{ $categories[$i]["description"] }}</label>
                                                            </li>
                                                        @endfor
                                                    </ul>
                                                </fieldset>
                                            </div>
                                        @endif
                                    </div>
                                    <footer class="bottom_box">
                                        <div class="buttons_row" style="text-align: center;">
                                            <button type="submit" class="button_grey button_submit">Search</button>
                                            @if (isset($settings['search']))
                                                <a href="{{ route('shop') }}">
                                                    <button type="button" class="button_grey filter_reset" >Back to Shop</button>
                                                </a>
                                            @else
                                                <button type="reset" class="button_grey filter_reset" >Reset</button>
                                            @endif
                                        </div>
                                    </footer>
                                </div><!--/ .table_layout -->
                        
                                
                        
                            </form>
                        </div>
                        </div>
                </aside>   
            </div>
        </div>
    </section>
    <br>
@endsection