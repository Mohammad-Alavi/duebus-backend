<?php namespace Denora\Duebusprofile\Http;

use Backend\Classes\Controller;
use Denora\Duebusprofile\Classes\Repositories\EntrepreneurRepository;
use Denora\Duebusprofile\Classes\Transformers\EntrepreneurTransformer;
use Denora\Duebusprofile\Classes\Transformers\ProfileTransformer;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use RainLab\User\Facades\Auth;

/**
 * Entrepreneur Controller Back-end Controller
 */
class EntrepreneurController extends Controller {
    public $implement = [
        'Mohsin.Rest.Behaviors.RestController'
    ];

    public $restConfig = 'config_rest.yaml';

    public function store() {
        $entrepreneurRepository = new EntrepreneurRepository();

        if (Auth::user()->entrepreneur) return Response::make(['You already are an entrepreneur'], 400);

        $data = Request::all();

        $validator = Validator::make($data, [
            'educations'  => 'json',
            'experiences' => 'json',
        ]);

        if ($validator->fails())
            return Response::make($validator->messages(), 400);

        //  Validate educations json
        $hasEducations = array_has($data, 'educations');
        if ($hasEducations) {
            $educations = $data['educations'];
            $educationsValidation = $this->validateEducationsJson($educations);
            if ($educationsValidation->fails()) return Response::make($educationsValidation->messages(), 400);
        }

        //  Validate experiences json
        $hasExperiences = array_has($data, 'experiences');
        if ($hasExperiences) {
            $experiences = $data['experiences'];
            $experiencesValidation = $this->validateExperiencesJson($experiences);
            if ($experiencesValidation->fails()) return Response::make($experiencesValidation->messages(), 400);
        }

        $entrepreneur = $entrepreneurRepository->createEntrepreneur(
            Auth::user()->id,
            $hasEducations ? $data['educations'] : null,
            $hasExperiences ? $data['experiences'] : null
        );

        return ProfileTransformer::transform($entrepreneur->user);
    }

    public function update($id) {
        $entrepreneurRepository = new EntrepreneurRepository();
        $entrepreneur = $entrepreneurRepository->findById($id);
        if (!$entrepreneur) return Response::make(['No element found'], 404);

        if ($entrepreneur->user->id != Auth::user()->id) return Response::make(['You must be the owner of the object'], 400);

        $data = Request::all();

        $validator = Validator::make($data, [
            'educations'  => 'json',
            'experiences' => 'json',
        ]);

        if ($validator->fails())
            return Response::make($validator->messages(), 400);

        //  Validate educations json
        $hasEducations = array_has($data, 'educations');
        if ($hasEducations) {
            $educations = $data['educations'];
            $educationsValidation = $this->validateEducationsJson($educations);
            if ($educationsValidation->fails()) return Response::make($educationsValidation->messages(), 400);
        }

        //  Validate experiences json
        $hasExperiences = array_has($data, 'experiences');
        if ($hasExperiences) {
            $experiences = $data['experiences'];
            $experiencesValidation = $this->validateExperiencesJson($experiences);
            if ($experiencesValidation->fails()) return Response::make($experiencesValidation->messages(), 400);
        }

        $entrepreneur = $entrepreneurRepository->updateEntrepreneur($id, $data);

        return EntrepreneurTransformer::transform($entrepreneur);
    }

    /**
     * @param $json
     *
     * @return mixed
     */
    private function validateEducationsJson($json) {
        $data = ['data' => json_decode($json, true)];

        return Validator::make($data, [
            'data.*.school'         => 'required|string|min:2',
            'data.*.field_of_study' => 'required|string|min:2',
            'data.*.from'           => 'required|date',
            'data.*.to'             => 'required|date',
        ]);
    }

    /**
     * @param $json
     *
     * @return mixed
     */
    private function validateExperiencesJson($json) {
        $data = ['data' => json_decode($json, true)];

        return Validator::make($data, [
            'data.*.company'   => 'required|string|min:2',
            'data.*.job_title' => 'required|string|min:2',
            'data.*.from'      => 'required|date',
            'data.*.to'        => 'required|date',
        ]);
    }

    /**
     * @param $id
     *
     * @return mixed
     * @throws \Exception
     */
    public function destroy($id) {
        $entrepreneurRepository = new EntrepreneurRepository();
        $entrepreneur = $entrepreneurRepository->findById($id);
        if (!$entrepreneur) return Response::make(['No element found'], 404);

        if ($entrepreneur->user->id != Auth::user()->id) return Response::make(['You must be the owner of the object'], 400);

        $entrepreneurRepository->deleteEntrepreneur($id);
    }

}
