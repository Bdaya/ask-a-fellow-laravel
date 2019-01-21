@extends('layouts.app');
@section('content')

    <div class=info_section>
        @if (session('updated'))
            <div class="flash-message">
                <div class="alert alert-info" style="background-color: #FFAF6C; border-color: #FF6B2D; color:#AA5B0B">
                    {{session('updated')}}
                </div>
            </div>
        @endif
        @if (session('mail'))
            <div class="flash-message">
                <div class="alert alert-info" style="background-color: #FFAF6C; border-color: #FF6B2D; color:#AA5B0B">
                    {{session('mail')}}
                </div>
            </div>
        @endif
        <div class="profile_picture">
            @if($user->profile_picture)
                <img src="{{asset($user->profile_picture)}}" style="">
            @else
                <img src="{{asset('art/default_pp.png')}}" style="">
            @endif

        </div>
            @if($user->verified_badge >=1)
                <h1>{{$user->first_name.' '.$user->last_name}}  <span class="verified"></span></h1>
            @else
                <h1>{{$user->first_name.' '.$user->last_name}}  </h1>
            @endif

        <a href="#" style="color:#0057A2">{{$user->email}}</a>
        <p style="font-size: 20px;">{{$user->semester?'Semester '.$user->semester:''}}<br> {{$user->major?$user->major->major:''}}</p>
        <p>{{$user->bio}}</p>
        @if(Auth::user() && Auth::user()->id == $user->id)
                <a class="btn btn-success " href="{{url('user/update')}}">Update info</a>
        @endif
        @if(Auth::user() && Auth::user()->role >= 1 && Auth::user()->id != $user->id)
                <a class="btn btn-info " href="{{url('admin/mail/one/'.$user->id)}}">Email user</a>
        @endif
        @if($user->verified_badge ==1)
            <h3 style="text-align: left">Associated User Courses:</h3>
                <table>  
                    <tr style="border: 1px solid #dddddd;">
                    @foreach($verified_users_courses as $course)
                        @if($user->id == $course['user_id'])
                        <th style="border: 1px solid #dddddd; padding: 4%">{{$courses[$course['course_id']-1]['course_code']}}</th>
                        @endif
                    @endforeach
                    </tr>
                </table>
        @endif

    </div>
    <div class="questions_answers">

       @yield('question_answer_section')


    </div>


    <link href="{{asset('css/textAnimation.css')}}" rel="stylesheet">
    <style>
        /* Profile styling */

        @font-face {
            font-family: ubuntu;
            src: url('{{asset('fonts/ubuntu.ttf')}}');
        }
        #main_content{

            padding-top: 0px !important;
            padding-bottom: 50px !important;
            /*height: 800px;*/
            width: 80% !important;
            /*background-color: #F7E8D6;*/
            margin-top: 30px !important;
            margin-bottom: 10px ;
            /*box-shadow: 0px 5px 10px -1px #888888;*/
            z-index: 1;
        }

        .info_section
        {
            /*height: 400px;*/
            background-color: #FF953D;
            text-align: center;
            padding: 40px;
        }
        .profile_picture
        {




        }
        .profile_picture img{
            /*position: relative;*/
            /*display: inline-block;*/
            width: 200px;
            height: 200px;
            border-radius: 100px;
            object-fit: cover;
        }
        .info_section h1
        {
            font-size: 40px;
            color: #621708;
            font-family: 'ubuntu', sans-serif;
        }
        .info_section p{

        }

        .questions_answers{
            padding: 50px;
            padding-left: 100px;
            padding-right: 100px;
            margin-top: 20px;
        }
        .questions_answers .media
        {
            width: 70%;
            min-width: 200px;
            margin-bottom: 50px;
        }
        .questions_answers .media img
        {
            width: 70px;
            height: 70px;
            border-radius: 100px;
            margin-bottom: 10px;
        }
        .questions_answers .media h4
        {
            /*width: 100%;*/
            /*font-size: 17px;*/
            font-weight: bold;
        }



        @media (max-width: 600px) {
            .profile_picture img{
                width: 100px;
                height: 100px;
            }
            .info_section
            {
                font-size: 14px;
            }
            .info_section h1
            {
                font-size: 25px;
            }
            .questions_answers{

                padding-left: 20px;

            }
            .questions_answers .media
            {
                width: 100%;
                font-size: 13px;
            }
            .questions_answers .media h4
            {
                /*width: 100%;*/
                font-size: 17px;
                font-weight: bold;
            }

            #switch a
            {
                font-size: 13px !important;
            }
            #main_content h3
            {
                font-size: 18px;
            }


        }
        span.verified{
            display: inline-block;
            vertical-align: middle;
            height: 40px;
            width: 40px;
            background-image: url("{{asset('art/ver.png')}}");
            background-repeat: no-repeat;
            z-index: 200000;

        }



    </style>

@endsection