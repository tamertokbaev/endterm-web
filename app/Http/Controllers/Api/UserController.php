<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isNull;

class UserController extends Controller
{

    public function subscribe(Request $request)
    {
        $user = $request->user();
        $follower_id = $request->follower_id;


        return response()->json([
           'message' => 'success',

        ]);
    }

    public function getUserSubscriptions(Request $request)
    {
        $user = $request->user();

        return response()->json([
           'message' => 'success',
           'subscriptions' => $user->followingUsers()->get()
        ]);
    }

    public function getUserFollowers(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'message' => 'success',
            'followers' => $user->followers()->get()
        ]);
    }

    public function getMailsForFamilySubscriptions(Request $request)
    {
        $userId = $request->userId;
        $user = User::find($userId);


        if ($user->subscription == 3) {
            $mails = DB::table('user_family')
                ->where('user_id', $user->id)
                ->get();

            $mailsId = [];
            foreach ($mails as $mail) {
                $mailsId[] = $mail->email;
            }

            $alreadyAttachedUsers =
                DB::table('users')
                    ->whereIn('email', $mailsId)
                    ->get();

            return response()->json([
                'message' => 'success',
                'users' => $alreadyAttachedUsers
            ]);
        }

        return response()->json([
            'message' => 'success',
            'users' => []
        ]);
    }

    public function addNewUserIntoFamilySubscription(Request $request)
    {
        $user = $request->user();
        $email = $request->email;

        $userToAdd = User::where('email', $email)->first();

        if ($userToAdd) {
            DB::table('user_family')->insert([
                ['email' => $email, 'user_id' => $user->id]
            ]);
            DB::table('users')
                ->where('id', $userToAdd->id)
                ->update(['subscription' => 3]);
            return response()->json([
                'message' => 'success'
            ]);
        } else {
            return response()->json([
                'message' => 'not_found'
            ]);
        }
    }
}
