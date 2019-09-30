<?php namespace Denora\Duebusprofile\Classes\Repositories;

use Denora\Duebusprofile\Models\Education;
use Denora\Duebusprofile\Models\Entrepreneur;

class EducationRepository {

    /**
     * @param int $educationId
     *
     * @return Education
     */
    public function findById(int $educationId) {
        return Education::find($educationId);
    }

    /**
     * @param int    $userId
     * @param string $school
     * @param string $fieldOfStudy
     *
     * @return Education
     */
    public function createEducation(int $userId, string $school, string $fieldOfStudy, $from, $to) {
        $userRepository = new UserRepository();
        /** @var Entrepreneur $entrepreneur */
        $entrepreneur = $userRepository->findById($userId)->entrepreneur;

        $education = new Education();
        $education->school = $school;
        $education->field_of_study = $fieldOfStudy;
        $education->from = $from;
        $education->to = $to;
        $education->entrepreneur_id = $entrepreneur->id;

        $education->save();

        return $education;
    }

    /**
     * @param int   $educationId
     * @param array $data
     *
     * @return Education
     */
    public function updateEducation(int $educationId, array $data) {

        $education = $this->findById($educationId);

        if (array_has($data, 'school'))
            $education->school = $data['school'];
        if (array_has($data, 'field_of_study'))
            $education->field_of_study = $data['field_of_study'];
        if (array_has($data, 'from'))
            $education->from = $data['from'];
        if (array_has($data, 'to'))
            $education->to = $data['to'];

        $education->save();

        return $education;
    }

    /**
     * @param int $educationId
     *
     * @throws \Exception
     */
    public function deleteEducation(int $educationId) {
        $education = $this->findById($educationId);
        $education->delete();
    }

}
