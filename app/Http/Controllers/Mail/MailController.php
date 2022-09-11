<?php

namespace App\Http\Controllers\Mail;

use App\Http\Controllers\Controller;
use App\Repositories\Mail\MailRepository;
use Illuminate\Http\Request;


class MailController extends Controller
{

    private $mailRepository;

    public function __construct(MailRepository $mailRepository)
    {
        $this->mailRepository = $mailRepository;
    }


    public function send(Request $request)

    {
        $email = $request->email;
        $subject = $request->subject;
        $text = $request->text;

        return  $this->mailRepository->send($subject, $text, $email);

    }


}
