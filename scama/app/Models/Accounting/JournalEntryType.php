<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class JournalEntryType extends Model
{
    use HasFactory;

    protected $table = 'journal_entry_types';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function journalEntries(): HasMany
    {
        return $this->hasMany(JournalEntry::class, 'entry_type_id');
    }
}
