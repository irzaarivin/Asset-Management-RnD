<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AssetRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

use App\Models\Asset;

/**
 * Class AssetCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AssetCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

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

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
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

        CRUD::column('name');
        CRUD::column('description');
        CRUD::column('category_id');
        CRUD::column('status');
        CRUD::column('created_at');
        CRUD::column('updated_at');

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

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

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

        CRUD::column('name');
        CRUD::column('description')->type('markdown');
        CRUD::column('category_id');
        CRUD::column('status');
        CRUD::column('created_at');
        CRUD::column('updated_at');
    }
}
