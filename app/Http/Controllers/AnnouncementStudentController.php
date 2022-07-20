<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\AnnouncementStudent;
use Mail;

class AnnouncementStudentController extends Controller
{
    public function sendAnnouncement()
    {
        $user = User::find('651')->email;

        $data = [
            'try' => 'okey',
            'try2' => 'okey2'
        ];

        dd($user);

        //$announcementData = [
           // 'body' => 'You Received Test Announcement',
           // 'announcementText' => 'You are allowed to enroll',
           // 'url' => url('/'),
           // 'thankYou' => 'You have 14 days to enroll'
       // ];

       // $user->notify(new AnnouncementStudent($announcementData));

       Mail::send('emails.welcome', $data, function($message) use ($user)
        {    
            $message->to($user)->subject('This is test e-mail');    
        });
        //var_dump( Mail:: failures());
        exit;
    }
}
