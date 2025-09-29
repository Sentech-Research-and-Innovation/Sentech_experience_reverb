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
        \Log::info('📞 Incoming sendOffer request', $request->all());

        $data = $request->validate([
            'from' => 'required|integer',
            'to' => 'required|integer',
            'offer' => 'required',
        ]);

        \Log::info('✅ Validated Offer Data', $data);

        broadcast(new CallOfferEvent($data))->toOthers();

        \Log::info("📤 CallOfferEvent broadcasted to user {$data['to']} from {$data['from']}");

        return response()->json(['status' => 'offer_sent']);
    }

    public function sendAnswer(Request $request)
    {
        \Log::info('📞 Incoming sendAnswer request', $request->all());

        $data = $request->validate([
            'from' => 'required|integer',
            'to' => 'required|integer',
            'answer' => 'required',
        ]);

        \Log::info('✅ Validated Answer Data', $data);

        broadcast(new CallAnswerEvent($data))->toOthers();

        \Log::info("📤 CallAnswerEvent broadcasted to user {$data['to']} from {$data['from']}");

        return response()->json(['status' => 'answer_sent']);
    }

    public function sendCandidate(Request $request)
    {
        \Log::info('📞 Incoming sendCandidate request', $request->all());

        $data = $request->validate([
            'from' => 'required|integer',
            'to' => 'required|integer',
            'candidate' => 'required'
        ]);

        \Log::info('✅ Validated Candidate Data', $data);

        broadcast(new CallCandidateEvent($data))->toOthers();

        \Log::info("📤 CallCandidateEvent broadcasted to user {$data['to']} from {$data['from']}");

        return response()->json(['status' => 'candidate_sent']);
    }
}
