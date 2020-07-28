<?php

namespace App\Interfaces;

interface VideoStorageInterface {
    public function get( string $filename );
    public function save( $file ): string;
    public function destroy( $filename );
}