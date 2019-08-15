@php
	$notifications = Auth::user()->unreadNotifications;
@endphp
<li style="float:right;">
	<a href="#!" class="waves-effect dropdown-button" data-beloworigin="true" data-activates="notifications" data-constrainWidth="false">
		<i class="material-icons left">notifications</i>
			<span class="new badge blue darken-2">{{ count($notifications) }}</span>
	</a>
</li>

<ul id='notifications' class='dropdown-content'>
	@foreach($notifications as $notification)
		<li>
			<a href="{{ route('notifications.show', $notification->id) }}" class="left">
				@if(Auth::user()->avatar)
					<img src="/storage/usuarios/avatars/{{ Auth::user()->avatar }}" class="left circle" width="50px">
				@else
					<i class="material-icons blue-text left">notifications</i>
				@endif
				<span class="blue-text">{{ $notification->data['mensaje'] }}</span>
			</a>
		</li>
		<li class="divider"></li>
	@endforeach
	@if(count($notifications) > 0)
		<li><a class="blue-text" href="{{ route('notifications.readAll') }}">Marcar todas como leídas</a></li>
		{{-- <li><a href="{{ route('notifications.markAsReadAll', Auth::user()->id) }}">Marcar todas como leídas</a></li> --}}
	@endif
</ul>
