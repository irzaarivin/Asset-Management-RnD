<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

class Asset extends Model
{
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    use CrudTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    // protected $table = 'assets';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    // protected $primaryKey = 'id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    // public $timestamps = false;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = [];

    /**
     * The attributes that should be hidden for arrays
     *
     * @var array
     */
    // protected $hidden = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function borrowings() {
        return $this->hasMany(Borrowing::class);
    }

    public function maintenances() {
        return $this->hasMany(AssetMaintenance::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getCategoryNameAttribute() {
        return $this->category->name ?? 'Uncategorized';
    }

    public function getStatusDescriptionAttribute() {
        return match ($this->status) {
            'available' => 'Tersedia',
            'borrowed' => 'Sedang Dipinjam',
            'maintenance' => 'Dalam Perawatan',
            default => 'Tidak Diketahui',
        };
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    // public function setImageAttribute($value)
    // {
    //     $attribute_name = "thumbnail";
    //     $disk = 's3';
    //     $destination_path = "assets";

    //     if (empty($value)) {
    //         if (isset($this->{$attribute_name}) && !empty($this->{$attribute_name})) {
    //             \Storage::disk($disk)->delete($this->{$attribute_name});
    //         }
    //         $this->attributes[$attribute_name] = null;
    //     }

    //     if (Str::startsWith($value, 'data:image')) {
    //         preg_match('/data:image\/(.*?);base64,/', $value, $matches);
    //         $extension = $matches[1];

    //         $image = \Image::make($value)->stream($extension);

    //         $filename = md5($value . time()) . '.' . $extension;

    //         \Storage::disk($disk)->put($destination_path . '/' . $filename, $image);

    //         if (isset($this->{$attribute_name}) && !empty($this->{$attribute_name})) {
    //             \Storage::disk($disk)->delete($this->{$attribute_name});
    //         }

    //         $this->attributes[$attribute_name] = $destination_path . '/' . $filename;
    //     } elseif (!empty($value)) {
    //         $this->attributes[$attribute_name] = $this->{$attribute_name};
    //     }
    // }
}
