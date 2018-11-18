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
        if (Auth::user()) {
            return redirect('/home');
        }
        return view('welcome');
    });

    /*
     * Get the available components
     */

    Route::get('/about', 'StaticController@about');
    Route::get('/howitworks', 'StaticController@howitworks');
    Route::get('/user/components/{category_id}', 'AppController@view_components');
    Route::get('/user/component/{id}', 'AppController@component_details');
    Route::get('/user/components/{component_id}/delete/{question_id}', 'AppController@delete_component_question');
    Route::get('/user/components/{component_id}/bookmark/{question_id}', 'AppController@bookmark_component_question');
    Route::get('/user/delete_component_answers/{question_id}/{answer_id}', 'AppController@delete_component_answer');
    Route::get('/user/update', 'UserController@updateInfoPage');
    Route::post('/user/update', 'UserController@updateInfo');
    Route::get('/user/stores', 'UserController@view_storelist');
    Route::get('/user/stores/{id}', 'UserController@view_store_details');
    Route::post('/user/stores/{id}', 'UserController@add_review');
    Route::get('/user/{id}', 'UserController@show');
    Route::get('/user/{id}/questions', 'UserController@show');
    Route::get('/user/bookmark/{id}', 'AppController@bookmark_question');
    Route::get('/user/{id}/answers', 'UserController@showProfileAnswers');
    Route::get('/user/{id}/bookmarks', 'UserController@showProfileBookmarks');
    Route::get('/pending_products', 'UserController@pending_products');

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

    // Announcements Routes
    Route::get('/admin/add_announcement', 'AdminController@add_announcement_page');//add announcement from admin roles page
    Route::post('/admin/add_announcement', 'AdminController@add_announcement');

    Route::post('/event/add_announcement/{event_id}', 'EventController@add_announcement');//add announcement from event page

    // Events Routes
    Route::get('/admin/add_event', 'AdminController@add_event_page');
    Route::get('/course/add_event/{course_id}', 'EventController@add_event_page');
    Route::post('/admin/add_event', 'AdminController@add_event');
    Route::get('/delete_event/{id}', 'EventController@deleteEvent');
    Route::get('/admin/event_requests', 'AdminController@eventRequests'); //viewing event request
    Route::get('/admin/request/{id}', 'AdminController@viewRequest'); //viewing event information
    Route::get('/admin/accept/{id}', 'AdminController@acceptRequest'); //accepting an event
    Route::get('/admin/reject/{id}', 'AdminController@rejectRequest'); //rejecting an event
    Route::post('/mail/{type}', 'AdminController@processMailToUsers');

    /** Routes for admin approving/rejectin note upload and deletion **/
    Route::get('admin/note_requests', 'AdminController@noteRequests');
    Route::get('admin/approve_note/{id}', 'AdminController@approveNoteUpload');
    Route::get('admin/delete_note/{id}', 'AdminController@deleteNote');
    Route::get('admin/reject_note_delete/{id}', 'AdminController@reject_note_delete');
    Route::get('admin/view_note/{id}', 'AdminController@viewNote');


    Route::get('/browse', 'AppController@browse');
    Route::get('/list_courses/{major}/{semester}', 'AjaxController@getCourses');
    Route::get('/browse/{course_id}', 'AppController@list_questions');
    Route::post('/browse/{course_id}', 'AppController@post_question');
    Route::get('/browse/questions/{major_id}/{semester}', 'AppController@list_questions_all');
    Route::post('/browse/{major}/{semester}', 'AppController@post_question_all');
    Route::get('/answers/{question_id}', 'AppController@inside_question');
    Route::post('/answers/{question_id}', 'AppController@post_answer');
    Route::get('/delete_answer/{id}', 'AppController@delete_answer');
    Route::get('/delete_question/{id}', 'AppController@delete_question');


    Route::get('/vote/answer/{answer_id}/{type}', 'AjaxController@vote_answer');
    Route::get('/vote/question/{question_id}/{type}', 'AjaxController@vote_question');


    Route::get('/notifications_partial/', 'AjaxController@view_notifications_partial');
    Route::get('/notifications/', 'AppController@view_notifications');
    Route::get('/mark_notification/{notification_id}/{read}', 'AjaxController@mark_notification');
    Route::get('/subscriptions', 'AppController@subscription_page');
    Route::post('/subscriptions', 'AppController@subscribe_to_courses');


    Route::post('/feedback', 'AppController@send_feedback');
    Route::get('/report_question', 'AjaxController@send_report_question');
    Route::get('/report_answer', 'AjaxController@send_report_answer');
    Route::get('/verify/{token}', 'AuthController@verify');

    Route::get('/add_component', 'AppController@add_component');
    Route::post('/user/post_component', 'AppController@post_component');

    //WIP
    Route::post('/user/post_component_question/{component_id}', 'AppController@post_component_question');
    Route::post('/user/post_component_answer/{question_id}', 'AppController@post_component_answer');
    Route::get('/user/view_component_answers/{question_id}', 'AppController@View_component_answers');
    //END WIP

    /**
     * Download course and component question/answer attachements.
     */
    Route::get('/user/component_question/download_attachement/{question_id}', 'AppController@download_component_question_attachement');
    Route::get('/user/question/download_attachement/{question_id}', 'AppController@download_question_attachement');
    Route::get('/user/component_answer/download_attachement/{answer_id}', 'AppController@download_component_answer_attachement');
    Route::get('/user/answer/download_attachement/{answer_id}', 'AppController@download_answer_attachement');

    /**
     * Edit note comment
     */
    Route::get('/edit_comment', 'AjaxController@edit_note_comment');
    /**
     * Edit component question
     */
    Route::get('/edit_component_question', 'AjaxController@edit_component_question');
    /**
     * Edit course question
     */
    Route::get('/edit_question', 'AjaxController@edit_question');
    /**
     * Edit component answer
     */
    Route::get('/edit_component_answer', 'AjaxController@edit_component_answer');
    /**
     * Edit course answer
     */
    Route::get('/edit_answer', 'AjaxController@edit_answer');

    Route::get('/admin/delete_note/{id}', 'AdminController@deleteNoteAdmin');
    Route::get('/browse/notes/{course_id}', 'AppController@list_notes');
    Route::get('/browse/notes/view_note/{note_id}', 'NotesController@downloadNote');

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
    Route::get('/note/request_delete', 'NotesController@request_delete');
    /**
     *  Post comment on a note
     */
    Route::post('/note_comment/{note_id}', 'NotesController@post_note_comment');
    /**
     *  Delete comment on a note
     */
    Route::get('/delete_note_comment/{note_id}/{comment_id}', 'NotesController@delete_note_comment');
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

