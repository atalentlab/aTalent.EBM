<?php

namespace App\Repositories;

use App\Models\Period;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class PeriodRepository
{
    /**
     * @return Period
     */
    public function getCurrentPeriod(): ?Period
    {
        $now = Carbon::now();

        $currentPeriod = Period::whereDate('start_date', '<=', $now)
                                ->whereDate('end_date', '>=', $now)->first();


        if ($currentPeriod) {
            return $currentPeriod;
        }

        // create the current period if it was not found
        $startDate = $now->startOfWeek();

        $currentPeriod = Period::updateOrCreate([
            'name'          => 'Week ' . $startDate->weekOfYear,
            'start_date'    => $startDate,
            'end_date'      => $startDate->copy()->endOfWeek(),
        ], [
            'published'     => 0,
        ]);

        return $currentPeriod;
    }

    /**
     * @param string $periodId
     * @return Period|null
     */
    public function getPeriodById(string $periodId): ?Period
    {
        return Period::find($periodId);
    }

    /**
     * @return Period
     * @throws \Exception
     */
    public function getNextPeriod(): ?Period
    {
        $nextWeek = (new Carbon('next week'));

        return Period::whereDate('start_date', '<=', $nextWeek)
            ->whereDate('end_date', '>=', $nextWeek)->first();
    }


    /**
     * Get the previous period of a given period
     *
     * @param Period $period
     * @return Period|null
     */
    public function getPreviousPeriod(Period $period): ?Period
    {
        return Period::whereDate('end_date', '<=', $period->start_date)
                    ->orderBy('end_date', 'desc')->first();
    }

    /**
     * Get the previous period of a given period that an organization has data for
     *
     * @param Period $period
     * @param int $organizationId
     * @return Period|null
     */
    public function getPreviousPeriodForOrganization(Period $period, int $organizationId): ?Period
    {
        return Period::whereDate('end_date', '<=', $period->start_date)
            ->whereHas('srmIndices', function ($query) use ($organizationId) {
                $query->where('organization_id', $organizationId);
            })
            ->whereHas('channelIndices', function ($query) use ($organizationId) {
                $query->where('organization_id', $organizationId);
            })
            ->orderBy('end_date', 'desc')
            ->first();
    }

    /**
     * Get the most recent period for an organization that has indices
     *
     * @param int $organizationId
     * @return Period|null
     */
    public function getLatestPeriodForOrganization(int $organizationId): ?Period
    {
        return Period::whereDate('end_date', '<=', now())
            ->whereHas('srmIndices', function ($query) use ($organizationId) {
                $query->where('organization_id', $organizationId);
            })
            ->whereHas('channelIndices', function ($query) use ($organizationId) {
                $query->where('organization_id', $organizationId);
            })
            ->orderBy('end_date', 'desc')
            ->first();
    }

    /**
     * Get all present and past periods that have post and organization data
     *
     * @return Collection
     */
    public function getAllPresentPeriodsWithData(): Collection
    {
        $now = Carbon::now();

        return Period::whereDate('start_date', '<=', $now)
            ->whereHas('postData')
            ->whereHas('organizationData')
            ->orderBy('start_date')->get();
    }

    /**
     * Get all past periods in [id => name] format that have indices
     *
     * @return Collection
     */
    public function getPastPeriodListWithIndices(): Collection
    {
        $now = Carbon::now();

        return Period::whereDate('end_date', '<=', $now)
            ->whereHas('srmIndices')
            ->whereHas('channelIndices')
            ->orderBy('start_date', 'desc')
            ->orderBy('name')
            ->select('name', 'id', 'start_date')->get();
    }

    /**
     * Get all past and present periods in [id => name] format that have indices
     *
     * @return Collection
     */
    public function getAllPresentPeriodsWithIndicesList(): Collection
    {
        return $this->getAllPresentPeriodsWithIndices()->pluck('name_with_year', 'id');
    }

    /**
     * Get all past and present periods in [id => name] format that have indices
     *
     * @return Collection
     */
    public function getAllPresentPeriodsWithIndices(): Collection
    {
        $now = Carbon::now();

        return Period::whereDate('start_date', '<=', $now)
            ->whereHas('srmIndices')
            ->whereHas('channelIndices')
            ->orderBy('start_date', 'desc')
            ->select('name', 'id', 'start_date')
            ->get();
    }

    /**
     * Get the latest present period that has indices
     *
     * @return Period|null
     */
    public function getLatestPeriodWithIndices(): ?Period
    {
        $now = Carbon::now();

        return Period::whereDate('start_date', '<=', $now)
            ->whereHas('srmIndices')
            ->whereHas('channelIndices')
            ->orderBy('start_date', 'desc')
            ->first();
    }


    public function getAllPresentPeriodsWithIndicesForOrganization(int $organizationId): Collection
    {
        $now = Carbon::now();

        return Period::whereDate('start_date', '<=', $now)
            ->whereHas('srmIndices', function ($query) use ($organizationId) {
                $query->where('organization_id', $organizationId);
            })
            ->whereHas('channelIndices', function ($query) use ($organizationId) {
                $query->where('organization_id', $organizationId);
            })
            ->orderBy('start_date', 'desc')
            ->select('name', 'id', 'start_date')
            ->get();
    }

    /**
     * @return Collection
     */
    public function getPeriodsList(): Collection
    {
        return Period::orderBy('start_date', 'desc')->get()->pluck('name_with_year', 'id');
    }

    /**
     * @return Collection
     */
    public function getPresentPeriodsList(): Collection
    {
        $now = Carbon::now();

        return Period::whereDate('start_date', '<=', $now)
                    ->orderBy('start_date', 'desc')
                    ->get()
                    ->pluck('name_with_year', 'id');
    }
}
