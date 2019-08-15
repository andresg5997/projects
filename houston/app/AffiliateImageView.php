<?php

namespace App;

class AffiliateImageView extends Affiliate
{
    protected $fillable = ['media_id', 'owner_id', 'user_id',
        'country', 'country_group', 'state', 'city', 'ip', 'adblock',
        'commission', 'embed', ];
}
