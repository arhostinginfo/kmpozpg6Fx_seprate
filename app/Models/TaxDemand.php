<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaxDemand extends Model
{
    public $table = 'tax_demands';
    public $timestamps = true;

    protected $fillable = [
        'tax_type',
        'year_type',
        'demand_amount',
        'collected_amount',
        'percentage',
        'is_active',
        'is_deleted',
    ];

    /**
     * Auto-calculate percentage before saving.
     */
    public static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            if ($model->demand_amount > 0) {
                $model->percentage = round(($model->collected_amount / $model->demand_amount) * 100, 2);
            } else {
                $model->percentage = 0;
            }
        });
    }

    public function taxTypeLabel(): string
    {
        return match ($this->tax_type) {
            'ghar_patti'  => 'घरपट्टी कर',
            'paani_patti' => 'पाणीपट्टी कर',
            'other'       => 'गाळाभाडे / व्यवसायकर / इतर',
            default       => $this->tax_type,
        };
    }

    public function yearTypeLabel(): string
    {
        return $this->year_type === 'magil' ? 'मागील वर्ष' : 'चालू वर्ष';
    }
}
