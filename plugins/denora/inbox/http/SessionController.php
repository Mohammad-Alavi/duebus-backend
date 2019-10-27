<?php namespace Denora\Inbox\Http;

use Backend\Classes\Controller;
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
            'receiver_id' => 'required|integer',
            'business_id' => 'required|integer',
            'preferred_date' => 'required|date',
            'message_title' => 'required|string',
            'message_text' => 'required|string',
            'type' => [
                'required',
                Rule::in(['inquiry', 'meeting request']),
            ],
        ]);

        if ($validator->fails())
            return Response::make($validator->messages(), 400);

        //  TODO: uncomment it for production
        if ($user->id == $data['receiver_id']) return Response::make(['You can not send a message to yourself'], 400);

        //  Return the session if exists and create a new one if not!
        $session = SessionRepository::find(
            $user->id,
            $data['receiver_id'],
            $data['business_id'],
            $data['type']
        ) ?:
            SessionRepository::createSession(
                $user->id,
                $data['receiver_id'],
                $data['business_id'],
                $data['type'],
                $data['preferred_date']
            );

        $message = MessageRepository::createMessage(
            $user->id,
            $session->id,
            $data['message_title'],
            $data['message_text']
        );

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

        if ($user->id != $session->sender_id && $user->id != $session->receiver_id)
            return Response::make(['You don\'t own the session'], 400);

        return SessionTransformer::transform($session, $user);
    }

}
