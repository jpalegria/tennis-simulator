<?php

namespace App\Utils;

/**
 * A simple ID Validator
 */
trait IdValidatorUtil
{
    /**
     * Check if a string is a valid ID number.
     * @param string $id
     * @return string|null Return ID if It's valid or null if not.
     */
    public function validateId(string $id): ?string
    {
        if (!preg_match('/^[0-9]+$/', $id)) {
            return null;
        }

        return filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    }
}
