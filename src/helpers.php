<?php

declare(strict_types=1);

use Stancl\Tenancy\Tenant;
use Stancl\Tenancy\TenantManager;

if (! \function_exists('tenancy')) {
    /** @return TenantManager|mixed */
    function tenancy($key = null)
    {
        if ($key) {
            return app(TenantManager::class)->getTenant($key) ?? null;
        }

        return app(TenantManager::class);
    }
}

if (! \function_exists('tenant')) {
    /** @return Tenant|mixed */
    function tenant($key = null)
    {
        if (! is_null($key)) {
            return optional(app(Tenant::class))->get($key) ?? null;
        }

        return app(Tenant::class);
    }
}

if (! \function_exists('tenant_asset')) {
    /** @return string */
    function tenant_asset($asset)
    {
        return route('stancl.tenancy.asset', ['path' => $asset]);
    }
}

if (! \function_exists('global_asset')) {
    function global_asset($asset)
    {
        return app('globalUrl')->asset($asset);
    }
}

if (! \function_exists('global_cache')) {
    function global_cache()
    {
        return app('globalCache');
    }
}


if (! function_exists('routeForTenant')) {
    function routeForTenant($routeName, $params = [])
    {
        if (! tenant()) {
            // When there is no tenant, lets just return the base route.
            return route($routeName, $params);
        }

        return sprintf('%s://%s%s', parse_url(config('app.url'))['scheme'], tenant()->domains[0], route($routeName, $params, false));
    }
}
