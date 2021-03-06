<?php

namespace App;

use Jenssegers\Mongodb\Model;

/**
 * Showcase Application Model.
 * 
 * @package Election API Website
 * @author Nyan Lynn Htut <naynlynnhtut@hexcores.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class Showcase extends Model
{
	/**
     * The database collection used by the model.
     *
     * @var string
     */
    protected $collection = 'showcase';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'description', 'url', 'type', 'icon', 'screenshots', 'published'];

    public function setSlugAttribute($value)
    {
        $ins = new static;

        if ( $ins->where('slug', $value)->count() > 0) {
            $value = $value . '-' . time();
        }

        $this->attributes['slug'] = $value;
    }

    /**
     * Check showcase is ready to publish.
     *
     * @return boolean
     */
    public function readyToPublish()
    {
        if ( count($this->screenshots) > 0 && ! is_null($this->icon)) {
            return true;
        }

        return false;
    }
}