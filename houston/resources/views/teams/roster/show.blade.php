@extends('layouts.app', ['title' => trans('player.title_show') . $player->first_name . ' ' . $player->last_name])

@section('styles')
	{!! Html::style('assets/admin/assets/plugins/datatables/dataTables.bootstrap.css') !!}
    {!! Html::style('assets/admin/assets/plugins/select2/select2.min.css') !!}
    {!! Html::style('assets/admin/assets/plugins/sweet-alert/sweetalert.css') !!}
    <style>
    	.table-striped>tbody>tr:first-child{
    		background: #95ba68;
    	}
    </style>
@endsection

@section('content')
<div class="dotted-white-bg">
	<div class="container">
		<div class="row">
	        <div class="col-sm-12 d-flex justify-content-center" style="margin-top: 40px">
	            <ol class="breadcrumb">
	              <li><a href="/">{{ trans('breadcrumb.home') }}</a></li>
	              <li><a href="{{ route('teams.show', $team->id) }}">{{ $team->name }}</a></li>
	              <li>
	              	<a href="{{ route('roster.index', $team->id) }}" title="">{{ trans('breadcrumb.roster') }}</a>
	              </li>
	              <li class="active">
	              	{{ trans('breadcrumb.roster_show') }}
	              </li>
	            </ol>
	        </div>
	    </div>

		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				<div class="card card-info">
					<div class="card-header">
						<h6 class="card-title">{{ trans('player.player_details') }}</h6>
					</div>
					<div class="card-block">
						<div class="row">
							<div class="col-sm-2">
								@if($player->picture)
									@if(\App::environment('production'))
									<img src="{{ Storage::disk('s3')->temporaryUrl('public/pictures/' . $player->picture, Carbon::now()->addMinutes(5)) }}" alt="">
									@else
									<img src="{{ Storage::url('public/pictures/' . $player->picture) }}" alt="">
                                    @endif
								@else
									<small>{{ trans('player.no_picture') }}</small>
								@endif
							</div>
							<div class="col-sm-10">
								<table class="table table-padding-bottom table-bordered table-hover table-responsive table-striped">
									<tr>
										<td class="first-col-width"><b>{{ trans('player.name') }}</b></td>
										<td>{{ $player->first_name . ' ' . $player->last_name }}</td>
									</tr>
									<tr>
										<td class="first-col-width"><b>{{ trans('player.email') }}</b></td>
										<td>{{ $player->email }}</td>
									</tr>
									<tr>
										<td class="first-col-width"><b>{{ trans('player.phone') }}</b></td>
										<td>{{ $player->phone }}</td>
									</tr>
									<tr>
										<td class="first-col-width"><b>{{ trans('player.grade') }}</b></td>
										<td>{{ $player->grade }}</td>
									</tr>
									<tr>
										<td class="first-col-width"><b>{{ trans('player.teacher') }}</b></td>
										<td>{{ $player->teacher }}</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				<div class="card card-info">
					<div class="card-header">
						<h6 class="card-title">{{ trans('player.parent_info') }}</h6>
					</div>
					<div class="card-block">
						<table class="table table-responsive table-hover table-bordered table-striped">
							<tr>
								<td class="first-col-width"><b>{{ trans('player.parent_name') }}</b></td>
								<td>{{ $player->parent_first_name . ' ' . $player->parent_last_name }}</td>
							</tr>
							<tr>
								<td class="first-col-width"><b>{{ trans('player.parent_email') }}</b></td>
								<td>{{ $player->parent_email }}</td>
							</tr>
							<tr>
								<td class="first-col-width"><b>{{ trans('player.parent_phone') }}</b></td>
								<td>{{ $player->parent_phone }}</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				<div class="card card-info">
					<div class="card-header">
						<h6 class="card-title">{{ trans('player.other_info') }}</h6>
					</div>
					<div class="card-block">
						<table class="table table-responsive table-hover table-bordered table-striped">
							<tr>
								<td class="first-col-width"><b>{{ trans('player.address') }}</b></td>
								<td>{{ $player->address }}</td>
							</tr>
							<tr>
								<td class="first-col-width"><b>{{ trans('player.state') }}</b></td>
								<td>{{ $player->state }}</td>
							</tr>
							<tr>
								<td class="first-col-width"><b>{{ trans('player.city') }}</b></td>
								<td>{{ $player->city }}</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
			<div class="col-md-10 col-md-offset-1">
				<div class="card">
					<div class="card-header">
						<h6>{{ trans('player.emergency_cases') }}</h6>
					</div>
					<div class="card-block">
						<table class="table table-responsive table-hover table-bordered table-striped">
							<tr>
								<td class="first-col-width"><b>{{ trans('player.emergency_name') }}</b></td>
								<td>{{ $player->emergency_name }}</td>
							</tr>
							<tr>
								<td class="first-col-width"><b>{{ trans('player.emergency_phone') }}</b></td>
								<td>{{ $player->emergency_phone }}</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<main class="container">
</main>
@endsection
@section('scripts')
@endsection
