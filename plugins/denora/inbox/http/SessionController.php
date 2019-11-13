<?php namespace Denora\Inbox\Http;

use Backend\Classes\Controller;
use Denora\Duebusbusiness\Classes\Repositories\BusinessRepository;
use Denora\Inbox\Classes\Repositories\MessageRepository;
use Denora\Inbox\Classes\Repositories\SessionRepository;
use Denora\Inbox\Classes\Transformers\SessionsTransformer;
use Denora\Inbox\Classes\Transformers\SessionTransformer;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use RainLab\User\Facades\Auth;

/**
 * Session Controller Back-end Controller
 */
class SessionController extends Controller
{
    public $implement = [
        'Mohsin.Rest.Behaviors.RestController'
    ];

    public $restConfig = 'config_rest.yaml';

    public function store()
    {
        $user = Auth::user();

        $data = Request::all();

        $validator = Validator::make($data, [
            'business_id' => 'required|integer',
            'preferred_date' => 'date',
            'preferred_time' => 'string',
            'message_title' => 'array',
            'message_text' => 'required|array',
            'type' => [
                'required',
                Rule::in(['inquiry', 'meeting request']),
            ],
        ]);

        if ($validator->fails())
            return Response::make($validator->messages(), 400);

        $businessId = $data['business_id'];
        $type = $data['type'];
        $preferredDate = Request::input('preferred_date', null);
        $preferredTime = Request::input('preferred_time', null);
        $messageTitles = Request::input('message_title', []);
        $messageTexts = Request::input('message_text', []);

        $business = (new BusinessRepository())->findById($businessId);
        if (!$business) return Response::make(['No element found'], 404);
        $receiverId = $business->entrepreneur->user->id;

        //  TODO: uncomment it for production
        if ($user->id == $receiverId) return Response::make(['You can not send a message to yourself'], 400);


        //  Return the session if exists and create a new one if not!
        $session = SessionRepository::find(
            $user->id,
            $receiverId,
            $businessId,
            $type
        ) ?:
            SessionRepository::createSession(
                $user->id,
                $receiverId,
                $businessId,
                $type,
                $preferredDate,
                $preferredTime
            );

        for ($i = 0; $i < count($messageTexts); $i++){
            MessageRepository::createMessage(
                $user->id,
                $session->id,
                empty($messageTitles[$i])?null:$messageTitles[$i],
                $messageTexts[$i]
            );
        }

        $session = SessionRepository::findById($session->id);
        return SessionTransformer::transform($session, $user);
    }

    public function index()
    {
        $user = Auth::user();

        $data = Request::all();

        $validator = Validator::make($data, [
            'page' => 'integer',
            'business_id' => 'integer',
            'type' => [
                Rule::in(['inquiry', 'meeting request']),
            ],
        ]);

        if ($validator->fails())
            return Response::make($validator->messages(), 400);

        $page = Request::input('page', 1);
        $type = Request::input('type', null);
        $business_id = Request::input('business_id', null);
        $sessions = SessionRepository::paginate($page, $user->id, $type, $business_id);

        return new LengthAwarePaginator(
            SessionsTransformer::transform($sessions, $user),
            $sessions->total(),
            $sessions->perPage()
        );

    }

    public function show($id)
    {
        $user = Auth::user();

        $session = SessionRepository::findById($id);

        if (!$session) return Response::make(['No session found!'], 404);

        if ($user->id != $session->sender_id && $user->id != $session->receiver_id)
            return Response::make(['You don\'t own the session'], 400);

        return SessionTransformer::transform($session, $user);
    }

}
