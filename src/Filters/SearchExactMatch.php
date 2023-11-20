<?php

namespace LaraSearch\Filters;

use Closure;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class SearchExactMatch
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
    public function handle(Builder $builder, \Closure $next): Builder
    {
        return $next($builder)->when(Arr::has($this->request, $this->column),
            fn($q) => $q->where($this->table . '.' . $this->column, Arr::get($this->request, $this->column)));
    }
}
