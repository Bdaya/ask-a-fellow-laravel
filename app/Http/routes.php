<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    Route::get('/', function () {
        if (Auth::user())
            return redirect('/home');
        return view('welcome');
    });

    /*
     * Get the available components
     */

    Route::get('/about', 'StaticController@about');
    Route::get('/howitworks', 'StaticController@howitworks');
    Route::get('/user/components', 'AppController@view_components');
    Route::get('/user/components/{id}', 'AppController@component_details');
    Route::get('/user/update', 'UserController@updateInfoPage');
    Route::post('/user/update', 'UserController@updateInfo');
    Route::get('/user/stores', 'UserController@view_storelist');
    Route::get('/user/stores/{id}', 'UserController@view_store_details');
                Route::post('/user/stores/{id}','UserController@add_review');
    Route::get('/user/{id}', 'UserController@show');
    Route::get('/user/{id}/questions', 'UserController@show');
    Route::get('/user/{id}/answers', 'UserController@showProfileAnswers');

    Route::get('/admin', 'AdminController@index');
    Route::get('/admin/add_badge', 'AdminController@add_badge');
    Route::post('/admin/add_badge/{id}', 'AdminController@save_badge');
    Route::post('/admin/remove_badge/{id}', 'AdminController@remove_badge');
    Route::get('/admin/add_course', 'AdminController@add_course_page');
    Route::get('/admin/add_major', 'AdminController@add_major_page');
    Route::get('/admin/add_component_category', 'AdminController@add_component_category_page');
    Route::post('/admin/add_major', 'AdminController@add_major');
    Route::post('/admin/add_course', 'AdminController@add_course');
    Route::post('/admin/add_component_category', 'AdminController@add_component_category');
    Route::get('/admin/delete_course/{id}', 'AdminController@delete_course');
    Route::get('/admin/delete_major/{id}', 'AdminController@delete_major');
    Route::get('/admin/delete_component_category/{id}', 'AdminController@delete_component_category');
    Route::get('/admin/update_course/{id}', 'AdminController@update_course_page');
    Route::get('/admin/update_major/{id}', 'AdminController@update_major_page');
    Route::get('/admin/update_component_category/{id}', 'AdminController@update_component_category_page');
    Route::post('/admin/update_course/{id}', 'AdminController@update_course');
    Route::post('/admin/update_major/{id}', 'AdminController@update_major');
    Route::post('/admin/update_component_category/{id}', 'AdminController@update_component_category');
    Route::get('/admin/delete_accept_component', 'AdminController@delete_accept_component_page');
    Route::get('/admin/delete_component/{id}', 'AdminController@delete_component');
    Route::get('/admin/accept_component/{id}', 'AdminController@accept_component');
    Route::get('/admin/reject_component/{id}', 'AdminController@reject_component');
    Route::get('/admin/add_store', 'AdminController@add_store_page');
    Route::post('/admin/add_store', 'AdminController@add_store');
    Route::get('/admin/delete_store/{id}', 'AdminController@delete_store');
    Route::get('/admin/update_store/{id}', 'AdminController@update_store_page');
    Route::post('/admin/update_store/{id}', 'AdminController@update_store');
    Route::get('/admin/feedbacks', 'AdminController@view_feedbacks');
    Route::get('/admin/reports', 'AdminController@view_reports');
    Route::get('/admin/mail/many', 'AdminController@manyMailView');
    Route::get('/admin/mail/one/{id}', 'AdminController@oneMailView');
    Route::get('/admin/users', 'AdminController@listUsers');
    Route::get('/admin/mail/log', 'AdminController@showMailLog');
    Route::get('/admin/statistics', 'AdminController@statistics');

    Route::get('/admin/event_requests', 'AdminController@eventRequests'); //viewing event request
    Route::get('/admin/request/{id}', 'AdminController@viewRequest'); //viewing event information
    Route::get('/admin/accept/{id}', 'AdminController@acceptRequest'); //accepting an event
    Route::get('/admin/reject/{id}', 'AdminController@rejectRequest'); //rejecting an event
    Route::post('/mail/{type}', 'AdminController@processMailToUsers');

    /** Routes for admin approving/rejectin note upload and deletion **/
    Route::get('admin/note_requests', 'AdminController@noteRequests');
    Route::get('admin/approve_note/{id}', 'AdminController@approveNoteUpload');
    Route::get('admin/delete_note/{id}', 'AdminController@deleteNote');
    Route::get('admin/view_note/{id}', 'AdminController@viewNote');


    Route::get('/browse', 'AppController@browse');
    Route::get('/list_courses/{major}/{semester}', 'AjaxController@getCourses');
    Route::get('/browse/{course_id}', 'AppController@list_questions');
    Route::post('/browse/{course_id}', 'AppController@post_question');
    Route::get('/browse/{major}/{semester}', 'AppController@list_questions_all');
    Route::post('/browse/{major}/{semester}', 'AppController@post_question_all');
    Route::get('/answers/{question_id}', 'AppController@inside_question');
    Route::post('/answers/{question_id}', 'AppController@post_answer');
    Route::get('/delete_answer/{id}', 'AppController@delete_answer');
    Route::get('/delete_question/{id}', 'AppController@delete_question');


    Route::get('/vote/answer/{answer_id}/{type}', 'AjaxController@vote_answer');
    Route::get('/vote/question/{answer_id}/{type}', 'AjaxController@vote_question');


    Route::get('/notifications_partial/', 'AjaxController@view_notifications_partial');
    Route::get('/notifications/', 'AppController@view_notifications');
    Route::get('/mark_notification/{notification_id}/{read}', 'AjaxController@mark_notification');
    Route::get('/subscriptions', 'AppController@subscription_page');
    Route::post('/subscriptions', 'AppController@subscribe_to_courses');


    Route::post('/feedback', 'AppController@send_feedback');
    Route::get('/report_question', 'AjaxController@send_report_question');
    Route::get('/report_answer', 'AjaxController@send_report_answer');
    Route::get('/verify/{token}', 'AuthController@verify');

    Route::post('/note/{note_id}/requestDelete', 'NotesController@request_delete');

    Route::get('/add_component', 'AppController@add_component');
    Route::post('/user/post_component', 'AppController@post_component');

    Route::get('/admin/delete_note/{id}', 'AdminController@deleteNoteAdmin');
    Route::get('/browse/notes/{course_id}', 'AppController@list_notes');
    Route::get('/browse/notes/view_note/{note_id}', 'AppController@view_note');


    /**
     * Create a new calender for the user
     */
    Route::post('calender/create', 'CalenderController@store');
    /**
     * Show a calender for a specific user
     */
    Route::get('calender/{calender_id}', 'CalenderController@show');
    /**
     * View the current authenticated user calender
     */
    Route::get('calender', 'CalenderController@viewCalender');
    /**
     * Add an event to the user's calender
     */
    Route::get('calender/add/{event_id}', 'CalenderController@addEvent');
    /**
     * Show all events
     */
    Route::get('events', 'EventController@index');
    /**
     * Show a specific event
     */
    Route::get('events/{id}', 'EventController@show');
    /**
     * Show the calender of a specific user
     */
    Route::get('user/{user_id}/calender', 'CalenderController@showUserCalender');
    /**
     * Request to delete a note
     */
    Route::post('/note/{note_id}/requestDelete', 'NotesController@request_delete');
    /**
     *  Post comment on a note
     */
    Route::post('/note_comment/{note_id}', 'NotesController@post_note_comment');
    /**
     *  Vote a note
     */
    Route::get('/vote/note/{note_id}/{type}', 'NotesController@vote_note');
    /**
     *  View specific note details
     */
    Route::get('/notes/view_note_details/{note_id}', 'NotesController@view_note_details');

    /**
     * A form to upload a note
     */
    Route::get('/course/{courseID}/uploadNote', 'NotesController@upload_notes_form');
    /**
     * Upload a note
     */
    Route::post('/course/{courseID}/uploadNote', 'NotesController@upload_notes');


});

