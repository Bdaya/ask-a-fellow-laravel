@extends('layouts.app') @section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">

                <div class="panel-heading">
                    <h1>Store Details</h1>
                </div>

                <div class="panel-body">
                    <img src="{{asset($store->logo)}}" alt="No Logo Found!" width="100" height="100">
                    <h2 align="center">{{ $store->name }}</h2>
                    <br>
                    <h3>Rate: {{ $store->rate }}</h3>
                    <h3>Description: {{ $store->description }}</h3>
                    <h3>Location: {{ $store->location }}</h3>
                    <h3>Phone Number: {{ $store->phone }}</h3>
                    <br>
                    <a href="#" id="show">Add a review for this store</a>
                    <div id="form" hidden="true">
                        <form method="post" action="/user/stores/{{ $store->id }}">


                            <div class=" form-group ">
                                <textarea class="form-control " name="review"></textarea>
                            </div>

                            <button type="submit " class="btn btn-primary ">Submit Review</button>
                        </form>
                    </div>
                </div>

            </div>

            <div class="panel panel-default">

                <div class="panel-heading">
                    <h1>Store Reviews</h1>
                </div>

                <div class="panel-body">
                    @foreach($reviews as $review)
                    <div class="rev">
                        <div>
                            <h6><i>by <a href="/user/{{$review->id}}">{{$review->first_name}} {{$review->last_name}}</a></i></h6>
                            <p>{{$review->review}}</p>
                        </div>
                    </div>
                    <hr> @endforeach
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    $("#show ").on("click ", function() {
        $("#show ").hide();
        $("#form ").show();
    });

</script>

<style>
    textarea {
        max-width: 100%;
        max-height: 100%;
    }

</style>

@endsection
