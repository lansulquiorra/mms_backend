<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Form_question;
use App\Form_result;
use Datatables;
use Illuminate\Support\Facades\Auth;
use App\User;
use Carbon\Carbon;
use App\Kta;
use DB;
use App\Notification;
use App\Form_question_group;
use Illuminate\Support\Str;
use App\Regnum;
use Illuminate\Support\Collection;

class KadinProvinsiController extends Controller
{
    public function dashboard()
    {                       
    	$notifs = \App\Helpers\Notifs::getNotifs();

        $user = Auth::user();
        $terr = $user->territory;
        $owner = User::where('territory', 'like', $terr.'%')->where('role', '=', 2)->pluck('id');

        $totalkta = Kta::where('kta', '<>', 'requested')
                    ->where('kta', '<>', 'cancelled')
                    ->whereIn('owner', $owner)
                    ->get()->count();
        $totalreqkta = Kta::where('kta', '=', 'validated')->whereIn('owner', $owner)->get()->count();
        $totalcancelkta = Kta::where('kta', '=', 'cancelled')->whereIn('owner', $owner)->get()->count();

        $ktas = Kta::where('kta', '<>', 'requested')
                    ->where('kta', '<>', 'cancelled')
                    ->whereIn('owner', $owner)
                    ->get();
        $totalexpkta = 0;
        $today = new Carbon();
        foreach ($ktas as $key => $value) {
            $exp = Carbon::parse($value->expired_at);
            $exp_at = $exp->diffInMonths($today);

            if ($exp_at<=3||$today>=$exp) {
                $totalexpkta = $totalexpkta+1;
            }
        }
        
        return view('provinsi.dashboard.index', compact('notifs', 'totalkta', 'totalreqkta', 'totalcancelkta', 'user', 'totalexpkta'));
    }

    public function ktaDetail($id)
    {
        $notifs = \App\Helpers\Notifs::getNotifs();
        $member = User::find($id);
        
        $detail1 = \App\Helpers\Details::detail1($member->id);
        $detail2 = \App\Helpers\Details::detail2($member->id);
        $detail3 = \App\Helpers\Details::detail3($member->id);
        $docs = \App\Helpers\Details::docs($member->id);

        // return $detail1;
        return view('provinsi.kta.detail', compact('notifs', 'member', 'detail1', 'detail2', 'detail3', 'docs'));
    }

    public function profile() {
        $notifs = \App\Helpers\Notifs::getNotifs();

        $user = Auth::user();
        $fr = Form_result::                
                where('id_user', '=', $user->id)                
                ->where('id_question', '=', "1")
                ->first();

        $required = 0;
        $percentage = 0;
        $completed = 0;

        if ($fr) {
            $fr = $fr->answer;
            $btk = Str::upper($fr);

            $fqg1 = Form_question_group::where('name', 'like', '%'.$btk.'%')->first()->id;
            $fq1 = Form_question::where('group_question', '=', $fqg1)->count();

            $fqg2 = Form_question_group::where('name', 'like', '%Pendaftaran%')->first()->id;
            $fq2 = Form_question::where('group_question', '=', $fqg2)->count();

            $fqg3 = Form_question_group::where('name', 'like', '%Tahap 3%')->first()->id;
            $fq3 = Form_question::where('group_question', '=', $fqg3)->count();

            $required = $fq1+$fq2+$fq3;
            $completed = Form_result::where('id_user', '=', $user->id)->count();       
            $percentage = ($completed/$required) * 100;                
        }         

        $kta = $user->no_kta;

        return view('provinsi.profile.profile', compact('notifs', 'required', 'completed', 'percentage', 'kta'));
    }

    public function ktaRequest()
    {
    	$notifs = \App\Helpers\Notifs::getNotifs();

        $terr = Auth::user()->territory;
        $owner = User::where('territory', 'like', $terr.'%')->where('role', '=', 2)->pluck('id');
        $kta = Kta::where('kta', '=', 'validated')->whereIn('owner', $owner)->get();

        $carbon = new Carbon();
        $monthsago = $carbon->subMonths(6)->month;
        $monthslater = new Carbon();

        $labels = array();
        $data = array();
        for ($i=$monthsago; $i != $monthslater->month+1 ; $i++) { 
            if ($i==12) {
                $i = 0;
            }

            $labels[] = date('F', strtotime("2000-$i-01"));
            $data[] = Kta::where('kta', '=', 'validated')->whereMonth('created_at', '=', $i)->count();            
        }        

    	return view('provinsi.kta.request.index', compact('notifs', 'kta', 'labels', 'data'));
    }