Route::group(['prefix' => 'api/v1', 'middleware' => []], function () {

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
    Route::post('/vote/question/{question_id}/{type}', 'API\QuestionAPIController@vote_question');
    /*
     *  Bookmark a question
     */
    Route::post('/bookmark_question/{id}/', 'API\QuestionAPIController@bookmark_question');
    /*
     *  Edit a component question
     */
    Route::post('/question/edit/{question_id}', 'API\QuestionAPIController@edit_question');
    /*
     *  Edit a component question
     */
    Route::post('/answer/edit/{answer_id}', 'API\QuestionAPIController@edit_answer');
    /*
     *  Vote an answer
     */
    Route::post('/vote/answer/{answer_id}/{type}', 'API\QuestionAPIController@vote_answer');
    /*
     *  Post a question
     */
    Route::post('/browse/{course_id}', 'ApiController@post_question');
    /*
     *  Post an answer
     */
    Route::post('/answers/{question_id}', 'ApiController@post_answer');
    /*
     *  Edit a component question
     */
    Route::post('/component/edit_question/{question_id}', 'API\ComponentAPIController@edit_component_question');
    /*
     *  Edit a component question
     */
    Route::post('/component/edit_answer/{answer_id}', 'API\ComponentAPIController@edit_component_answer');
    /*
     * Home page data
     */
    Route::get('/home', 'ApiController@home');
    /*
     * Get the available components
     */
    Route::get('/components', 'API\ComponentAPIController@index');
    /*
     * Get the full details of a specific component
     */
    Route::get('/components/{component_id}', 'API\ComponentAPIController@show');
    /*
     *  Post a question about a component
     */
    Route::post('/component/ask/{component_id}', 'API\ComponentAPIController@component_ask');
    /*
     *  Post an answer about a component
     */
    Route::post('/component/answers/{question_id}', 'API\ComponentAPIController@post_answer');
    /*
     *  Get answers about a component question
     */
    Route::get('/component/view_answers/{question_id}', 'API\ComponentAPIController@view_answer');
    /*
     *  Bookmark a component question
     */
    Route::post('/component/bookmark_question/{question_id}/', 'API\ComponentAPIController@bookmark_component_question');
    /*
     * Get the events of a specific course
     */
    Route::get('/events/{course_id}', 'API\EventsAPIController@index');
    /*
     * Get the details of an event
     */
    Route::get('/event_details/{id}', 'API\EventsAPIController@show');
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
    /*
     * Get a list of all of notes of a specific course
     */
    Route::get('/notes/{course_id}', 'API\NotesAPIController@index');
    /*
     * Get the full details of a note
     */
    Route::get('/note_details/{note_id}', 'API\NotesAPIController@show');
    /*
     *  Post a comment about a note
     */
    Route::post('/note/comment/{note_id}', 'API\NotesAPIController@post_comment');
    /*
     *  Vote a note
     */
    Route::post('/note/vote/{note_id}/{type}', 'API\NotesAPIController@vote_note');
    /*
     *  Vote a note
     */
    Route::post('/note/request_delete/{note_id}', 'API\NotesAPIController@request_delete');
    /*
     *  Edit a note comment
     */
    Route::post('/note/edit_comment/{comment_id}', 'API\NotesAPIController@edit_note_comment');
});
