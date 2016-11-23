@extends('member.app')

@section('head')
    <!-- <link href="{{ asset('resources/assets/css/table_styles.css') }}" rel="stylesheet"> -->

@stop

@section('sidebar')
  @include('member.kta.sidebar')
@stop

@section('content')
<div class="col-lg-10">
  <h2>KTA</h2>
  <ol class="breadcrumb">
    <li>
      <a>Member</a>
    </li>
    <li class="active">
      <strong>KTA</strong>
    </li>
  </ol>
</div>
<div class="col-lg-2">
  <div class="title-action">      
  </div>
</div>
@stop

@section('iframe')
<div class="row">
  <div class="col-lg-12">
    <div class="ibox float-e-margins">
      <div class="ibox-title">
        <h5>Your KTA Information</h5>
      </div>
      <div class="ibox-content">
        @if ($kta=="")
          <table class="main" width="100%" cellpadding="0" cellspacing="0">
            <tbody>
              <tr>
                <td class="alert alert-good">
                  Silahkan klik tombol dibawah untuk mengirimkan permintaan Nomor KTA!
                </td>
              </tr>
              <tr>
                <td class="content-wrap">
                  <table width="100%" cellpadding="0" cellspacing="0">
                    <tbody>                                    
                      <tr>
                        <td class="content-block" align="center">
                          <br>
                          <br>
                          <br>
                          <button type="submit" class="btn btn-primary" onclick="requestKta()">
                            <i class="fa fa-paper-plane"></i>
                            &nbsp;
                            Kirim Permintaan KTA
                          </button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
            </tbody>
          </table>
        @elseif ($kta=="requested")
          <table class="main" width="100%" cellpadding="0" cellspacing="0">
            <tbody>
              <tr>
                <td class="alert alert-good">
                  Permintaan KTA Telah Terkirim !
                </td>
              </tr>
              <tr>
                <td class="alert alert-good">
                  Member Kadin yang terhormat.<br>              
                  <br>
                  Permintaan KTA anda telah berhasil dikirimkan. Proses registrasi dan implementasi KTA akan memakan waktu beberapa saat. Kami ucapkan terima kasih atas pengertian dan kesabaran anda.<br>
                  <br>
                  Terima Kasih atas kepercayaan anda pada kami.                            
                </td>
              </tr>
            </tbody>
          </table>
        @elseif ($kta=="validated")
          <table class="main" width="100%" cellpadding="0" cellspacing="0">
            <tbody>
              <tr>
                <td class="alert alert-good">
                  Permintaan KTA Telah Terkirim !
                </td>
              </tr>
              <tr>
                <td class="alert alert-good">
                  Member Kadin yang terhormat.<br>              
                  <br>
                  Permintaan KTA anda telah berhasil dikirimkan. Proses registrasi dan implementasi KTA akan memakan waktu beberapa saat. Kami ucapkan terima kasih atas pengertian dan kesabaran anda.<br>
                  <br>
                  Terima Kasih atas kepercayaan anda pada kami.                            
                </td>
              </tr>
            </tbody>
          </table>
        @elseif ($kta=="cancelled")
          <table class="main" width="100%" cellpadding="0" cellspacing="0">
            <tbody>
              <tr>
                <td class="alert alert-good">
                  Permintaan KTA Ditolak !
                </td>
              </tr>
              <tr>
                <td class="alert alert-good">
                  Member Kadin yang terhormat.<br>
                  <br>
                  Permintaan KTA anda telah ditolak. Harap perhatikan syarat dan ketentuan anggota kadin. Untuk pengajuan ulang dan bantuan silahkan...<br>
                  <br>
                  Terima Kasih atas kepercayaan anda pada kami.                            
                </td>
              </tr>
            </tbody>
          </table>
        @else
          <table class="main" width="100%" cellpadding="0" cellspacing="0">
            <tbody>
              <tr>
                <td class="alert alert-good">
                  KTA
                </td>
              </tr>
              <tr>
                <td class="content-wrap">
                  <table width="100%" cellpadding="0" cellspacing="0">
                    <tbody>
                      <tr>
                        <td class="content-block">
                          KTA Anda telah Dibuat!.
                        </td>
                      </tr>
                      <tr>
                        <td class="content-block">
                          Permintaan KTA anda telah berhasil digenerate. Berikut adalah nomor KTA Anda
                          <br><br>
                          <div class="form-group">
                            <input type="text" id="thekta" class="form-control" value="{{ $kta }}" style="text-align:center; font-size: 24px; font-family: monospace;" readonly>
                          </div>
                          <br>
                          <div class="text-center">
                            <button type="button" class="btn btn-white" data-dismiss="modal">Copy</button>
                            <button type="button" class="btn btn-success" onclick="printpage()">Print KTA</button>
                          </div>                                  
                          <br><br>
                          Mohon diperhatikan, Nomor KTA bersifat abadi atau nomor yang anda miliki tidak akan digenerate lagi. Bila nomor KTA anda tidak tervalidasi, kemungkinan nomor KTA anda telah habis masa berlaku. Gunakan fitur perpanjangan KTA untuk memperpanjang masa berlaku KTA anda.                                            
                        </td>
                      </tr>
                      <tr>
                        <td class="content-block">
                          Terima Kasih atas kepercayaan anda pada kami.
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
            </tbody>
          </table>
        @endif
      </div>
    </div>
  </div>

<div class="hidden">
  <canvas id="myCanvas" style="border:1px solid #d3d3d3;">
  </canvas>
</div>
<div class="hidden">
  <img id="theimg" alt="image" class="img-responsive" src="{{ asset('resources/assets/images/kta.jpg') }}"/>  
</div>
@stop

@push('scripts')
<script type="text/javascript">
  function printpage() {    
    $.ajax({    
      url: "{{ url('member/ktaprint') }}",
      type: "post",
      data: {        
        _token: "{{ csrf_token() }}"
      }
    }).done(function(data) {      
      if (data.success) {
        var win = window.open("{{ url('member/printkta') }}", '_blank');
        if (win) {
            //Browser has allowed it to be opened
            win.focus();
        } else {
            //Browser has blocked it
            alert('Please allow popups for this website');
        }
      } else {
        toastr.error(data.msg);
      }              
    });
  }

  window.onload = function() {
    var canvas = document.getElementById("myCanvas");    
    var img = document.getElementById("theimg");
    
    canvas.height = img.height ;
    canvas.width = img.width ;    

    var ctx = canvas.getContext("2d");
    ctx.drawImage(img, 1, 1);

    console.log(canvas.height+" "+canvas.width);
    console.log(img.height+" "+img.width);
  }

  function requestKta() {    
    $.ajax({    
      url: "{{ url('member/requestkta/') }}",
      type: "post",
      data: {        
        _token: "{{ csrf_token() }}",        

      }
    }).done(function(data) {                    
      if (data.success) {
        toastr.success(data.msg);
      } else {
        toastr.error(data.msg);
      }

      console.log(data);
      // location.reload();
      // setTimeout(location.reload.bind(location), 1000);
         
    });
  }
</script>
@endpush