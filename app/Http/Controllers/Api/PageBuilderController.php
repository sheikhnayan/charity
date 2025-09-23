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
        $userId = Auth::id();
        $pageId = $id;

        $page = Page::find($pageId);
        $state = $request->input('state');

        
        if ($page->is_main_site) {
            // Main site page - update directly
            $page->update(['state' => $state]);
            // dd($page);
        } else {
            // Regular website page (existing logic)
            $websiteId = $page->website_id;
            
            $builderState = Page::updateOrCreate(
                [
                    'website_id' => $websiteId,
                    'id' => $pageId,
                ],
                [
                    'state' => $state,
                ]
            );
        }
        
        return response()->json(['success' => true]);
    }

    // Load builder state
    public function load(Request $request, $id)
    {
        $userId = Auth::id();
        $pageId = $id;

        $page = Page::find($pageId);

        if ($page->is_main_site) {
            // Main site page
            $builderState = $page;
        } else {
            // Regular website page (existing logic)
            $websiteId = $page->website_id;
            $builderState = Page::where('website_id', $websiteId)
                ->where('id', $pageId)
                ->first();
        }
        
        if ($builderState) {
            return response()->json(['state' => $builderState->state]);
        } else {
            return response()->json(['state' => null]);
        }
    }

    public function index()
    {
        // Get both regular pages and main site pages
        $data = Page::with(['website'])->get();
        $mainSitePages = Page::mainSite()->get();
        
        return view('admin.page.index', compact('data', 'mainSitePages'));
    }

    public function create()
    {
        $data = Website::get();

        return view('admin.page.create',compact('data'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        
         // Validate input
        if ($request->has('is_main_site') && $request->is_main_site) {
            // Creating a main site page
            $nextPosition = Page::mainSite()->max('position') + 1;
            
            $add = new Page;
            $add->user_id = null; // Main site pages don't belong to specific users
            $add->website_id = null; // Main site pages don't belong to specific websites
            $add->is_main_site = true;
            $add->name = $request->name;
            $add->meta_title = $request->meta_title;
            $add->meta_description = $request->meta_description;
            $add->background_color = $request->background_color;
            $add->default = $request->default;
            $add->position = $nextPosition;
            $add->status = 1;
            $add->save();
        } else {
            // Creating a regular website page (existing logic)
            $website = Website::find($request->website_id);
            
            // Get the next position for this website
            $nextPosition = Page::where('website_id', $request->website_id)->max('position') + 1;
            
            $add = new Page;
            $add->user_id = $website->user_id;
            $add->website_id = $request->website_id;
            $add->is_main_site = false;
            $add->name = $request->name;
            $add->meta_title = $request->meta_title;
            $add->meta_description = $request->meta_description;
            $add->background_color = $request->background_color;
            $add->default = $request->default;
            $add->position = $nextPosition;
            $add->status = 1;
            $add->save();
        }

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
        $update->meta_title = $request->meta_title;
        $update->meta_description = $request->meta_description;
        $update->background_color = $request->background_color;
        $update->default = $request->default;
        $update->status = $request->status;
        
        // Handle main site page updates
        if ($request->has('is_main_site') && $request->is_main_site) {
            $update->is_main_site = true;
            $update->user_id = null;
            $update->website_id = null;
        } else {
            $update->is_main_site = false;
            // For regular pages, keep existing website relationship
        }
        
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
        $data = Page::with(['website', 'website.header'])->find($id);
        return view('admin.page.page-builder', compact('data'));
    }
}
