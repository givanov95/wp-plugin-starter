<?php

namespace WpPluginStarter\Providers;

use WpPluginCore\Providers\RestEndpointServiceProvider;
use WpPluginStarter\Controllers\ExampleController;

class ApiServiceProvider extends RestEndpointServiceProvider
{
    protected function registerEndpoints(): void
    {
        $ns = config('app.rest_namespace');
        $controller = new ExampleController();

        $this->addRestEndpoint(
            namespace: $ns,
            route:     '/examples',
            callback:  [$controller, 'index'],
            method:    'GET',
            public:    false,
            capability: 'manage_options',
        );

        $this->addRestEndpoint(
            namespace: $ns,
            route:     '/examples',
            callback:  [$controller, 'store'],
            method:    'POST',
            public:    false,
            capability: 'manage_options',
        );

        $this->addRestEndpoint(
            namespace: $ns,
            route:     '/examples/(?P<id>\d+)',
            callback:  [$controller, 'destroy'],
            method:    'DELETE',
            public:    false,
            capability: 'manage_options',
        );
    }
}
