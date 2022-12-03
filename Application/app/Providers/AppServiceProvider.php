<?php

namespace App\Providers;

use App\Models\AdminNotification;
use App\Models\BlogArticle;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\Faq;
use App\Models\Feature;
use App\Models\FooterMenu;
use App\Models\Language;
use App\Models\NavbarMenu;
use App\Models\Plan;
use App\Models\Rating;
use App\Models\SeoConfiguration;
use App\Models\Subscription;
use App\Models\SupportTicket;
use App\Models\Transaction;
use App\Models\Transfer;
use App\Models\User;
use App\Models\UserNotification;
use Config;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (env('VIRONEER_SYSTEMSTATUS')) {

            Paginator::useBootstrap();
            Config::set('laravellocalization.supportedLocales', getSupportedLocales());

            view()->composer('*', function ($view) {
                $view->with(['settings' => settings(), 'additionals' => additionals()]);
            });

            if (request()->segment(1) != "admin") {
                if (settings('website_force_ssl_status')) {
                    $this->app['request']->server->set('HTTPS', true);
                }
                view()->composer('*', function ($view) {
                    $languages = Language::all();
                    $view->with('languages', $languages);
                });
                view()->composer(['frontend.configurations.metaTags', 'frontend.home'], function ($view) {
                    $lang = LaravelLocalization::getCurrentLocale();
                    $SeoConfiguration = SeoConfiguration::where('lang', $lang)->with('language')->first();
                    $view->with('SeoConfiguration', $SeoConfiguration);
                });
                view()->composer('frontend.layouts.front', function ($view) {
                    $features = Feature::where('lang', getLang())->latest()->get();
                    $monthlyPlans = Plan::where('interval', 0)->get();
                    $yearlyPlans = Plan::where('interval', 1)->get();
                    $blogArticles = BlogArticle::where('lang', getLang())->with(['blogCategory', 'admin'])->latest()->limit(3)->get();
                    $faqs = Faq::where('lang', getLang())->latest()->limit(10)->get();
                    $view->with([
                        'features' => $features,
                        'monthlyPlans' => $monthlyPlans,
                        'yearlyPlans' => $yearlyPlans,
                        'blogArticles' => $blogArticles,
                        'faqs' => $faqs,
                    ]);
                });
                view()->composer('frontend.user.layouts.dash', function ($view) {
                    $userNotifications = UserNotification::where('user_id', userAuthInfo()->id)->orderbyDesc('id')->limit(20)->get();
                    $unreadUserNotifications = UserNotification::where([['status', 0], ['user_id', userAuthInfo()->id]])->get()->count();
                    $repliedTicketsCount = SupportTicket::where([['status', 2], ['user_id', userAuthInfo()->id]])->get()->count();
                    $unreadUserNotificationsAll = $unreadUserNotifications;
                    if ($unreadUserNotifications > 9) {
                        $unreadUserNotifications = "9+";
                    }
                    $view->with([
                        'userNotifications' => $userNotifications,
                        'unreadUserNotifications' => $unreadUserNotifications,
                        'repliedTicketsCount' => $repliedTicketsCount,
                        'unreadUserNotificationsAll' => $unreadUserNotificationsAll,
                    ]);
                });
                view()->composer('frontend.includes.footer', function ($view) {
                    $footerMenuLinks = FooterMenu::where('lang', getLang())->orderBy('sort_id', 'asc')->get();
                    $view->with('footerMenuLinks', $footerMenuLinks);
                });
                view()->composer('frontend.includes.header', function ($view) {
                    if (request()->routeIs('home') || request()->routeIs('transfer.download.index') || request()->routeIs('transfer.download.password')) {
                        $navbarLinks = NavbarMenu::where([['lang', getLang()], ['page', 0]])->orderBy('sort_id', 'asc')->get();
                    } else {
                        $navbarLinks = NavbarMenu::where([['lang', getLang()], ['page', 1]])->orderBy('sort_id', 'asc')->get();
                    }
                    $navbarMenuLinks = $navbarLinks;
                    $view->with('navbarMenuLinks', $navbarMenuLinks);
                });
                view()->composer('frontend.includes.blogSidebar', function ($view) {
                    $recentBlogArticles = BlogArticle::where('lang', getLang())->orderbyDesc('views')->limit(5)->get();
                    $blogCategories = BlogCategory::where('lang', getLang())->orderbyDesc('views')->limit(10)->get();
                    $view->with(['recentBlogArticles' => $recentBlogArticles, 'blogCategories' => $blogCategories]);
                });
            }

            if (request()->segment(1) == "admin") {
                view()->composer('*', function ($view) {
                    $adminLanguages = Language::all();
                    $view->with('adminLanguages', $adminLanguages);
                });
                view()->composer('backend.includes.header', function ($view) {
                    $adminNotifications = AdminNotification::orderbyDesc('id')->limit(20)->get();
                    $unreadAdminNotifications = AdminNotification::where('status', 0)->count();
                    $unreadAdminNotificationsAll = $unreadAdminNotifications;
                    if ($unreadAdminNotifications > 9) {
                        $unreadAdminNotifications = "9+";
                    }
                    $view->with([
                        'adminNotifications' => $adminNotifications,
                        'unreadAdminNotifications' => $unreadAdminNotifications,
                        'unreadAdminNotificationsAll' => $unreadAdminNotificationsAll,
                    ]);
                });
                view()->composer('backend.includes.sidebar', function ($view) {
                    $unreadUsersCount = User::where('read_status', 0)->count();
                    $unreadSubscriptions = Subscription::where('read_status', 0)->count();
                    $unreadTransactionsCount = Transaction::where([['status', '!=', 0], ['read_status', 0]])->count();
                    $unreadUsersTransfersCount = Transfer::where([['user_id', '!=', null], ['read_status', 0]])->count();
                    $unreadGuestsTransfersCount = Transfer::where([['user_id', null], ['read_status', 0]])->count();
                    $unreadRatingsCount = Rating::where('read_status', 0)->count();
                    $ticketsNeedsAction = SupportTicket::where('status', 0)->OrWhere('status', 1)->count();
                    $commentsNeedsAction = BlogComment::where('status', 0)->count();
                    $view->with([
                        'unreadUsersCount' => $unreadUsersCount,
                        'unreadSubscriptions' => $unreadSubscriptions,
                        'ticketsNeedsAction' => $ticketsNeedsAction,
                        'commentsNeedsAction' => $commentsNeedsAction,
                        'unreadTransactionsCount' => $unreadTransactionsCount,
                        'unreadUsersTransfersCount' => $unreadUsersTransfersCount,
                        'unreadGuestsTransfersCount' => $unreadGuestsTransfersCount,
                        'unreadRatingsCount' => $unreadRatingsCount,
                    ]);
                });
            }
        }
    }
}
