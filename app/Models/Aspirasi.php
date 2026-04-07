<?php

namespace App\Models;

use App\Models\InputAspirasi;
use App\Models\Kategori;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['status', 'id_kategori', 'feedback'])]
class Aspirasi extends Model
{
    protected $table = 'aspirasi';

    protected $primaryKey = 'id_aspirasi';

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    public function inputAspirasi(): HasOne
    {
        return $this->hasOne(InputAspirasi::class, 'id_aspirasi', 'id_aspirasi');
    }
}
