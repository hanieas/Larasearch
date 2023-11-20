<?php

namespace LaraSearch\Filters;

use Closure;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class SearchPeriodMatch
{
    /** @var array */
    protected array $request;
    /** @var string */
    protected string $column, $firstOfRange, $endOfRange;

    /**
     * @param array $request
     * @param string $column
     */
    public function __construct(array $request, string $column)
    {
        $this->request = $request;
        $this->column = $column;
        $this->firstOfRange = $this->column . '_from';
        $this->endOfRange = $this->column . '_to';
    }

    /**
     * @param Builder $builder
     * @param Closure $next
     * @return Builder
     */
    public function handle(Builder $builder, Closure $next): Builder
    {
        if (Arr::get($this->request, $this->firstOfRange) !== null && Arr::get($this->request, $this->endOfRange) === null) {
            return $next($builder)->where($this->column, '>=', $this->request[$this->firstOfRange]);
        } elseif (Arr::get($this->request, $this->firstOfRange) === null && Arr::get($this->request, $this->endOfRange) !== null) {
            return $next($builder)->where($this->column, '<=', $this->request[$this->endOfRange]);
        } elseif (Arr::get($this->request, $this->firstOfRange) != null && Arr::get($this->request, $this->endOfRange) !== null) {
            return $next($builder)->where($this->column, '>=', Arr::get($this->request, $this->firstOfRange))->where($this->column, '<=', Arr::get($this->request, $this->endOfRange));
        }
        return $next($builder);
    }

}
