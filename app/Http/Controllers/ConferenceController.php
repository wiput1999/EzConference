<?php

namespace App\Http\Controllers;

use App\User;
use App\Conference;
use App\Questions;
use App\ConferenceAttend;
use App\Answers;
use App\Attachments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Routing\Matcher\RedirectableUrlMatcher;
use Illuminate\Support\Facades\Input;

class ConferenceController extends Controller
{
    public function getConferenceDetails($token)
    {
        $conference = Conference::where('remember_token', $token)->get();
        $conference = $conference[0]['id'];

        $attend = ConferenceAttend::where([
            ['user_id', \Auth::user()->id],
            ['conference_id', $conference]
        ])->count();

        if ($attend != 0) {
            return redirect('/conference/' . $token);
        }

        $conference = Conference::where('remember_token', $token)->get();
        $conference[0]['owner_name'] = User::find($conference[0]['owner'])['name'];
        $conference = $conference[0];
        return view('conference.detail', ['conference' => $conference]);
    }

    public function getNewConferenceForm()
    {
        return view('conference.new');
    }

    public function storeNewConferenceForm(Request $request)
    {
        $inputs = $request->all();

        $rules = [
            'name' => 'required',
            'description' => 'required',
            'capacity' => 'required|integer',
            'open' => 'required|in:1,0'
        ];
        $messages = [
            'name.required' => 'Please enter conference name',
            'description.required' => 'Please enter description',
            'capacity.integer' => 'Invalid capacity number',
            'capacity.required' => 'Please enter capacity',
            'open.in' => 'Invalid choice'
        ];
        $validator = Validator::make($inputs, $rules, $messages);
        if($validator->fails()){
            return redirect('/conference/new')->withInput()->withErrors($validator);
        }

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

        return redirect('/conference/' . $token);
    }

    public function getConferenceEdit($token)
    {
        $conference = Conference::where('remember_token', $token)->get();
        return view('conference.edit', ['conference' => $conference[0], 'token' => $token]);
    }

    public function storeConferenceEdit(Request $request, $token)
    {
        $inputs = $request->all();

        $rules = [
            'name' => 'required',
            'description' => 'required',
            'capacity' => 'required|integer',
            'open' => 'required|in:1,0'
        ];
        $messages = [
            'name.required' => 'Please enter conference name',
            'description.required' => 'Please enter description',
            'capacity.integer' => 'Invalid capacity number',
            'capacity.required' => 'Please enter capacity',
            'open.in' => 'Invalid choice'
        ];
        $validator = Validator::make($inputs, $rules, $messages);
        if($validator->fails()){
            return redirect('/conference/edit/'. $token)->withInput()->withErrors($validator);
        }

        $conference = Conference::where('remember_token', $token)->get()[0];

        $conference->name = $inputs['name'];
        $conference->description = $inputs['description'];
        $conference->open = $inputs['open'];
        $conference->capacity = $inputs['capacity'];

        $conference->save();

        return redirect('/conference/' . $token);
    }

    public function joinConference($token)
    {
        $conference = Conference::where('remember_token', $token)->get();
        $conference = $conference[0]['id'];

        $attend = new ConferenceAttend();

        $data['user_id'] = \Auth::user()->id;
        $data['conference_id'] = $conference;

        $attend->fill($data);

        $attend->save();

        return redirect('/conference/' . $token);

    }

