<?php namespace Denora\Duebus\Classes\Repositories;

use Denora\Duebus\Models\Package;

class PackageRepository {

    /**
     * @param int $packageId
     *
     * @return Package
     */
    public function findById(int $packageId) {
        return Package::find($packageId);
    }


    /**
     * @return Package[]|\Illuminate\Database\Eloquent\Collection
     */
    public function findAll() {
        return Package::all();
    }

}