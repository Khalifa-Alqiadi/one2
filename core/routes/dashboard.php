<?php

use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\LandingPageController;
use App\Http\Controllers\Dashboard\TopicBlockController;
use App\Http\Controllers\Dashboard\UsersController;
use App\Http\Controllers\Dashboard\WebmasterLicenseController;
use App\Http\Controllers\Dashboard\WebmasterSettingsController;
use App\Http\Controllers\Dashboard\WebmasterBannersController;
use App\Http\Controllers\Dashboard\WebmasterSectionsController;
use App\Http\Controllers\Dashboard\SettingsController;
use App\Http\Controllers\Dashboard\BannersController;
use App\Http\Controllers\Dashboard\CategoriesController;
use App\Http\Controllers\Dashboard\Football\LeaguesController;
use App\Http\Controllers\Dashboard\Football\SeasonsController;
use App\Http\Controllers\Dashboard\TopicsController;
use App\Http\Controllers\Dashboard\ContactsController;
use App\Http\Controllers\Dashboard\WebmailsController;
use App\Http\Controllers\Dashboard\EventsController;
use App\Http\Controllers\Dashboard\AnalyticsController;
use App\Http\Controllers\Dashboard\MenusController;
use App\Http\Controllers\Dashboard\FileManagerController;
use App\Http\Controllers\Dashboard\Football\RoundsController;
use App\Http\Controllers\Dashboard\MatchesController;
use App\Http\Controllers\Dashboard\TagController;
use App\Http\Controllers\Dashboard\PopupController;
use App\Http\Controllers\Dashboard\TeamsController;
use Illuminate\Support\Facades\Route;

