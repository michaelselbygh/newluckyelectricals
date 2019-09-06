@extends('layouts.main.master')
@section('page-title') Our Stores @endsection
@section('page-content')
    <h3 style="text-align: center">About New Lucky Electricals</h3>
    <section class="so-spotlight1" style="padding-top: 30px; min-height: 450px;">

        <div class="container">
            <div class="row">
                <div class="col-md-6" style="text-align:center;">
                    <video style="width: 100%; height: auto; border-radius: 10px;" controls autoplay muted loop>
                        <source src="{{ url('app/assets/video/about.mp4') }}" type="video/mp4">
                        <!-- <source src="assets/images/vid1.ogg" type="video/ogg"> -->
                        Your browser does not support the video tag.
                    </video>
                </div>

                <div class="col-md-6" style="">
                    <div>
                        New Lucky Electricals Company was incorporated in Ghana in 1996. It’s a company that deals in electrical products. It’s authorized business includes, the importation of general electrical wares, execution of general electrical contract works, wholesale of general electrical wares and general merchants. With its Head Office located in the central Business district of Accra, the capital of Ghana. New lucky Electricals has become one of Ghana’s leading indigenous electrical companies with a whole lot of branches in the Capital. 
                    </div>
                    <br>
                    <div>
                        New Lucky Electricals niche is carved out of its commitment to emphasize and personify quality for which it attributes its extra ordinary market growth opportunities. Under the dynamic leadership of its founder Dr Joseph Obeng, New Lucky Main objective is to serve consumers with the best of electrical items to avoid any disappointments whatsoever New lucky Electricals is committed to supplying high quality Electrical products at affordable prices to institutions and individuals. A policy which has contributed to the success story of the company. New Lucky!!! Where Quality Costs Less.
                    </div>
                </div>
            </div>
            <br><br>
        </div>  
    </section>

    <br>
@endsection