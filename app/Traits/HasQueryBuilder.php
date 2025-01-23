<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

trait HasQueryBuilder
{
    protected function buildQuery(Request $request, Builder $query): Builder
    {
        $this->loadRelations($request, $query);
        $this->loadCounts($request, $query);
        $this->applyFilters($request, $query);
        $this->applySorting($request, $query);

        return $query;
    }

    protected function loadRelations(Request $request, Builder $query): void
    {
        $relations = $this->parseRelations($request->get('with'));
        if ($relations->isNotEmpty()) {
            $query->with($relations->all());
        }

    }

    protected function loadCounts(Request $request, Builder $query): void
    {
        $counts = $this->parseCounts($request->get('with_count'));
        if ($counts->isNotEmpty()) {
            $query->withCount($counts->all());
        }
    }

    protected function parseRelations(?string $relations): Collection
    {
        if (empty($relations)) {
            return collect();
        }

        $allowedRelations = $this->getAllowedRelations();

        return collect(explode(',', $relations))
            ->map(fn($relation) => trim($relation))
            ->filter(function ($relation) use ($allowedRelations) {
                $baseRelation = Str::before($relation, '.');
                return $allowedRelations->contains($relation) ||
                    $allowedRelations->contains($baseRelation);
            });
    }

    protected function parseCounts(?string $counts): Collection
    {
        if (empty($counts)) {
            return collect();
        }

        $allowedCounts = $this->getAllowedCounts();

        return collect(explode(',', $counts))
            ->map(fn($count) => trim($count))
            ->filter(fn($count) => $allowedCounts->contains($count));
    }

    protected function getAllowedRelations(): Collection
    {
        if (!property_exists($this, 'allowedRelationships')) {
            return collect();
        }

        return collect($this->allowedRelationships)
            ->flatMap(function ($value, $key) {
                // Handle both simple arrays and associative arrays
                $relation = is_numeric($key) ? $value : $key;
                return [$relation];
            });
    }

    protected function getAllowedCounts(): Collection
    {
        if (!property_exists($this, 'countableRelationships')) {
            return collect();
        }

        return collect($this->countableRelationships);
    }

    protected function applySorting(Request $request, Builder $query): void
    {
        if (!$request->has('sort_by')) {
            $this->applyDefaultSorting($query);
            return;
        }

        $sortBy = $request->get('sort_by');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
    }

    protected function applyDefaultSorting(Builder $query): void
    {
        if (property_exists($this, 'defaultSortField')) {
            $query->orderBy(
                $this->defaultSortField,
                $this->defaultSortOrder ?? 'desc'
            );
            return;
        }

        $query->latest();
    }

    protected function applyFilters(Request $request, Builder $query): void
    {
        if ($request->query->count() > 0) {
            $query->filter($request->all());
        }
    }
}
