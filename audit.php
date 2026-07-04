<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$user = App\Models\User::where('email', 'ayanjafri150@gmail.com')->first();
if (!$user) {
    die("User not found\n");
}

$routesToTest = [
    '/',
    '/solar-clients',
    '/solar-clients/create',
    '/solar-sites',
    '/solar-sites/create',
    '/leads',
    '/leads/create',
    '/sofa-cleaning',
    '/sofa-cleaning/create',
    '/deep-cleaning',
    '/deep-cleaning/create',
    '/expenses',
    '/expenses/create',
    '/customer-history',
    '/reports',
    '/settings',
];

foreach ($routesToTest as $uri) {
    echo "Testing $uri ... ";
    $request = Illuminate\Http\Request::create($uri, 'GET');
    $request->setUserResolver(function () use ($user) {
        return $user;
    });
    
    // Simulate auth
    auth()->login($user);

    try {
        $response = $kernel->handle($request);
        if ($response->getStatusCode() === 200) {
            echo "OK\n";
        } else {
            echo "FAILED (Status: " . $response->getStatusCode() . ")\n";
        }
    } catch (\Throwable $e) {
        echo "ERROR: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine() . "\n";
    }
}
