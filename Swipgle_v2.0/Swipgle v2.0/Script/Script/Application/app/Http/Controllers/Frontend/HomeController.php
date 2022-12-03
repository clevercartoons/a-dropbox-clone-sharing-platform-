<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BlogArticle;
use App\Models\Faq;
use App\Models\Feature;
use App\Models\Plan;
use App\Models\Rating;
use App\Models\Transfer;
use App\Models\TransferFile;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $features = Feature::where('lang', getLang())->latest()->get();
        $monthlyPlans = Plan::where('interval', 0)->get();
        $yearlyPlans = Plan::where('interval', 1)->get();
        $blogArticles = BlogArticle::where('lang', getLang())->with(['blogCategory', 'admin'])->latest()->limit(3)->get();
        $faqs = Faq::where('lang', getLang())->latest()->limit(10)->get();
        $ratings = Rating::all();
        $rating = Rating::where('ip', vIpInfo()->ip)->first();

        $statistics_download_files = settings('statistics_download_files') ?? TransferFile::where('downloads', '!=', 0)->count();
        $statistics_send_files = settings('statistics_send_files') ?? TransferFile::all()->count();
        $statistics_total_transfers = settings('statistics_total_transfers') ?? Transfer::where('status', 1)->count();

        return view('frontend.home', [
            'features' => $features,
            'monthlyPlans' => $monthlyPlans,
            'yearlyPlans' => $yearlyPlans,
            'blogArticles' => $blogArticles,
            'faqs' => $faqs,
            'ratings' => $ratings,
            'rating' => $rating,
            'statistics_download_files' => $statistics_download_files,
            'statistics_send_files' => $statistics_send_files,
            'statistics_total_transfers' => $statistics_total_transfers,
        ]);
    }

    public function rate(Request $request)
    {
        $rating = Rating::where('ip', vIpInfo()->ip)->first();
        if (!is_null($rating)) {
            return response()->json(['error' => lang('You have already rate the service', 'upload zone')]);
        }
        $arr = [1, 2, 3, 4, 5];
        if (!in_array($request->stars, $arr)) {
            return response()->json(['error' => lang('Invalid rating request', 'upload zone')]);
        }
        $createRating = Rating::create(['ip' => vIpInfo()->ip, 'stars' => $request->stars]);
        if ($createRating) {
            $stars = $createRating->stars > 1 ? 'stars' : 'star';
            $title = 'New rating (' . $createRating->stars . ' ' . $stars . ')';
            $image = asset('images/icons/rating.png');
            $link = route('admin.ratings.index');
            adminNotify($title, $image, $link);
            return response()->json(['success' => lang('Rating recorded successfully', 'upload zone')]);
        }
    }
}
