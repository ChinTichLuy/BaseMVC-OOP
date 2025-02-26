@extends('client.layouts.main')

@section('title')
    Home Page
@endsection

@section('content')
    <!-- Header-->
    @include('client.layouts.header')

    <section class="py-5">
        <div class="container px-4 px-lg-5 mt-5">
            <a href="/" class="btn btn-warning">Back to home</a>
            <div>
                <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 ">
                    <div style="width: 500px; margin-right: 50px;">
                        <img src="{{ file_url($product['p_img_thumbnail']) }}" width="500px" alt="...">
                    </div>
                    <div style="width: 500px;">
                        <div style=" height: 400px;">
                            <h3>{{ $product['p_name'] }}</h3>
                            <i><h3>${{ $product['p_price'] }}</h3> </i>
                        </div>

                        <div style="margin-left: 300px">
                            <a href="#" class="btn btn-primary">Add to Cart</a>
                        </div>
                    </div>
                </div>

            </div>
            <h3>Mo ta: </h3>
            <p class="card-text">
                {!! $product['p_content'] !!}
            </p>


        </div>

    </section>
@endsection
