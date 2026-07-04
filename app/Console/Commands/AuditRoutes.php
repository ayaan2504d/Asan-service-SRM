<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class AuditRoutes extends Command
{
    protected $signature = 'audit:routes';
    protected $description = 'Audit all application GET routes for 500 errors';

    public function handle()
    {
        $user = User::where('email', 'ayanjafri150@gmail.com')->first();
        if (!$user) {
            $this->error('Admin user not found. Run db:seed first.');
            return;
        }

        auth()->login($user);

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
            $this->info("Testing $uri ...");
            try {
                $request = Request::create($uri, 'GET');
                $response = app()->handle($request);
                
                if ($response->getStatusCode() === 200) {
                    $this->line("<info>OK</info> - $uri");
                } else {
                    $this->error("FAILED (Status: {$response->getStatusCode()}) - $uri");
                }
            } catch (\Throwable $e) {
                $this->error("ERROR: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());
            }
        }
    }
}
