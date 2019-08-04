<?php namespace Denora\Duebusprofile\Http;

use Backend\Classes\Controller;
use Denora\Duebusprofile\Classes\Repositories\ExperienceRepository;
use Denora\Duebusprofile\Classes\Transformers\ExperienceTransformer;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use RainLab\User\Facades\Auth;

/**
 * Experience Controller Back-end Controller
 */
class ExperienceController extends Controller {
    public $implement = [
        'Mohsin.Rest.Behaviors.RestController'
    ];

    public $restConfig = 'config_rest.yaml';

    public function store() {
        $user = Auth::user();
        if (!$user->entrepreneur) return Response::make(['You must create an entrepreneur profile first'], 400);

        $data = Request::all();

        $validator = Validator::make($data, [
            'company'   => 'required|min:3',
            'job_title' => 'required|min:3',
        ]);

        if ($validator->fails())
            return Response::make($validator->messages(), 400);

        $experienceRepository = new ExperienceRepository();
        $experience = $experienceRepository->createExperience($user->id, $data['company'], $data['job_title']);

        return ExperienceTransformer::transform($experience);
    }

    public function update($id) {
        $experienceRepository = new ExperienceRepository();
        $experience = $experienceRepository->findById($id);
        if (!$experience) return Response::make(['No element found'], 404);

        if ($experience->entrepreneur->user->id != Auth::user()->id) return Response::make(['You must be the owner of the object'], 400);

        $data = Request::all();

        $validator = Validator::make($data, [
            'company'   => 'min:3',
            'job_title' => 'min:3',
        ]);

        if ($validator->fails())
            return Response::make($validator->messages(), 400);

        $experience = $experienceRepository->updateExperience($id, $data);

        return ExperienceTransformer::transform($experience);
    }

    public function destroy($id) {
        $experienceRepository = new ExperienceRepository();
        $experience = $experienceRepository->findById($id);
        if (!$experience) return Response::make(['No element found'], 404);

        if ($experience->entrepreneur->user->id != Auth::user()->id) return Response::make(['You must be the owner of the object'], 400);

        $experienceRepository->deleteExperience($id);

    }


}
