<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMessageTemplateRequest;
use App\Models\MessageTemplate;
use App\Http\Services\WhatsappService;
use Illuminate\Http\Request;

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
   
}