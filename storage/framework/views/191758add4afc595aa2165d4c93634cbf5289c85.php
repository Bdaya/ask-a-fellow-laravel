<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/formInput.css" rel="stylesheet">
    <link href="css/textAnimation.css" rel="stylesheet">
    <script type="text/javascript" src = "js/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src = "js/bootstrap.min.js" ></script>
    <script type="text/javascript" src = "js/classie.js"></script>

    <title>
        Ask a Fellow
    </title>
</head>
<body>

<nav class="navbar navbar-default">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#"><img class="logo" src="<?php echo e(asset('art/logo.png')); ?>"></a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">


            <ul class="nav navbar-nav navbar-right">
                <li><a href="<?php echo e(url('/home')); ?>">Home</a></li>
                <li class="link-separator"><a>|</a></li>
                <li><a href="<?php echo e(url('/about')); ?>">About</a></li>
                <li class="link-separator"><a>|</a></li>
                <li><a href="<?php echo e(url('/howitworks')); ?>">How it works</a></li>

            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
<header>
    <?php if(session('feedback')): ?>
        <div class="flash-message feedback_flash">
            <div class="alert alert-success" style="z-index: 20;left:50%; margin-left:-25%;width:50%; text-align: center; position: fixed; top:100px;">
                <?php echo e(session('feedback')); ?>

            </div>
        </div>
    <?php endif; ?>
    <?php if(session('register')): ?>
        <div class="flash-message feedback_flash">
            <div class="alert alert-success" style="z-index: 20;left:50%; margin-left:-25%;width:50%; text-align: center; position: fixed; top:100px;">
                <?php echo e(session('register')); ?>

            </div>
        </div>
    <?php endif; ?>

    <div class="description_and_image">

        <div class="name_and_description">
            <h1>Ask a Fellow</h1>
            <p>An educational social platform made for GUC students</p>
            <br>
        </div>
        <div class="header_image"></div>
    </div>
    <div class="login_register">
        <nav id="switch" class="cl-effect-21" style="">
            <a id="loginSwitch" style="color: #CA6A1B; float:left; margin-left:5%; margin-bottom:15px; border-bottom:1px solid #CA6A1B;" href="#">Login</a>
            <a id="registerSwitch" style="color: #CA6A1B; float:right; margin-right:5%; border-bottom:1px solid #CA6A1B;" href="#">Register</a>
        </nav>

        <form id="register_form" class="form-horizontal" method="POST" action="<?php echo e(url('/register')); ?>">
            <?php echo csrf_field(); ?>

            <span class="input input--manami">
            <input class="input__field input__field--manami" type="text" required id="register_firstname" name="first_name" value="<?php echo e(old('first_name')); ?>">
            <label class="input__label input__label--manami" for="firstName">
                <span class="input__label-content input__label-content--manami">First Name</span>
            </label>
                <?php if($errors->has('first_name')): ?>
                    <span class="help-block">
                    <strong><?php echo e($errors->first('first_name')); ?></strong>
                </span>
                <?php endif; ?>
        </span>
        <span class="input input--manami">
            <input class="input__field input__field--manami" type="text" required id="register_lastname" name="last_name" value="<?php echo e(old('first_name')); ?>">
            <label class="input__label input__label--manami">
                <span class="input__label-content input__label-content--manami">Last Name</span>
            </label>
            <?php if($errors->has('last_name')): ?>
                <span class="help-block">
                    <strong><?php echo e($errors->first('last_name')); ?></strong>
                </span>
            <?php endif; ?>
        </span>
        <span class="input input--manami">
            <input class="input__field input__field--manami" type="email" required id="register_email" name="email" value="<?php echo e(old('email')); ?>">
            <label class="input__label input__label--manami">
                <span class="input__label-content input__label-content--manami">Email</span>
            </label>
            <?php if($errors->has('email')): ?>
                <span class="help-block">
                    <strong><?php echo e($errors->first('email')); ?></strong>
                </span>
            <?php endif; ?>
        </span>
        <span class="input input--manami">
            <input class="input__field input__field--manami" type="password" required id="register_password" name="password">
            <label class="input__label input__label--manami">
                <span class="input__label-content input__label-content--manami">Password</span>
            </label>
            <?php if($errors->has('password')): ?>
                <span class="help-block">
                    <strong><?php echo e($errors->first('password')); ?></strong>
                </span>
            <?php endif; ?>
        </span>
        <span class="input input--manami">
            <input class="input__field input__field--manami" type="password" required id="register_confirm_password" name="password_confirmation">
            <label class="input__label input__label--manami">
                <span class="input__label-content input__label-content--manami">Confirm Password</span>
            </label>
            <?php if($errors->has('password_confirmation')): ?>
                <span class="help-block">
                    <strong><?php echo e($errors->first('password_confirmation')); ?></strong>
                </span>
            <?php endif; ?>
        </span>
            <div style="color:red" id="register_feedback"></div>
            <div class="form-group">
                <div class=" col-sm-10" style="margin-top:20px; margin-left:10px;">
                    <!--<a class="submit" style="float:right;" href="#">Register</a>-->
                    <input type="submit" class="btn btn-default submit" style="background-color:#FF953D; border: 1px solid #CC953D;float:right;" href="#" value="Register" id="register_submit">
                </div>
            </div>
        </form>
        <form id="login_form" class="form-horizontal"  method="POST" action="<?php echo e(url('/login')); ?>">
            <?php echo csrf_field(); ?>

            <span class="input input--manami">
            <input class="input__field input__field--manami" type="email" name="email" required id="login_email" value="<?php echo e(old('email')); ?>">
                <label class="input__label input__label--manami">
                    <span class="input__label-content input__label-content--manami">Email</span>
                </label>
                <?php if($errors->has('email')): ?>
                    <span class="help-block">
                        <strong><?php echo e($errors->first('email')); ?></strong>
                    </span>
                <?php endif; ?>
        </span>
        <span class="input input--manami">
            <input class="input__field input__field--manami" type="password" name="password" required id="login_password">
                <label class="input__label input__label--manami">
                    <span class="input__label-content input__label-content--manami">Password</span>
                </label>
            <?php if($errors->has('password')): ?>
                <span class="help-block">
                        <strong><?php echo e($errors->first('password')); ?></strong>
                    </span>
            <?php endif; ?>
        </span>
            <a class="pull-left" style="text-align:right" href="<?php echo e(url('/password/reset')); ?>">Forgot password?</a>
            <div class="form-group">
                <div class=" col-sm-10" style="margin-top:20px; margin-left:10px;">
                    <input type="submit" class="btn btn-default submit" style="background-color:#FF953D; border: 1px solid #CC953D;float:right;" href="#" value="Login" id="login_submit">
                </div>
            </div>
        </form>

    </div>
