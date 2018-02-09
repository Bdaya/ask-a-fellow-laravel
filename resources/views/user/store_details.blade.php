@extends('layouts.app')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

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
                    <a href="#" id="show">Review this store</a>
                    <div id="form" hidden="true">

                        <form method="post" action="/user/stores/{{ $store->id }}">

                          <div class=" form-group ">
                          <input type="hidden" name="rate" value="10">

                          <h4>Rate this store</h4>
                          @for($i = 0;$i<10;++$i)
                            <span class="fa fa-star star checked" id="star{{$i}}"></span>
                          @endfor
                          </div>

                            <div class=" form-group ">
                                <textarea class="form-control " name="review" placeholder="Optional: write a review"></textarea>
                            </div>

                            <input type="button" onclick="submit()" value="Submit Review">
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
                    <div>
                        <div>
                            <h6><i>by <a href="/user/{{$review->id}}">{{$review->first_name}} {{$review->last_name}}</a></i></h6>
                            <p>{{$review->review}}</p>
                        </div>
                    </div>
                    <hr>
                   @endforeach
                </div>

            </div>
        </div>
    </div>
</div>

<script>

    $(document).ready(function(){

    $("#show").on("click", function() {
        $("#show").hide();
        $("#form").show();
    });

    for (var i = 0; i < 10; i++)
    {
      $("#star"+i).click({param1: i},color);
    }

    function color(rate)
    {
        rate = rate.data.param1;
        for(var i = 0;i<10;++i)
        {
          if(i<=rate)
            $("#star"+i).addClass("checked");
          else
            $("#star"+i).removeClass("checked");
        }
        $("[name='rate']").val(rate+1);
    }
  });

</script>

<style>
    textarea {
        max-width: 100%;
        max-height: 100%;
    }


    .checked {
        color: orange;
    }

    .star:hover{
      cursor: pointer;
    }

</style>

@endsection
