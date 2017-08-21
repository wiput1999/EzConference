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

Route::get('/', function () {
    return view('home');
});

//Laravel Auth Route
Auth::routes();
//Default authenticated route wil route to /dashboard
Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function() {
//    Dashboard
    Route::get('/dashboard', 'DashboardController@getDashboard');
//    Profile
    Route::get('/dashboard/profile', 'DashboardController@getProfile');
//    New Conference
    Route::get('/conference/new', 'ConferenceController@getNewConferenceForm');
//    Store New Conference
    Route::post('/conference/new', 'ConferenceController@storeNewConferenceForm');
//    View Conference Description by id (token)
    Route::get('/conference/view/{id}', 'ConferenceController@getConferenceDetails');
//    Join conference
    Route::post('/conference/join/{id}', 'ConferenceController@joinConference');
//    Edit conference
    Route::get('/conference/edit/{id}', 'ConferenceController@getConferenceEdit');
//    Save conference edit
    Route::post('/conference/edit/{id}', 'ConferenceController@storeConferenceEdit');
//    Conference index page
    Route::get('/conference/{id}', 'ConferenceController@getConferenceIndex');
//    Create new question in conference
    Route::get('/conference/{id}/questions/new', 'ConferenceController@getConferenceNewQuestions');
//    Save new question
    Route::post('/conference/{id}/questions/new', 'ConferenceController@storeConferenceNewQuestions');
//    See question details
    Route::get('/conference/{token}/questions/{id}', 'ConferenceController@getConferenceQuestionsDetail');
//    Edit question
    Route::get('/conference/{token}/questions/{id}/edit', 'ConferenceController@getConferenceQuestionsEdit');
//    Save edited question
    Route::post('/conference/{token}/questions/{id}/edit', 'ConferenceController@storeConferenceQuestionsEdit');
//    New answer for question
    Route::get('/conference/{token}/questions/{id}/answer/new', 'ConferenceController@getNewAnswerForm');
//    Save new answer
    Route::post('/conference/{token}/questions/{id}/answer/new', 'ConferenceController@storeNewAnswerForm');

    Route::get('/conference/{token}/questions/{id}/answer/{ans_id}/edit', 'ConferenceController@getEditAnswerForm');

    Route::post('/conference/{token}/questions/{id}/answer/{ans_id}/edit', 'ConferenceController@storeEditAnswerForm');

    Route::delete('/conference/{token}/destroy', 'ConferenceController@destroyConference');

    Route::get('/conference/{token}/questions/{id}/answer/{ans_id}/destroy', 'ConferenceController@destroyAnswer');

    Route::get('/conference/{token}/attachments/new', 'ConferenceController@getUploadForm');

    Route::post('/conference/{token}/attachments/new', 'ConferenceController@storeUploadForm');

    Route::get('/conference/{token}/attachments/{id}', 'ConferenceController@getAttachment');

//    See my conference list and manage
    Route::get('/dashboard/conference/', 'ConferenceController@getOwnConferenceList');
});