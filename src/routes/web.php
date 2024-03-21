<?php

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

/*
|--------------------------------------------------------------------------
| Route Patterns
|--------------------------------------------------------------------------
| Constraints for route parameters with a specific name
|
*/

Route::pattern('id', '[0-9]+');
Route::pattern('locale', 'zh');

if (in_array(request()->segment(1), config('translatable.route-prefix-locales'))) {
    app()->setLocale(request()->segment(1));
    setlocale(LC_TIME, config('translatable.locales-full')[request()->segment(1)]['lc_time']);
    $locale = request()->segment(1);
} else {
    app()->setLocale(config('app.fallback_locale'));
    setLocale(LC_TIME, config('app.fallback_LCTIME'));
    $locale = null;
}

/*
|--------------------------------------------------------------------------
| Admin Auth Routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => $locale], function () use ($locale) {
    Route::prefix('app')->group(function () {
        // Authentication routes
        Route::namespace('Admin\Auth')->prefix('auth')->name('admin.auth.')->group(function () {

            // Login
            Route::get('login', 'LoginController@showLoginForm')->name('login');
            Route::post('login', 'LoginController@login');
            Route::get('logout', 'LoginController@logout')->name('logout');

            // Password Reset
            Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
            Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
            Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
            Route::post('password/reset', 'ResetPasswordController@reset');

            // Verification
            Route::get('verification/{token}', 'VerificationController@verifyUser')->name('verify');
            Route::post('verification/confirm/{token}', 'VerificationController@confirm')->middleware(['throttle:10,1', 'honeypot'])->name('verify.confirm');

            // Register
            Route::get('register', 'RegisterController@showForm')->name('register');
            Route::post('register/submit', 'RegisterController@submit')->middleware(['throttle:10,1', 'honeypot'])->name('register.submit');
            Route::get('register/success', 'RegisterController@success')->name('register.success');
        });

        // Unprotected CMS routes
        Route::namespace('Admin')->name('admin.')->group(function () {
            // Organizations select ajax route
            Route::post('/organizations/list', 'OrganizationController@getOrganizationsSelectListOptions')->name('organizations.list');

            // user agreement
            Route::get('/eula', 'ViewsController@showEula')->name('static.eula');

            Route::post('/switchlocale', 'LocaleController@switchLocale')->name('switch-locale');

        });

        // Protected CMS routes
        Route::namespace('Admin')->name('admin.')->middleware(['auth:admin', 'log-active'])->group(function () {
            Route::name('dashboard.')->group(function () {
                Route::get('/', 'DashboardController@index')->name('index');
                Route::post('/top-performers-data', 'DashboardController@getTopPerformersData')->middleware('can:view statistics dashboard.top performers')->name('top-performers-data');
                Route::post('/top-performing-content-data', 'DashboardController@getTopPerformingContentData')->middleware('can:view statistics dashboard.top performing content')->name('top-performing-content-data');
                Route::post('/indices-table-data', 'DashboardController@getIndicesTableData')->middleware('can:view statistics dashboard.index table')->name('indices-table-data');
                Route::post('/indices-detailed-table-data', 'DashboardController@getIndicesDetailedTableData')->middleware('can:view indices dashboard')->name('indices-detailed-table-data');
            });

            Route::prefix('my-organization')->name('my-organization.')->group(function () {
                Route::get('/', 'MyOrganizationController@index')->middleware('can:view my organization')->name('index');
                Route::post('/data', 'MyOrganizationController@getIndexData')->middleware('can:view my organization')->name('index-data');
                Route::post('/data/competition-analysis', 'MyOrganizationController@getCompetitionAnalysisData')->middleware('can:view my organization')->name('index-data.competition-analysis');
                Route::post('/data/competition-analysis-chart', 'MyOrganizationController@getCompetitionAnalysisChartData')->middleware('can:view my organization')->name('index-data.competition-analysis-chart');
                Route::get('/settings', 'MyOrganizationController@settings')->middleware('can:manage my organization')->name('settings');
                Route::post('/settings/update', 'MyOrganizationController@update')->middleware('can:manage my organization')->name('settings.update');
            });

            Route::prefix('profile')->name('profile.')->group(function () {
                Route::get('/', 'ProfileController@show')->name('show');
                Route::post('/update', 'ProfileController@update')->name('update');

                Route::get('/password/edit', 'ProfileController@editPassword')->name('password.edit');
                Route::post('/password/update', 'ProfileController@updatePassword')->name('password.update');
            });

            Route::prefix('contact')->name('contact.')->group(function () {
                Route::post('/upgrade-account', 'ContactController@submitUpgradeAccountForm')->name('upgrade-account');
            });

            //Route::get('/layout', 'LayoutTestController@layoutComponents')->name('layout-components');


            Route::prefix('admin')->group(function () {
                Route::prefix('user')->name('user.')->middleware('can:view users')->group(function () {
                    Route::get('/', 'UserController@index')->name('index');
                    Route::post('/data', 'UserController@getDatatableData')->name('data');
                    Route::get('/edit/{id}', 'UserController@edit')->name('edit');
                    Route::post('/update/{id}', 'UserController@update')->name('update');
                    Route::get('/create', 'UserController@create')->name('create');
                    Route::put('/store', 'UserController@store')->name('store');
                    Route::delete('/delete/{id}', 'UserController@delete')->name('delete');
                });

                Route::prefix('activity')->name('activity.')->middleware('can:view activity log')->group(function () {
                    Route::get('/', 'ActivityController@index')->name('index');
                    Route::post('/data', 'ActivityController@getDatatableData')->name('data');
                    Route::get('/{id}', 'ActivityController@show')->name('show');
                });

                Route::prefix('notification')->name('notification.')->middleware('can:view notifications')->group(function () {
                    Route::get('/', 'NotificationController@index')->name('index');
                    Route::post('/data', 'NotificationController@getDatatableData')->name('data');
                    Route::get('/edit/{id}', 'NotificationController@edit')->name('edit');
                    Route::post('/update/new-user/{id}/accept', 'NotificationController@acceptNewUser')->name('update.new-user.accept');
                    Route::post('/update/new-user/{id}/reject', 'NotificationController@rejectNewUser')->name('update.new-user.reject');
                    Route::delete('/delete/{id}', 'NotificationController@delete')->name('delete');
                });
            });

            Route::prefix('management')->group(function () {
                Route::prefix('industry')->name('industry.')->middleware('can:view industries')->group(function () {
                    Route::get('/', 'IndustryController@index')->name('index');
                    Route::post('/data', 'IndustryController@getDatatableData')->name('data');
                    Route::get('/edit/{id}', 'IndustryController@edit')->name('edit');
                    Route::post('/update/{id}', 'IndustryController@update')->name('update');
                    Route::get('/create', 'IndustryController@create')->name('create');
                    Route::put('/store', 'IndustryController@store')->name('store');
                    Route::delete('/delete/{id}', 'IndustryController@delete')->name('delete');
                });

                Route::prefix('organization')->name('organization.')->middleware('can:view organizations')->group(function () {
                    Route::get('/', 'OrganizationController@index')->name('index');
                    Route::post('/data', 'OrganizationController@getDatatableData')->name('data');
                    Route::get('/edit/{id}', 'OrganizationController@edit')->name('edit');
                    Route::get('/{id}', 'OrganizationController@show')->name('show');
                    Route::post('/{id}/data', 'OrganizationController@getShowData')->name('show-data');
                    Route::post('/{id}/data/competition-analysis', 'OrganizationController@getCompetitionAnalysisData')->name('show-data.competition-analysis');
                    Route::post('/{id}/data/competition-analysis-chart', 'OrganizationController@getCompetitionAnalysisChartData')->name('show-data.competition-analysis-chart');

                    Route::post('/update/{id}', 'OrganizationController@update')->name('update');
                    Route::get('/create', 'OrganizationController@create')->name('create');
                    Route::put('/store', 'OrganizationController@store')->name('store');
                    Route::delete('/delete/{id}', 'OrganizationController@delete')->name('delete');
                    Route::get('/activity/{id}', 'OrganizationController@activity')->name('activity');
                    Route::post('/activity/{id}/data', 'OrganizationController@getActivityData')->name('activity.data');


                    Route::prefix('/{organization}/post')->name('post.')->where(['organization' => '[0-9]+'])->middleware('can:view posts')->group(function () {
                        Route::get('/', 'PostController@index')->name('index');
                        Route::post('/data', 'PostController@getDatatableData')->name('data');
                        Route::get('/edit/{id}', 'PostController@edit')->name('edit');
                        Route::post('/update/{id}', 'PostController@update')->name('update');
                        Route::get('/create', 'PostController@create')->name('create');
                        Route::put('/store', 'PostController@store')->name('store');
                        Route::delete('/delete/{id}', 'PostController@delete')->name('delete');
                    });

                    Route::prefix('/{organization}/data')->name('data.')->where(['organization' => '[0-9]+'])->middleware('can:view organization data')->group(function () {
                        Route::get('/', 'OrganizationDataController@index')->name('index');
                        Route::post('/data', 'OrganizationDataController@getDatatableData')->name('data');
                        Route::get('/edit/{id}', 'OrganizationDataController@edit')->name('edit');
                        Route::post('/update/{id}', 'OrganizationDataController@update')->name('update');
                        Route::get('/create', 'OrganizationDataController@create')->name('create');
                        Route::put('/store', 'OrganizationDataController@store')->name('store');
                        Route::delete('/delete/{id}', 'OrganizationDataController@delete')->name('delete');
                    });

                    Route::prefix('/{organization}/crawler-log')->name('crawler-log.')->where(['organization' => '[0-9]+'])->middleware('can:view crawler dashboard')->group(function () {
                        Route::get('/', 'CrawlerLogController@index')->name('index');
                        Route::post('/data', 'CrawlerLogController@getDatatableData')->name('data');
                        Route::delete('/delete/{id}', 'CrawlerLogController@delete')->name('delete');
                    });
                });

                Route::prefix('/organization/post/{post}/data')->name('post.data.')->where(['post' => '[0-9]+'])->middleware('can:view post data')->group(function () {
                    Route::get('/', 'PostDataController@index')->name('index');
                    Route::post('/data', 'PostDataController@getDatatableData')->name('data');
                    Route::get('/edit/{id}', 'PostDataController@edit')->name('edit');
                    Route::post('/update/{id}', 'PostDataController@update')->name('update');
                    Route::get('/create', 'PostDataController@create')->name('create');
                    Route::put('/store', 'PostDataController@store')->name('store');
                    Route::delete('/delete/{id}', 'PostDataController@delete')->name('delete');
                });

                Route::prefix('channel')->name('channel.')->middleware('can:view channels')->group(function () {
                    Route::get('/', 'ChannelController@index')->name('index');
                    Route::post('/data', 'ChannelController@getDatatableData')->name('data');
                    Route::get('/edit/{id}', 'ChannelController@edit')->name('edit');
                    Route::post('/update/{id}', 'ChannelController@update')->name('update');
                    Route::get('/create', 'ChannelController@create')->name('create');
                    Route::put('/store', 'ChannelController@store')->name('store');
                    Route::delete('/delete/{id}', 'ChannelController@delete')->name('delete');
                });

                Route::prefix('api-user')->name('api-user.')->middleware('can:view api users')->group(function () {
                    Route::get('/', 'ApiUserController@index')->name('index');
                    Route::post('/data', 'ApiUserController@getDatatableData')->name('data');
                    Route::get('/edit/{id}', 'ApiUserController@edit')->name('edit');
                    Route::post('/update/{id}', 'ApiUserController@update')->name('update');
                    Route::get('/create', 'ApiUserController@create')->name('create');
                    Route::put('/store', 'ApiUserController@store')->name('store');
                    Route::delete('/delete/{id}', 'ApiUserController@delete')->name('delete');
                });

                Route::prefix('crawler')->name('crawler.')->middleware('can:view crawler dashboard')->group(function () {
                    Route::get('/', 'CrawlerDashboardController@index')->name('index');
                    Route::post('/data', 'CrawlerDashboardController@getIndexData')->name('data');
                    Route::post('/errors-data', 'CrawlerDashboardController@getErrorsData')->name('errors-data');
                });
            });

            // catch anything else as 404, but unauthenticated users still have to login (thus not revealing any URL's to unauthenticated users)
            Route::any('{query}', function () {
                abort(404);
            });
        });
    });


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

    Route::namespace('Main')->name('main.')->group(function () {
        Route::post('/switchlocale', 'LocaleController@switchLocale')->name('switch-locale');

        Route::get('/', 'ViewsController@index')->middleware('set-locale')->name('home');
        Route::post('index-data', 'ViewsController@getIndexData')->name('index-data');
        Route::post('register', 'FormController@submitRegisterForm')->middleware(['throttle:10,1', 'honeypot'])->name('register');
        Route::post('newsletter', 'FormController@submitNewsletterSubscriptionForm')->middleware(['throttle:10,1', 'honeypot'])->name('newsletter-subscribe');
    });
});
