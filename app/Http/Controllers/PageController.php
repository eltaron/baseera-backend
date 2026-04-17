<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\Faq;
use App\Models\SupportCard;
use App\Models\SupportTicket;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $faqs = Faq::where('is_active', true)->orderBy('sort_order')->get();
        return view('home.index', compact('faqs'));
    }

    public function storeContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string',
        ]);
        ContactMessage::create($validated);
        return back()->with('success', 'تم استلام رسالتك بنجاح! شكراً لتواصلك.');
    }
    public function support()
    {
        $cards = SupportCard::where('is_active', true)->orderBy('sort_order')->get();
        return view('page.support', compact('cards'));
    }
    public function storeTicket(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'priority' => 'required',
            'message' => 'required',
        ]);

        if (auth()->check()) {
            $data['user_id'] = auth()->id();
        }

        $ticket = SupportTicket::create($data);

        return back()->with('success', "تم إنشاء التذكرة بنجاح! رقم تذكرتك هو: {$ticket->ticket_number}");
    }
    public function conditions()
    {
        return view('page.conditions');
    }
}
