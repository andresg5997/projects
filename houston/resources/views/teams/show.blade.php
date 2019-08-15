@extends('layouts.app', ['title' => trans('team.view_team') . $team->name])

@section('styles')
{!! Html::style('assets/admin/assets/plugins/select2/select2.min.css') !!}
@endsection

@section('content')
<div class="row">
    <div class="col-md-10 col-md-offset-1" style="margin-top: 20px">
        <ol class="breadcrumb">
          <li><a href="/">{{ trans('breadcrumb.home') }}</a></li>
          <li class="active"><a href="{{ route('teams.show', $team->id) }}">{{ $team->name }}</a></li>
        </ol>
    </div>
</div>

<main class="container">
	<div class="row">
		<div class="col-md-8">
			<div class="panel panel-primary">
				<div class="panel-heading clearfix">
					<h3 class="panel-title pull-left">{{ $team->name }}</h3>
					<div class="pull-right">
						<a href="{{ route('teams.manage', $team->id) }}" class="btn btn-info">{{ trans('team.roster_manager') }}</a>
						<a href="{{ route('teams.edit', $team->id) }}" class="btn btn-info">{{ trans('team.edit') }}</a>
					</div>
				</div>
				<div class="panel-body">
					<h4>{{ trans('team.roster') }}</h4>
					<table class="table table-responsive table-bordered table-hover table-striped">
						<thead>
							<th width="130px"><i class="fa fa-id-badge"></i> {{ trans('team.player_number') }}</th>
							<th><i class="fa fa-user"></i> {{ trans('team.player_name') }}</th>
							<th><i class="fa fa-flag"></i> {{ trans('team.player_position') }}</th>
							<th><i class="fa fa-cog"></i> {{ trans('team.player_manage') }}</th>
						</thead>
						<tbody>
							@foreach($team->players as $player)
								<tr>
									<td align="center">{{ $player->pivot->number }}</td>
									<td>{{ $player->first_name . ' ' . $player->last_name }}</td>
									<td>{{ $player->pivot->position }}</td>
									<td align="center">
		                                <a href="{{ route('players.show', $player->id) }}" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
		                                <a href="{{ route('players.edit', $player->id) }}" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i></a>
		                            </td>
								</tr>
							@endforeach					
						</tbody>
					</table>
				</div>
			</div>			
		</div>
		<div class="col-md-4">
			<div class="panel panel-info">
				<div class="panel-heading clearfix">
					<h3 class="panel-title">{{ trans('team.team_stats') }}</h3>
				</div>
				<div class="panel-body">
					<table class="table-responsive table table-bordered">
						<tbody>
							<tr>
								<td>{{ trans('team.total_events') }}</td>
								<td>4</td>
							</tr>
							<tr>
								<td>{{ trans('team.total_players') }}</td>
								<td>{{ count($team->players) }}</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</main>
@endsection

@section('scripts')
@endsection