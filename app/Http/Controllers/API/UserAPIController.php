<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\User;
/**
 * Class UserAPIController
 * @package App\Http\Controllers\API
 */

class UserAPIController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['only' => [
            'updateInfo'
        ]]);

    }


      /**
       * getUser is used to get all user's profile data
       */

    public function getUser($userID){
        // get specified user by id
        $user = User::find($userID);

        // check if the user is valid
        if(!$user)
          // create an error json object to be send in the http response
          return response()->json([
              'status'=> '404',
              'message'=> 'Bad Request',
              "errors"=>
                  [
                    'resourse' => 'users',
                    'message'=> 'Invalid User ID'
                  ]

            ]
            ,404);


          // the user is valid

          // get most recently five answers answered by this user
          $answers =  $user->lastFiveAnswers();

          // get most recently five questions asked by this user
          $questions = $user->lastFiveQuestions();

          // create returned success json object
          return response()->json(
            [
            'status'=> '200',
            'message'=> 'OK',
            'results'=>[
                'first_name'      => $user->first_name,
                'last_name'       => $user->last_name,
                'email'           => $user->email,
                'major'           => $user->major,
                'semester'        => $user->semester,
                'bio'             => $user->bio,
                'profile_picture' => $user->profile_picture,
                'questions'       => $questions,
                'answers'         => $answers
            ]
          ]
             ,200);

    }

    public function updateInfo(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'alpha|required',
            'last_name' => 'alpha|required',
            'major' => 'sometimes|numeric|exists:majors,id',
            'semester' => 'numeric|min:0|max:10',
            'profile_picture' => 'image|max:1000'
        ]);
        $user = Auth::user();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->semester = $request->semester;
        if ($request->major) {
            $user->major_id = $request->major;
        } else {
            $user->major_id = null;
        }
        $user->bio = $request->bio;
        if ($request->file('profile_picture')) {
            \Cloudinary::config(array(
                "cloud_name" => env("CLOUDINARY_NAME"),
                "api_key" => env("CLOUDINARY_KEY"),
                "api_secret" => env("CLOUDINARY_SECRET")
            ));
            if ($user->profile_picture) {
                // delete previous profile picture
                $this->delete_image($user->profile_picture);
            }
            // upload and set new picture
            $file = $request->file('profile_picture');
            $image = Uploader::upload($file->getRealPath(), ["width" => 300, "height" => 300, "crop" => "limit"]);
            $user->profile_picture = $image["url"];
        }
        $user->save();
        Session::flash('updated', 'Info updated successfully!');
        return ['state' => '200 ok', 'error' => false];
    }


}
