@extends('layouts.app', ['title' => trans('player.edit')])

@section('styles')
{!! Html::style('assets/admin/assets/plugins/select2/select2.min.css') !!}
@endsection

@section('content')
<div class="row">
    <div class="col-md-10 col-md-offset-1" style="margin-top: 20px">
        <ol class="breadcrumb">
          <li><a href="/">{{ trans('breadcrumb.home') }}</a></li>
          <li><a href="{{ route('teams.show', $team->id) }}">{{ $team->name }}</a></li>
          <li><a href="{{ route('roster.index', $team->id) }}">{{ trans('breadcrumb.roster') }}</a></li>
          <li><a href="{{ route('roster.show', [$team->id, $player->id]) }}">{{ trans('breadcrumb.roster_show') }}</a></li>
          <li class="active">{{ trans('breadcrumb.roster_edit') }}</li>
        </ol>
    </div>
</div>

<main class="container">
	<h1>{{ trans('player.edit') }}: {{ $player->first_name . ' ' . $player->last_name }}</h1>
	{!! Form::model($player, ['route' => ['roster.update', $team->id , $player->id], 'method' => 'PATCH']) !!}
		<div class="row">
			<h3>{{ trans('player.player_info') }}</h3>
			<div class="col-md-5">
				<div class="form-group">
					{!! Form::label('first_name', trans('player.first_name')) !!}
					{!! Form::text('first_name', null, ['class' => 'form-control', 'placeholder' => trans('player.first_name')]) !!}
				</div>
				<div class="form-group">
					{!! Form::label('grade', trans('player.grade')) !!}
					{!! Form::text('grade', null, ['class' => 'form-control', 'placeholder' => trans('player.grade')]) !!}
				</div>
				<div class="form-group">
					{!! Form::label('teacher', trans('player.teacher')) !!}
					{!! Form::text('teacher', null, ['class' => 'form-control', 'placeholder' => trans('player.teacher')]) !!}
				</div>
			</div>
			 <div class="col-md-5">
			 	<div class="form-group">
					{!! Form::label('last_name', trans('player.last_name')) !!}
					{!! Form::text('last_name', null, ['class' => 'form-control', 'placeholder' => trans('player.last_name')]) !!}
				</div>
				<div class="form-group">
					{!! Form::label('gender', trans('player.gender')) !!}<br>
					{!! Form::select('gender', ['M' => 'Male', 'F' => 'Female'], null, ['class' => 'select2']) !!}
				</div>
				<div class="form-group">
						{!! Form::label('phone', trans('player.phone')) !!}
						{!! Form::number('phone', null, ['class' => 'form-control', 'placeholder' => '(999)999-9999', 'data-mask' => '(999)999-9999']) !!}
				</div>
			 </div>
			 <div class="col-md-2">
			 	<img src="..." alt="..." class="img-responsive">
			 	file
			 </div>
		</div>

		<div class="row">
			<h3>{{ trans('player.other_info') }}</h3>
			<div class="col-md-10">
				<div class="form-group">
					{!! Form::label('address', trans('player.address')) !!}<br>
					{!! Form::text('address', null, ['class' => 'form-control', 'placeholder' => trans('player.address')]) !!}
				</div>
			</div>
			<div class="row">
				<div class="col-md-5">
					<div class="form-group">
						{!! Form::label('state', trans('player.state')) !!}<br>
						{!! Form::text('state', null, ['class' => 'form-control', 'placeholder' => trans('player.state')]) !!}
					</div>
				</div>
				<div class="col-md-5">
					<div class="form-group">
						{!! Form::label('city', trans('player.city')) !!}<br>
						{!! Form::text('city', null, ['class' => 'form-control', 'placeholder' => trans('player.city')]) !!}
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<h3>{{ trans('player.parent_info') }}</h3>
			<div class="col-md-5">
				<div class="form-group">
					{!! Form::label('parent_first_name', trans('player.parent_first_name')) !!}
					{!! Form::text('parent_first_name', null, ['class' => 'form-control', 'placeholder' => trans('player.parent_first_name')]) !!}
				</div>
				<div class="form-group">
					{!! Form::label('parent_email', trans('player.parent_email')) !!}
					{!! Form::email('parent_email', null, ['class' => 'form-control', 'placeholder' => trans('player.parent_email')]) !!}
				</div>
			</div>
			<div class="col-md-5">
				<div class="form-group">
					{!! Form::label('parent_last_name', trans('player.parent_last_name')) !!}
					{!! Form::text('parent_last_name', null, ['class' => 'form-control', 'placeholder' => trans('player.parent_last_name')]) !!}
				</div>
				<div class="form-group">
					{!! Form::label('parent_phone', trans('player.parent_phone')) !!}
					{!! Form::text('parent_phone', null, ['class' => 'form-control', 'placeholder' => trans('player.parent_phone')]) !!}
				</div>
			</div>
		</div>
		<div class="row">
			<h3>{{ trans('player.emergency_cases') }}</h3>
			<div class="col-md-5">
				{!! Form::label('emergency_name', trans('player.emergency_name')) !!}
				{!! Form::text('emergency_name', null, ['class' => 'form-control', 'placeholder' => trans('player.emergency_name')]) !!}
			</div>
			<div class="col-md-5">
				{!! Form::label('emergency_phone', trans('player.emergency_phone')) !!}
				{!! Form::text('emergency_phone', null, ['class' => 'form-control', 'placeholder' => trans('player.emergency_phone')]) !!}
			</div>
		</div>
		<hr>
		<div class="row" align="center">
		 	<button type="submit" class="btn btn-success">{{ trans('player.register_button') }}</button>
		 	<a href="{{ url()->previous() }}" class="btn btn-danger">{{ trans('player.cancel_button') }}</a>
		 </div>
	{!! Form::close() !!}
</main>
@endsection

@section('script')
{!! Html::script('assets/admin/assets/plugins/select2/select2.full.min.js') !!}
<script type="text/javascript">
$(document).ready(function(){
	$('.select2').select2();
});
</script>
@endsection
