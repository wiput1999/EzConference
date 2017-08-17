<?php

namespace App\Http\Controllers;

use App\User;
use App\Conference;
use App\ConferenceAttend;
use Illuminate\Http\Request;

class ConferenceController extends Controller
{
    public function getConferenceDetails($token) {
        $conference = Conference::where('remember_token', $token)->get();
        $conference[0]['owner_name'] = User::find($conference[0]['owner'])['name'];
        $conference = $conference[0];
        return view('conference.detail', ['conference' => $conference]);
    }

    public function getNewConferenceForm() {
        return view('conference.new');
    }

    public function storeNewConferenceForm(Request $request) {
        $inputs = $request->all();

        $conference = new Conference();

        $inputs['remember_token'] = str_random(20);

        $conference->fill($inputs);

        $conference->save();
    }

    public function getConferenceEdit($token) {
        $conference = Conference::where('remember_token', $token)->get();
        return view('conference.edit', ['conference' => $conference[0]]);
    }

    public function joinConference($token) {
        $conference = Conference::where('remember_token', $token)->get();
        $conference = $conference[0]['id'];

        $attend = new ConferenceAttend();

        $data['user_id'] = \Auth::user()->id;
        $data['conference_id'] = $conference;

        $attend->fill($data);

        $attend->save();

        return redirect('/conference/'.$token);

    }
}