Route::group(['middleware' => 'web'], function () {
    Route::auth();
    Route::get('/register/verify/{token}', 'Auth\AuthController@verify');
    Route::get('/home', 'HomeController@index');
});

/*
|==========================================================================
| API Routes
|==========================================================================
|
| These routes are related to the API routes of this project
| The routes inside this prefix Matches The "/api/v1/your_route" URL
*/

Route::group(['prefix' => 'api/v1', 'middleware' => ['cors']], function () {

    /*
        |--------------------------
        | Question API Routes
        |--------------------------
    */

    /**
     * Users Authentication
     */
    Route::post('register', 'API\AuthAPIController@register');
    Route::get('register/verify/{token}', 'API\AuthAPIController@verify');
    Route::post('login', 'API\AuthAPIController@login');
    Route::post('logout', 'API\AuthAPIController@logout');

     /**
     * API documentaion
     */    
    Route::get('/', 'ApiController@documentation');

    /*
     * Question header viewing
     */
    Route::get('questions/{id}', 'API\QuestionAPIController@view_question_header');

    /*
     * Question viewing with answers and sorting.
     * */
    Route::get('answers/{id}/{order}', 'API\QuestionAPIController@view_answers');

    /**
     *  Users Profile
     */

    Route::get('user/{id}', 'API\UserAPIController@getUser');
    /*
     * browse majors and semesters API
     */
    Route::get('browse', 'ApiController@browse');
    /*
     * browse courses API
     */
    Route::get('/list_courses/{major}/{semester}', 'ApiController@getCourses');
    /*
     * Browse Questions of a course API
     */
    Route::get('/browse/{course_id}', 'ApiController@list_questions');
    /*
     *  Vote a question
     */
    Route::get('/vote/question/{answer_id}/{type}', 'ApiController@vote_question');
    /*
     *  Post a question
     */
    Route::post('/browse/{course_id}', 'ApiController@post_question');
    /*
     *  Post an answer
     */
    Route::post('/answers/{question_id}', 'ApiController@post_answer');
    /*
     * Home page data
     */
    Route::get('/home', 'ApiController@home');
    /*
     * Get the available components
     */
    Route::get('/components', 'API\ComponentAPIController@index');
    /*
     *  Post a question about a component
     */
    Route::post('/component/ask/{component_id}', 'API\ComponentAPIController@component_ask');
    /*
     *  Post an answer about a component
     */
    Route::post('/component/answers/{question_id}', 'API\ComponentApiController@post_answer');
    
    /*
     * Get the events of a specific course
     */
    Route::get('/events/{course_id}', 'API\EventsAPIController@index');
    /*
     * Create an event of a specific course
     */
    Route::post('/events/{course_id}', 'API\EventsAPIController@create');
    /*
     * Get a list of all of stores
     */
    Route::get('/stores', 'API\StoresAPIController@index');
    /*
     * Get the full details of a specific store
     */
    Route::get('/stores/{store_id}', 'API\StoresAPIController@show');
    /*
     * Post a review of a store
     */
    Route::post('/stores/{store_id}/reviews', 'API\StoresAPIController@addReview');
});
