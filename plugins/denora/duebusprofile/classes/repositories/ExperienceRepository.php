<?php namespace Denora\Duebusprofile\Classes\Repositories;

use Denora\Duebusprofile\Models\Entrepreneur;
use Denora\Duebusprofile\Models\Experience;

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
     * @param        $from
     * @param        $to
     *
     * @return Experience
     */
    public function createExperience(int $userId, string $company, string $jobTitle, $from, $to) {
        $userRepository = new UserRepository();
        /** @var Entrepreneur $entrepreneur */
        $entrepreneur = $userRepository->findById($userId)->entrepreneur;

        $experience = new Experience();
        $experience->company = $company;
        $experience->job_title = $jobTitle;
        $experience->from = $from;
        $experience->to = $to;
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
        if (array_has($data, 'from'))
            $experience->from = $data['from'];
        if (array_has($data, 'to'))
            $experience->to = $data['to'];

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
