<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = ['title', 'description', 'skills', 'compensation', 'status'];
    protected function casts(): array { return ['skills' => 'array']; }
    public function startup(): BelongsTo { return $this->belongsTo(User::class, 'startup_id'); }
    public function applications(): HasMany { return $this->hasMany(Application::class); }
}