    public function ajaxKta() {        
        $terr = Auth::user()->territory;
        $ktas = Kta::whereHas('user', function ($query) use($terr) {
            $query->where('territory', 'like', $terr.'%');
        })->where('kta', '=', 'validated');

        // $fr = Form_result::leftJoin('users', 'form_result.id_user', '=', 'users.id')
        //         ->where('id_question', '=', '8')
        //         ->whereIn('id_user', $ktas->pluck('owner'))
        //         ->get();

        // return $ktas->count()." ".$fr->count();
        $dt = new Collection;
        // return $ktas->get()." +++++ ".$fr;
        foreach ($ktas->get() as $key => $kta) {            
            if (str_contains($kta->perpanjangan, 'processed')) {
                $status = "Perpanjangan KTA";
            } else {
                $status = "Pembuatan KTA";
            }

            $comp = Form_result::where('id_user', '=', $kta->owner)
                        ->where('id_question', '=', '8')
                        ->first()->answer_value;
            $comptype = Form_result::where('id_user', '=', $kta->owner)
                        ->where('id_question', '=', '1')
                        ->first()->answer;
            $regat = Form_result::where('id_user', '=', $kta->owner)->first()->created_at->format('d/m/Y');

            $dt->push([                       
                    'company' => $comptype." ".$comp,
                    'comprep' => $kta->user->name,
                    'registered_at' => $regat,
                    'id_user' => $kta->owner,
                    'territory' => $kta->user->territory,
                    'status' => $status,
                    'perpanjangan' => $kta->perpanjangan,
                ]);
        }

        return Datatables::of($dt)->make(true);
    }    

    public function cancelKta(Request $request) {        
        $keterangan = $request['keterangan'];        
        $id_owner = $request['id_user'];

        $kta = Kta::where('owner', '=', $id_owner)->first();        
        
        if ($kta) {
            try {
                $kta->kta = "cancelled";
                $kta->keterangan = $keterangan;
                $kta->save();

                $deleted = true;
                $deletedMsg = "KTA request from " . $kta->user->name . " is cancelled";      
            }catch(\Exception $e){
                $deleted = false;
                $deletedMsg = "Error while executing command";      
            }        
        } else {
            $deleted = false;
            $deletedMsg = "Data is not available";
        }
        
        return response()->json(['success' => $deleted, 'msg' => $deletedMsg]);
    }

    public function ktaCancel() {
        $notifs = \App\Helpers\Notifs::getNotifs();

        $terr = Auth::user()->territory;
        $owner = User::where('territory', 'like', $terr.'%')->where('role', '=', 2)->pluck('id');
        $kta = Kta::where('kta', '=', 'cancelled')->whereIn('owner', $owner)->get();

        $carbon = new Carbon();
        $monthsago = $carbon->subMonths(6)->month;
        $monthslater = new Carbon();

        $labels = array();
        $data = array();
        for ($i=$monthsago; $i != $monthslater->month+1 ; $i++) { 
            if ($i==12) {
                $i = 0;
            }

            $labels[] = date('F', strtotime("2000-$i-01"));
            $data[] = Kta::where('kta', '=', 'cancelled')->whereMonth('created_at', '=', $i)->count();            
        }

        return view('provinsi.kta.canceled.index', compact('notifs', 'kta', 'labels', 'data'));
    }

    public function ajaxKtaCancel() {
        $terr = Auth::user()->territory;
        $kta = Kta::whereHas('user', function ($query) use($terr) {
            $query->where('territory', 'like', $terr.'%');
        })->where('kta', '=', 'cancelled')->pluck('owner');

        $fr = Form_result::where('id_question', '=', '8')
                ->whereIn('id_user', $kta)                
                ->get();
        return Datatables::of($fr)->make(true);
    }    

