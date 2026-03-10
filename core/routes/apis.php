<?php
use App\Http\Controllers\APIs\APIsController;

Route::Group(['prefix' => '/api/v1', 'middleware' => ['LanguageSwitcher']], function () {
    Route::get('/', [APIsController::class, 'api'])->name('apiURL');
// general
    Route::get('/website/status', [APIsController::class, 'website_status']);
    Route::get('/website/info', [APIsController::class, 'website_info']);
    Route::get('/website/contacts', [APIsController::class, 'website_contacts']);
    Route::get('/website/style', [APIsController::class, 'website_style']);
    Route::get('/website/social', [APIsController::class, 'website_social']);
    Route::get('/website/settings', [APIsController::class, 'website_settings']);
    Route::get('/menu/{menu_id}', [APIsController::class, 'menu']);
    Route::get('/banners/{group_id}', [APIsController::class, 'banners']);
// section & topics
    Route::get('/section/{section_id}', [APIsController::class, 'section']);
    Route::get('/categories/{section_id}', [APIsController::class, 'categories']);
    Route::get('/topics/{section_id}/page/{page_number?}/count/{topics_count?}', [APIsController::class, 'topics']);
    Route::get('/category/{cat_id}/page/{page_number?}/count/{topics_count?}', [APIsController::class, 'category']);
// topic sub details
    Route::get('/topic/fields/{topic_id}', [APIsController::class, 'topic_fields']);
    Route::get('/topic/photos/{topic_id}', [APIsController::class, 'topic_photos']);
    Route::get('/topic/photo/{photo_id}', [APIsController::class, 'topic_photo']);
    Route::get('/topic/maps/{topic_id}', [APIsController::class, 'topic_maps']);
    Route::get('/topic/map/{map_id}', [APIsController::class, 'topic_map']);
    Route::get('/topic/files/{topic_id}', [APIsController::class, 'topic_files']);
    Route::get('/topic/file/{file_id}', [APIsController::class, 'topic_file']);
    Route::get('/topic/comments/{topic_id}', [APIsController::class, 'topic_comments']);
    Route::get('/topic/comment/{comment_id}', [APIsController::class, 'topic_comment']);
    Route::get('/topic/related/{topic_id}', [APIsController::class, 'topic_related']);
// topic page
    Route::get('/topic/{topic_id}', [APIsController::class, 'topic']);
// user topics
    Route::get('/user/{user_id}/topics/page/{page_number?}/count/{topics_count?}', [APIsController::class, 'user_topics']);
// Forms Submit
    Route::post('/subscribe', [APIsController::class, 'subscribeSubmit']);
    Route::post('/comment', [APIsController::class, 'commentSubmit']);
    Route::post('/order', [APIsController::class, 'orderSubmit']);
    Route::post('/contact', [APIsController::class, 'ContactPageSubmit']);
});
