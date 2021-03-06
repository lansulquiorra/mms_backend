<!DOCTYPE html>
<html>
    <head>
        <title>MMS Backend</title>
                   
        <!-- Bootstrap Core CSS -->
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
        <!-- Simple Sidebar CSS -->
        <!-- <link href="{{ asset('css/simple-sidebar.css') }}" rel="stylesheet">        -->
        <!-- Datatables CSS -->
        {{--<link href="{{ asset('css/jquery.dataTables.min.css') }}" rel="stylesheet">--}}
        <link href="{{ asset('css/plugins/dataTables/dataTables.bootstrap.css') }}" rel="stylesheet">
        <link href="{{ asset('css/plugins/dataTables/dataTables.responsive.css') }}" rel="stylesheet">
        <link href="{{ asset('css/plugins/dataTables/dataTables.tableTools.min.css') }}" rel="stylesheet">
        <!-- Toastr -->
        <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/toastr/toastr.min.css') }}">  
        <!-- Font Awesome -->
        <link href="{{ asset('font-awesome/css/font-awesome.css') }}" rel="stylesheet">
        <!-- Gritter -->
        <link href="{{ asset('js/plugins/gritter/jquery.gritter.css') }}" rel="stylesheet">

        <link href="{{ asset('css/animate.css') }}" rel="stylesheet">
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">

        <link href="{{ asset('css/plugins/cropper/cropper.min.css') }}" rel="stylesheet">
        
        <!-- Kraaje Fileinputmin CSS -->
        <link href="{{ asset('css/fileinput.min.css') }}" rel="stylesheet">
        @yield('head')
    </head>
    <body>                        
        <div id="wrapper">
            <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="side-menu">
                    <li class="nav-header">
                        <div class="dropdown profile-element">
                            <a href="" data-toggle="modal" data-target="#profileimgModal">
                                <span>
                                    <img alt="image" class="img-circle" src="{{ url('/images') }}/{{ Auth::user()->username}}" height="48" width="48" />
                                </span>
                            </a>                            
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold">{{ Auth::user()->name }}</strong>
                             </span> <span class="text-muted text-xs block">{{ Auth::user()->myrole->name }}<b class="caret"></b></span> </span> </a>
                            <ul class="dropdown-menu animated fadeInRight m-t-xs">
                                <li><a href="{{ url('profile')}}">Profile</a></li>
                                <li><a href="{{ url('contacts')}}">Contacts</a></li>
                                <li><a href="{{ url('mailbox')}}">Mailbox</a></li>
                                <li class="divider"></li>
                                <li><a href="{{ url('/logout')}}">Logout</a></li>
                            </ul>
                        </div>
                        <div class="logo-element">
                            <img class="logo-name" src="{{ asset('img/icon144-128x128-10.png') }}" height="48" width="48"/>
                        </div>
                    </li>
                    @if (Auth::user()->role==1)
                        @include('admin.sidebar')
                    @elseif (Auth::user()->role==2)
                        @include('member.sidebar')
                    @elseif (Auth::user()->role==3)
                        @include('pusat.sidebar')
                    @elseif (Auth::user()->role==4)
                        @include('provinsi.sidebar')
                    @elseif (Auth::user()->role==5)
                        @include('daerah.sidebar')
                    @elseif (Auth::user()->role==6)
                        @include('alb.sidebar')
                    @endif
                </ul>

            </div>
            </nav>
            <div id="page-wrapper" class="gray-bg dashbard-1">
            <div id="div2" class="row border-bottom">
            <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0"> 
                <div class="navbar-header">
                    <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>                    
                    <form role="search" class="navbar-form-custom" action="">
                        <div class="form-group">
                            <input type="text" placeholder="Search for something..." class="form-control" name="top-search" id="top-search">
                        </div>
                    </form>
                </div>          
                <ul class="nav navbar-top-links navbar-right">           
                    <li>
                        <span class="m-r-sm text-muted welcome-message">Welcome to KADIN Member Panel.</span>
                    </li>                             
                    <li class="dropdown">
                        <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">                            
                            <i class="fa fa-bell"></i>  
                                @if (count($notifs) > 0)
                                    <span class="label label-primary">
                                        {{ count($notifs) }}
                                    </span>
                                @endif
                        </a>
                        <ul class="dropdown-menu dropdown-alerts">
                            @php
                                if (Auth::user()->role==1) {
                                    $nurl = url('admin/notif/');
                                } else if (Auth::user()->role==2) {
                                    $nurl = url('member/notif/');
                                } else if (Auth::user()->role==3) {
                                    $nurl = url('pusat/notif/');
                                } else if (Auth::user()->role==4) {
                                    $nurl = url('provinsi/notif/');
                                } else if (Auth::user()->role==5) {
                                    $nurl = url('daerah/notif/');
                                } else if (Auth::user()->role==6) {
                                    $nurl = url('alb/notif/');
                                }
                            @endphp
                            @foreach ($notifs->slice(0, 3) as $key=>$notif)
                                <li>
                                    <a href="{{ $nurl }}/{{ $notif->id }}">
                                        <div>
                                            <i class="fa fa-envelope fa-fw"></i> {{ $notif->value }}
                                            <span class="pull-right text-muted small">{{ $notif->crt_human }}</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="divider"></li>
                            @endforeach
                            <li>
                                <div class="text-center link-block">
                                    <a href="{{ $nurl }}/all">
                                        <strong>See All Alerts</strong>
                                        <i class="fa fa-angle-right"></i>
                                    </a>
                                </div>
                            </li>                         
                        </ul>
                    </li>   
                    <li>
                        <a href="{{ url('/logout')}}">
                            <i class="fa fa-sign-out"></i> Log out
                        </a>
                    </li>                 
                </ul>

            </nav>
            </div>
            
            <div id="div3" class="row  border-bottom white-bg dashboard-header">
                @yield('content')
            </div>       
            <br>

            @yield('iframe')

            <div id="div4" class="footer">
              <div>
                <strong>Copyright</strong> MMS Kadin Indonesia &copy; 2016
              </div>
            </div>
            </div>
        </div>                                       

        <!-- Modal -->
        <div class="modal fade" id="profileimgModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Change Your Profile Image</h4>
                    </div>
                    <div class="modal-body">
                        <div class="center-block" align="center">
                            <img id="theimage" src="{{ url('/images') }}/{{ Auth::user()->username}}" alt="your image" class="img-responsive center-block" />
                            &nbsp;
                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                    <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form id="imgUploadForm" action="{{ url('image-upload') }}" enctype="multipart/form-data" method="POST">
                                {{ csrf_field() }}
                                <div class="input-group-btn">
                                    <div class="btn btn-primary btn-file">
                                        <i class="glyphicon glyphicon-folder-open"></i>&nbsp;
                                        <span class="hidden-xs">Browse …</span>
                                        <input name="image" type="file" id="imgInp">
                                    </div>
                                    &nbsp;
                                    <button type="submit" class="btn btn-success">Upload</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>     

        <!-- JQuery -->
        <script src="{{ asset('js/jquery-3.1.0.min.js') }}"></script>
        <!-- Bootstrap JS -->
        <script src="{{ asset('js/bootstrap.js') }}"></script>                
        <!-- DataTables -->
        <script src="{{ asset('js/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('js/datatables/dataTables.bootstrap.js') }}"></script>
        <script src="{{ asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
        <!-- Toastr -->
        <script src="{{ asset('js/plugins/toastr/toastr.min.js') }}"></script>
        <!-- Menu & Pace -->        
        <script src="{{ asset('js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
        <script src="{{ asset('js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
        <!-- Custom and plugin javascript -->
        <script src="{{ asset('js/inspinia.js') }}"></script>
        <script src="{{ asset('js/plugins/pace/pace.min.js') }}"></script>
        <!-- Image cropper -->
        <script src="{{ asset('js/plugins/cropper/cropper.min.js') }}"></script>

        <script type="text/javascript">
            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('#theimage').attr('src', e.target.result);
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            }

            $("#imgInp").change(function(){
                readURL(this);
            });

            $('#profileimgModal').on('show.bs.modal', function (event) {  
              $('#theimage').attr('src', "{{ url('/images') }}/{{ Auth::user()->username}}");
            });

            $('#imgUploadForm').on('submit',(function(e) {
                e.preventDefault()                
                var formData = new FormData(this);

                $.ajax({
                    type:'POST',
                    url: $(this).attr('action'),
                    data:formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    success:function(data){                        
                        toastr.success(data.msg);

                        $('#profileimgModal').modal('hide'); 
                        location.reload();
                    },
                    error: function(data){
                        console.log(data);
                        if (data.msg) {
                            toastr.error(data.msg);
                        } else if (data.responseText) {
                            toastr.error(data.responseText);
                        }

                        $('#profileimgModal').modal('hide');                         
                    }
                });
            }));
        </script>
        @stack('scripts')
    </body>
</html>
