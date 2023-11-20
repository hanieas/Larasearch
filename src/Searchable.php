<?php

namespace LaraSearch;

interface Searchable
{
    /**
     * @return array
     */
    public function getColumnsForExactMatch(): array;

    /**
     * @return array
     */
    public function getColumnsForLikeMatch(): array;

    /**
     * @return array
     */
    public function getColumnsForBooleanMatch(): array;

    /**
     * @return array
     */
    public function getColumnsForPeriodMatch(): array;
}