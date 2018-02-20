<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Store;
use App\Review;
use App\Http\Requests;
use Auth;
use App\Major;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
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
        if (!$user) {
            return 'Ooops! User doesn\'t exist';
        }
        return view('user.questions', compact(['user']));
    }

    public function showProfileAnswers($id)
    {
        $user = User::find($id);
        if (!$user) {
            return 'Ooops! User doesn\'t exist';
        }
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
        $reviews = Review::where("store_id", $id)->get();

        $reviews = DB::table('reviews')
                ->where('review', '<>', '')
                ->join('users', 'users.id', '=', 'reviews.user_id')
                ->select("reviews.rate", "reviews.review", "users.id", "users.first_name", "users.last_name")
                ->get();

        return view('user.store_details', compact('store', 'reviews'));
    }

    // Add review for a specific store
    public function add_review($id, Request $request)
    {
        $user = Auth::user();
        // TODO modify a suitable view for such exceptions
        if (!$user) {
            return 'Ooops! Not authorized';
        }


        $this->validate($request, [
            'rate' => 'numeric|min:1|max:10'
        ]);

        $currentStore = Store::find($id);

        // Returns any previous review made by the user to the current store
        $entry = Review::where([
          ["user_id", "=", $user->id],
          ["store_id", "=", $currentStore["id"]]
        ])->get();

        // If the user didn't make a review before
        if (count($entry)==0) {
            // Create a new review
            $review = new Review();
            $review->review = $request->input("review");
            $review->rate = $request->input("rate");
            $review->user_id = $user->id;
            $review->store_id = $id;

            $review->save();

            $currentStore->add_rating($request->input("rate"));
        } else {
            // Else update the user's previous review with the new input
            $review = $request->input("review");


            $entry = $entry[0];

            // If the user didn't enter a review then only the rate will be updated
            if ($review==null) {
                $review = $entry->review;
            }

            $currentStore->alter_rating($entry["rate"], $request->input("rate"));

            $entry->rate = $request->input("rate");
            $entry->review = $review;

            $entry->save();
        }

        return redirect(url('/user/stores/' . $id));
    }

    public function updateInfoPage()
    {
        $user = Auth::user();
        if (!$user) {
            return 'Ooops! Not authorized';
        }
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
