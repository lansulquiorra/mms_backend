@extends('common.app')

@section('active-dform')
  active
@stop
@section('active-dform-types')
  active
@stop

@section('content')
    <div class="col-lg-10">
        <h2>Form Type</h2>
        <ol class="breadcrumb">
            <li>
                <a>Admin</a>
            </li>
            <li>
                <a>CRUD Forms</a>
            </li>
            <li class="active">
                <strong>Form Type</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-2">
        <div class="title-action">
            <a href='types/create' class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;Tambah Data</a>
        </div>
    </div>
@stop

@section('iframe')

<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>List Form Type</h5>
          <div class="ibox-tools"><!-- any link icon --></div>
        </div>
        <div class="ibox-content">
          <table class="table table-striped table-bordered table-hover dataTables-example" id="type-table">
            <thead>
              <tr>
                <th>Name</th>
                <th>Description</th>
                <th>HTML TAG</th>
                <th>Options</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal inmodal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog">
      <div class="modal-content animated bounceInRight">
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>

              <i class="fa fa-warning modal-icon"></i>
              <h4 class="modal-title">Warning!</h4>
          </div>
          <div class="modal-body">
              <p>This record will be deleted permanently.</p>
              <p>Are you sure to delete record <span class="recordname"></span></p>
          </div>
          <div class="modal-footer">
            <input type="hidden" class="form-control" id="id">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button id="submit_delete" type="submit" class="btn btn-danger">Delete</button>
          </div>
      </div>
  </div>
</div>
@stop

@push('scripts')
<script>
$('#myModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  url = button.data('url');
  id = button.data('id');
  name = button.data('name');

  console.log(url + " " + id + " " + name);

  var modal = $(this);
  modal.find('.recordname').text('"' + name + '"?').css('font-weight', 'bold');
  modal.find('.modal-footer .form-control').val(id);

});

$('#submit_delete').on('click', function (event) {
  console.log("submit clicked");
  console.log(url+"/"+id);

  $.ajax({
    url: url+"/"+id,
    type: "post",
    data: {
      _method: 'DELETE',
      _token: "{{ csrf_token() }}",
    }
  }).done(function(data) {
    console.log(data);

    $('#myModal').modal('hide');

    if (data.success) {
      toastr.success(data.msg);
    } else {
      toastr.error(data.msg);
    }

    var ref = $('#type-table').DataTable();
    ref.ajax.reload(null, false);
  });
});

$(function() {
  $('#type-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: "{{ url('admin/ajax/types')}}",
    columns: [
      { "data" : "name" },
      { "data" : "description" },
      { "data" : "html_tag" },
      { "data" : "id"}
    ],
    "columnDefs": [
      {
        // The `data` parameter refers to the data for the cell (defined by the
        // `data` option, which defaults to the column being worked with, in
        // this case `data: 0`.
        "render": function ( data, type, row ) {
        return '<a href="types/'+row.id+'/edit" class="btn btn-white btn-xs">'+
                  '<span class="fa fa-edit fa-fw"></span>'+
                  '&nbsp;&nbsp;Edit'+
                '</a>'+
                '<a href="" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#myModal" data-id="'+row.id+'" data-name="'+row.name+'" data-url="types" title="Delete Item">'+
                  '<span class="fa fa-trash fa-fw"></span>'+
                '</a>';
        },
        "targets": 3
      },
      {
        "render": function ( data, type, row ) {
        return '<p>'+row.html_tag+'</p>';
        },
        "targets": 2
      }
    ]
  });
});

function goBack() {
    window.history.back();
}
</script>
@endpush
