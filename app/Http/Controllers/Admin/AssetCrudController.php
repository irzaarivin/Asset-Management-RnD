<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AssetRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

use App\Models\Asset;

/**
 * Class AssetCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AssetCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation {
        showDetailsRow as backpackList;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation {
        store as backpackStore;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation {
        update as backpackUpdate;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation {
        show as backpackShow;
    }

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Asset::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/asset');
        CRUD::setEntityNameStrings('asset', 'assets');
    }

    // public function index()
    // {
    //     $this->crud->hasAccessOrFail('list');

    //     $this->data['crud'] = $this->crud;
    //     $this->data['title'] = $this->crud->getTitle() ?? mb_ucfirst($this->crud->entity_name_plural);

    //     // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
    //     // return view($this->crud->getListView(), $this->data);

    //     dd($this->data);
    // }

    // protected function showDetailsRow($id) {
    //     dd($this);
    //     $this->crud->entry->thumbnail = Storage::disk('s3')->temporaryUrl($this->crud->entry->thumbnail, Carbon::now()->addDay());
    // }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    // protected function search() {
    //     dd($this->crud);
    // }

    protected function setupListOperation() {
        $checkCreatePerm = backpack_user()->can('Create Asset');
        $checkUpdatePerm = backpack_user()->can('Update Asset');
        $checkDeletePerm = backpack_user()->can('Delete Asset');

        if($checkCreatePerm === false) {
            $this->crud->denyAccess('create');
        }

        if($checkUpdatePerm === false) {
            $this->crud->denyAccess('update');
        }

        if($checkDeletePerm === false) {
            $this->crud->denyAccess('delete');
        }

        // CRUD::column('thumbnail')->type('image')->height('100px')->width('100px')->disk('s3')->function(function ($entry) {
        //     dd($entry);
        //     return $entry = Storage::disk('s3')->temporaryUrl($entry->thumbnail, Carbon::now()->addDay());
        // });

        // CRUD::column('thumbnail')->type('image')->disk('s3')->prefix('assets')->height('100px')->width('100px');
        CRUD::column('name');
        CRUD::column('description');
        CRUD::column('category_id');
        CRUD::column('status');
        CRUD::column('created_at');
        CRUD::column('updated_at');

        // dd($this->crud->settings()['list.columns']['thumbnail']);

        $this->crud->addFilter([
            'type'  => 'dropdown',
            'name'  => 'status',
            'label' => 'Status'
        ], [
            1 => 'Tersedia',
            2 => 'Dipinjam',
            3 => 'Pemeliharaan',
        ], function($value) {
            $checkString = $value == 1 ? 'available' : ($value == 2 ? 'borrowed' : 'maintenance');
            $this->crud->addClause('where', 'status', $checkString);
        });
    }

    // protected function store(Request $request) {
    //     $create = $this->backpackCreate();
    //     $request = $this->crud->getRequest();

    //     dd($request);

    //     if($request->hasFile('thumbnail')) {
    //         $file = $request->file('thumbnail');
    //         $path = $file->store('assets', 's3');
    //         $create->thumbnail = Storage::disk('s3')->url($path);
    //         $create->save();
    //     }

    //     return $create;
    // }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */

    protected function setupCreateOperation()
    {
        $check = backpack_user()->can('Create Asset');

        if($check === true) {
            CRUD::setValidation(AssetRequest::class);

            // CRUD::field('thumbnail')
            //     ->type('upload')
            //     ->upload(true)
            //     ->label('Thumbnail')
            //     ->withFiles([
            //         'disk' => 's3',
            //         'path' => 'assets',
            //         'temporaryUrl' => true,
            //         'temporaryUrlExpirationTime' => 200
            //     ]);

            CRUD::addField([
                'name' => 'thumbnail',
                'label' => 'Thumbnail',
                'type' => 'upload',
                'upload' => true,
                'disk' => 's3',
                'prefix' => 'assets',
                'temporary' => 10,
            ]);
            CRUD::addField([
                'name' => 'name',
                'label' => 'Nama',
                'type' => 'text',
                'attributes' => ['required' => 'required']
            ]);
            CRUD::addField([
                'name' => 'description',
                'label' => 'Deskripsi',
                'type' => 'textarea',
                'attributes' => ['required' => 'required']
            ]);
            CRUD::field('category_id')->label('Kategori')->attributes(['required' => 'required']);
            CRUD::addField([
                'name' => 'status',
                'label' => 'Status',
                'type' => 'enum',
                'options' => [
                    'available' => 'Tersedia',
                    'borrowed' => 'Dipinjam',
                    'maintenance' => 'Pemeliharaan',
                ],
            ]);
        } else {
            $this->crud->denyAccess('create');
        }

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
    }

    protected function store() {
        $create = $this->backpackStore();
        $request = $this->crud->getRequest();
        $asset = new Asset;

        if($request->hasFile('thumbnail')) {
            $asset->thumbnail = Storage::disk('s3')->putFile('/assets', request()->thumbnail);
            $asset->name = $request->name;
            $asset->description = $request->description;
            $asset->category_id = $request->category_id;
            $asset->status = $request->status;
            $asset->save();
        }

        // if($request->hasFile('thumbnail')) {
        //     $this->crud->entry->thumbnail = Storage::disk('s3')->putFile('/assets', $request->thumbnail);
        // }

        return redirect()->back();
    }
    protected function edit() {
        $this->crud->addField([
            'name' => 'thumbnail',
            'label' => 'Thumbnail',
            'type' => 'upload',
            'upload' => true,
            'disk' => 's3',
            'prefix' => 'assets',
            'temporary' => 10,
        ]);
    }

    protected function update() {
        $update = $this->backpackUpdate();
        $request = $this->crud->getRequest();

        if($request->hasFile('thumbnail')) {
            $asset = Asset::find($request->id);
            $asset->thumbnail = Storage::disk('s3')->putFile('/assets', request()->thumbnail);
            $asset->save();
        }

        return $update;
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $check = backpack_user()->can('Update Asset');

        if($check === true) {
            $this->setupCreateOperation();
        } else {
            $this->crud->denyAccess('update');
        }
    }

    protected function show($id) {
        $show = $this->backpackShow($id);

        if($this->crud->entry->thumbnail) {
            $this->crud->entry->thumbnail = Storage::disk('s3')->temporaryUrl($this->crud->entry->thumbnail, Carbon::now()->addDay());
        }

        return $show;
    }

    protected function setupShowOperation()
    {
        $checkUpdatePerm = backpack_user()->can('Update Asset');
        $checkDeletePerm = backpack_user()->can('Delete Asset');

        if($checkUpdatePerm === false) {
            $this->crud->denyAccess('update');
        }

        if($checkDeletePerm === false) {
            $this->crud->denyAccess('delete');
        }

        // dd($this->crud);

        // dd(Storage::disk('s3')->exists($this->crud->entry->thumbnail));

        // CRUD::column('thumbnail')->type('image')->disk('s3')->height('100px')->width('100px')->function(function ($entry) {
        //     return Storage::disk('s3')->temporaryUrl($entry->thumbnail, Carbon::now()->addDay());
        // });
        CRUD::column('thumbnail')->type('image')->height('100px')->width('100px');
        CRUD::column('name');
        CRUD::column('description')->type('markdown');
        CRUD::column('category_id');
        CRUD::column('status');
        CRUD::column('created_at');
        CRUD::column('updated_at');
    }
}
