<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BorrowingRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

use App\Models\Asset;

/**
 * Class BorrowingCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class BorrowingCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation {
        update as backpackUpdate;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Borrowing::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/borrowing');
        CRUD::setEntityNameStrings('borrowing', 'borrowings');
    }



    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation() {
        $checkUpdatePerm = backpack_user()->can('Update Borrowing');
        $checkDeletePerm = backpack_user()->can('Delete Borrowing');

        if($checkUpdatePerm === false) {
            $this->crud->denyAccess('update');
        }

        if($checkDeletePerm === false) {
            $this->crud->denyAccess('delete');
        }

        if(backpack_user()->hasRole('Administrator') === false) {
            $this->crud->addClause('where', 'user_id', backpack_user()->id);
        }

        CRUD::column('user_id');
        CRUD::column('asset_id');
        CRUD::column('notes');
        CRUD::column('borrow_date');
        CRUD::column('return_date');
        CRUD::column('status');

        if(backpack_user()->can('Approving Borrower')) {
            CRUD::addButtonFromView('line', 'approve-borrowing-button', 'approve-borrowing-button', 'end');
        }

        if(backpack_user()->can('Return Anybody Borrower')) {
            CRUD::addButtonFromView('line', 'returned-borrowing-button', 'returned-borrowing-button', 'end');
        }

        CRUD::column('created_at');
        CRUD::column('updated_at');

        $this->crud->addFilter([
            'type'  => 'dropdown',
            'name'  => 'status',
            'label' => 'Status'
        ], [
            1 => 'Menunggu',
            2 => 'Meminjam',
            3 => 'Dikembalikan',
            4 => 'Terlambat',
        ], function($value) {
            $checkString = $value == 1 ? 'pending' : ($value == 2 ? 'borrowed' : ($value == 3 ? 'returned' : 'overdue'));
            $this->crud->addClause('where', 'status', $checkString);
        });
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */

    protected function setupCreateOperation() {
        CRUD::setValidation(BorrowingRequest::class);

        if(backpack_user()->can('Create Anybody Borrower') === true) {
            CRUD::field('user_id');
        } else {
            CRUD::addField([
                'name' => 'name',
                'type' => 'text',
                'label' => 'Nama',
                'value' => backpack_user()->name,
                'attributes' => [
                    'disabled' => 'disabled',
                ]
            ]);
            CRUD::addField([
                'name' => 'user_id',
                'type' => 'hidden',
                'value' => backpack_user()->id,
            ]);
        }

        CRUD::addField([
            'name' => 'asset_id',
            'type' => 'select',
            'label' => 'Asset',
            'entity' => 'asset',
            'model' => 'App\Models\Asset',
            'attribute' => 'name',
            'options' => function ($query) {
                return $query->where('status', 'available')->get();
            },
        ]);

        CRUD::field('notes');
        CRUD::field('borrow_date');
        CRUD::field('return_date');

        if(backpack_user()->hasRole('Administrator')) {
            CRUD::field('status');
        } else {
            CRUD::field('status')->type('hidden')->value('pending');
        }

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
    }

    protected function update() {
        $request = $this->crud->getRequest();
        $create = $this->backpackUpdate();

        // dd($request);

        return $create;
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