    public function insertKta(Request $request) {
        $st = $request['st'];
        $nd = $request['nd'];
        $rd = $request['rd'];
        $id_owner = $request['id_user'];

        $kta = Kta::where('owner', '=', $id_owner)->first();

        if ($kta) {            
            try {
                $carbon = new Carbon();
                
                // $kta->kta = $st."-".$nd."/".$rd;
                $kta->kta = $st."-".$nd;
                $kta->granted_at = $carbon;
                $kta->expired_at = $carbon->addYear(1);
                if (str_contains($kta->perpanjangan, 'processed')) {
                    $kta->perpanjangan = "granted";
                    $kta->save();
                } else {
                    $kta->save();

                    $rn = new Regnum();
                    $rn->owner = $id_owner;
                    $rn->regnum = 'requested';
                    $rn->requested_at = new Carbon();
                    $rn->granted_at = "";
                    $rn->save();
                }                                
                
                $deleted = true;
                $deletedMsg = "KTA for " . $kta->user->name . " is set";
            }catch(\Exception $e){
                $deleted = false;
                $deletedMsg = "Error while executing command";                
            }        
        } else {
            $deleted = false;
            $deletedMsg = "Data is not available";            
        }
        
        return response()->json(['success' => $deleted, 'msg' => $deletedMsg]);
    }

    public function ktaList()
    {        
        $notifs = \App\Helpers\Notifs::getNotifs();

        $terr = Auth::user()->territory;
        $owner = User::where('territory', 'like', $terr.'%')->where('role', '=', 2)->pluck('id');        
        $kta = Kta::where('kta', '<>', 'requested')->where('kta', '<>', 'validated')->where('kta', '<>', 'cancelled')->whereIn('owner', $owner)->get();

        $carbon = new Carbon();
        $monthsago = $carbon->subMonths(6)->month;
        $monthslater = new Carbon();

        $labels = array();
        $data = array();
        for ($i=$monthsago; $i != $monthslater->month+1 ; $i++) { 
            if ($i==12) {
                $i = 0;
            }

            $labels[] = date('F', strtotime("2000-$i-01"));
            $data[] = Kta::where('kta', '<>', 'requested')
                        ->where('kta', '<>', 'cancelled')
                        ->whereMonth('created_at', '=', $i)
                        ->count();
        }

        return view('provinsi.kta.list.index', compact('notifs', 'kta', 'labels', 'data'));
    }

    public function ajaxKtaList() {
        $terr = Auth::user()->territory;
        $kta = Kta::whereHas('user', function ($query) use($terr) {
                $query->where('territory', 'like', $terr.'%');
            })->where('kta', '<>', 'requested')
            ->where('kta', '<>', 'validated')
            ->where('kta', '<>', 'cancelled')
            ->pluck('owner');

        $fr = Form_result::where('id_question', '=', '8')
                ->whereIn('id_user', $kta)
                ->get();
        $fr = Form_result::leftJoin('kta', 'form_result.id_user', '=', 'kta.owner')
                ->where('form_result.id_question', '=', '8')
                ->whereIn('form_result.id_user', $kta)
                ->get();
        return Datatables::of($fr)->make(true);
    }    

    /**
     * Menangani permintaan detail notif
     *
     * @return \Illuminate\Http\Response
     */
    public function notif($id)
    {                           
        $notif = Notification::find($id);

        $notif->active=false;
        $notif->save();

        $notifs = \App\Helpers\Notifs::getNotifs();
        
        if ($notif->value == "New Request KTA") {            
            return redirect('/dashboard/provinsi/kta/request');
        }
        

    }    

    public function notifall()
    {                           
        $notifs = \App\Helpers\Notifs::getNotifs();        
        
        return view('provinsi.notif.indexall', compact('notifs'));
    }

    public function valnas()
    {
        $notifs = \App\Helpers\Notifs::getNotifs();
        return view('provinsi.valnas.index', compact('notifs'));
    }

    public function ajaxvalnas() {                
        $rn = Regnum::where('regnum', '=', 'requested')->pluck('owner');
        $fr = User::whereIn('id', $rn)->get();

        return Datatables::of($fr)->make(true);
    }

