<?php

namespace App\Providers;

use App\Http\Validators\ValidationTestFactory;
use App\Http\Validators\ValidatorTest;
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
        $this->app->singleton(ValidationTestFactory::class, ValidatorTest::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Builder::macro('paginateAnother', function (int $pageSize, int $page = 1, array $columns = ['*']) {
            $total = $this->count();
            $data = $this->orderBy('id')
                ->where(function ($query) use ($page, $pageSize, $total) {
                    if ($page === -1) {
                        return $query->where('id', '>', $total - $pageSize);
                    }

                    return $query->where('id', '>', ($page - 1) * $pageSize);
                })
                ->limit($pageSize)
                ->get($columns);

            $res = [];
            $res['total'] = $total;
            $res['currentPage'] = $page === -1 ? (int) ($total / $pageSize) + ($total % $pageSize === 0 ? 0 : 1) : $page;
            $res['pageSize'] = $pageSize;
            $res['data'] = $data;

            return $res;
        });

        Collection::macro('paginate', function (int $pageSize, int $page = 1, array $relations = []) {
            $total = $this->count();
            if ($page === -1) {
                $data = $this->slice($total - $pageSize, $pageSize)->values();
            } else {
                $data = $this->slice(($page - 1) * $pageSize, $pageSize)->values();
            }

            foreach ($data as $e) {
                $e->load($relations);
            }

            $res = [];
            $res['total'] = $total;
            $res['currentPage'] = $page === -1 ? (int) ($total / $pageSize) + ($total % $pageSize === 0 ? 0 : 1) : $page;
            $res['pageSize'] = $pageSize;
            $res['data'] = $data;

            return $res;
        });
    }
}
