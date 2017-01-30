@extends('provinsi.app')

@section('active-groupkta')
	active
@stop
@section('active-ktacncl')
	active
@stop

@section('content')	
<div class="col-lg-10">
  <h2>Canceled KTA Request</h2>
    <ol class="breadcrumb">
      <li>
        <a>Kadin Provinsi</a>
      </li>
      <li>
        <a>KTA</a>
      </li>
      <li class="active">
        <strong>Canceled KTA Request</strong>
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
                	<h5>List Cancelled Request KTA</h5>
					<div class="ibox-tools">
						<a class="collapse-link">
							<i class="fa fa-chevron-up"></i>
						</a>
					</div>
				</div>
				<div class="ibox-content">
					<table class="table table-bordered" id="list-table" width=100%>
						<thead>
							<tr>
								<th>Company</th>
								<th>Registered At</th>
								<th>Cancelled At</th>
							    <th>Options</th>
							</tr>
						</thead>
					</table>
                </div>
            </div>
        </div>    
	</div>
@stop

@push('scripts')
	<!-- ChartJS-->	
    <script src="{{ asset('resources/assets/js/plugins/chartJs/Chart.min.js') }}"></script>
    <script type="text/javascript">
		$(function() {
		  $('#list-table').DataTable({
		    processing: true,
		    serverSide: true,
		    iDisplayLength: 100,
		    ajax: "{{ url('/dashboard/provinsi/ajax/ktacancelled')}}",
		    columns: [
		      { "data" : "answer" },
		      { "data" : "created_at"},
		      { "data" : "updated_at"},
		      { "data" : "id_user"},
		    ],    
		    "columnDefs": [
		      {
		        "render": function ( data, type, row ) {
		        return '<a href="cancel/'+row.id_user+'" class="btn btn-warning btn-xs">'+
		        			'<span class="glyphicon glyphicon-search"></span>'+
		        			'&nbsp;&nbsp; Detail'+
		        		'</a>'+
		        		'&nbsp;&nbsp;';
		        },
		        "targets": 3
		      },
		    ]
		  });
		});
    </script>
@endpush