<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Request;

use App\Http\Requests;
use App\Form_result;
use App\Form_question;
use App\Form_answer;
use App\User;
use App\Http\Requests\FormResultRequest;
use Datatables;
use Illuminate\Support\Collection;

class FormResultController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        
        // $fresults = Form_result::whereHas('question', function ($q) {
        //     $search = \Request::get('search');
        //     $q->where('question', 'like', '%'.$search.'%');
        // })->paginate(7);                

        // if (Request::ajax()) {                                            
        //     return view('admin.dform.result.results', compact('fresults'));
        // }        

        // // return $fanswers[0]->question;
        // return view('admin.dform.result.index', compact('fresults'));        
        $notifs = \App\Helpers\Notifs::getNotifs();
        return view('admin.dform.result.index', compact('notifs'));
    }

    public function indexAjax() {
        $frs = Form_result::get();

        $dt = new Collection;        
        foreach ($frs as $key => $fr) {

            $question = $fr->question;
            $atype = $fr->answer_type;
            $answer = $fr->answer;
            $name = $fr->val_by;            
            
            $dt->push([
                    'question' => $question,
                    'answer_type' => $atype,
                    'answer' => $answer,
                    'name' => $name,
                    'trackingcode' => $fr->trackingcode,
                    'id' => $fr->id,                    
                ]);
        }

        return Datatables::of($dt)->make(true);
        // ->where('form_result.id_user', '=', '13')
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $fqs = Form_question::pluck('question', 'id');
        $fas = Form_answer::pluck('answer', 'id');
        $users = User::pluck('username', 'id');
        
        $notifs = \App\Helpers\Notifs::getNotifs();
        return view('admin.dform.result.create', compact('fqs', 'fas', 'users', 'notifs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FormResultRequest $request)
    {
        $input = $request->all();
         
        Form_result::create($input);

        return redirect('/admin/result');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {      
        return redirect('/admin/result_');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $fr = Form_result::find($id);        
        $fqs = Form_question::pluck('question', 'id');
        $fas = Form_answer::pluck('answer', 'id');
        $users = User::pluck('username', 'id');
        
        $notifs = \App\Helpers\Notifs::getNotifs();
        return view('admin.dform.result.edit', compact('fr', 'fqs', 'fas', 'users', 'notifs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FormResultRequest $request, $id)
    {
        $fa = Form_result::findOrFail($id);

        $fa->update($request->all());

        return redirect('/admin/result_');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {                
        $fr = Form_result::findOrFail($id);         

        try {
            $fr->delete();
            $deleted = true;
            $deletedMsg = "Data " . $fr->question->question . " is deleted";      
        }catch(\Exception $e){
            $deleted = false;
            $deletedMsg = "Error while deleting data";      
        }
        
        return response()->json(['success' => $deleted, 'msg' => $deletedMsg]);
    }
}
