@extends('form.app')

@section('sidebar')
  @include('form.setting.sidebar') 
@stop

@section('content')

<div class="col-lg-12">
  <div class="ibox float-e-margins">
    <h1>All Notifications</h1>
    <div class="ibox-content">
      <div>
        <div class="feed-activity-list">          
        @foreach ($notifs as $key=>$notif)
          <div class="feed-element">
            <a href="{{ url('/crud/form/notif') }}/{{ $notif->id }}" class="pull-left">
              <img alt="image" class="img-circle" src="img/a2.jpg">
            </a>
            <div class="media-body ">
              <small class="pull-right">2h ago</small>
              {{ $notif->value }}<br>
              <small class="text-muted">{{ $notif->created_at }}</small>
            </div>
          </div>                                                
        @endforeach
      </div>
      <!-- <button class="btn btn-primary btn-block m-t"><i class="fa fa-arrow-down"></i> Show More</button> -->
    </div>
  </div>
</div>
</div>
@stop

@push('scripts')
@endpush