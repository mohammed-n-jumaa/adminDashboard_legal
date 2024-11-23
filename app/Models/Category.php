<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * خصائص قابلة للتعبئة
     */
    protected $fillable = ['name', 'description'];

    /**
     * علاقة بين التصنيفات والاستشارات
     */
    public function consultations()
    {
        return $this->hasMany(Consultation::class, 'category_id');
    }

    /**
     * علاقة بين التصنيفات والمكتبة القانونية
     */
    public function legalLibrary()
    {
        return $this->hasMany(LegalLibrary::class, 'category_id');
    }

    /**
     * نطاق التصنيفات النشطة فقط
     */
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    /**
     * تحديد حالة التصنيف (نشط أو محذوف)
     */
    public function getStatusAttribute()
    {
        return $this->deleted_at ? 'Deleted' : 'Active';
    }
}
