@extends('common.app')

@section('active-dform')
  active
@stop
@section('active-dform-setting')
  active
@stop

@section('content')
    <div class="col-lg-10">
        <h2>Form Setting</h2>
        <ol class="breadcrumb">
            <li>
                <a>CRUD Forms</a>
            </li>
            <li>
                <a>Form Setting</a>
            </li>
            <li>
                <a>Edit</a>
            </li>
            <li class="active">
                <strong>{!! $fsetting->name !!}</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-2">
        <div class="title-action">
            <a href='/' class="btn btn-primary" onclick="goBack()">
                <span class="fa fa-arrow-left fa-fw"></span>
                &nbsp;&nbsp;Back
            </a>
        </div>
    </div>
@stop

@section('iframe')
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>Edit Item</h5>
          <div class="ibox-tools"><!-- any link icon --></div>
        </div>
        <div class="ibox-content">
          @include('errors.error_list')

        	{!! Form::model($fsetting, ['method' => 'PATCH', 'action' => ['Admin\FormSettingController@update', $fsetting->id], 'class' => 'form-horizontal']) !!}
        		@include('admin.dform.setting.form', ['submitButtonText' => 'Update Form Setting'])
        	{!! Form::close() !!}
        </div>
      </div>
    </div>
  </div>
</div>
@stop

@push('scripts')
<script>
  function goBack() {
    window.history.back();
  }
</script>
@endpush