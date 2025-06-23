<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Website;

class TicketController extends Controller
{
    public function index()
    {
        $data = Ticket::all();
        return view('admin.ticket.index', compact('data'));
    }

    public function create()
    {
        $data = Website::all();
        return view('admin.ticket.create', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $add = new Ticket;
        $add->name = $request->name;
        $add->description = $request->description;
        $add->status = $request->status;
        $add->hide_until = $request->hide_until;
        $add->hide_after = $request->hide_after;
        $add->price = $request->price;
        $add->quantity = $request->quantity;
        $add->website_id = $request->website_id;

        $website = Website::find($request->website_id);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/tickets'), $filename);
            $add->image = 'uploads/tickets/' . $filename;
        }
        $add->user_id = $website->user_id;
        $add->save();

        return redirect()->route('admin.ticket.index')->with('success', 'Ticket created successfully.');
    }

    public function edit($id)
    {
        $data = Ticket::findOrFail($id);
        return view('admin.ticket.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $add = Ticket::findOrFail($id);
        $add->name = $request->name;
        $add->description = $request->description;
        $add->status = $request->status;
        $add->hide_until = $request->hide_until;
        $add->hide_after = $request->hide_after;
        $add->price = $request->price;
        $add->quantity = $request->quantity;

        $website = Website::find($request->website_id);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/tickets'), $filename);
            $add->image = 'uploads/tickets/' . $filename;
        }
        $add->update();
        return redirect()->route('admin.ticket.index')->with('success', 'Ticket updated successfully.');
    }

    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();
        return redirect()->route('admin.ticket.index')->with('success', 'Ticket deleted successfully.');
    }
}
