<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Rating;

class RatingController extends Controller
{
    public function index()
    {
        $unreadRatings = Rating::where('read_status', 0)->get();
        if (count($unreadRatings) > 0) {
            foreach ($unreadRatings as $unreadRating) {
                $unreadRating->read_status = 1;
                $unreadRating->save();
            }
        }
        $ratings = Rating::all();
        return view('backend.ratings.index', ['ratings' => $ratings]);
    }

    public function destroy($id)
    {
        $rating = Rating::findOrFail($id);
        $rating->delete();
        toastr()->success(__('Deleted Successfully'));
        return back();
    }
}
