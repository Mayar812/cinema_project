<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RoleAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_built_in_account_credentials_are_defined_in_code_and_seeded_with_roles(): void
    {
        $accounts = collect(config('cinema_accounts.accounts'))->keyBy('role');

        $this->assertSame('admin', $accounts['admin']['username']);
        $this->assertSame('password', $accounts['admin']['password']);
        $this->assertSame('user', $accounts['user']['username']);
        $this->assertSame('password', $accounts['user']['password']);

        $this->seed();

        $admin = User::where('username', 'admin')->firstOrFail();
        $user = User::where('username', 'user')->firstOrFail();

        $this->assertSame('admin', $admin->role);
        $this->assertSame('user', $user->role);
        $this->assertTrue(Hash::check('password', $admin->password));
        $this->assertTrue(Hash::check('password', $user->password));
    }

    public function test_admin_login_opens_the_admin_dashboard(): void
    {
        $this->seed();

        $this->post(route('login.store'), [
            'username' => 'admin',
            'password' => 'password',
        ])
            ->assertRedirect(route('showtimes.index'))
            ->assertSessionHas('username', 'admin')
            ->assertSessionHas('role', 'admin');
    }

    public function test_user_login_opens_the_customer_homepage(): void
    {
        $this->seed();

        $this->post(route('login.store'), [
            'username' => 'user',
            'password' => 'password',
        ])
            ->assertRedirect(route('user.home'))
            ->assertSessionHas('username', 'user')
            ->assertSessionHas('role', 'user');
    }

    public function test_users_and_admins_cannot_open_each_others_protected_pages(): void
    {
        $this->withSession(['username' => 'user', 'role' => 'user'])
            ->get(route('showtimes.index'))
            ->assertRedirect(route('user.home'));

        $this->withSession(['username' => 'admin', 'role' => 'admin'])
            ->get(route('user.home'))
            ->assertRedirect(route('showtimes.index'));
    }
}
