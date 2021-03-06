<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use App\Form_result;
use Illuminate\Support\Str;
use App\Form_question_group;
use App\Form_question;
use App\Form_type;
use App\Kta;
use App\Notification;
use Carbon\Carbon;
use App\User;
use App\Regnum;
use Datatables;

class MemberController extends Controller
{
    /**
     * Menampilkan halaman dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {                       
        $notifs = \App\Helpers\Notifs::getNotifs();
        $member = Auth::user();
        $detail = Form_result::                    
                    where('id_user', '=', $member->id)
                    ->get();
        $fr = Form_result::                
                where('id_user', '=', $member->id)                
                ->where('id_question', '=', "1")
                ->first();

        $required = $this->required();
        $completed = $this->completed();
        $percentage = $this->percentage();

        $kta = Kta::where('owner', '=', $member->id)->first();
        $exp_show = false;
        $exp_text1 = "";
        $exp_text2 = "";
        if ($kta) {
            $kta = $member->kta->first()->kta;

            if ($kta=="") {
            } else if ($kta=="requested") {                
            } else if ($kta=="validated") {                
            } else if ($kta=="cancelled") {                
            } else {                
                $today = new Carbon();
                $exp = Carbon::parse($member->kta->first()->expired_at);

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
                    
                    if ($today >= $exp) {
                        $exp_text1 = "Your KTA is Expired";
                        $exp_text2 = $exp_at." ".$m." Ago";
                    } else if ($exp_month<=3) {                    
                        $exp_text1 = "Your KTA will be Expired";
                        $exp_text2 = "In ".$exp_at." ".$m;
                    }
                }
            }            
        } else {
            $kta = "";            
        }

        $rn = Regnum::where('owner', '=', $member->id)->first();
        if ($rn) {
            $rn = $member->regnum->regnum;
        } else {
            $rn = "";
        }

        $results = Form_result::where('id_user', '=', $member->id)->get();
        $compclass = "";
        $compform = "";
        $compname = "";
        $daerah = "";
        $provinsi = "";
        foreach ($results as $key => $result) {
            $question = $result->question;
            if (str_contains($question, "Klasifikasi")) {
                $compclass = $result->answer;
            } else if (str_contains($question, "Bentuk Perusahaan")) {
                $compform = $result->answer;
            } else if (str_contains($question, "Nama Perusahaan")) {
                $compname = $result->answer;
            } else if (str_contains($question, "Kabupaten / Kota")) {
                $daerah = $result->territory_name;
            } else if (str_contains($question, "Provinsi")) {
                $provinsi = $result->territory_name;
            }
        }

        $idoc = Form_question_group::where('name', 'like', '%Upload%')->first()->id;
        $qdoc = Form_question::where('group_question', '=', $idoc);
        $rdoc = $qdoc->count();
        $fdoc = Form_result::where('id_user', '=', $member->id)->whereIn('id_question', $qdoc->pluck('id'));
        $cdoc = $fdoc->count();
        $docs = $fdoc->get();

        $comm = Form_result::where('id_user', '=', $member->id)->where('commentary', '!=', '')->count();
        $corr = Form_result::where('id_user', '=', $member->id)->where('correction', '!=', '')->count();

        // $chat = $member->chat_acc == 'created' : true ? false;
        $chat = false;
        if ($member->chat_acc=='created') $chat = true;

        return view('member.dashboard.index', compact('notifs', 'member', 'detail', 'required', 'completed', 
                'percentage', 'kta', 'compclass', 'compform', 'compname', 'daerah', 'provinsi', 'cdoc', 'rdoc', 
                'docs', 'rn', 'exp_at', 'exp_show', 'exp_text1', 'exp_text2', 'comm', 'corr', 'chat'));
    }

    public function kta()
    {                       
        $user = Auth::user();          

        $thekta = Kta::where('owner', '=', $user->id)->first();
        if ($thekta) {
            $kta = $user->kta->first()->kta;

            $today = new Carbon();
            $exp = Carbon::parse($user->kta->first()->expired_at);

            $exp_month = $exp->diffInMonths($today);

            $exp_show = false;
            if ($exp_month<=3||$today >= $exp) {
                $exp_show = true;

                $exp_at = $exp_month;
                if ($exp_month==0) {
                    $exp_at = $exp->diffInDays($today);

                    $m = "Hari";                
                } else {
                    $m = "Bulan";                
                }                
                if ($today >= $exp) {
                    $exp_text1 = "Masa Berlaku KTA anda telah habis. Segera perpanjang KTA anda untuk terus menikmati layanan anggota Kadin.";
                    $exp_text2 = "Masa berlaku KTA anda telah habis sejak ";
                    $exp_text3 = $exp_at." ".$m." Lalu";
                } else if ($exp_month<=3) {
                    $exp_text1 = "Masa Berlaku KTA anda telah berada di masa tenggang.";
                    $exp_text2 = "Kartu Tanda Anggota Anda tidak akan berlaku dalam ";
                    $exp_text3 = $exp_at." ".$m;
                }
            }
            
            $ext_show = true;
            if ($thekta->perpanjangan=="requested") {
                $ext_show = false;
            }
        } else {
            $kta = "";
        }        

        $notifs = \App\Helpers\Notifs::getNotifs();
        return view('member.kta.index', compact('notifs', 'kta', 'exp_show', 'exp_text1', 'exp_text2', 'exp_text3',
                'ext_show'));
    }

    public function ajaxKta() {
        $user = Auth::user();
        $kta = Kta::where('owner', '=', $user->id)->get();

        return Datatables::of($kta)->make(true);
    }

    public function regnum()
    {                       
        $user = Auth::user();          

        $rn = Regnum::where('owner', '=', $user->id)->first();        
        if ($rn) {
            $rn = $user->regnum->regnum;
        } else {
            $rn = "";
        }        

        $notifs = \App\Helpers\Notifs::getNotifs();
        return view('member.rn.index', compact('notifs', 'rn'));
    }
    
    public function compprof()
    {                                       
        $notifs = \App\Helpers\Notifs::getNotifs();
        $member = Auth::user();

        $trackingcode = Form_result::where('id_user', '=', $member->id)->first()->trackingcode;
        //detail 1 
        $qg1 = Form_question_group::where('name', 'like', '%Pendaftaran%')->first();
        $q1 = Form_question::where('group_question', '=', $qg1->id)->pluck('id');
        $q1[] = 'Provinsi';
        $q1[] = 'Kabupaten / Kota';
        $q1[] = 'Alamat Lengkap';
        $q1[] = 'Kode Pos';
        $detail1 = Form_result::where('id_user', '=', $member->id)
                     ->whereIn('id_question', $q1)
                     ->get();
        //detail 2
        $detail2 = Form_result::                
                where('id_user', '=', $member->id)                
                ->get();
        $fq = Form_result::
                where('id_user', '=', $member->id)
                ->where('id_question', '=', "1")
                ->first();
        $qg2 = 0;
        if ($fq) {
            $fq = $fq->answer;
            $btk = Str::upper($fq);
            $fqg = Form_question_group::where('name', 'like', '%'.$btk.'%')->first()->name;
            foreach ($detail2 as $key => $value) {
                if ($value->question_group == $fqg) {
                } else {
                    unset($detail2[$key]);
                }
            }

            $qg2 = Form_question_group::where('name', 'like', '%'.$btk.'%')->first();
        }         

        //detail 3
        $detail3 = Form_result::                
                where('id_user', '=', $member->id)                
                ->get();            
        $fqg = Form_question_group::where('name', 'like', '%Tahap 3%')->first()->name;
        $qg3 = Form_question_group::where('name', 'like', '%Tahap 3%')->first();
        foreach ($detail3 as $key => $value) {
            if ($value->question_group == $fqg) {                
            } else {                
                unset($detail3[$key]);
            }


        }
        
        //documents uploded
        $docs = Form_result::
                where('id_user', '=', $member->id)                
                ->get();
        $fqg = Form_question_group::where('name', 'like', '%Upload%')->first()->name;
        $qgd = Form_question_group::where('name', 'like', '%Upload%')->first();
        foreach ($docs as $key => $value) {
            if ($value->question_group == $fqg) {
            } else {
                unset($docs[$key]);
            }
        }

        $show = true;
        $thekta = Kta::where('owner', '=', $member->id)->first();
        if ($thekta) {
            $kta = $member->kta->first()->kta;
            if ($kta=="requested"||$kta=="validated") {
                $show = false;
            } else {
                if ($thekta->perpanjangan=="requested") {
                    $show = false;
                }
            }
        }

        return view('member.compprof.index', compact('notifs', 'member', 'show', 'detail1', 'detail2', 'detail3', 'docs',
                'qg1', 'qg2', 'qg3', 'qgd', 'trackingcode'));
    }

    public function indexii()
    {                
        // return "asdad";
        $user = Auth::user();
        $fr = Form_result::                
                where('id_user', '=', $user->id)
                ->where('id_question', '=', "1")
                ->first()->answer;
        $btk = Str::upper($fr);
        // return $btk;

        $tahap2 = Form_question_group::where('name', 'like', '%'.$btk.'%')->first()->id;
        $tahap3 = Form_question_group::where('name', 'like', '%Tahap 3%')->first()->id;

        // $fquestions = Form_question::whereHas('group', function ($q) use($btk) {        
        //     $q->whereIn('name', 'like', '%'.$btk.'%')
        //     ->where('name', 'like', '%Pendaftaran%');
        // })->orderBy('order', 'asc')->get();

        $fquestions = Form_question::whereIn('group_question', [$tahap2, $tahap3])->orderBy('group_question', 'asc')->orderBy('order', 'asc')->get();
        $fresults = Form_result::where('id_user', '=', $user->id)->get();
        $notifs = \App\Helpers\Notifs::getNotifs();
        return view('member.register.tahapii', compact('notifs', 'user', 'fquestions', 'fresults'));
    }

    public function completeprofile($id)
    {
        $user = Auth::user();
        $fqg = Form_question_group::where('id', '=', $id)->first();
        $fquestions = Form_question::where('group_question', '=', $id)->orderBy('group_question', 'asc')->orderBy('order', 'asc')->get();        
        $fresults = Form_result::where('id_user', '=', $user->id)->get();

        $notifs = \App\Helpers\Notifs::getNotifs();
        return view('member.register.completeprofile', compact('notifs', 'fquestions', 'fresults', 'fqg'));
    }

    public function requestkta(Request $request) {
        $user = Auth::user();
        $type = $request['type'];

        $idoc = Form_question_group::where('name', 'like', '%Upload%')->first()->id;
        $qdoc = Form_question::where('group_question', '=', $idoc);
        $rdoc = $qdoc->count();
        $fdoc = Form_result::where('id_user', '=', $user->id)->whereIn('id_question', $qdoc->pluck('id'));
        $cdoc = $fdoc->count();

        $results = Form_result::where('id_user', '=', $user->id)->get();        

        $compname = "";
        $complead = "";
        $compaddr = "";
        $compbdus = "";
        $comppermit = "";
        $compqual = "";
        $jabatan = "";
        $postcode = "";
        $compnpwp = "";
        $daerah = "";
        $provinsi = "";
        foreach ($results as $key => $result) {
            $question = $result->question;
            if (str_contains($question, "Nama Perusahaan")) {
                $compname = $result->answer;
            } else if (str_contains($question, "Pemimpin Perusahaan")) {
                $complead = $result->answer;
            } else if (str_contains($question, "Alamat Lengkap")) {
                $compaddr = $result->answer;
            } else if (str_contains($question, "Bidang Usaha")) {
                $compbdus = $result->answer;
            } else if (str_contains($question, "No SIUP/SIUJK")) {
                $comppermit = $result->answer;
            } else if (str_contains($question, "Klasifikasi Perusahaan")) {
                $compqual = $result->answer;
            } else if (str_contains($question, "Jabatan")) {
                $jabatan = $result->answer;
            } else if (str_contains($question, "Kode Pos")) {
                $postcode = $result->answer;
            } else if (str_contains($question, "No NPWP")) {
                $compnpwp = $result->answer;
            } else if (str_contains($question, "Kabupaten / Kota")) {
                $daerah = $result->territory_name;
            } else if (str_contains($question, "Provinsi")) {
                $provinsi = $result->territory_name;
            }
        }

        if ($rdoc!=$cdoc) {            
            return response()->json(['success' => false, 'msg' => "Please complete Uploading required documents!"]);
        }
        if ($compname=="") {
            return response()->json(['success' => false, 'msg' => "Field 'Nama Perusahaan' is required!"]);
        }
        if ($complead=="") {
            return response()->json(['success' => false, 'msg' => "Field 'Pemimpin Perusahaan' is required!"]);
        }
        if ($compaddr=="") {
            return response()->json(['success' => false, 'msg' => "Field 'Alamat Lengkap' is required!"]);
        }
        if ($compbdus=="") {
            return response()->json(['success' => false, 'msg' => "Field 'Bidang Usaha' is required!"]);
        }
        if ($comppermit=="") {
            return response()->json(['success' => false, 'msg' => "Field 'No SIUP/SIUJK' is required!"]);
        }
        if ($compqual=="") {
            return response()->json(['success' => false, 'msg' => "Field 'Klasifikasi Perusahaan' is required!"]);
        }
        if ($jabatan=="") {
            return response()->json(['success' => false, 'msg' => "Field 'Jabatan' is required!"]);
        }
        if ($postcode=="") {
            return response()->json(['success' => false, 'msg' => "Field 'Kode Pos' is required!"]);
        }
        if ($compnpwp=="") {
            return response()->json(['success' => false, 'msg' => "Field 'No NPWP' is required!"]);
        }
        if ($daerah=="") {
            return response()->json(['success' => false, 'msg' => "Field 'Kabupaten / Kota' is required!"]);
        }
        if ($provinsi=="") {
            return response()->json(['success' => false, 'msg' => "Field 'Provinsi' is required!"]);
        }

        try {                
            $kta = Kta::where('owner', '=', $user->id)->first();

            if ($kta) {
                if ($type=="reqpostkta") {
                    $kta->owner = $user->id;
                    $kta->kta = 'requested';
                    $kta->requested_at = new Carbon();
                    $kta->granted_at = "";
                    $kta->save();

                    $idSender = Auth::user()->id;
                    $idDaerah = Auth::user()->territory;
                    $daerah = User::where('role', '=', '5')->where('territory', '=', $idDaerah)->get();
                    foreach ($daerah as $key => $d) {
                        \App\Helpers\Notifs::create($d->id, $idSender, null, "New KTA Request");
                    }
                    \App\Helpers\Notifs::create($user->id, $user->id, null, "KTA Request is Sent");

                    $deleted = true;
                    $deletedMsg = "KTA Request is Sent";
                } else {
                    $deleted = false;
                    $deletedMsg = "Your KTA is Already Generated";
                }
            } else {
                $kta = new Kta;
                $kta->owner = $user->id;
                $kta->kta = 'requested';
                $kta->requested_at = new Carbon();
                $kta->granted_at = "";
                $kta->save();

                $idSender = Auth::user()->id;
                $idDaerah = Auth::user()->territory;
                $daerah = User::where('role', '=', '5')->where('territory', '=', $idDaerah)->get();
                foreach ($daerah as $key => $d) {
                    \App\Helpers\Notifs::create($d->id, $idSender, null, "New KTA Request");
                }
                \App\Helpers\Notifs::create($user->id, $user->id, null, "KTA Request is Sent");

                $deleted = true;
                $deletedMsg = "KTA Request is Sent";
            }
        }catch(\Exception $e){
            $deleted = false;
            $deletedMsg = "Error while requesting KTA";
        }

        return response()->json(['success' => $deleted, 'msg' => $deletedMsg]);
    }

    function required() {
        $user = Auth::user();
        $fr = Form_result::
        where('id_user', '=', $user->id)
            ->where('id_question', '=', "1")
            ->first()->answer;
        $btk = Str::upper($fr);

        $tahap2 = Form_question_group::where('name', 'like', '%'.$btk.'%')->first()->id;
        $tahap3 = Form_question_group::where('name', 'like', '%Tahap 3%')->first()->id;

        $fquestions = Form_question::whereIn('group_question', [$tahap2, $tahap3])->count();

        return $fquestions;
    }

    function completed() {
        $user = Auth::user();
        $fr = Form_result::
        where('id_user', '=', $user->id)
            ->where('id_question', '=', "1")
            ->first()->answer;
        $btk = Str::upper($fr);
        // return $btk;

        $tahap2 = Form_question_group::where('name', 'like', '%'.$btk.'%')->first()->id;
        $tahap3 = Form_question_group::where('name', 'like', '%Tahap 3%')->first()->id;

        $fquestions = Form_question::whereIn('group_question', [$tahap2, $tahap3])->pluck('id');
        $fresults = Form_result::where('id_user', '=', $user->id)
                        ->whereIn('id_question', $fquestions)
                        ->count();

        return $fresults;
    }

    function percentage() {
        $required = $this->required();
        $completed = $this->completed();

        return ($completed/$required) * 100;
    }

    public function ktaprint(Request $request) {
        $user = Auth::user();
        $kta = $user->kta->first()->kta;
        $rn = $user->regnum->regnum;        
        
        if ($kta==""||$kta=="requested"||$kta=="validated"||$kta=="cancelled") {
            return response()->json(['success' => false, 'msg' => "KTA is not Available!"]);
        }
        if ($rn==""||$rn=="requested"||$rn=="validated"||$rn=="cancelled") {
            return response()->json(['success' => false, 'msg' => "RN Number is not Available!"]);
        }

        return response()->json(['success' => true, 'msg' => "Printing KTA"]);        
    }

    public function printkta()
    {                               
        $user = Auth::user();
        $kta = $user->kta->first()->kta;
        $rn = $user->regnum->regnum;
        $exp = Carbon::parse($user->kta->first()->expired_at)->format('m-d-Y');

        $results = Form_result::where('id_user', '=', $user->id)->get();        

        $compname = "";
        $complead = "";
        $compaddr = "";
        $compbdus = "";
        $comppermit = "";
        $compqual = "";
        $jabatan = "";
        $postcode = "";
        $compnpwp = "";
        $daerah = "";
        $provinsi = "";
        foreach ($results as $key => $result) {
            $question = $result->question;
            if (str_contains($question, "Nama Perusahaan")) {
                $compname = $result->answer;
            } else if (str_contains($question, "Pemimpin Perusahaan")) {
                $complead = $result->answer;
            } else if (str_contains($question, "Alamat Lengkap")) {
                $compaddr = $result->answer;
            } else if (str_contains($question, "Bidang Usaha")) {
                $compbdus = $result->answer;
            } else if (str_contains($question, "No SIUP/SIUJK")) {
                $comppermit = $result->answer;
            } else if (str_contains($question, "Klasifikasi Perusahaan")) {
                $compqual = $result->answer;
            } else if (str_contains($question, "Jabatan")) {
                $jabatan = $result->answer;
            } else if (str_contains($question, "Kode Pos")) {
                $postcode = $result->answer;
            } else if (str_contains($question, "No NPWP")) {
                $compnpwp = $result->answer;
            } else if (str_contains($question, "Kabupaten / Kota")) {
                $daerah = $result->territory_name;
            } else if (str_contains($question, "Provinsi")) {
                $provinsi = $result->territory_name;
            }
        }
        
        return view('member.kta.print', compact('kta', 'rn', 'exp',
                'compname', 'complead', 'compaddr', 'compbdus', 'comppermit', 'compqual', 'jabatan', 'postcode', 'compnpwp', 'daerah', 'provinsi'));
    }

    function extkta(Request $request) {
        $user = Auth::user();

        try {                
            $kta = Kta::where('owner', '=', $user->id);            

            if (!$kta) {
                $deleted = false;
                $deletedMsg = "KTA Data not Found!";
            } else {                
                                    
                $kta->update([
                        "perpanjangan" => "requested",
                    ]);

                $idSender = Auth::user()->id;
                $pusat = User::where('role', '=', '3')->get();
                foreach ($pusat as $key => $p) {
                    \App\Helpers\Notifs::create($p->id, $idSender, null, "New KTA Extension Request");
                }

                \App\Helpers\Notifs::create($user->id, $user->id, null, "KTA Extension Request is Sent");
                $deleted = true;
                $deletedMsg = "KTA Extension Request is Sent";
            }
        }catch(\Exception $e){
            $deleted = false;
            $deletedMsg = "Error while requesting KTA";

            $kta->update([
                "perpanjangan" => "",
            ]);
        }

        return response()->json(['success' => $deleted, 'msg' => $deletedMsg]);
    }    
}