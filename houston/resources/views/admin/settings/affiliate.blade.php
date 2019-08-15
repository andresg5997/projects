@extends('admin.settings.layouts.settings', ['title' => 'Affiliate'])
@section('settings-content')
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="general">
                    <i style="font-size:16px;" class="fa fa-info-circle"></i> General
                </label>
            </div>
            <div class="panel-body">
                <div class="callout callout-info" role="callout">
                    <p>This will enable the affiliate system. Please refer to the FAQ or contact Clooud to understand how it works.</p>
                </div>

                <div class="checkbox checkbox-switch">
                    <label>
                        <input name="active" class="js-switch" {{ $attributes->active ? "checked" : "" }} type="checkbox">
                        Activate?
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="col-md-12" style="margin-bottom: 50px">
        <hr>
    </div>

    <div class="col-md-8 col-md-offset-2">
        <div class="callout callout-info" role="callout">
            <p>Please, create the list of countries that you would like to be in each country group.</p>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="country_group_1_list">
                    <i style="font-size:16px;" class="fa fa-list"></i> Country Group 1

                </label>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::textarea('country_group_1_list', $attributes->country_group_1_list, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="country_group_2_list">
                    <i style="font-size:16px;" class="fa fa-list"></i> Country Group 2

                </label>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::textarea('country_group_2_list', $attributes->country_group_2_list, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="country_group_3_list">
                    <i style="font-size:16px;" class="fa fa-list"></i> Country Group 3

                </label>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::textarea('country_group_3_list', $attributes->country_group_3_list, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="country_group_4_list">
                    <i style="font-size:16px;" class="fa fa-list"></i> Country Group 4

                </label>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::textarea('country_group_4_list', $attributes->country_group_4_list, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="col-md-12" style="margin-bottom: 50px">
        <hr>
    </div>

    <div class="col-md-8 col-md-offset-2">
        <div class="callout callout-info" role="callout">
            <p>Please, select the amount you would like to payout for 10,000 views for each country group.</p>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="amount_for_country_group_1">
                    <i style="font-size:16px;" class="fa fa-list"></i> Amount for Country Group 1

                </label>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::number('amount_for_country_group_1', $attributes->amount_for_country_group_1, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="amount_for_country_group_2">
                    <i style="font-size:16px;" class="fa fa-list"></i> Amount for Country Group 2

                </label>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::number('amount_for_country_group_2', $attributes->amount_for_country_group_2, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="amount_for_country_group_3">
                    <i style="font-size:16px;" class="fa fa-list"></i> Amount for Country Group 3

                </label>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::number('amount_for_country_group_3', $attributes->amount_for_country_group_3, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="amount_for_country_group_4">
                    <i style="font-size:16px;" class="fa fa-list"></i> Amount for Country Group 4

                </label>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::number('amount_for_country_group_4', $attributes->amount_for_country_group_4, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12" style="margin-bottom: 50px">
        <hr>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="audio_multiplier">
                    <i style="font-size:16px;" class="fa fa-list"></i> Audio Multiplier

                </label>
            </div>
            <div class="panel-body">
                <div class="callout callout-info" role="callout">
                    <p>Which percentage of the amounts listed above would you want to pay out for audio "listens"? Example: 0.5. This means you pay 40 dollars for country group 1 for 10k views, you would pay them 20 dollars per 10k "listens".</p>
                </div>

                <div class="form-group">
                    {!! Form::number('audio_multiplier', $attributes->audio_multiplier, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="image_multiplier">
                    <i style="font-size:16px;" class="fa fa-list"></i> Image Multiplier

                </label>
            </div>
            <div class="panel-body">
                <div class="callout callout-info" role="callout">
                    <p>Example: 0.1. This means if you pay 40 dollars for country group 1 for 10k views, you would pay them 4 dollars per 10k image views.</p>
                </div>

                <div class="form-group">
                    {!! Form::number('image_multiplier', $attributes->image_multiplier, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12" style="margin-bottom: 50px">
        <hr>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="image_duration_for_commission">
                    <i style="font-size:16px;" class="fa fa-list"></i> Image View Duration for Commission

                </label>
            </div>
            <div class="panel-body">
                <div class="callout callout-info" role="callout">
                    <p>How many seconds do you want a viewer to need to watching the image in order for the affiliate to receive the commission?</p>
                </div>

                <div class="form-group">
                    {!! Form::number('image_duration_for_commission', $attributes->image_duration_for_commission, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12" style="margin-bottom: 50px">
        <hr>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="referral_multiplier">
                    <i style="font-size:16px;" class="fa fa-list"></i> Referral Multiplier

                </label>
            </div>
            <div class="panel-body">
                <div class="callout callout-info" role="callout">
                    <p>How much percentage of the total commission do you want to pay your affiliate for their referral affiliates? For example, "0.2".</p>
                </div>

                <div class="form-group">
                    {!! Form::number('referral_multiplier', $attributes->referral_multiplier, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12" style="margin-bottom: 50px">
        <hr>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="payout_minimum">
                    <i style="font-size:16px;" class="fa fa-list"></i> Payout Minimum?

                </label>
            </div>
            <div class="panel-body">
                <div class="callout callout-info" role="callout">
                    <p>How much commission does the affiliate have to accumulate in order to request a payout? For example, "20".</p>
                </div>

                <div class="form-group">
                    {!! Form::number('payout_minimum', $attributes->payout_minimum, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>
@endsection
