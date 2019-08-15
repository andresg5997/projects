@php
$country_group_1_list = config('country_group_1_list');
$country_group_2_list = config('country_group_2_list');
$country_group_3_list = config('country_group_3_list');
$country_group_4_list = config('country_group_4_list');

$countries_group_1 = explode(",", $country_group_1_list);
$countries_group_2 = explode(",", $country_group_2_list);
$countries_group_3 = explode(",", $country_group_3_list);
$countries_group_4 = explode(",", $country_group_4_list);

@endphp
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-md-3">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Tier 1</h3>
                </div>
                <div class="panel-body">
                    <div class="the-price">
                        <h1>${{ config('amount_for_country_group_1') / 10 }}</h1>
                        <small>per 1k downloads</small>
                    </div>
                    <table class="table">
                        @php
                            $counter = 0;
                        @endphp
                        @foreach($countries_group_1 as $country)
                            @php
                                // reset the variable
                                $class = '';

                                // on every third result, set the variable value
                                if(++$counter % 2 === 0) {
                                    $class = 'active';
                                }
                            @endphp
                            <tr class="{{ $class }}">
                                <td>{{ $country }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-md-3">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">Tier 2</h3>
                </div>
                <div class="panel-body">
                    <div class="the-price">
                        <h1>${{ config('amount_for_country_group_2') / 10 }}</h1>
                        <small>per 1k downloads</small>
                    </div>
                    <table class="table">
                        @php
                            $counter = 0;
                        @endphp
                        @foreach($countries_group_2 as $country)
                            @php
                                // reset the variable
                                $class = '';

                                // on every third result, set the variable value
                                if(++$counter % 2 === 0) {
                                    $class = 'active';
                                }
                            @endphp
                            <tr class="{{ $class }}">
                                <td>{{ $country }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-md-3">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">Tier 3</h3>
                </div>
                <div class="panel-body">
                    <div class="the-price">
                        <h1>${{ config('amount_for_country_group_3') / 10 }}</h1>
                        <small>per 1k downloads</small>
                    </div>
                    <table class="table">
                        @php
                            $counter = 0;
                        @endphp
                        @foreach($countries_group_3 as $country)
                            @php
                                // reset the variable
                                $class = '';

                                // on every third result, set the variable value
                                if(++$counter % 2 === 0) {
                                    $class = 'active';
                                }
                            @endphp
                            <tr class="{{ $class }}">
                                <td>{{ $country }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-md-3">
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">Tier 4</h3>
                </div>
                <div class="panel-body">
                    <div class="the-price">
                        <h1>${{ config('amount_for_country_group_4') / 10 }}</h1>
                        <small>per 1k downloads</small>
                    </div>
                    <table class="table">
                        @php
                            $counter = 0;
                        @endphp
                        @foreach($countries_group_4 as $country)
                            @php
                                // reset the variable
                                $class = '';

                                // on every third result, set the variable value
                                if(++$counter % 2 === 0) {
                                    $class = 'active';
                                }
                            @endphp
                            <tr class="{{ $class }}">
                                <td>{{ $country }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>