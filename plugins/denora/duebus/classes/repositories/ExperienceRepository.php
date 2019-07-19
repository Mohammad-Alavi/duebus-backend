<?php namespace Denora\Duebus\Classes\Repositories;

use Carbon\Carbon;
use Denora\Duebus\Models\Entrepreneur;
use Denora\Duebus\Models\Experience;

class ExperienceRepository {

    /**
     * @param int $experienceId
     *
     * @return Experience
     */
    public function findById(int $experienceId) {
        return Experience::find($experienceId);
    }

    /**
     * @param int    $userId
     * @param string $company
     * @param string $jobTitle
     *
     * @return Experience
     */
    public function createExperience(int $userId, string $company, string $jobTitle) {
        $userRepository = new UserRepository();
        /** @var Entrepreneur $entrepreneur */
        $entrepreneur = $userRepository->findById($userId)->entrepreneur;

        $experience = new Experience();
        $experience->company = $company;
        $experience->job_title = $jobTitle;
        $experience->from = Carbon::now();
        $experience->to = Carbon::now();
        $experience->entrepreneur_id = $entrepreneur->id;

        $experience->save();

        return $experience;
    }

    /**
     * @param int   $experienceId
     * @param array $data
     *
     * @return Experience
     */
    public function updateExperience(int $experienceId, array $data) {

        $experience = $this->findById($experienceId);

        if (array_has($data, 'company'))
            $experience->company = $data['company'];
        if (array_has($data, 'job_title'))
            $experience->job_title = $data['job_title'];

        $experience->save();

        return $experience;
    }

    /**
     * @param int $experienceId
     *
     * @throws \Exception
     */
    public function deleteExperience(int $experienceId) {
        $experience = $this->findById($experienceId);
        $experience->delete();
    }

}
