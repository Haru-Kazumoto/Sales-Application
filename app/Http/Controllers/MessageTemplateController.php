<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMessageTemplateRequest;
use App\Models\MessageTemplate;
use App\Http\Services\WhatsappService;
use App\Models\Messages;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class MessageTemplateController extends Controller
{

    protected $whatsappService;

    public function __construct(WhatsappService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    public function store(CreateMessageTemplateRequest $request) {
        $data = MessageTemplate::create([
            "name" => $request->name,
            "message" => $request->message
        ]);

        return response()->json(["data" => $data],200);
    }

     public function replacePlaceholders($template, $placeholders)
    {
        foreach ($placeholders as $obj) {
            $template = str_replace("{" . $obj["key"] . "}", $obj["value"], $template);
        }
        return $template;
    }


    public function sendWhatsapp(Request $request) {
        $recipient = $request->input('to');       // Recipient phone number
        $tmpl   = $request->input('template_id'); // Message body
        $placeholders = $request->input("placeholders");

        $template = MessageTemplate::findOrFail($tmpl);

        $message = $this->replacePlaceholders($template->message,$placeholders);

        // Call the service to send the message
        $response = $this->whatsappService->sendMessage($recipient, $message);

        if ($response) {
            return response()->json(['status' => 'success', 'data' => $response]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to send message'], 500);
        }
    }

    public function create() 
    {
        $templates = MessageTemplate::all();

        return Inertia::render("AgingFinance/TestWhatsappMessage", compact('templates'));
    }

    public function saveMessage(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'recipient' => 'required|string',
            'content' => 'required|string',
            'template_id' => 'required|numeric',
            'placeholders' => 'array|required',
            'placeholders.*.key' => 'string|required',
            'placeholders.*.value' => 'string|required',
        ]);

        DB::transaction(function() use ($request) {
            $template = MessageTemplate::where('id', $request->input('template_id'))->first();

            $message = $this->replacePlaceholders($template->message,$request->input('placeholders'));

            // Call the service to send the message
            $response = $this->whatsappService->sendMessage($request->input('recipient'), $message);

            Messages::create([
                'recipient' => $request->input('recipient'),
                'content' => $message,
                'template_id' => $template->id,
                'sent_at' => Carbon::now(),
                'sent_by' => Auth::user()->id,
                'correlation_type' => $template->name,
                'correlation_id' => rand(100000,9000000),
            ]);
        });

        return redirect()->route('aging-finance.test-whatsapp-message')->with('success', 'Berhasil menyimpan pesan');
    }
   
}