<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Request;

use App\Http\Requests;
use App\Form_answer;
use App\Form_question;
use App\Form_setting;
use App\Http\Requests\FormAnswerRequest;
use Datatables;

class FormAnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $search = \Request::get('search');
        
        // $fanswers = Form_answer::where('answer','like','%'.$search.'%')->paginate(7);
        
        // if (Request::ajax()) {                                            
        //     return view('admin.dform.answer.answers', compact('fanswers'));
        // }
        
        // $deleted = false;        

        $notifs = \App\Helpers\Notifs::getNotifs();
        return view('admin.dform.answer.index', compact('notifs'));        
    }

    public function indexAjax() {        
        $fr = Form_answer::
                leftJoin('form_question', 'form_answer.question_id', '=', 'form_question.id')
                ->leftJoin('form_setting', 'form_answer.options_type', '=', 'form_setting.id')
                ->select(['form_answer.id', 'form_answer.answer', 'form_answer.description', 'form_answer.options_type', 'form_question.question', 'form_setting.name']);        
        return Datatables::of($fr)->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $fqs = Form_question::pluck('question', 'id');        
        $ats = Form_setting::where('name', 'like', '%Option%')->pluck('name', 'id');

        $notifs = \App\Helpers\Notifs::getNotifs();
        return view('admin.dform.answer.create', compact('fqs', 'ats', 'notifs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FormAnswerRequest $request)
    {
        $input = $request->all();

        Form_answer::create($input);
        
        return redirect('/admin/answer');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $fa = Form_answer::findOrFail($id);
                
        return redirect('/admin/answer');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $fa = Form_answer::findOrFail($id);
        $fqs = Form_question::pluck('question', 'id');        
        $ats = Form_setting::where('name', 'like', '%Option%')->pluck('name', 'id');

        $notifs = \App\Helpers\Notifs::getNotifs();
        return view('admin.dform.answer.edit', compact('fa', 'fqs', 'ats', 'notifs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FormAnswerRequest $request, $id)
    {        
        $fa = Form_answer::findOrFail($id);

        $fa->update($request->all());

        return redirect('/admin/answer');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $fa = Form_answer::findOrFail($id);

        try {
            $fa->delete();
            $deleted = true;
            $deletedMsg = "Data " . $fa->answer . " is deleted";      
        }catch(\Exception $e){
            $deleted = false;
            $deletedMsg = "Error while deleting data";      
        }
        
        return response()->json(['success' => $deleted, 'msg' => $deletedMsg]);
    }
}
