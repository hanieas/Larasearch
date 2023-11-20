<?php

namespace LaraSearch\Filters;

use Closure;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class SearchBooleanMatch
{
    /** @var array */
    protected array $request;
    /** @var string */
    protected string $column, $table;

    /**
     * @param array $request
     * @param string $column
     * @param string $table
     */
    public function __construct(array $request, string $column, string $table)
    {
        $this->request = $request;
        $this->column = $column;
        $this->table = $table;
    }

    /**
     * @param Builder $builder
     * @param Closure $next
     * @return Builder
     */
    public function handle(Builder $builder, Closure $next): Builder
    {
        return $next($builder)->when(Arr::has($this->request, $this->column),
            function ($q) {
                if (Arr::get($this->request, $this->column) == 1) {
                    $q->whereNotNull($this->table . '.' . $this->column);
                } else {
                    $q->whereNull($this->table . '.' . $this->column);
                }
            });
    }
}
