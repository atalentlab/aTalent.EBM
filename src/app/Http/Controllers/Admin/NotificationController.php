<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreAcceptNewUserAdminNotificationRequest;
use App\Http\Requests\Admin\StoreRejectNewUserAdminNotificationRequest;
use App\Models\AdminNotification;
use App\Models\Organization;
use App\Notifications\UserAcceptedNotification;
use App\Notifications\UserRejectedNotification;
use App\Recipients\AdminRecipient;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REJECTED = 'rejected';

    public function index()
    {
        return view('admin.notification.index');
    }

    public function edit($id)
    {
        $notification = AdminNotification::findOrFail($id);

        return view('admin.notification.edit')->with([
            'entity' => $notification,
        ]);
    }

    public function acceptNewUser(StoreAcceptNewUserAdminNotificationRequest $request, $id)
    {
        $notification = AdminNotification::findOrFail($id);

        $user = $notification->getNewlyRegisteredUser();

        if (!$user) {
            return redirect()->route('admin.notification.edit', ['id' => $id])->with('error', 'No user is associated with this notification.');
        }

        $feedback = 'The user has been successfully accepted.';

        if ($request->input('organization')) {
            $organization = Organization::create([
                'name' => [
                    'en' => $request->input('organization'),
                    'zh' => $request->input('organization'),
                ]
            ]);

            $user->organization_id = $organization->id;

            $feedback .= ' A new organization <a href="' . route('admin.organization.edit', ['id' => $organization->id]) . '" target="_blank">' . $organization->name . '</a> has been created for ' . $user->name . '.';
        }
        else {
            $user->organization_id = $request->input('organization_id');
        }

        // cleanup additional data from when user registered
        if (isset($user->additional_data['organization'])) {
            $data = $user->additional_data;
            unset($data['organization']);
            $user->additional_data = $data;
        }

        $user->save();

        $user->assignRole('Basic User');
        $user->removeRole('Pending User');

        $notification->setStatus(self::STATUS_ACCEPTED);
        $notification->save();

        // notify user
        (new AdminRecipient($user->email))->notify(new UserAcceptedNotification($user));

        return redirect()->route('admin.notification.edit', ['id' => $id])->with('success', $feedback);
    }

    public function rejectNewUser(StoreRejectNewUserAdminNotificationRequest $request, $id)
    {
        $notification = AdminNotification::findOrFail($id);

        $user = $notification->getNewlyRegisteredUser();

        if (!$user) {
            return redirect()->route('admin.notification.edit', ['id' => $id])->with('error', 'No user is associated with this notification.');
        }

        $notification->setStatus(self::STATUS_REJECTED);
        $content = $notification->content;

        $content['rejection_reason'] = $request->input('rejection_reason');
        $content['rejection_message'] = $request->input('rejection_message');
        $notification->content = $content;
        $notification->save();

        // notify user
        (new AdminRecipient($user->email))->notify(new UserRejectedNotification($user, $notification));

        return redirect()->route('admin.notification.edit', ['id' => $id])->with('success', 'The user has been successfully rejected.');
    }

    public function delete($id)
    {
        $notification = AdminNotification::findOrFail($id);

        $name = $notification->title;

        $notification->delete();

        return redirect()->route('admin.notification.index')->with('success', 'Notification ' . $name . ' has been deleted.');
    }

    public function getDatatableData(Request $request)
    {
        $query = AdminNotification::select('id', 'created_at', 'title', 'type', 'status');

        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        return datatables()->of($query)
            ->rawColumns(['actions', 'status'])
            ->editColumn('created_at', function ($notification) {
                return optional($notification->created_at)->format('Y-m-d H:i:s');
            })
            ->editColumn('type', function ($notification) {
                return $notification->getType();
            })
            ->editColumn('status', function ($notification) {
                return $notification->getStatusColored();
            })
            ->addColumn('actions', function ($notification) {
                return '<a href="' . route('admin.notification.edit', ['id' => $notification->id]) . '" class="btn btn-outline-primary btn-sm" title="Edit notification"><i class="fe fe-edit"></i></a>';
            })
            ->make(true);
    }
}
