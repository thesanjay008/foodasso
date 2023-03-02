<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Department;
use App\Models\country;
use Validator,Auth,DB;
use App\Models\Helpers\CommonHelper;

class CategoryController extends Controller
{
  
    use CommonHelper;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:category-list', ['only' => ['index','show']]);
        $this->middleware('permission:category-create', ['only' => ['create','store']]);
        $this->middleware('permission:category-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:category-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index(){
      try {
        $page_title = trans('title.category_index');
        $category = Category::all();
         return view('backend.admin.category.index',compact('category','page_title'));
      } catch (Exception $e) {
          return redirect()->back()->withError($e->getMessage());
      }   
    } 
    /**
     * Ajax for Index Pagination.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_ajax(Request $request){
        $request         =    $request->all();
        $draw            =    $request['draw'];
        $row             =    $request['start'];
        $rowperpage      =    $request['length']; // Rows display per page
        $columnIndex     =    $request['order'][0]['column']; // Column index
        $columnName      =    $request['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder =    $request['order'][0]['dir']; // asc or desc
        $searchValue     =    $request['search']['value']; // Search value

        $query = new Category();   
    
        ## Total number of records without filtering
        $total = $query->count();
        $totalRecords = $total;

        ## Total number of record with filtering
        $filter = $query;

        if($searchValue != ''){
            $filter =   $filter->whereHas('translation',function($query) use ($searchValue){
                          $query->where('title','like','%'.$searchValue.'%');
                        })
                          ->orWhere(function($query) use ($searchValue){
                          $query->where('status','like','%'.$searchValue.'%')
                                ->orWhere('image','like','%'.$searchValue.'%');
                                // ->orWhere('module_name','like','%'.$searchValue.'%');
                          });
        }
        $search = $filter->count();
        $totalRecordwithFilter = $search;

        ## Fetch records
        $empQuery = $filter;
        $empQuery = $empQuery->orderBy($columnName, $columnSortOrder)->offset($row)->limit($rowperpage)->get();

        $data = array();
        foreach ($empQuery as $emp) {
        ## Set dynamic route for action buttons
          $emp['edit']   = route("category.edit",$emp["id"]);
          $emp['show']   = route("category.show",$emp["id"]);
          $emp['delete'] = route("category.destroy",$emp["id"]);
          // $emp['category'] = route("category.destroy",$emp["id"]);
          //$media_path = \Storage::disk('s3')->url($emp["icon"]);

          $media_path = asset($emp["image"]);
          $emp['image'] = "<img src='".$media_path."' class='form-control'>";
          $data[]      = $emp;
        }

        ## Response
        $response = array(
          "draw" => intval($draw),
          "iTotalRecords" => $totalRecordwithFilter,
          "iTotalDisplayRecords" => $totalRecords,
          "aaData" => $data
        );

        echo json_encode($response);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
      try {
          $page_title = trans('title.add_new_category');
          return view('backend.admin.category.create',compact('page_title'));
      } catch (Exception $e) {
         return redirect()->back()->withError($e->getMessage());
      }   
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
      // print_r($request->all());die;
      $this->validate($request, [
        'title:ar' =>  'required|min:3|max:99',
        'title:en' =>  'required|min:3|max:99',
        'description:ar' => 'required|min:3|max:10000',
        'description:en' => 'required|min:3|max:10000',
        'image'      => 'required|image|mimes:png,jpg,jpeg,svg|max:10000',
        // 'select_type' => 'required',
        'status'    => 'required',
        'modules_type'    => 'required',
      ]);
      if (is_numeric($request['title:en'])) {
          throw ValidationException::withMessages(['title:en' => trans('common.invalid_title')]);
        }else if (is_numeric($request['title:ar'])) {
          throw ValidationException::withMessages(['title:ar' => trans('common.invalid_title')]);
      }
      DB::beginTransaction();
        try {
            $createArray = $request->all();

            //Save category image
            if(!empty($request->image)){
                $path = $this->saveMedia($request->file('image'),'category');
                $createArray['image'] = $path;

            } else {
                return redirect()->route('category.index')->with('error', trans('category.image_not_found'));
            }

            $category = Category::create($createArray);
            if($category){
              DB::commit();
                return redirect()->route('category.index')->with('success', trans('category.category_added_successfully'));
            } else {
                DB::rollback();
                return redirect()->route('category.index')->with('error', trans('category.category_added_not_successfully'));
            }
        }catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withError($e->getMessage());
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
      try {
            $page_title = trans('title.category_detail');
            $category = Category::find($id);
            if(!empty($category) && $category->count() > 0){
                return view('backend.admin.category.show',compact(['page_title','category']));
            } else {
                return redirect()->route('category.index')->with('error', trans('category.category_error'));
            }
      } catch (Exception $e) {
          return redirect()->back()->withError($e->getMessage());            
        }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
      try {
          $page_title = trans('title.update_categorys');
          $category = Category::find($id);
          if(!empty($category) && $category->count() > 0){
              return view('backend.admin.category.edit',compact(['category','page_title']));
          } else {
              return redirect()->route('category.index')->with('success', trans('category.category_error'));
          }
      } catch (Exception $e) {
          return redirect()->back()->withError($e->getMessage());
      }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        $category = Category::find($id);
        $this->validate($request, [
        'title:ar' =>  'required|min:3|max:99',
        'title:en' =>  'required|min:3|max:99',
        'description:ar' => 'required|min:3|max:100000',
        'description:en' => 'required|min:3|max:100000',
        'image'      => 'sometimes|required|image|mimes:png,jpg,jpeg,svg|max:10000',
        // 'select_type' => 'required',
        'status'    => 'required',
         'modules_type'    => 'required',
        ]);
        if (is_numeric($request['title:en'])) {
          throw ValidationException::withMessages(['title:en' => trans('common.invalid_title')]);
        }else if (is_numeric($request['title:ar'])) {
          throw ValidationException::withMessages(['title:ar' => trans('common.invalid_title')]);
        }
        DB::beginTransaction();
        try {
            $updateArray = $request->all();
            if(empty($category) && $category->count() == 0){
                return redirect()->route('category.index')->with('error', trans('category.category_data_not_found'));
            }
            if(!empty($updateArray['image'])){
              if(file_exists($category->image)){
              unlink($category->image);
              }
              $path = $this->saveMedia($request->file('image'),'category');
              $updateArray['image'] = $path;
            }
            $category->fill($updateArray);
            if($category->save()){
                DB::commit();
                return redirect()->route('category.index')->with('success', trans('category.category_updated_successfully'));
            } else {
                return redirect()->route('category.index')->with('error', trans('category.category_not_updated_success'));
            }
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withError($e->getMessage());
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
      DB::beginTransaction();
      try {
            $category = Category::find($id);
            if(empty($category) && $category->count() == 0){
                return redirect()->route('category.index')->with('error', trans('category.item_not_found'));
            }
            if(file_exists($category->image)){
                unlink($category->image);
            }
            if($category->delete()){
                DB::commit();
                return redirect()->route('category.index')->with('success', trans('category.category_deleted_successfully'));
            } else {
                DB::rollback();
                return redirect()->route('category.index')->with('error', trans('category.category_not_deleted_successfully'));
            }
      } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function status(Request $request)
    {
        $category= Category::where('id',$request->id)
               ->update(['status'=>$request->status]);
        if($category){
        return response()->json(['success' => trans('category.category_status_updated_successfully')]);
       }else{
        return response()->json(['error' => trans('category.category_status_updated_not_sucessfully')]);
       }
    }
}
