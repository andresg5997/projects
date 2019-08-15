@extends('layouts.app', ['title' => 'Temporary Title'])

@section('styles')
    {!! Html::style('assets/css/bootstrap-datetimepicker.css') !!}
@endsection

@section('content')
<main class="container">
	<div class="panel panel-primary">
		<div class="panel-heading clearfix">
			<h3 class="panel-title">{{ trans('event.create_event') }}</h3>
		</div>
		<div class="panel-body">
			{!! Form::open(['action' => 'EventController@store', 'method' => 'POST']) !!}
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							{!! Form::label('name', trans('event.event_name')) !!}
							{!! Form::text('name', null, ['class' => 'form-control']) !!}
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							{!! Form::label('team_1', trans('event.team_1')) !!}<br>
							<select name="team_1">
								@foreach($teams as $team)
									<option value="{{ $team->id }}">{{ $team->name }}</option>
								@endforeach
							</select>
						</div>
					</div>
				</div> {{-- End Row  --}}

				<div class="row">
					<div class="col-md-6">
						{!! Form::label('type_id', trans('event.event_type')) !!}<br>
						<select name="type_id">
								@foreach($types as $type)
									<option value="{{ $type->id }}">{{ $type->name }}</option>
								@endforeach
						</select>
					</div>
				</div> {{-- End Row  --}}

				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							{!! Form::label('location_id', trans('event.location')) !!}<br>
							<select name="location_id">
								@foreach($locations as $location)
									<option value="{{ $location->id }}">{{ $location->name }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							{!! Form::label('date', trans('event.date_time')) !!}<br>
			                <div class='input-group date' id='datetimepicker1'>
								{!! Form::text('date', null, ['class' => 'form-control', 'id' => 'datetimepicker']) !!}
			                    <span class="input-group-addon">
			                        <span class="glyphicon glyphicon-calendar"></span>
			                    </span>
			                </div>
			            </div>
					</div>
				</div> {{-- End Row  --}}
				<div class="row">
					<div class="col-md-6">
						{!! Form::label('team_2', trans('event.form_team_2')) !!}
						{!! Form::text('team_2', null, ['class' => 'form-control', 'placeholder' => 'Opponent team\'s name']) !!}
					</div>
					<div class="col-md-6">
						{!! Form::label('uniform', trans('event.uniform')) !!}
						{!! Form::select('uniform', ['0' => 'Home', '1' => 'Visitor'], 0, ['class' => 'form-control']) !!}
					</div>
				</div>

				<div class="row">
					<div class="col-md-10">
						{!! Form::label('notes', trans('event.notes')) !!}
						{!! Form::text('notes', null, ['class' => 'form-control', 'placeholder' => 'Write down your notes']) !!}
					</div>
				</div>
		</div>
		<div class="panel-footer">
			<div class="form-group" align="center">
				<button class="btn btn-success">{{ trans('event.create_event') }}</button> <a href="{{ url()->previous() }}" class="btn btn-danger">Cancel</a>	
			</div>
		</div>
		{!! Form::close() !!}
	</div>
</main>
@endsection

@section('scripts')
	<script type="text/javascript" src="{{ asset('assets/js/moment.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/transition.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/collapse.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/bootstrap-datetimepicker.min.js') }}"></script>
	<script type="text/javascript">
            $(function () {
                $('#datetimepicker').datetimepicker();
            });
    </script>
@endsection