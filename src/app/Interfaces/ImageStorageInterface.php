<?php

namespace App\Interfaces;

interface ImageStorageInterface {
    public function get( string $filename );
    public function save( $file ): string;
    public function destroy( $filename );
}