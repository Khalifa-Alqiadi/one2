<?php

use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\Dashboard\FileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\SiteMapController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Language Route
Route::post('/lang', [LanguageController::class, 'index'])->middleware('LanguageSwitcher')->name('lang');
// For Language direct URL link
Route::get('/lang/{lang}', [LanguageController::class, 'change'])->middleware('LanguageSwitcher')->name('langChange');
Route::get('/locale/{lang}', [LanguageController::class, 'locale'])->middleware('LanguageSwitcher')->name('localeChange');
// .. End of Language Route

// Not Found
Route::get('/{lang?}/404', [HomeController::class, 'page_404'])->name('NotFound');


// RSS Feed Routes
if (config('smartend.rss_status')) {
    Route::feeds();
}

// Social Auth
Route::get('/oauth/{driver}', [SocialAuthController::class, 'redirectToProvider'])->name('social.oauth');
Route::get('/oauth/{driver}/callback', [SocialAuthController::class, 'handleProviderCallback'])->name('social.callback');

Route::Group(['prefix' => config('smartend.backend_path')], function () {
    Auth::routes();
});

// define dashboard routes
include base_path('routes/dashboard.php');
// define APIs routes
include base_path('routes/apis.php');
// define Custom routes
include base_path('routes/custom.php');

// Files Routes
Route::get('/files/{path?}', [FileController::class, 'view'])->where('path', '.*')->name('fileView');
Route::get('/downloads/{path?}', [FileController::class, 'download'])->where('path', '.*')->name('fileDownload');

// Start of Frontend Routes
// - site map
Route::get('/sitemap.xml', [SiteMapController::class, 'siteMap'])->name('siteMap');
Route::get('/{lang}/sitemap', [SiteMapController::class, 'siteMap'])->name('siteMapByLang');

// - Public form submit
Route::post('/form-submit', [HomeController::class, 'form_submit'])->name('formSubmit');

// - Newsletter form submit
Route::post('/subscribe', [HomeController::class, 'subscribe_submit'])->name('subscribeSubmit');

// - Comment form submit
Route::post('/comment', [HomeController::class, 'comment_submit'])->name('commentSubmit');

// - Order form submit
Route::post('/order', [HomeController::class, 'order_submit'])->name('orderSubmit');

// - Contact page form submit
Route::post('/contact-submit', [HomeController::class, 'contact_submit'])->name('contactPageSubmit');

// - Tags
Route::get('/tags', [HomeController::class, 'tags'])->name('tagsListPage');
Route::get('/tag/{tag_slug?}', [HomeController::class, 'tag'])->name('tag');

// - All Other slugs
Route::get('/{part1?}/{part2?}/{part3?}/{part4?}/{part5?}/{part6?}', [HomeController::class, 'seo'])->name("frontendRoute");
// End of Frontend Route
