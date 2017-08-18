<?php

namespace App\Http\Controllers;

use App\User;
use App\Conference;
use App\Questions;
use App\ConferenceAttend;
use App\Attachments;
use Illuminate\Http\Request;

class ConferenceController extends Controller
{
    public function getConferenceDetails($token) {
        $conference = Conference::where('remember_token', $token)->get();
        $conference = $conference[0]['id'];

        $attend = ConferenceAttend::where([
            ['user_id', \Auth::user()->id],
            ['conference_id', $conference ]
        ])->count();

        if( $attend != 0 ) {
            return redirect('/conference/'. $token);
        }

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

        $attend = new ConferenceAttend();

        $data['user_id'] = \Auth::user()->id;
        $data['conference_id'] = $conference['id'];

        $attend->fill($data);

        $attend->save();

        $token = $inputs['remember_token'];

        return redirect('/conference/'. $token);
    }

    public function getConferenceEdit($token) {
        $conference = Conference::where('remember_token', $token)->get();
        return view('conference.edit', ['conference' => $conference[0], 'token' => $token]);
    }

    public function storeConferenceEdit(Request $request, $token) {

        $inputs = $request->all();

        $conference = Conference::where('remember_token', $token)->get()[0];

        $conference->name = $inputs['name'];
        $conference->description = $inputs['description'];
        $conference->open = $inputs['open'];
        $conference->capacity = $inputs['capacity'];

        $conference->save();

        return redirect('/conference/'.$token);
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

    public function getConferenceIndex($token) {
        $conference = Conference::where('remember_token', $token)->get()[0];

        $attend = ConferenceAttend::where([
            ['user_id', \Auth::user()->id],
            ['conference_id', $conference['id'] ]
        ])->count();

        if( $attend == 0 ) {
            return redirect('/conference/view/'. $token);
        }

        $conference['owner_name'] = User::find($conference['owner'])['name'];

        $questions = Questions::where('conference_id', '=', $conference['id'])->orderBy('created_at', 'desc')->get();

        $attachments = Attachments::where('conference_id', '=', $conference['id'])->orderBy('created_at', 'desc')->get();

        foreach ($questions as $question) {
            $question['owner_name'] = User::find($question['owner'])['name'];
        }

        foreach ($attachments as $attachment) {
            $attachment['owner_name'] = User::find($attachment['owner'])['name'];
        }

        return view('conference.index', [
            'conference' => $conference,
            'questions' => $questions,
            'attachments' => $attachments
        ]);
    }

    public function getOwnConferenceList() {
        $conferences = Conference::where('owner', \Auth::user()->id)->get();

        foreach ($conferences as $conference) {
            $conference['owner_name'] = User::find($conference['owner'])['name'];
        }

        return view('conference.ownlist', ['conferences' => $conferences]);
    }

    public function getConferenceNewQuestions($token) {
        return view('conference.questions.new', ['conference' => $token]);
    }

    public function storeConferenceNewQuestions(Request $request, $token) {
        $inputs = $request->all();

        $question = new Questions();

        $question->fill($inputs);

        $question['conference_id'] = Conference::where('remember_token', $token)->get()[0]['id'];

        $question->save();

        return redirect('/conference/'.$token);
    }

    public function getConferenceQuestionsDetail($token, $id) {
        $conference_id = Conference::where('remember_token', $token)->get()[0]['id'];

        $attend = ConferenceAttend::where([
            ['user_id', \Auth::user()->id],
            ['conference_id', $conference_id ]
        ])->count();

        if( $attend == 0 ) {
            return redirect('/conference/view/'. $token);
        }

        $question = Questions::where([
            ['conference_id', $conference_id],
            ['id', $id]
        ])->get()[0];

        return $question;
    }

}
