<?php namespace Denora\Inbox\Http;

use Backend\Classes\Controller;
use Denora\Inbox\Classes\Repositories\MessageRepository;
use Denora\Inbox\Classes\Repositories\SessionRepository;
use Denora\Inbox\Classes\Transformers\MessagesTransformer;
use Denora\Inbox\Classes\Transformers\MessageTransformer;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use RainLab\User\Facades\Auth;

/**
 * Message Controller Back-end Controller
 */
class MessageController extends Controller
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
            'session_id' => 'required|integer',
            'text' => 'required|string',
        ]);

        if ($validator->fails())
            return Response::make($validator->messages(), 400);

        //  Check if the user owns the session
        $session = SessionRepository::findById($data['session_id']);

        if (!$session) return Response::make(['No session found!'], 404);

        if ($user->id != $session->sender_id && $user->id != $session->receiver_id) return Response::make(['You are not participating the session'], 400);

        $message = MessageRepository::createMessage($user->id, $data['session_id'], null, $data['text']);

        return MessageTransformer::transform($message);
    }

    public function index()
    {
        $user = Auth::user();

        $data = Request::all();

        $validator = Validator::make($data, [
            'page' => 'integer',
            'session_id' => 'required|integer',
        ]);

        if ($validator->fails())
            return Response::make($validator->messages(), 400);

        $page = Request::input('page', 1);
        $session_id = Request::input('session_id');

        $session = SessionRepository::findById($data['session_id']);
        if (!$session) return Response::make(['No session found!'], 404);

        //  Check if the user owns the session
        if ($user->id != $session->sender_id && $user->id != $session->receiver_id) return Response::make(['You are not participating the session'], 400);

        $messages = MessageRepository::paginate($page, $session_id);

        return new LengthAwarePaginator(
            MessagesTransformer::transform($messages),
            $messages->total(),
            $messages->perPage()
        );

    }

}
