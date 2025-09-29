<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\CallOfferEvent;
use App\Events\CallAnswerEvent;
use App\Events\CallCandidateEvent;

class CallController extends Controller
{
    public function sendOffer(Request $request)
    {
        $data = $request->validate([
            'from' => 'required|integer',
            'to' => 'required|integer',
            'offer' => 'required',
        ]);

        broadcast(new CallOfferEvent($data))->toOthers();
        return response()->json(['status' => 'offer_sent']);
    }

    public function sendAnswer(Request $request)
    {
        $data = $request->validate([
            'from' => 'required|integer',
            'to' => 'required|integer',
            'answer' => 'required',
        ]);

        broadcast(new CallAnswerEvent($data))->toOthers();
        return response()->json(['status' => 'answer_sent']);
    }

    public function sendCandidate(Request $request)
    {
        $data = $request->validate([
            'from' => 'required|integer',
            'to' => 'required|integer',
            'candidate' => 'required'
        ]);

        broadcast(new CallCandidateEvent($data))->toOthers();
        return response()->json(['status' => 'candidate_sent']);
    }
}
