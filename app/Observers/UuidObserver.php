<?php

namespace App\Observers;

use Illuminate\Support\Str;

class UuidObserver
{

    /**
     * Add uuid string to model on creation.
     *
     * @param $model
     * @return void
     */
    public function creating( $model )
    {
        if( empty( $model->id ) ){
            $model->id = (string) Str::uuid();
        }
    }
}
