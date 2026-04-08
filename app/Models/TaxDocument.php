<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaxDocument extends Model
{
    public $table = 'tax_documents';
    public $timestamps = true;

    protected $fillable = [
        'tax_type',
        'document_type',
        'file_path',
        'original_name',
        'is_active',
        'is_deleted',
    ];

    /**
     * Used in frontend blade to decide whether to show QR image inline or open PDF.
     */
    public function isImage(): bool
    {
        $ext = strtolower(pathinfo($this->file_path, PATHINFO_EXTENSION));
        return in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
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

    public function documentTypeLabel(): string
    {
        return $this->document_type === 'view_pdf' ? 'PDF पहा' : 'QR पेमेंट';
    }
}