</header>
<div class="center-block" id="main_content">

    <div class="center-block" style="text-align:center;">
        <h2>Looking for a specific course?</h2>
        <div>




                <select name="quick_select" id="states" style="width:40%">
                    <option> </option>
                    <?php foreach(App\Course::all() as $course): ?>
                        <option value="<?php echo e($course->id); ?>"><?php echo e($course->course_name); ?></option>
                    <?php endforeach; ?>
                </select>
                <?php /*<input class="flexsearch--submit" type="submit" value="&#10140;"/>*/ ?>
                <input class="btn btn-link" type="button" id="quick_submit" value="Go">

        </div>


        <h2><a href="<?php echo e(url('/browse')); ?>">Or browse all courses.</a></h2>

    </div>


    <div style="text-align: center"class="section_2">
        <h1>Ask a Fellow</h1>
        <p style="font-size: 20px">
            Ask a fellow is an educational social network connecting students from the GUC.<br>
            It's the right place to ask your questions and get answers from your colleagues.<br>
            No more Facebook study groups hassle, welcome to your learning environment!<br>
        </p>
    </div>
</div>
<?php echo $__env->make('layouts.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
</body>
</html>
<style>
    .navbar
    {
        background-color: #FF953D !important;
        margin-bottom: 0px;
        border: none;
        padding: 20px;
        /*height: 100px;*/
        z-index: 20;
    }
    header
    {
        width: 100%;
        min-height: 500px;

        background-color: #FF953D;
        box-shadow: 0px 3px 10px #888888;
        /*padding: 00px;*/
        padding-bottom: 50px;
        z-index: 1;

    }
    .main-nav-scrolled
    {
        box-shadow: 0px 1px 5px #888888;
        position: fixed;
        width: 100%;
        top: 0;
        background-color: #FFAF6C !important;
    }

    .section_2
    {
        padding: 30px;
        margin-top: 100px;
        background-color: #FBD19F;
    }
    #main_content{
        padding-top: 100px;
        padding-bottom: 50px;
        /*height: 800px;*/
        width: 80%;
        background-color: #F7E8D6;
        margin-top: 5px;
        box-shadow: 3px 5px 10px 1px #888888;
        z-index: 1;
    }

    .navbar .active a
    {
        background-color: #FFA83E !important;


    }
    .navbar a
    {
        color: white !important;
        font-size: 16px;

    }
    .link-separator a:hover
    {
        background-color: #FFE9CF !important;
    }

    .navbar ul a:hover
    {

        background-color: #FF9E28 !important;
    }

    .navbar .navbar-brand
    {
        font-size: 25px;
    }
    .navbar #search_bar
    {
        background-color: #FFE9CF;

        width: 300px;
    }

    .navbar #search_button
    {
        background-color: #FFE9CF;
        z-index: 5;
    }

