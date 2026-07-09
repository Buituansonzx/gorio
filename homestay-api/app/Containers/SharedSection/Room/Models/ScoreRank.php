<?php

namespace App\Containers\SharedSection\Room\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

final class ScoreRank extends ParentModel
{
    use HasUuids;
    protected $table = 'score_rank';

    protected $guarded = [];
}
