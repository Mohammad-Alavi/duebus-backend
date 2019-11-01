<?php namespace Denora\Notification\Http;

use Backend\Classes\Controller;
use Denora\Notification\Classes\Repositories\NotificationRepository;
use Denora\Notification\Classes\Transformers\NotificationsTransformer;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Request;
use RainLab\User\Facades\Auth;

/**
 * Notification Controller Back-end Controller
 */
class NotificationController extends Controller
{
    public $implement = [
        'Mohsin.Rest.Behaviors.RestController'
    ];

    public $restConfig = 'config_rest.yaml';

    public function index(){
        $user = Auth::user();

        $page = Request::input('page', 1);

        $notifications = NotificationRepository::paginate($page, $user->id);

        return new LengthAwarePaginator(
            NotificationsTransformer::transform($notifications),
            $notifications->total(),
            $notifications->perPage()
        );


    }

}