Route::Group(['prefix' => config('smartend.backend_path'), 'middleware' => ['auth', 'LanguageSwitcher']], function () {
// Admin Home
    Route::get('/', [DashboardController::class, 'index'])->name('adminHome');
//Search
    Route::get('/search', [DashboardController::class, 'search'])->name('adminSearch');

// Webmaster
    Route::get('/webmaster', [WebmasterSettingsController::class, 'edit'])->name('webmasterSettings');
    Route::get('/webmaster-save/{tab?}', [WebmasterSettingsController::class, 'saved'])->name('webmasterSettingsSaved');
    Route::post('/webmaster', [WebmasterSettingsController::class, 'update'])->name('webmasterSettingsUpdate');
    Route::post('/webmaster/languages/store',
        [WebmasterSettingsController::class, 'language_store'])->name('webmasterLanguageStore');
    Route::post('/webmaster/languages/store',
        [WebmasterSettingsController::class, 'language_store'])->name('webmasterLanguageStore');
    Route::post('/webmaster/languages/update',
        [WebmasterSettingsController::class, 'language_update'])->name('webmasterLanguageUpdate');
    Route::get('/webmaster/languages/destroy/{id}',
        [WebmasterSettingsController::class, 'language_destroy'])->name('webmasterLanguageDestroy');
    Route::get('/webmaster/seo/repair', [WebmasterSettingsController::class, 'seo_repair'])->name('webmasterSEORepair');

    Route::post('/webmaster/mail/smtp', [WebmasterSettingsController::class, 'mail_smtp_check'])->name('mailSMTPCheck');
    Route::post('/webmaster/mail/test', [WebmasterSettingsController::class, 'mail_test'])->name('mailTest');


    Route::post('/webmaster-license', [WebmasterLicenseController::class, 'index'])->name('licenseCheck');

// Webmaster Sections
    Route::get('/modules', [WebmasterSectionsController::class, 'index'])->name('WebmasterSections');
    Route::get('/modules/create', [WebmasterSectionsController::class, 'create'])->name('WebmasterSectionsCreate');
    Route::post('/modules/store', [WebmasterSectionsController::class, 'store'])->name('WebmasterSectionsStore');
    Route::get('/modules/{id}/edit', [WebmasterSectionsController::class, 'edit'])->name('WebmasterSectionsEdit');
    Route::post('/modules/{id}/update',
        [WebmasterSectionsController::class, 'update'])->name('WebmasterSectionsUpdate');

    Route::post('/modules/{id}/seo', [WebmasterSectionsController::class, 'seo'])->name('WebmasterSectionsSEOUpdate');
    Route::post('/modules/{id}/code',
        [WebmasterSectionsController::class, 'custom_code'])->name('WebmasterSectionsCodeUpdate');

    Route::get('/modules/destroy/{id?}',
        [WebmasterSectionsController::class, 'destroy'])->name('WebmasterSectionsDestroy');
    Route::get('/modules/clone/{id?}',
        [WebmasterSectionsController::class, 'clone'])->name('WebmasterSectionsClone');
    Route::post('/modules/updateAll',
        [WebmasterSectionsController::class, 'updateAll'])->name('WebmasterSectionsUpdateAll');

// Webmaster Sections :Custom Fields
    Route::get('/modules/{webmasterId}/fields',
        [WebmasterSectionsController::class, 'webmasterFields'])->name('webmasterFields');
    Route::get('/{webmasterId}/fields/create',
        [WebmasterSectionsController::class, 'fieldsCreate'])->name('webmasterFieldsCreate');
    Route::post('/modules/{webmasterId}/fields/store',
        [WebmasterSectionsController::class, 'fieldsStore'])->name('webmasterFieldsStore');
    Route::get('/modules/{webmasterId}/fields/{field_id}/edit',
        [WebmasterSectionsController::class, 'fieldsEdit'])->name('webmasterFieldsEdit');
    Route::post('/modules/{webmasterId}/fields/{field_id}/update',
        [WebmasterSectionsController::class, 'fieldsUpdate'])->name('webmasterFieldsUpdate');
    Route::get('/modules/{webmasterId}/fields/destroy/{field_id}',
        [WebmasterSectionsController::class, 'fieldsDestroy'])->name('webmasterFieldsDestroy');
    Route::post('/modules/{webmasterId}/fields/updateAll',
        [WebmasterSectionsController::class, 'fieldsUpdateAll'])->name('webmasterFieldsUpdateAll');

// Settings
    Route::get('/settings', [SettingsController::class, 'edit'])->name('settings');
    Route::post('/settings', [SettingsController::class, 'updateSiteInfo'])->name('settingsUpdateSiteInfo');

// Social Links Routes
    Route::get('/settings/social-links', [SettingsController::class, 'getSocialLinks'])->name('settingsGetSocialLinks');
    Route::post('/settings/save-social-link', [SettingsController::class, 'saveSocialLink'])->name('settingsSaveSocialLinks');
    Route::post('/settings/save-social-link-order', [SettingsController::class, 'saveSocialLinkOrder'])->name('settingsSaveSocialLinksOrder');
    Route::post('/settings/delete-social-link', [SettingsController::class, 'deleteSocialLink'])->name('settingsDeleteSocialLinks');

// Ad. Banners
    Route::get('/banners', [BannersController::class, 'index'])->name('Banners');
    Route::get('/banners/create/{sectionId}', [BannersController::class, 'create'])->name('BannersCreate');
    Route::post('/banners/store', [BannersController::class, 'store'])->name('BannersStore');
    Route::get('/banners/{id}/edit', [BannersController::class, 'edit'])->name('BannersEdit');
    Route::post('/banners/{id}/update', [BannersController::class, 'update'])->name('BannersUpdate');
    Route::get('/banners/destroy/{id?}', [BannersController::class, 'destroy'])->name('BannersDestroy');
    Route::post('/banners/updateAll', [BannersController::class, 'updateAll'])->name('BannersUpdateAll');

// Webmaster Banners
    Route::get('/banners-settings', [WebmasterBannersController::class, 'index'])->name('WebmasterBanners');
    Route::get('/banners-settings/create',
        [WebmasterBannersController::class, 'create'])->name('WebmasterBannersCreate');
    Route::post('/banners-settings/store', [WebmasterBannersController::class, 'store'])->name('WebmasterBannersStore');
    Route::get('/banners-settings/{id}/edit',
        [WebmasterBannersController::class, 'edit'])->name('WebmasterBannersEdit');
    Route::post('/banners-settings/{id}/update',
        [WebmasterBannersController::class, 'update'])->name('WebmasterBannersUpdate');
    Route::get('/banners-settings/destroy/{id}',
        [WebmasterBannersController::class, 'destroy'])->name('WebmasterBannersDestroy');
    Route::post('/banners-settings/updateAll',
        [WebmasterBannersController::class, 'updateAll'])->name('WebmasterBannersUpdateAll');


// Sections
    Route::get('/{webmasterId}/categories', [CategoriesController::class, 'index'])->name('categories');
    Route::get('/{webmasterId}/categories/create', [CategoriesController::class, 'create'])->name('categoriesCreate');
    Route::post('/{webmasterId}/categories/store', [CategoriesController::class, 'store'])->name('categoriesStore');
    Route::get('/{webmasterId}/categories/{id}/edit', [CategoriesController::class, 'edit'])->name('categoriesEdit');
    Route::get('/{webmasterId}/categories/{id}/clone', [CategoriesController::class, 'clone'])->name('categoriesClone');
    Route::post('/{webmasterId}/categories/{id}/update',
        [CategoriesController::class, 'update'])->name('categoriesUpdate');
    Route::post('/{webmasterId}/categories/{id}/seo',
        [CategoriesController::class, 'seo'])->name('categoriesSEOUpdate');
    Route::post('/{webmasterId}/categories/{id}/code',
        [CategoriesController::class, 'custom_code'])->name('categoriesCodeUpdate');
    Route::get('/{webmasterId}/categories/destroy/{id?}',
        [CategoriesController::class, 'destroy'])->name('categoriesDestroy');
    Route::post('/{webmasterId}/categories/updateAll',
        [CategoriesController::class, 'updateAll'])->name('categoriesUpdateAll');

// Leagues
    Route::get('/leagues', [LeaguesController::class, 'index'])->name('leagues');
    Route::get('/leagues/update/api', [LeaguesController::class, 'update_api'])->name('leaguesUpdateAPI');
    Route::get('/leagues/edit/{id}', [LeaguesController::class, 'edit'])->name('leaguesEdit');
    Route::post('/leagues/update/{id}', [LeaguesController::class, 'update'])->name('leaguesUpdate');
    Route::post('/leagues/updateAll', [LeaguesController::class, 'updateAll'])->name('leaguesUpdateAll');

    // Rounds
    Route::get('/leagues/{league_id?}/rounds', [RoundsController::class, 'index'])->name('leaguesRounds');
    Route::get('/leagues/{league_id?}/season/{season_id?}/rounds/API', [RoundsController::class, 'syncSeasonStructure'])->name('leaguesRoundsAPI');
    Route::post('/leagues/matches/rounds-update-all', [RoundsController::class, 'matchesUpdateAllAPI'])->name('roundsUpdateAll');
    Route::get('/leagues/matches/{id}/edit', [RoundsController::class, 'matcheRoundsEdit'])->name('matcheRoundsEdit');
    Route::post('/leagues/matches/{id}/update', [RoundsController::class, 'matchUpdate'])->name('matchUpdate');
    Route::post('/leagues/{league_id}/matches/list', [RoundsController::class, 'list'])->name('leagues.matches.list');
    Route::get('/leagues/{league_id?}/standings/sync/{seasonId}', [RoundsController::class, 'syncBySeason'])
        ->name('standings.sync');
    // Seasons
    Route::get('/leagues/{league_id?}/seasons', [SeasonsController::class, 'index'])->name('seasons');
    Route::get('/leagues/{league_id?}/seasons/update', [SeasonsController::class, 'update'])->name('seasonsUpdate');


    Route::get('/teams', [TeamsController::class, 'index'])->name('teams');
    Route::post('teams-list', [TeamsController::class, 'list'])->name('teams.list');
    Route::get('teams/edit/{id}', [TeamsController::class, 'edit'])->name('teams.edit');
    Route::post('/teams/teamsUpdateAll', [TeamsController::class, 'updateAll'])->name('teamsUpdateAll');
    Route::post('/teams/update/{id}', [TeamsController::class, 'update'])->name('teamsUpdate');
    Route::get('/teams/teams-update/API', [TeamsController::class, 'updateAPI'])->name('teamsUpdateAPI');
    Route::get('/teams/destroy/{id?}', [TeamsController::class, 'destroy'])->name('teamsDestroy');
// Topics
    Route::get('/{webmasterId}/topics', [TopicsController::class, 'index'])->name('topics');
    Route::post('/topics-list', [TopicsController::class, 'list'])->name('topicsList');
    Route::get('/{webmasterId}/view/{id}', [TopicsController::class, 'view'])->name('topicView');
    Route::get('/{webmasterId}/topics/create', [TopicsController::class, 'create'])->name('topicsCreate');
    Route::post('/{webmasterId}/topics/store', [TopicsController::class, 'store'])->name('topicsStore');
    Route::get('/{webmasterId}/topics/{id}/edit', [TopicsController::class, 'edit'])->name('topicsEdit');
    Route::get('/{webmasterId}/topics/{id}/clone', [TopicsController::class, 'clone'])->name('topicsClone');
    Route::get('/{webmasterId}/topics/{id}/instant-index',
        [TopicsController::class, 'instant_index'])->name('topicsInstantIndex');
    Route::post('/{webmasterId}/topics/{id}/update', [TopicsController::class, 'update'])->name('topicsUpdate');
    Route::get('/{webmasterId}/topics/destroy/{id?}', [TopicsController::class, 'destroy'])->name('topicsDestroy');
    Route::post('/{webmasterId}/topics/updateAll', [TopicsController::class, 'updateAll'])->name('topicsUpdateAll');
    Route::get('/{webmasterId}/print', [TopicsController::class, 'print'])->name('topicsPrint');
// Topics :SEO
    Route::post('/{webmasterId}/topics/{id}/seo', [TopicsController::class, 'seo'])->name('topicsSEOUpdate');
// Topics :Custom code
    Route::post('/{webmasterId}/topics/{id}/code', [TopicsController::class, 'custom_code'])->name('topicsCodeUpdate');
// Topics :Photos
    Route::post('/topics/upload', [TopicsController::class, 'upload'])->name('topicsPhotosUpload');
    Route::post('/topics/custom-fields', [TopicsController::class, 'custom_fields'])->name('topicsCustomFields');
    Route::post('/{webmasterId}/topics/{id}/photos', [TopicsController::class, 'photos'])->name('topicsPhotosEdit');
    Route::get('/{webmasterId}/topics/{id}/photos-list',
        [TopicsController::class, 'photos_list'])->name('topicsPhotosList');
    Route::get('/{webmasterId}/topics/{id}/photos/{photo_id}/destroy',
        [TopicsController::class, 'photosDestroy'])->name('topicsPhotosDestroy');
    Route::post('/{webmasterId}/topics/{id}/photos/updateAll',
        [TopicsController::class, 'photosUpdateAll'])->name('topicsPhotosUpdateAll');


    Route::post('/topics-import', [TopicsController::class, 'import'])->name('topicsImport');


// Topics :Files
    Route::get('/{webmasterId}/topics/{id}/files', [TopicsController::class, 'topicsFiles'])->name('topicsFiles');
    Route::get('/{webmasterId}/topics/{id}/files/create',
        [TopicsController::class, 'filesCreate'])->name('topicsFilesCreate');
    Route::post('/{webmasterId}/topics/{id}/files/store',
        [TopicsController::class, 'filesStore'])->name('topicsFilesStore');
    Route::get('/{webmasterId}/topics/{id}/files/{file_id}/edit',
        [TopicsController::class, 'filesEdit'])->name('topicsFilesEdit');
    Route::post('/{webmasterId}/topics/{id}/files/{file_id}/update',
        [TopicsController::class, 'filesUpdate'])->name('topicsFilesUpdate');
    Route::get('/{webmasterId}/topics/{id}/files/destroy/{file_id}',
        [TopicsController::class, 'filesDestroy'])->name('topicsFilesDestroy');
    Route::post('/{webmasterId}/topics/{id}/files/updateAll',
        [TopicsController::class, 'filesUpdateAll'])->name('topicsFilesUpdateAll');


// Topics :Related
    Route::get('/{webmasterId}/topics/{id}/related', [TopicsController::class, 'topicsRelated'])->name('topicsRelated');
    Route::get('/relatedLoad/{id}', [TopicsController::class, 'topicsRelatedLoad'])->name('topicsRelatedLoad');
    Route::get('/{webmasterId}/topics/{id}/related/create',
        [TopicsController::class, 'relatedCreate'])->name('topicsRelatedCreate');
    Route::post('/{webmasterId}/topics/{id}/related/store',
        [TopicsController::class, 'relatedStore'])->name('topicsRelatedStore');
    Route::get('/{webmasterId}/topics/{id}/related/destroy/{related_id}',
        [TopicsController::class, 'relatedDestroy'])->name('topicsRelatedDestroy');
    Route::post('/{webmasterId}/topics/{id}/related/updateAll',
        [TopicsController::class, 'relatedUpdateAll'])->name('topicsRelatedUpdateAll');
// Topics :Comments
    Route::get('/{webmasterId}/topics/{id}/comments',
        [TopicsController::class, 'topicsComments'])->name('topicsComments');
    Route::get('/{webmasterId}/topics/{id}/comments/create',
        [TopicsController::class, 'commentsCreate'])->name('topicsCommentsCreate');
    Route::post('/{webmasterId}/topics/{id}/comments/store',
        [TopicsController::class, 'commentsStore'])->name('topicsCommentsStore');
    Route::get('/{webmasterId}/topics/{id}/comments/{comment_id}/edit',
        [TopicsController::class, 'commentsEdit'])->name('topicsCommentsEdit');
    Route::post('/{webmasterId}/topics/{id}/comments/{comment_id}/update',
        [TopicsController::class, 'commentsUpdate'])->name('topicsCommentsUpdate');
    Route::get('/{webmasterId}/topics/{id}/comments/destroy/{comment_id}',
        [TopicsController::class, 'commentsDestroy'])->name('topicsCommentsDestroy');
    Route::post('/{webmasterId}/topics/{id}/comments/updateAll',
        [TopicsController::class, 'commentsUpdateAll'])->name('topicsCommentsUpdateAll');
// Topics :Maps
    Route::get('/{webmasterId}/topics/{id}/maps', [TopicsController::class, 'topicsMaps'])->name('topicsMaps');
    Route::get('/{webmasterId}/topics/{id}/maps/create',
        [TopicsController::class, 'mapsCreate'])->name('topicsMapsCreate');
    Route::post('/{webmasterId}/topics/{id}/maps/store',
        [TopicsController::class, 'mapsStore'])->name('topicsMapsStore');
    Route::get('/{webmasterId}/topics/{id}/maps/{map_id}/edit',
        [TopicsController::class, 'mapsEdit'])->name('topicsMapsEdit');
    Route::post('/{webmasterId}/topics/{id}/maps/{map_id}/update',
        [TopicsController::class, 'mapsUpdate'])->name('topicsMapsUpdate');
    Route::get('/{webmasterId}/topics/{id}/maps/destroy/{map_id}',
        [TopicsController::class, 'mapsDestroy'])->name('topicsMapsDestroy');
    Route::post('/{webmasterId}/topics/{id}/maps/updateAll',
        [TopicsController::class, 'mapsUpdateAll'])->name('topicsMapsUpdateAll');

    Route::post('/table-columns-update', [TopicsController::class, 'update_table_columns'])->name('tableColumnsUpdate');

// keditor
    Route::get('/keditor/{topic_id?}', [TopicsController::class, 'keditor'])->name('keditor');
    Route::get('/keditor-snippets', [TopicsController::class, 'keditor_snippets'])->name('keditorSnippets');
    Route::post('/keditor-save', [TopicsController::class, 'keditor_save'])->name('keditorSave');

// Contacts Groups
    Route::post('/contacts/storeGroup', [ContactsController::class, 'storeGroup'])->name('contactsStoreGroup');
    Route::get('/contacts/{id}/editGroup', [ContactsController::class, 'editGroup'])->name('contactsEditGroup');
    Route::post('/contacts/{id}/updateGroup', [ContactsController::class, 'updateGroup'])->name('contactsUpdateGroup');
    Route::get('/contacts/destroyGroup/{id}',
        [ContactsController::class, 'destroyGroup'])->name('contactsDestroyGroup');
// Contacts
    Route::get('/contacts/{group_id?}', [ContactsController::class, 'index'])->name('contacts');
    Route::post('/contacts/store', [ContactsController::class, 'store'])->name('contactsStore');
    Route::post('/contacts/search', [ContactsController::class, 'search'])->name('contactsSearch');
    Route::get('/contacts/{id}/edit', [ContactsController::class, 'edit'])->name('contactsEdit');
    Route::post('/contacts/{id}/update', [ContactsController::class, 'update'])->name('contactsUpdate');
    Route::get('/contacts/destroy/{id}', [ContactsController::class, 'destroy'])->name('contactsDestroy');
    Route::post('/contacts/updateAll', [ContactsController::class, 'updateAll'])->name('contactsUpdateAll');

// WebMails Groups
    Route::post('/webmails/storeGroup', [WebmailsController::class, 'storeGroup'])->name('webmailsStoreGroup');
    Route::get('/webmails/{id}/editGroup', [WebmailsController::class, 'editGroup'])->name('webmailsEditGroup');
    Route::post('/webmails/{id}/updateGroup', [WebmailsController::class, 'updateGroup'])->name('webmailsUpdateGroup');
    Route::get('/webmails/destroyGroup/{id}',
        [WebmailsController::class, 'destroyGroup'])->name('webmailsDestroyGroup');
// WebMails
    Route::post('/webmails/store', [WebmailsController::class, 'store'])->name('webmailsStore');
    Route::post('/webmails/search', [WebmailsController::class, 'search'])->name('webmailsSearch');
    Route::get('/webmails/{id}/edit', [WebmailsController::class, 'edit'])->name('webmailsEdit');
    Route::get('/webmails/{group_id?}/{wid?}/{stat?}/{contact_email?}',
        [WebmailsController::class, 'index'])->name('webmails');
    Route::post('/webmails/{id}/update', [WebmailsController::class, 'update'])->name('webmailsUpdate');
    Route::get('/webmails/destroy/{id}', [WebmailsController::class, 'destroy'])->name('webmailsDestroy');
    Route::post('/webmails/updateAll', [WebmailsController::class, 'updateAll'])->name('webmailsUpdateAll');

// Calendar
    Route::get('/calendar', [EventsController::class, 'index'])->name('calendar');
    Route::get('/calendar/create', [EventsController::class, 'create'])->name('calendarCreate');
    Route::post('/calendar/store', [EventsController::class, 'store'])->name('calendarStore');
    Route::get('/calendar/{id}/edit', [EventsController::class, 'edit'])->name('calendarEdit');
    Route::post('/calendar/{id}/update', [EventsController::class, 'update'])->name('calendarUpdate');
    Route::get('/calendar/destroy/{id}', [EventsController::class, 'destroy'])->name('calendarDestroy');
    Route::get('/calendar/updateAll', [EventsController::class, 'updateAll'])->name('calendarUpdateAll');
    Route::post('/calendar/{id}/extend', [EventsController::class, 'extend'])->name('calendarExtend');

// Analytics
    Route::get('/ip/{ip_code?}', [AnalyticsController::class, 'ip'])->name('visitorsIP');
    Route::post('/ip/search', [AnalyticsController::class, 'search'])->name('visitorsSearch');
    Route::post('/analytics/{stat}', [AnalyticsController::class, 'filter'])->name('analyticsFilter');
    Route::get('/analytics/{stat?}', [AnalyticsController::class, 'index'])->name('analytics');
    Route::get('/visitors', [AnalyticsController::class, 'visitors'])->name('visitors');

// Users & Permissions
    Route::get('/users', [UsersController::class, 'index'])->name('users');
    Route::get('/users/create/', [UsersController::class, 'create'])->name('usersCreate');
    Route::post('/users/store', [UsersController::class, 'store'])->name('usersStore');
    Route::get('/users/{id}/edit', [UsersController::class, 'edit'])->name('usersEdit');
    Route::post('/users/{id}/update', [UsersController::class, 'update'])->name('usersUpdate');
    Route::get('/users/destroy/{id}', [UsersController::class, 'destroy'])->name('usersDestroy');
    Route::post('/users/updateAll', [UsersController::class, 'updateAll'])->name('usersUpdateAll');

    Route::get('/users/permissions/create/', [UsersController::class, 'permissions_create'])->name('permissionsCreate');
    Route::post('/users/permissions/store', [UsersController::class, 'permissions_store'])->name('permissionsStore');
    Route::get('/users/permissions/{id}/edit', [UsersController::class, 'permissions_edit'])->name('permissionsEdit');
    Route::post('/users/permissions/{id}/update',
        [UsersController::class, 'permissions_update'])->name('permissionsUpdate');
    Route::post('/users/permissions/{id}/save',
        [UsersController::class, 'update_custom_home'])->name('permissionsHomePageUpdate');
    Route::get('/users/permissions/destroy/{id}',
        [UsersController::class, 'permissions_destroy'])->name('permissionsDestroy');

    Route::post('/permissions-links/store', [UsersController::class, 'links_store'])->name('customLinksStore');
    Route::post('/permissions-links/update', [UsersController::class, 'links_update'])->name('customLinksUpdate');
    Route::get('/permissions-links/edit/{id?}/{p_id?}',
        [UsersController::class, 'links_edit'])->name('customLinksEdit');
    Route::get('/permissions-links/destroy/{id?}/{p_id?}',
        [UsersController::class, 'links_destroy'])->name('customLinksDestroy');
    Route::get('/permissions-links/list/{p_id?}', [UsersController::class, 'links_list'])->name('customLinksList');

// Page blocks
    Route::post('/pag-blocks', [TopicBlockController::class, 'blocks_list'])->name('topicBlocksList');
    Route::post('/pag-blocks/create', [TopicBlockController::class, 'block_create'])->name('topicBlocksCreate');
    Route::post('/pag-blocks/store', [TopicBlockController::class, 'block_store'])->name('topicBlocksStore');
    Route::post('/pag-blocks/edit', [TopicBlockController::class, 'block_edit'])->name('topicBlocksEdit');
    Route::post('/pag-blocks/clone', [TopicBlockController::class, 'block_clone'])->name('topicBlocksClone');
    Route::post('/pag-blocks/delete', [TopicBlockController::class, 'block_delete'])->name('topicBlocksDestroy');
    Route::post('/pag-blocks/updateAll', [TopicBlockController::class, 'updateAll'])->name('topicBlocksUpdateAll');
    Route::get('/module-categories/{module_id?}/{sel?}', [TopicBlockController::class, 'module_cats'])->name('getModuleCategories');

// Menus
    Route::post('/menus/store/parent', [MenusController::class, 'storeMenu'])->name('parentMenusStore');
    Route::get('/menus/parent/{id}/edit', [MenusController::class, 'editMenu'])->name('parentMenusEdit');
    Route::post('/menus/{id}/update/{ParentMenuId}', [MenusController::class, 'updateMenu'])->name('parentMenusUpdate');
    Route::get('/menus/parent/destroy/{id}', [MenusController::class, 'destroyMenu'])->name('parentMenusDestroy');

    Route::get('/menus/{ParentMenuId?}', [MenusController::class, 'index'])->name('menus');
    Route::get('/menus/create/{ParentMenuId?}', [MenusController::class, 'create'])->name('menusCreate');
    Route::post('/menus/store/{ParentMenuId?}', [MenusController::class, 'store'])->name('menusStore');
    Route::get('/menus/{id}/edit/{ParentMenuId?}', [MenusController::class, 'edit'])->name('menusEdit');
    Route::post('/menus/{id}/update', [MenusController::class, 'update'])->name('menusUpdate');
    Route::get('/menus/destroy/{id}', [MenusController::class, 'destroy'])->name('menusDestroy');
    Route::post('/menus/updateAll', [MenusController::class, 'updateAll'])->name('menusUpdateAll');

// Tags
    Route::get('/tags', [TagController::class, 'index'])->name('tags');
    Route::post('/tags/list', [TagController::class, 'list'])->name('tagsList');
    Route::post('/tags/store', [TagController::class, 'store'])->name('tagsStore');
    Route::get('/tags/edit/{id?}', [TagController::class, 'edit'])->name('tagsEdit');
    Route::post('/tags/update', [TagController::class, 'update'])->name('tagsUpdate');
    Route::get('/tags/destroy/{id?}', [TagController::class, 'destroy'])->name('tagsDestroy');
    Route::post('/tags/updateAll', [TagController::class, 'updateAll'])->name('tagsUpdateAll');

// Countries
    Route::get('/countries', [\App\Http\Controllers\Dashboard\CountriesController::class, 'index'])->name('countries');
    Route::post('/countries/list', [\App\Http\Controllers\Dashboard\CountriesController::class, 'list'])->name('countriesList');
    Route::get('/countries/edit/{id?}', [\App\Http\Controllers\Dashboard\CountriesController::class, 'edit'])->name('countriesEdit');
    Route::post('/countries/update', [\App\Http\Controllers\Dashboard\CountriesController::class, 'update'])->name('countriesUpdate');
    Route::get('/countries/destroy/{id?}', [\App\Http\Controllers\Dashboard\CountriesController::class, 'destroy'])->name('countriesDestroy');
    Route::post('/countries/updateAll', [\App\Http\Controllers\Dashboard\CountriesController::class, 'updateAll'])->name('countriesUpdateAll');
    Route::get('/countries/update/api', [\App\Http\Controllers\Dashboard\CountriesController::class, 'update_api'])->name('countriesUpdateAPI');

// popups
    Route::get('/popups', [PopupController::class, 'index'])->name('popups');
    Route::get('/popups/create', [PopupController::class, 'create'])->name('popupsCreate');
    Route::post('/popups/store', [PopupController::class, 'store'])->name('popupsStore');
    Route::get('/popups/edit/{id}', [PopupController::class, 'edit'])->name('popupsEdit');
    Route::post('/popups/update', [PopupController::class, 'update'])->name('popupsUpdate');
    Route::get('/popups/destroy/{id?}', [PopupController::class, 'destroy'])->name('popupsDestroy');
    Route::post('/popups/updateAll', [PopupController::class, 'updateAll'])->name('popupsUpdateAll');

// File manager
    Route::get('file-manager', [FileManagerController::class, 'index'])->name('FileManager');
    Route::get('files-manager', [FileManagerController::class, 'manager'])->name('FilesManager');

// No Permission
    Route::get('/403', [DashboardController::class, 'page_403']);
    Route::get('/oops', [DashboardController::class, 'page_oops'])->name('NoPermission');

// Clear Cache
    Route::get('/cache-clear', [DashboardController::class, 'cache_clear'])->name('cacheClear');
    Route::get('/cache-cleared', [DashboardController::class, 'cache_cleared'])->name('cacheCleared');
// logout
    Route::get('/logout', [DashboardController::class, 'logout'])->name('adminLogout');
});
