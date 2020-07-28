<?php

namespace App\Classes;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

use App\Interfaces\VideoStorageInterface;

class FileVideoStorage implements VideoStorageInterface {

    public function __construct( Filesystem $storage )
    {
        $this->storage = $storage;
    }

    /**
     * Get file from storage.
     * 
     * @param string $filename 
     * @return File
     */
    public function get( string $filename )
    {
        try {
            return $this->storage->get( $filename );
        } catch( FileNotFoundException $e ){
            \Log::info("App\Classes\FileVideoStorage::get(): $filename not found");
            abort(404);
        }
    }

    /**
     * Save file in Storage
     *
     * @param $file File
     * @return string file_path
     */
    public function save( $file ): string
    {
        $file_path = time() . $file->getClientOriginalName();
        $this->storage->put( $file_path, \File::get( $file ) );

        return $file_path;
    }

    public function destroy( $filename )
    {
        return $this->storage->delete( $filename );
    }
}