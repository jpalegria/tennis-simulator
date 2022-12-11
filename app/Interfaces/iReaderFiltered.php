<?php

namespace App\Interfaces;

/**
 * Filter Data Interfaces
 */
interface iReaderFiltered
{

    /**
     * Get resources filtered with any conditions
     * @param array $filters
     * @return array|null
     */
    public function readWithFilters(array $filters): ?array;
}
