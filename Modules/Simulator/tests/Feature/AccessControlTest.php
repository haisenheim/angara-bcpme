<?php

namespace Modules\Simulator\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Modules\Simulator\Http\Middleware\AllowedRoles;
use Tests\TestCase;

/**
 * Tests the role-based access middleware. Does not require simulator tables.
 *
 * @group simulator
 */
class AccessControlTest extends TestCase
{
    use DatabaseTransactions;

    public function test_unauthenticated_user_is_redirected(): void
    {
        $middleware = new AllowedRoles;
        $response = $middleware->handle(Request::create('/simulator', 'GET'), fn () => response('ok'));

        $this->assertSame(302, $response->getStatusCode());
        $this->assertStringContainsString('/login', $response->headers->get('Location'));
    }

    public function test_unauthenticated_api_request_returns_401_json(): void
    {
        $middleware = new AllowedRoles;
        $request = Request::create('/api/simulator/simulate', 'POST');
        $request->headers->set('Accept', 'application/json');

        $response = $middleware->handle($request, fn () => response()->json(['ok' => true]));

        $this->assertSame(401, $response->getStatusCode());
    }

    public function test_user_with_disallowed_role_is_blocked(): void
    {
        config()->set('simulator.allowed_roles', [99]);

        $user = new User;
        $user->id = 1;
        $user->role_id = 1;
        $this->actingAs($user);

        $middleware = new AllowedRoles;
        $response = $middleware->handle(Request::create('/simulator', 'GET'), fn () => response('ok'));

        $this->assertSame(302, $response->getStatusCode());
    }

    public function test_user_with_allowed_role_passes(): void
    {
        config()->set('simulator.allowed_roles', [16, 24]);

        $user = new User;
        $user->id = 1;
        $user->role_id = 24;
        $this->actingAs($user);

        $middleware = new AllowedRoles;
        $response = $middleware->handle(Request::create('/simulator', 'GET'), fn () => response('ok'));

        $this->assertSame(200, $response->getStatusCode());
    }

    public function test_empty_allowed_roles_lets_anyone_through(): void
    {
        config()->set('simulator.allowed_roles', []);

        $user = new User;
        $user->id = 1;
        $user->role_id = 1;
        $this->actingAs($user);

        $middleware = new AllowedRoles;
        $response = $middleware->handle(Request::create('/simulator', 'GET'), fn () => response('ok'));

        $this->assertSame(200, $response->getStatusCode());
    }
}
