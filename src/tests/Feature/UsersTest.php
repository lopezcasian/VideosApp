<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;

class UsersTest extends TestCase
{
    use DatabaseTransactions;
    
    /**
     * Get user data and videos.
     *
     * @return void
     */
    public function testShowUserAndItsVideos()
    {
        $user = factory(User::class)->create();
        
        $response = $this->get('/users/' . $user->id );

        $response->assertStatus(200);
    }

    /**
     * Request non existent users 
     */
    public function testShowUserNonExistent()
    {
        $response = $this->get('/users/asdaf');
        $response->assertStatus(404);
    }
}
