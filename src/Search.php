<?php

namespace LaraSearch;

use LaraSearch\Filters\SearchExactMatch;
use LaraSearch\Filters\SearchBooleanMatch;
use LaraSearch\Filters\SearchLikeMatch;
use LaraSearch\Filters\SearchPeriodMatch;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Pipeline;

class Search
{
    /** @var array */
    protected array $request;

    /**
     * @var Builder
     */
    protected Builder $query;

    /**
     * @var Model|Searchable
     */
    protected Model|Searchable $model;

    /**
     * @param array $request
     * @param Builder $query
     */
    public function __construct(array $request, Builder $query)
    {
        $this->request = $request;
        $this->query = $query;
        $this->model = $this->query->getModel();
    }

    /**
     * @return Builder
     */
    public function __invoke(): Builder
    {
        $filters = [];
        $table = $this->query->getModel()->getTable();
        foreach ($this->request as $key => $value) {
            $filters [] = match ($key) {
                in_array($key, $this->model->getColumnsForExactMatch(), true) ? $key : null => new SearchExactMatch($this->request, $key, $table),
                in_array($key, $this->model->getColumnsForLikeMatch(), true) ? $key : null => new SearchLikeMatch($this->request, $key, $table),
                in_array($key, $this->model->getColumnsForBooleanMatch(), true) ? $key : null => new SearchBooleanMatch($this->request, $key, $table),
                in_array($key, $this->model->getColumnsForPeriodMatch(), true) ? $key : null => new SearchPeriodMatch($this->request, $key, $table),
                default => null,
            };
        }
        return Pipeline::send($this->query)->through($filters)->thenReturn();
    }
}