.logo{
    width:70px;
    height:auto;
    margin-top:-10px;
}

    .description_and_image
    {
        margin-left: 100px;
        width: 50%;
        height: 100%;
        float: left;
        z-index: 0;



        /*background-color: black;*/
    }
    .header_image{

        margin-top:-20px;
        margin-left: 100px;
        background: url('art/landing_background.png') no-repeat;
        background-size: 100% auto;
        min-height: 300px;
        width: 57%;
        bottom: 00px;
        opacity: 0.7;
        /*position: absolute;*/
        z-index: 0;
    }
    .login_register
    {
        margin-right: 100px;
        width: 30%;
        min-width:350px;
        height: 100%;
        background-color: rgba(249, 195, 130, 0.55);
        float: right;
        padding: 50px;

    }
    /*.header_image
    {
        height: 65%;
        width: 100%;

    }*/
    .name_and_description h1
    {
        font-size: 80px;
        font-weight: bold;
        color: white;
    }
    .name_and_description p
    {
        font-size: 24px;

    }





    .flexsearch--wrapper {
        height: auto;
        width: auto;
        max-width: 100%;
        overflow: hidden;
        background: transparent;
        margin: 0;
        position: static;
    }

    .flexsearch--form {
        overflow: hidden;
        position: relative;
    }

    .flexsearch--input-wrapper {
        padding: 0px 66px 0px 0px; /* Right padding for submit button width */
        overflow: hidden;
    }

    .flexsearch--input {
        width: 100%;
    }

    /***********************
     * Configurable Styles *
     ***********************/
    .flexsearch {
        padding: 0 25px 0 0px; /* Padding for other horizontal elements */
        width: 50%;
    }

    .flexsearch--input {
        -webkit-box-sizing: content-box;
        -moz-box-sizing: content-box;
        box-sizing: content-box;
        height: 60px;
        padding: 0 46px 0 10px;
        border-color: #888;
        border-radius: 10px; /* (height/2) + border-width */
        border-style: solid;
        border-width: 5px;
        margin-top: 15px;
        color: #333;
        font-family: 'Helvetica', sans-serif;
        font-size: 26px;
        -webkit-appearance: none;
        -moz-appearance: none;
    }

    .flexsearch--submit {
        position: absolute;
        right: 0;
        top: 0;
        display: block;
        width: 60px;
        height: 60px;
        padding: 0;
        border: none;
        margin-top: 20px; /* margin-top + border-width */
        margin-right: 5px; /* border-width */
        background: transparent;
        color: #888;
        font-family: 'Helvetica', sans-serif;
        font-size: 40px;
        line-height: 60px;
    }

    .flexsearch--input:focus {
        outline: none;
        border-color: #333;
    }

    .flexsearch--input:focus.flexsearch--submit {
        color: #333;
    }

    .flexsearch--submit:hover {
        color: #333;
        cursor: pointer;
    }

    ::-webkit-input-placeholder {
        color: #888;
    }

    input:-moz-placeholder {
        color: #888
    }


    @media (max-width: 1100px) {
        .description_and_image h1
        {
            font-size:46px;
        }
        .description_and_image p{
            font-size: 18px;
        }
        .description_and_image
        {
            width: 30%;
        }
    }


    @media (max-width:800px)
    {
        .header_image
        {
            display: none;
        }
        .description_and_image
        {

            width:80%;

        }
        header
        {
            display: inline-block;
        }

    }
    @media (max-width: 500px) {
        .flexsearch h1
        {
            font-size: 16px;
        }
        .flexsearch
        {
            width: 200px;
        }
        .name_and_description h1
        {
            font-size: 32px;
        }
        .name_and_description p
        {
            display: none;
        }
        .description_and_image
        {
            display: none;
        }
        .login_register
        {
            min-width: 300px;
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

        h2
        {
            font-size: 22px;
        }


    }
</style>

<script>
    var  mn = $(".navbar");
    mns = "main-nav-scrolled";
    hdr = $('header').height();
    $(window).scroll(function(){
        if( $(this).scrollTop() > hdr ) {
            mn.addClass(mns);

            mn.stop().animate({
                padding:'1px'
            },'fast');


        }
        else if($(this).scrollTop() <= 100)
        {
            mn.removeClass(mns);
            mn.stop().animate({
                padding:'20px'
            },'fast');
        }
    });
    $('#register_form').hide();
    $("#loginSwitch").css("cursor", "default");
    $("#registerSwitch").css("opacity", "0.5");
    var visible = 0;

    $('#loginSwitch').click(function(){
        if(visible==1){
            $('#register_form').hide();
            $('#login_form').fadeIn('fast');
            $("#loginSwitch").css("cursor", "default");
            $("#registerSwitch").css("cursor", "pointer");
            $("#registerSwitch").css("opacity", "0.5");
            $("#loginSwitch").css("opacity", "1");
            visible=0;
        }

    });
    $('#registerSwitch').click(function(){

        if(visible==0){
            $('#login_form').hide();
            $('#register_form').fadeIn('fast');
            $("#registerSwitch").css("cursor", "default");
            $("#loginSwitch").css("cursor", "pointer");
            $("#loginSwitch").css("opacity", "0.5");
            $("#registerSwitch").css("opacity", "1");
            visible=1;
        }

    });
</script>
<script>
    (function() {
        // trim polyfill : https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/String/Trim
        if (!String.prototype.trim) {
            (function() {
                // Make sure we trim BOM and NBSP
                var rtrim = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g;
                String.prototype.trim = function() {
                    return this.replace(rtrim, '');
                };
            })();
        }

        [].slice.call( document.querySelectorAll( 'input.input__field' ) ).forEach( function( inputEl ) {
            // in case the input is already filled..
            if( inputEl.value.trim() !== '' ) {
                classie.add( inputEl.parentNode, 'input--filled' );
            }

            // events:
            inputEl.addEventListener( 'focus', onInputFocus );
            inputEl.addEventListener( 'blur', onInputBlur );
        } );

        function onInputFocus( ev ) {
            classie.add( ev.target.parentNode, 'input--filled' );
        }

        function onInputBlur( ev ) {
            if( ev.target.value.trim() === '' ) {
                classie.remove( ev.target.parentNode, 'input--filled' );
            }
        }
    })();
</script>


<script>
    $(document).ready(function() {
        if ($('#states :selected').text()==' '){
            ($('#quick_submit').hide());
            //($('#go').hide());
        }
        $("#states").select2({
            placeholder: "Select a State",
            allowClear: true
        });
        $("#states").change(function() {
            if ($('#states :selected').text()==' '){
                ($('#quick_submit').hide());

                //($('#go').hide());
            }
            else
                ($('#quick_submit').show());

        });

        setTimeout(function(){
            $('.feedback_flash').fadeOut('slow');
        },3000);
    });

    $('#quick_submit').click(function(){
       var course = $('#states').val();
        if(course)
                window.location.href = "<?php echo e(url('/browse')); ?>"+"/"+course;
    });

</script>
<link href="<?php echo e(asset('css/select2.css')); ?>" rel="stylesheet"/>
<script src="<?php echo e(asset('js/select2.js')); ?>"></script>
