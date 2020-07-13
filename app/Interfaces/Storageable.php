<?php

namespace App\Interfaces;

interface Storageable {
    public function get( $filename );
    public function save( $file );
    public function destroy( $filename );
}