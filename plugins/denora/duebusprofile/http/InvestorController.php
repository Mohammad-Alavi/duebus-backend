<?php namespace Denora\Duebusprofile\Http;

use Backend\Classes\Controller;
use Denora\Duebusprofile\Classes\Repositories\InvestorRepository;
use Denora\Duebusprofile\Classes\Transformers\InvestorTransformer;
use Denora\Duebusprofile\Classes\Transformers\ProfileTransformer;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use RainLab\User\Facades\Auth;

/**
 * Investor Controller Back-end Controller
 */
class InvestorController extends Controller {
    public $implement = [
        'Mohsin.Rest.Behaviors.RestController'
    ];

    public $restConfig = 'config_rest.yaml';

    public function store() {

        $user = Auth::user();

        if ($user->investor) return Response::make(['You already are an investor'], 400);

        $data = Request::all();

        $validator = Validator::make($data, [
            'range_of_investment'             => 'required',
            'range_of_businesses_invested_in' => 'required',
            'sectors'                         => 'required',
        ]);

        if ($validator->fails())
            return Response::make($validator->messages(), 400);

        $investorRepository = new InvestorRepository();
        $investor = $investorRepository->createInvestor(
            $user->id,
            $data['range_of_investment'],
            $data['range_of_businesses_invested_in'],
            $data['sectors']
        );

        return ProfileTransformer::transform($investor->user);
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function update($id) {
        $investorRepository = new InvestorRepository();
        $investor = $investorRepository->findById($id);
        if (!$investor) return Response::make(['No element found'], 404);

        if ($investor->user->id != Auth::user()->id) return Response::make(['You must be the owner of the object'], 400);

        $data = Request::all();

        $validator = Validator::make($data, [
            'range_of_investment'             => 'string',
            'range_of_businesses_invested_in' => 'string',
        ]);

        if ($validator->fails())
            return Response::make($validator->messages(), 400);

        $investor = $investorRepository->updateInvestor($id, $data);

        return ProfileTransformer::transform($investor->user);
    }

    /**
     * @param $id
     *
     * @return mixed
     * @throws \Exception
     */
    public function destroy($id) {
        $investorRepository = new InvestorRepository();
        $investor = $investorRepository->findById($id);
        if (!$investor) return Response::make(['No element found'], 404);

        if ($investor->user->id != Auth::user()->id) return Response::make(['You must be the owner of the object'], 400);

        $investorRepository->deleteInvestor($id);
    }


}
