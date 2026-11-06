<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    protected $fillable = ['graduate_id', 'message', 'status'];
    public function project(): BelongsTo { return $this->belongsTo(Project::class); }
    public function graduate(): BelongsTo { return $this->belongsTo(User::class, 'graduate_id'); }
}
