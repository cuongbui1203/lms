<?php

namespace App\Providers;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (app()->isLocal()) {
            app()->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Builder::macro('paginateAnother', function (int $pageSize, int $page = 1, array $columns = ['*']) {
            $total = $this->count();
            $data = $this->orderBy('id')->where('id', '>', ($page - 1) * $pageSize)->limit($pageSize)->get($columns);

            $res = [];
            $res['total'] = $total;
            $res['currentPage'] = $page;
            $res['pageSize'] = $pageSize;
            $res['data'] = $data;

            return $res;
        });

        Collection::macro('paginate', function (int $pageSize, int $page = 1, array $relations = []) {
            $total = $this->count();
            $data = $this->slice($page - 1, $pageSize);
            foreach ($data as $e) {
                $e->load($relations);
            }

            $res = [];
            $res['total'] = $total;
            $res['currentPage'] = $page;
            $res['pageSize'] = $pageSize;
            $res['data'] = $data;

            return $res;
        });
    }
}
