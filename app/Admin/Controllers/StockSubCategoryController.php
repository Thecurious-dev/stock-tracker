<?php

namespace App\Admin\Controllers;

use App\Models\StockCategory;
use App\Models\StockSubCategory;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class StockSubCategoryController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Stock SubCategory';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new StockSubCategory());

        $grid->disableBatchActions();
        $grid->quickSearch('name', 'company_id');

        $u = Admin::user();

        $grid->model()
            ->where('company_id', $u->company_id)
            ->orderBy('name', 'asc');
            //Display items for a particular company
        $grid->column('id', __('ID'))->sortable();
        $grid->column('image', __('Image'))
            ->lightbox([
               'width' => 50, 'height' => 50, 'zooming' => true
            ]);
        $grid->column('name', __('Name'));
        $grid->column('stock_category_id', __('Stock category id'))
            ->display(function ($stock_category_id){
                $category = StockCategory::find($stock_category_id);
                if($category == null){
                    return '';
                }
                
                return $category->name;
            })->sortable();
        $grid->column('company_id', __('Company id'));
        $grid->column('description', __('Description'))->hide();
        $grid->column('buying_price', __('Investment'))->sortable();
        $grid->column('selling_price', __('Expected Sales'))->sortable();
        $grid->column('expected_profit', __('Expected Profit'))->sortable();
        $grid->column('earned_profit', __('Earned Profit'))->sortable();
        $grid->column('measurement_unit', __('Measurement unit'));
        $grid->column('current_quantity', __('Current quantity'))
            ->display(function ($current_quantity){
                return number_format($current_quantity). ' '.$this->measurement_unit;
            });
        $grid->column('re0rder_level', __('Reorder level'))
            ->display(function ($re0rder_level){
                return number_format($re0rder_level). ' '.$this->measurement_unit;
            })->editable();
        $grid->column('in_stock', __('In Stock'))
            ->dot([
                'Yes'=> 'success',
                'No'=> 'danger'
            ])
            ->filter([
                'Yes' => 'In Stock',
                'No' => 'Out of Stock'
            ]);
        $grid->column('status', __('Status'))
            ->label([
                'Active' =>'success',
                'Inactive' => 'danger'
            ])->filter([
                'Active' => 'Active',
                'Inactive' => 'Inactive'
            ]);;

        

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(StockSubCategory::findOrFail($id));

        
        $show->field('id', __('Id'));
        $show->field('company_id', __('Company id'));
        $show->field('stock_category_id', __('Stock category id'));
        $show->field('name', __('Name'));
        $show->field('description', __('Description'));
        $show->field('status', __('Status'));
        $show->field('image', __('Image'));
        $show->field('buying_price', __('Buying price'));
        $show->field('selling_price', __('Selling price'));
        $show->field('expected_profit', __('Expected profit'));
        $show->field('earned_profit', __('Earned profit'));
        $show->field('measurement_unit', __('Measurement unit'));
        $show->field('current_quantity', __('Current quantity'));
        $show->field('re0rder_level', __('Re0rder level'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new StockSubCategory());

        $u = Admin::user();
        $form->hidden('company_id', __('Company id'))->default($u->company_id);

        $categories = StockCategory::where([
            'company_id'=> ($u->company_id),
            'status' => 'active'
        ])->get()->pluck('name', 'id');

        $form->select('stock_category_id', __('Stock category'))
            ->options($categories)->rules('required') ;

        $form->text('name', __('Name'))->required();
        $form->textarea('description', __('Description'));
        $form->text('status', __('Status'))->options([
            'Active' => 'Active',
            'Inactive'=>'Inactive'
         ])->default('Active')->rules('required');
        $form->image('image', __('Image'))->uniqueName();
      
        $form->text('measurement_unit', __('Measurement unit'))->required();
        $form->decimal('re0rder_level', __('Re0rder level'))->required();

        
        return $form;
    }
}
