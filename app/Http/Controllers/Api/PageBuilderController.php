<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Website;
use Illuminate\Support\Facades\Auth;

class PageBuilderController extends Controller
{
    // Save builder state
    public function save(Request $request, $id)
    {
        // $request->validate([
        //     'state' => 'required|array'
        // ]);
        $userId = Auth::id();
        $pageId = $id;

        $page = Page::find($pageId);

        $websiteId = $page->website_id;

        $state = $request->input('state');

        $builderState = Page::updateOrCreate(
            [
                'website_id' => $websiteId,
                'id' => $pageId,
            ],
            [
                'state' => $state,
            ]
        );
        return response()->json(['success' => true]);
    }

    // Load builder state
    public function load(Request $request, $id)
    {
        $userId = Auth::id();
        $pageId = $id;

        $page = Page::find($pageId);

        $websiteId = $page->website_id;

        $builderState = Page::where('website_id', $websiteId)
            ->where('id', $pageId)
            ->first();
        if ($builderState) {
            return response()->json(['state' => $builderState->state]);
        } else {
            return response()->json(['state' => null]);
        }
    }

    public function index()
    {
        $data = Page::get();
        return view('admin.page.index', compact('data'));
    }

    public function create()
    {
        $data = Website::get();

        return view('admin.page.create',compact('data'));
    }

    public function store(Request $request)
    {
        
        $website = Website::find($request->website_id);
        
        $add = new Page;
        $add->user_id = $website->user_id;
        $add->website_id = $request->website_id;
        $add->name = $request->name;
        $add->save();

        return redirect()->route('admin.page.index')->with('success', 'Page created successfully.');
    }

    public function edit($id)
    {
        $data = Page::find($id);
        $website = Website::where('user_id', Auth::user()->id)->get();
        return view('admin.page.edit', compact('data','website'));
    }

    public function update(Request $request, $id)
    {
        $update = Page::find($id);
        $update->name = $request->name;
        $update->status = $request->status;
        $update->update();

        return redirect()->route('admin.page.index')->with('success', 'Page updated successfully.');
    }

    public function delete($id)
    {
        $delete = Page::find($id);
        $delete->delete();

        return redirect()->route('admin.page.index')->with('success', 'Page deleted successfully.');
    }

    public function show($id)
    {
        $data = Page::find($id);
        return view('admin.page.page-builder', compact('data'));
    }
}
