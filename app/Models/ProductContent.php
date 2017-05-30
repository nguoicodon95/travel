<?php
namespace App\Models;

use App\Models;
use App\Models\AbstractModel;

class ProductContent extends AbstractModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'product_contents';

    public $timestamps = true;

    /**
     * Primary key
     *
     * @var string
     */
    protected $primaryKey = 'id';

    protected $rules = [
        'title' => 'required|max:255',
        'slug' => 'required|max:255|unique_multiple:product_contents,slug',
        'description' => 'max:1000',
        'content' => 'string',
        'status' => 'integer|required|between:0,1',
        'thumbnail' => 'string|max:255',
        'tags' => 'string',
        'label' => 'string|max:100',
        'price' => 'required|numeric',
        'old_price' => 'numeric',
        'sale_status' => 'integer|required',
        'sale_from' => 'date',
        'sale_to' => 'date',
        'is_out_of_stock' => 'integer|required|between:0,1',
    ];

    protected $editableFields = [
        'title',
        'slug',
        'description',
        'content',
        'status',
        'thumbnail',
        'tags',
        'label',
        'price',
        'old_price',
        'sale_status',
        'sale_from',
        'sale_to',
        'is_out_of_stock',
    ];

    /**
     * Set the relationship
     *
     * @return mixed
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }

    /*
    * Title: Search fulltextsearch on product_content table
    * Author: Kin
    */

    public function scopeSearchByKeyword($query, $keyword)
    {
        if ($keyword!='') {
            $query->where(function ($query) use ($keyword) {
                $query->whereRaw('MATCH (title, tags, description) AGAINST (? IN BOOLEAN MODE)' , [$keyword]);
                $query->where('status', 1);
            });
        }
        return $query;
    }
}
