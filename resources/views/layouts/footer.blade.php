<div id="footer">
    <div class="container" >
        <h3>Ask a Fellow</h3>
        <div class=".col-sm-4 col-md-4">

            <ul>
                <li>
                    <a href="{{url('/')}}">Home</a>
                </li>
                <li>
                    <a href="{{url('/about')}}">About</a>
                </li>
                <li>
                    <a href="{{url('/howitworks')}}">How it works</a>
                </li>
            </ul>
        </div>
        <div class=".col-sm-4 col-md-4">
            <ul>
                <li>
                    <a href="{{url('/browse')}}">Browse Courses</a>
                </li>
                <li>
                    <a href="{{url('/subscriptions')}}">Subscribe</a>
                </li>
            </ul>
        </div>
        <div class=".col-sm-4 col-md-4">
            <ul>
                <li>
                    <a href="mailto:askafellow.techhub@gmail.com?subject=Ask a Fellow">Contact us</a>
                </li>
                <li>
                    <a data-toggle="modal" data-target="#feedback_modal">Send Feedback</a>
                </li>

            </ul>
        </div>

    </div>

</div>
<div id="rights">
    All rights reserved. Proudly created by <span style="color:cornflowerblue">Bdaya TechHub</span>
    <br>
    <a target="_blank" href="https://www.facebook.com/BdayaNGO"><img class="contact_logo" src="{{asset('/art/facebook.png')}}"></a>
    <a target="_blank" href="https://www.youtube.com/user/salfoosh"><img class="contact_logo" src="{{asset('/art/youtube.png')}}"></a>
    <a target="_blank" href="https://www.instagram.com/bdaya_ngo/"><img class="contact_logo" src="{{asset('/art/instagram.png')}}"></a>
    <a target="_blank" href="https://github.com/AyaKhaled1/ask-a-fellow-laravel/"><img class="contact_logo" src="{{asset('/art/github.png')}}"></a>
</div>


<div id="feedback_modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class=""  style="background-color:rgba(255,255,255,0.8)">

            <button style="margin-right:15px;margin:top:10px;"type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"></h4>

            <br>
            <div class="modal-body" style="padding: 0 50px 40px 50px;">
                <h3>Send Feedback </h3>
                <form method="post" action="{{url('/feedback')}}">
                    {{csrf_field()}}
                    <div class="form-group" style="width: 50%;">
                        <label for="name">Name (Optional)</label>
                        <input type="text" class="form-control" id="name" placeholder="Name" name="name">

                    </div>
                    <div class="form-group" style="width: 50%;">
                        <label for="email">Email address (Optional)</label>
                        <input type="email" class="form-control" id="email" placeholder="Email" name="email">

                    </div>
                    <div class="form-group">
                        <label for="feedback">Feedback*</label>
                        <textarea required class="form-control" id="feedback" name="feedback" style="height: 150px;resize: none;"></textarea>

                    </div>
                    <button type="submit" class="btn btn-default">Send</button>
                </form>
                @include('errors')
            </div>
            <!-- <div class="modal-footer"> -->

            <!-- </div> -->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<style>
    .contact_logo
    {
        margin-top: 20px;
        margin-left: 5px;
        margin-right: 5px;
        height: 30px;

    }
    #footer
    {
        /*margin-top: 5px;*/
        color:#FFAF6C;
        min-height: 200px;
        width: 100%;
        background-color: #621708;
        padding:25px;

    }
    #footer a
    {
        color:#FFAF6C;
        font-size: 16px;
        cursor: pointer;
    }
    #footer a:hover, #footer a:focus
    {
        text-decoration: none;
        color:#FFFF6C;

    }
    #rights{

        background-color: #9A4838;
        text-align: center;
        padding-top: 20px;
        padding-bottom: 20px;
        color:white;
    }

    #footer li
    {
        list-style-type: none;
    }
</style>