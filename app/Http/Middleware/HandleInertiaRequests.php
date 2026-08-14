<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;


class HandleInertiaRequests extends Middleware
{
    // ...

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $sharedProps = parent::share($request);

        // Check if the request comes from the mobile app based on the X-Mobile-App header
        if ($request->header('X-Mobile-App') === 'true') {
            // Use JSON responses for mobile app
            return array_merge($sharedProps, [
                'authenticated' => fn () => $request->user() ? true : false,
                'user' => fn () => $request->user() ? $request->user()->only('id', 'name', 'email') : null,
            ]);
        }

        // For web, return regular response
        return array_merge($sharedProps, [
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
        ]);
    }
}