    public function getConferenceIndex($token)
    {
        $conference = Conference::where('remember_token', $token)->get()[0];

        $attend = ConferenceAttend::where([
            ['user_id', \Auth::user()->id],
            ['conference_id', $conference['id']]
        ])->count();

        if ($attend == 0) {
            return redirect('/conference/view/' . $token);
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

    public function getOwnConferenceList()
    {
        $conferences = Conference::where('owner', \Auth::user()->id)->get();

        foreach ($conferences as $conference) {
            $conference['owner_name'] = User::find($conference['owner'])['name'];
        }

        return view('conference.ownlist', ['conferences' => $conferences]);
    }

    public function getConferenceNewQuestions($token)
    {
        return view('conference.questions.new', ['conference' => $token]);
    }

    public function storeConferenceNewQuestions(Request $request, $token)
    {
        $inputs = $request->all();

        $rules = [
            'question' => 'required',
            'description' => 'required',
        ];
        $messages = [
            'question.required' => 'Please enter question',
            'description.required' => 'Please enter description',
        ];
        $validator = Validator::make($inputs, $rules, $messages);
        if($validator->fails()){
            return redirect('/conference/'. $token .'/questions/new')->withInput()->withErrors($validator);
        }

        $question = new Questions();

        $question->fill($inputs);

        $question['owner'] = \Auth::user()->id;

        $question['conference_id'] = Conference::where('remember_token', $token)->get()[0]['id'];

        $question->save();

        return redirect('/conference/' . $token);
    }

    public function getConferenceQuestionsDetail($token, $id)
    {
        $conference_id = Conference::where('remember_token', $token)->get()[0]['id'];

        $attend = ConferenceAttend::where([
            ['user_id', \Auth::user()->id],
            ['conference_id', $conference_id]
        ])->count();

        if ($attend == 0) {
            return redirect('/conference/view/' . $token);
        }

        $question = Questions::where([
            ['conference_id', $conference_id],
            ['id', $id]
        ])->get()[0];

        $question['owner_name'] = User::find($question['owner'])['name'];

        $answers = Answers::where('question_id', '=', $id)->orderBy('created_at', 'desc')->get();

        foreach ($answers as $answer) {
            $answer['owner_name'] = User::find($answer['owner'])['name'];
        }

        return view('conference.questions.index', [
            'question' => $question,
            'answers' => $answers,
            'conference' => $token
        ]);
    }

    public function getConferenceQuestionsEdit($token, $id)
    {
        $conference_id = Conference::where('remember_token', $token)->get()[0]['id'];

        $attend = ConferenceAttend::where([
            ['user_id', \Auth::user()->id],
            ['conference_id', $conference_id]
        ])->count();

        if ($attend == 0) {
            return redirect('/conference/view/' . $token);
        }

        $question = Questions::where([
            ['conference_id', $conference_id],
            ['id', $id]
        ])->get()[0];

        return view('conference.questions.edit', [
            'question' => $question,
            'conference' => $token,
        ]);

    }

    public function storeConferenceQuestionsEdit(Request $request, $token, $id)
    {
        $conference_id = Conference::where('remember_token', $token)->get()[0]['id'];

        $attend = ConferenceAttend::where([
            ['user_id', \Auth::user()->id],
            ['conference_id', $conference_id]
        ])->count();

        if ($attend == 0) {
            return redirect('/conference/view/' . $token);
        }

        $inputs = $request->all();

        $rules = [
            'question' => 'required',
            'description' => 'required',
        ];
        $messages = [
            'question.required' => 'Please enter question',
            'description.required' => 'Please enter description',
        ];
        $validator = Validator::make($inputs, $rules, $messages);
        if($validator->fails()){
            return redirect('/conference/'. $token .'/questions/new')->withInput()->withErrors($validator);
        }

        $question = Questions::find($id);

        $question->question = $inputs['question'];
        $question->description = $inputs['description'];

        $question->save();

        return redirect('/conference/' . $token . '/questions/' . $id);
    }

    public function getNewAnswerForm($token, $id)
    {
        $conference_id = Conference::where('remember_token', $token)->get()[0]['id'];

        $attend = ConferenceAttend::where([
            ['user_id', \Auth::user()->id],
            ['conference_id', $conference_id]
        ])->count();

        if ($attend == 0) {
            return redirect('/conference/view/' . $token);
        }

        return view('conference.answers.new', [
            'conference' => $token,
            'question' => $id
        ]);
    }

    public function storeNewAnswerForm(Request $request, $token, $id)
    {
        $conference_id = Conference::where('remember_token', $token)->get()[0]['id'];

        $attend = ConferenceAttend::where([
            ['user_id', \Auth::user()->id],
            ['conference_id', $conference_id]
        ])->count();

        if ($attend == 0) {
            return redirect('/conference/view/' . $token);
        }

        $inputs = $request->all();

        $rules = [
            'answer' => 'required',
        ];
        $messages = [
            'answer.required' => 'Please enter answer',
        ];
        $validator = Validator::make($inputs, $rules, $messages);
        if($validator->fails()){
            return redirect('/conference/'. $token .'/questions/'. $id . '/answer/new')->withInput()->withErrors($validator);
        }

        $answer = new Answers();

        $answer->fill($inputs);

        $answer['question_id'] = $id;
        $answer['owner'] = \Auth::user()->id;

        $answer->save();

        return redirect('/conference/' . $token . '/questions/' . $id);
    }

    public function getEditAnswerForm($token, $id, $ans_id)
    {
        $conference_id = Conference::where('remember_token', $token)->get()[0]['id'];

        $attend = ConferenceAttend::where([
            ['user_id', \Auth::user()->id],
            ['conference_id', $conference_id]
        ])->count();

        if ($attend == 0) {
            return redirect('/conference/view/' . $token);
        }

        $answer = Answers::find($ans_id);

        if ($id != $answer['question_id']) {
            return redirect('/conference/view/' . $token);
        }

        return view('conference.answers.edit', [
            'conference' => $token,
            'question' => $id,
            'answer' => $answer
        ]);
    }

    public function storeEditAnswerForm(Request $request, $token, $id, $ans_id)
    {
        $conference_id = Conference::where('remember_token', $token)->get()[0]['id'];

        $attend = ConferenceAttend::where([
            ['user_id', \Auth::user()->id],
            ['conference_id', $conference_id]
        ])->count();

        if ($attend == 0) {
            return redirect('/conference/view/' . $token);
        }

        $answer = Answers::find($ans_id);

        if ($id != $answer['question_id']) {
            return redirect('/conference/view/' . $token);
        }

        $inputs = $request->all();

        $rules = [
            'answer' => 'required',
        ];
        $messages = [
            'answer.required' => 'Please enter answer',
        ];
        $validator = Validator::make($inputs, $rules, $messages);
        if($validator->fails()){
            return redirect('/conference/'. $token .'/questions/'. $id . '/answer/'. $ans_id .'/edit')->withInput()->withErrors($validator);
        }

        $answer['answer'] = $inputs['answer'];

        $answer->save();

        return redirect('/conference/' . $token . '/questions/' . $id);
    }

    public function destroyConference($token)
    {
        $conference_owner = Conference::where('remember_token', $token)->get()[0]['owner'];
        $conference_id = Conference::where('remember_token', $token)->get()[0]['id'];

        if ($conference_owner != \Auth::user()->id) {
            return redirect('/dashboard/conference');
        }

        Conference::find($conference_id)->delete();

        $questions = Questions::where('conference_id', '=', $conference_id)->get();

        foreach ($questions as $question) {
            Answers::where('question_id', '=', $question['id'])->delete();
        }

        Questions::where('conference_id', '=', $conference_id)->delete();

        Attachments::where('conference_id', '=', $conference_id)->delete();

        ConferenceAttend::where('conference_id', '=', $conference_id)->delete();

        return redirect('/dashboard/conference');

    }

    public function destroyAnswer($token, $id, $ans_id)
    {
        $conference_id = Conference::where('remember_token', $token)->get()[0]['id'];

        $attend = ConferenceAttend::where([
            ['user_id', \Auth::user()->id],
            ['conference_id', $conference_id]
        ])->count();

        if ($attend == 0) {
            return redirect('/conference/view/' . $token);
        }

        $answer = Answers::find($ans_id);

        if ($answer['owner'] != \Auth::user()->id) {
            return redirect('/conference/' . $token);
        }

        Answers::find($ans_id)->delete();

        return redirect('/conference/'. $token . '/questions/' . $id);
    }

    public function getUploadForm($token) {
        $conference_id = Conference::where('remember_token', $token)->get()[0]['id'];

        $attend = ConferenceAttend::where([
            ['user_id', \Auth::user()->id],
            ['conference_id', $conference_id]
        ])->count();

        if ($attend == 0) {
            return redirect('/conference/view/' . $token);
        }



        return view('conference.attachments.new', ['conference' => $token]);
    }

    public function storeUploadForm(Request $request, $token) {
        $conference_id = Conference::where('remember_token', $token)->get()[0]['id'];

        $attend = ConferenceAttend::where([
            ['user_id', \Auth::user()->id],
            ['conference_id', $conference_id]
        ])->count();

        if ($attend == 0) {
            return redirect('/conference/view/' . $token);
        }

        $inputs = $request->all();

        $rules = [
            'filename' => 'required',
            'attachment' => 'required|max:11240000|mimes:pdf',
        ];
        $messages = [
            'filename.required' => 'Please enter filename',
            'attachment.mimes' => 'Attachment only PDF',
            'attachment.max' => 'Attachment size not over 10 MB',
        ];
        $validator = Validator::make($inputs, $rules, $messages);
        if($validator->fails()){
            return redirect('/conference/'. $token .'/attachment/new')->withInput()->withErrors($validator);
        }

        $attachment = new Attachments();

        $attachment->fill($inputs);

        $filename = $token . md5(time() . str_random(100));

        $file = $request->file('attachment');

        Storage::disk('azure')->put($filename . '.pdf', File::get(Input::file('attachment')));

        $attachment['location'] = $filename;

        $attachment['filename'] = $inputs['filename'];

        $attachment['owner'] = \Auth::user()->id;

        $attachment['conference_id'] = $conference_id;

        $attachment->save();

        return redirect('/conference/'. $token);

    }

    public function getAttachment($token, $id) {
        $conference_id = Conference::where('remember_token', $token)->get()[0]['id'];

        $attend = ConferenceAttend::where([
            ['user_id', \Auth::user()->id],
            ['conference_id', $conference_id]
        ])->count();

        if ($attend == 0) {
            return redirect('/conference/view/' . $token);
        }

        $attachment = Attachments::find($id)['location'] . '.pdf';

        return response(Storage::disk('azure')->get($attachment), 200, ['Content-Type' => 'application/pdf']);
    }

    public function destroyAttachment($token, $id) {
        $conference_id = Conference::where('remember_token', $token)->get()[0]['id'];

        $attend = ConferenceAttend::where([
            ['user_id', \Auth::user()->id],
            ['conference_id', $conference_id]
        ])->count();

        if ($attend == 0) {
            return redirect('/conference/view/' . $token);
        }

        $attachment = Attachments::find($id);

        if ($attachment['owner'] != \Auth::user()->id) {
            return redirect('/conference/' . $token);
        }

        Storage::disk('azure')->delete($attachment['location'] . '.pdf');

        Attachments::find($id)->delete();

        return redirect('/conference/' . $token);

    }
}
