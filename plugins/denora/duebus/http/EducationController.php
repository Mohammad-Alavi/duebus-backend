<?php namespace Denora\Duebus\Http;

use Backend\Classes\Controller;
use Denora\Duebus\Classes\Repositories\EducationRepository;
use Denora\Duebus\Classes\Repositories\UserRepository;
use Denora\Duebus\Classes\Transformers\EducationsTransformer;
use Denora\Duebus\Classes\Transformers\EducationTransformer;
use Denora\Duebus\Models\Entrepreneur;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use RainLab\User\Facades\Auth;

/**
 * Education Controller Back-end Controller
 */
class EducationController extends Controller {

    public $implement = [
        'Mohsin.Rest.Behaviors.RestController'
    ];

    public $restConfig = 'config_rest.yaml';

    public function store() {
        $user = Auth::user();
        if (!$user->entrepreneur) return Response::make(['You must create an entrepreneur profile first'], 400);

        $data = Request::all();

        $validator = Validator::make($data, [
            'school'         => 'required|min:3',
            'field_of_study' => 'required|min:3',
        ]);

        if ($validator->fails())
            return Response::make($validator->messages(), 400);

        $educationRepository = new EducationRepository();
        $education = $educationRepository->createEducation($user->id, $data['school'], $data['field_of_study']);
        return EducationTransformer::transform($education);
    }

    public function update($id) {
        $educationRepository = new EducationRepository();
        $education = $educationRepository->findById($id);
        if (!$education) return Response::make(['No element found'], 400);

        if ($education->entrepreneur->user->id != Auth::user()->id) return Response::make(['You must be the owner of the object'], 400);

        $data = Request::all();

        $validator = Validator::make($data, [
            'school'         => 'min:3',
            'field_of_study' => 'min:3',
        ]);

        if ($validator->fails())
            return Response::make($validator->messages(), 400);

        $education = $educationRepository->updateEducation($id, $data);

        return EducationTransformer::transform($education);
    }

    public function destroy($id) {
        $educationRepository = new EducationRepository();
        $education = $educationRepository->findById($id);
        if (!$education) return Response::make(['No element found'], 400);

        if ($education->entrepreneur->user->id != Auth::user()->id) return Response::make(['You must be the owner of the object'], 400);

        $educationRepository->deleteEducation($id);

    }

}
