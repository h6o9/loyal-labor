<?php

namespace Modules\Product\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitType extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'unit_types';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'status',
        'ShortName',
        'base_unit',
        'operator',
        'operator_value',
    ];

    /**
     * @return mixed
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'unit_type_id', 'id');
    }

    /**
     * @return mixed
     */
    public function children()
    {
        return $this->hasMany(UnitType::class, 'base_unit', 'id');
    }

    /**
     * @return mixed
     */
    public function parent()
    {
        return $this->hasOne(UnitType::class, 'id', 'base_unit');
    }

    public function convertToBaseUnit($value)
    {
        if ($this->base_unit && $this->operator && $this->operator_value) {
            return eval("return $value {$this->operator} {$this->operator_value};");
        }

        return $value;
    }

    public function convertFromBaseUnit($value)
    {
        if ($this->base_unit && $this->operator && $this->operator_value) {
            $reverseOperator = $this->operator === '*' ? '/' : '*';

            return eval("return $value {$reverseOperator} {$this->operator_value};");
        }

        return $value;
    }
}
