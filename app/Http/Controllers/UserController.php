<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Store;
use App\Http\Requests;
use Auth;
use App\Major;
use Illuminate\Support\Facades\Session;
use Cloudinary\Uploader;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['only' => [
            'updateInfoPage'
        ]]);
    }

    public function show($id)
    {
        $user = User::find($id);
        if (!$user)
            return 'Ooops! User doesn\'t exit';
        return view('user.questions', compact(['user']));
    }

    public function showProfileAnswers($id)
    {
        $user = User::find($id);
        if (!$user)
            return 'Ooops! User doesn\'t exit';
        return view('user.answers', compact(['user']));
    }

    public function view_storelist()
    {
        $stores = Store::all();
        return view('user.stores', compact(['stores']));
    }
    
    public function view_store_details($id)
    {
        $store = Store::find($id);
        
        return view('user.store_details', compact(['store']));
    }


    public function updateInfoPage()
    {
        $user = Auth::user();
        if (!$user)
            return 'Ooops! Not authorized';
        $majors = Major::all();
        return view('user.update', compact(['user', 'majors']));
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
        if ($request->major)
            $user->major_id = $request->major;
        else
            $user->major_id = null;
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
        return redirect(url('/user/' . $user->id));
    }

    /**
     * @param uri : the uri to the image to be deleted
     */
    private function delete_image($uri)
    {
        // extract the public id
        $tmp = explode('/', $uri);
        $public_id = end($tmp);
        // remove the extension
        $tmp = explode('.', $public_id);
        $tmp = array_slice($tmp, 0, -1);
        $public_id = implode(".", $tmp);
        Uploader::destroy($public_id);
    }
}
