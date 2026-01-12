<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Uuid
{
    protected $isLockedUuid = true;
    public $uuidFieldName = 'uuid';
    public $orderedUuid = true;

    /**
     * Used to get the field name of the model for uuid
     */
    public function getUuidFieldName(): string
    {
        return $this->uuidFieldName;
    }

    /**
     * Is the uuid ordered for this model?
     */
    public function isOrdered(): bool
    {
        return $this->orderedUuid;
    }

    /**
     * Add behavior to creating and saving Eloquent events.
     */
    public static function bootUuid(): void
    {
        // Create a UUID to the model if it does not have one
        static::creating(function (Model $model) {
            if (! $model->getKey()) {
                $model->{$model->getUuidFieldName()} = $model->isOrdered() ? (string) Str::uuid() : (string) Str::orderedUuid();
            }
        });

        // Set original if someone try to change UUID on update/save existing model
        static::saving(function (Model $model) {
            $original_id = $model->getOriginal($model->getUuidFieldName());
            if (! is_null($original_id) && $model->isLockedUuid) {
                if ($original_id !== $model->{$model->getUuidFieldName()}) {
                    $model->{$model->getUuidFieldName()} = $original_id;
                }
            }
            // Make sure we have a uuid for records that didn't have them set before
            if (empty($original_id)) {
                $model->{$model->getUuidFieldName()} = $model->isOrdered() ? (string) Str::uuid() : (string) Str::orderedUuid();
            }
        });
    }
}