<?php

namespace Thotam\ThotamAuth\Models;

use Thotam\ThotamTeam\Models\Nhom;
use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class iCPC1HN_Group extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Userstamps;

    /**
     * Disable Laravel's mass assignment protection
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'icpc1hn_groups';

    /**
     * Get the nhom that owns the iCPC1HN_Group
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function nhom(): BelongsTo
    {
        return $this->belongsTo(Nhom::class, 'nhom_id');
    }
}
