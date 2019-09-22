<?php namespace Denora\Duebus\Classes\Repositories;

use Denora\Duebus\Models\Sector;

class SectorRepository {

    /**
     * @param int $sectorId
     *
     * @return Sector
     */
    public function findById(int $sectorId) {
        return Sector::find($sectorId);
    }


    /**
     * @return Sector[]|\Illuminate\Database\Eloquent\Collection
     */
    public function findAll() {
        return Sector::all();
    }

}