    public function ktaExpired()
    {
        $notifs = \App\Helpers\Notifs::getNotifs();        
        return view('provinsi.kta.expired.index', compact('notifs'));
    }

    public function ajaxKtaExpired() {
        $terr = Auth::user()->territory;        
        $owner = Kta::whereHas('user', function ($query) use($terr) {
            $query->where('territory', 'like', $terr.'%');
        })->pluck('owner');
        $ktas = Kta::whereIn('owner', $owner)->get();
        
        $fr = new Collection;
        $today = new Carbon();        
        foreach ($ktas as $key => $value) {
            if ($value->expired_at) {
                $exp = Carbon::parse($value->expired_at);
                $exp_month = $exp->diffInMonths($today);

                if ($exp_month<=3||$today >= $exp) {

                    $exp_show = true;                

                    $exp_at = $exp_month;
                    if ($exp_month==0) {
                        $exp_at = $exp->diffInDays($today);

                        if ($exp_at==1) {
                            $m = "Day";
                        } else {
                            $m = "Days";
                        }
                    } else {
                        if ($exp_at==1) {
                            $m = "Month";
                        } else {
                            $m = "Months";
                        }
                    }                

                    $exp_in = "";
                    if ($today >= $exp) {
                        $exp_in = $exp_at." ".$m." Ago";
                    } else if ($exp_at<=3) {
                        $exp_in = "In ".$exp_at." ".$m;
                    }

                    $comp = Form_result::where('id_user', '=', $value->owner)
                                ->where('id_question', '=', '8')
                                ->first()->answer_value;
                    $comprep = $value->user->name;
                    $kta = $value->kta;
                    $id_user = $value->owner;
                    $exp_at = $value->expired_at;

                    $fr->push([
                        'company' => $comp,
                        'companyrep' => $comprep,
                        'kta' => $kta,
                        'expired_at' => $exp_at,
                        'expired_in' => $exp_in,
                        'id_user' =>  $id_user,
                    ]);
                }      
            }            
        }
        
        return Datatables::of($fr)->make(true);
    }

    public function ktaExtension()
    {                       
        $notifs = \App\Helpers\Notifs::getNotifs();        

        return view('provinsi.kta.expired.extension', compact('notifs'));
    }

    public function ajaxKtaExtension() {   
        $terr = Auth::user()->territory;        
        $owner = Kta::whereHas('user', function ($query) use($terr) {
            $query->where('territory', 'like', $terr.'%');
        })->pluck('owner');
        $ktas = Kta::whereIn('owner', $owner)->where('keterangan', '=', 'request_perpanjangan')->get();
        
        $fr = new Collection;
        $today = new Carbon();
        foreach ($ktas as $key => $value) {
            $exp = Carbon::parse($value->expired_at);
            $exp_month = $exp->diffInMonths($today);

            if ($exp_month<=3||$today >= $exp) {
                $exp_show = true;                

                $exp_at = $exp_month;
                if ($exp_month==0) {
                    $exp_at = $exp->diffInDays($today);

                    if ($exp_at==1) {
                        $m = "Day";
                    } else {
                        $m = "Days";
                    }
                } else {
                    if ($exp_at==1) {
                        $m = "Month";
                    } else {
                        $m = "Months";
                    }
                }                

                $exp_in = "";
                if ($today >= $exp) {
                    $exp_in = $exp_at." ".$m." Ago";
                } else if ($exp_at<=3) {
                    $exp_in = "In ".$exp_at." ".$m;
                }

                $comp = Form_result::where('id_user', '=', $value->owner)
                            ->where('id_question', '=', '8')
                            ->first()->answer_value;
                $comprep = $value->user->name;
                $kta = $value->kta;
                $id_user = $value->owner;
                $exp_at = $value->expired_at;

                $fr->push([
                    'company' => $comp,
                    'companyrep' => $comprep,
                    'kta' => $kta,
                    'expired_at' => $exp_at,
                    'expired_in' => $exp_in,
                    'id_user' =>  $id_user,
                ]);
            }      
        }

        return Datatables::of($fr)->make(true);
    }        
}