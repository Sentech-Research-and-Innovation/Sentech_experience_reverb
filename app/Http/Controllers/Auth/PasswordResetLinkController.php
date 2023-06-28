<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\System\ActionResponse;
use App\Models\System\DataValidation;
use App\Models\Web\PersonalDetail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */

    private $errorBag;
    public function __construct()
    {
        $this->errorBag =[];
    }

    public function create(): Response
    {
        return Inertia::render('Auth/ForgotPassword', [
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
//        dd('dskoko');
        $required = [
            'id_number' => $request->id_number,
        ];
        $validate = new DataValidation();
        $this->errorBag = $validate->required($required);

        if (count($this->errorBag)) {
            return ActionResponse::error('Please ensure all required fields have been filled.', $this->errorBag, false);
        }

        $personalDetails  = PersonalDetail::where('id_number',$request->id_number)->latest()->first();
        if(is_null($personalDetails)){
            $this->errorBag['id_number'] = 'Sorry it looks like you have entered a wrong ID Number, please check and try again, if the problem persists please contact customer support.';
            return ActionResponse::error('Sorry it looks like you have entered the wrong ID Number, please check and try again, if the problem persists please contact customer support.', $this->errorBag, false);
        }else{
//            if
        }

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }
}
