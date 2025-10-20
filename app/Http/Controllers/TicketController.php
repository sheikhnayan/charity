<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketImage;
use App\Models\TicketFeature;
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
        // dd($request->all());
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
        $add->type = $request->type;
        $add->size = $request->size;
        $add->website_id = $request->website_id;

        $website = Website::find($request->website_id);

        if ($request->hasFile('image')) {
            $images = $request->file('image');

            foreach ($images as $key => $value) {
                # code...
                if($key == 1){
                    $file = $value;
                    $filename = time() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('uploads/tickets'), $filename);
                    $add->image = 'uploads/tickets/' . $filename;

                    $add->user_id = $website->user_id;
                    $add->save();

                    $new = new TicketImage;
                    $new->ticket_id = $add->id;
                    $new->image_path = 'uploads/tickets/' . $filename;
                    $new->save();
                }else{
                    $file = $value;
                    $filename = time() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('uploads/tickets'), $filename);
                    // $add->image = 'uploads/tickets/' . $filename;

                    $new = new TicketImage;
                    $new->ticket_id = $add->id;
                    $new->image_path = 'uploads/tickets/' . $filename;
                    $new->save();
                }

            }

        }

        if($request->features){
            foreach($request->features as $feature){
                $newFeature = new TicketFeature;
                $newFeature->ticket_id = $add->id;
                $newFeature->name = $feature['name'];
                $newFeature->value = $feature['value'];
                $newFeature->save();
            }
        }

        

        return redirect()->route('admin.ticket.index')->with('success', 'Ticket created successfully.');
    }

    public function edit($id)
    {
        $data = Ticket::findOrFail($id);
        return view('admin.ticket.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $add = Ticket::findOrFail($id);
        $add->name = $request->name;
        $add->description = $request->description;
        $add->status = $request->status;
        $add->hide_until = $request->hide_until;
        $add->hide_after = $request->hide_after;
        $add->price = $request->price;
        $add->type = $request->type;
        $add->size = $request->size;
        $add->quantity = $request->quantity;

        $website = Website::find($request->website_id);

        if ($request->hasFile('image')) {

            $delimage = TicketImage::where('ticket_id',$add->id)->delete();

            $images = $request->file('image');

            foreach ($images as $key => $value) {
                # code...
                if($key == 1){
                    $file = $value;
                    $filename = time() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('uploads/tickets'), $filename);
                    $add->image = 'uploads/tickets/' . $filename;
                    $add->update();

                    $new = new TicketImage;
                    $new->ticket_id = $add->id;
                    $new->image_path = 'uploads/tickets/' . $filename;
                    $new->save();
                }else{
                    $file = $value;
                    $filename = time() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('uploads/tickets'), $filename);
                    // $add->image = 'uploads/tickets/' . $filename;

                    $new = new TicketImage;
                    $new->ticket_id = $add->id;
                    $new->image_path = 'uploads/tickets/' . $filename;
                    $new->save();
                }

            }

        }

        if($request->features){

            $delfeature = TicketFeature::where('ticket_id',$add->id)->delete();

            foreach($request->features as $feature){
                $newFeature = new TicketFeature;
                $newFeature->ticket_id = $add->id;
                $newFeature->name = $feature['name'];
                $newFeature->value = $feature['value'];
                $newFeature->save();
            }
        }

        return redirect()->route('admin.ticket.index')->with('success', 'Ticket updated successfully.');
    }

    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();
        return redirect()->route('admin.ticket.index')->with('success', 'Ticket deleted successfully.');
    }
}
