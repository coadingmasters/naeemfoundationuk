<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunityEnquiry extends Model
{
    protected $table = 'community_enquiries';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
    ];
}
