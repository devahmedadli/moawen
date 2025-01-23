<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

trait Filterable
{
    /**
     * Default validation rules for common filter parameters
     * Override this in your model to customize
     */
    protected function getBaseFilterValidationRules(): array
    {
        return [
            'sortBy'       => 'sometimes|string|in:' . implode(',', $this->getAllowedSortFields()),
            'sortOrder'    => 'sometimes|string|in:asc,desc',
            'perPage'      => 'sometimes|integer|min:1|max:100',
            'page'         => 'sometimes|integer|min:1',
        ];
    }

    protected function filterValidationRules(): array
    {
        return array_merge(
            [
                'sortBy'    => 'sometimes|string|in:' . implode(',', $this->getAllowedSortFields()),
                'sortOrder' => 'sometimes|string|in:asc,desc',
                'perPage'   => 'sometimes|integer|min:1|max:100',
            ],
            $this->getCustomFilterValidationRules()
        );
    }

    protected function getCustomFilterValidationRules(): array
    {
        return [];
    }

    /**
     * Get allowed sort fields
     * Override this in your model to customize
     */
    protected function getAllowedSortFields(): array
    {
        return ['id', 'created_at', 'updated_at'];
    }

    /**
     * Apply filters to the query builder
     */
    public function scopeFilter(Builder $query, array $filters)
    {
        try {

            // Convert snake_case keys to camelCase for validation
            $camelCaseFilters = collect($filters)->mapWithKeys(function ($value, $key) {
                return [Str::camel($key) => $value];
            })->all();

            // Validate the filters
            $validator = Validator::make($camelCaseFilters, $this->filterValidationRules());

            if ($validator->fails()) {
                throw ValidationException::withMessages($validator->errors()->toArray());
            }

            // dd($filters);
            foreach ($filters as $field => $value) {
                if ($value === null || $value === '') {
                    continue;
                }

                // Sanitize the field name
                $field = strip_tags(trim($field));

                // Convert snake_case to camelCase for method name
                $camelField = Str::camel($field);
                $method = 'filter' . ucfirst($camelField);

                if (method_exists($this, $method)) {
                    $this->{$method}($query, $value);
                } elseif ($this->isValidField($field)) {
                    // Type check and sanitize the value
                    if (is_array($value)) {
                        throw new \InvalidArgumentException("Array values are not allowed for direct field filtering");
                    }
                    $query->where($field, '=', $value);  // Explicitly use '=' operator
                }
            }

            return $query;
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new \Exception('Error applying filters: ' . $e->getMessage());
        }
    }

    /**
     * Check if the field is valid for filtering
     * Override this in your model to customize
     */
    protected function isValidField(string $field): bool
    {
        // Stricter field validation
        return in_array($field, $this->getFillable(), true) &&
            preg_match('/^[a-zA-Z0-9_]+$/', $field);
    }
}
