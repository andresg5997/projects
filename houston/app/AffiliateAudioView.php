<?php

namespace App;

class AffiliateAudioView extends Affiliate
{
    protected $fillable = ['media_id', 'owner_id', 'user_id', 'ads_multiplier',
        'media_type', 'country', 'country_group', 'state', 'city', 'ip',
        'adblock', 'percent_played', 'commission', 'embed', ];
}
