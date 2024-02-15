<?php

namespace App\Admin\Controllers;

use App\Models\Company;
use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Grid\Filter\Where;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;

class CompanyController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'companies';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        
        $grid = new Grid(new Company());
        $grid->disableBatchActions();
        $grid->quickSearch('name', 'phone_number', 'phone_number_2', 'email');

        $grid->column('id', __('ID'))->hide();
        $grid->column('created_at', __('Registered'))
        ->display(function ($created_at){
            return date('Y-m-d', strtotime($created_at));
        })->sortable();

        $grid->column('updated_at', __('Updated at'))
        ->display(function ($updated_at){
            return date('Y-m-d', strtotime($updated_at));
        })->sortable(); 

        $grid->column('owner_id', __('Owner'))->display(function ($owner_id){
            $user = User::find($owner_id);
            if ($user == null){
                return 'Not found';
            }
            return $user->name;
        })->sortable();
        $grid->column('name', __('Company Name'));
        $grid->column('email', __('Email'));
        $grid->column('logo', __('Logo'));
        $grid->column('website', __('Website'))->hide();
        $grid->column('about', __('About'))->hide();
        $grid->column('status', __('Status'))->display(function ($status){
            return $status == 'Active' ? 'Active': 'Inactive';
        });

        $grid->column('address', __('Address'))->hide();

        $grid->column('license_expire', __('License expire'))
            ->display(function ($license_expire){
                return date ('Y-m-d', strtotime($license_expire));
            });
        $grid->column('phone_number', __('Phone number'))->hide();
        $grid->column('phone_number_2', __('Phone number 2'))->hide();
        $grid->column('pobox', __('Pobox'))->hide();
        $grid->column('color', __('Color'))->hide();
        $grid->column('slogan', __('Slogan'))->hide();
        $grid->column('facebook', __('Facebook'))->hide();
        $grid->column('twitter', __('Twitter'))->hide();
        

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
        $show = new Show(Company::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('owner_id', __('Owner id'));
        $show->field('name', __('Name'));
        $show->field('email', __('Email'));
        
        $show->field('logo', __('Logo'));
        $show->field('website', __('Website'));
        $show->field('about', __('About'));
        $show->field('status', __('Status'));
        $show->field('address', __('Address'));
        $show->field('license_expire', __('License expire'));
        $show->field('phone_number', __('Phone number'));
        $show->field('phone_number_2', __('Phone number 2'));
        $show->field('pobox', __('Pobox'));
        $show->field('color', __('Color'));
        $show->field('slogan', __('Slogan'));
        $show->field('facebook', __('Facebook'));
        $show->field('twitter', __('Twitter'));
        
        $show->field('currency', __('Currency'));
        $show->field('settings_worker_can_create_stock_item', __('Settings worker can create stock item'));
        $show->field('settings_worker_can_create_stock_record', __('Settings worker can create stock record'));
        $show->field('settings_worker_can_create_stock_category', __('Settings worker can create stock category'));
        $show->field('settings_worker_can_view_balance', __('Settings worker can view balance'));
        $show->field('settings_worker_can_view_stats', __('Settings worker can view stats'));
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
        $form = new Form(new Company());

        $admin_role_users = DB::table('admin_role_users')
                ->Where(['role_id' => 2])->get();

            $company_admins = [];
                foreach($admin_role_users as $key=>$value){
                    $user = User::find($value->user_id);
                    if($user == null){
                        continue;
                    }
                    $company_admins[$user->id]=$user->name; 
                }

        $form->select('owner_id', __('Company Owner'))->options($company_admins)->required();
        $form->textarea('name', __('Company Name'));
        $form->email('email', __('Email'));
        $form->image('logo', __('Logo'));
        $form->text('website', __('Website'));
        $form->text('about', __('About Company'));
        $form->text('status', __('Status'));
        $form->text('address', __('Address'));
        $form->date('license_expire', __('License expire'))->default(date('Y-m-d'));
        $form->text('phone_number', __('Phone number'));
        $form->text('phone_number_2', __('Phone number 2'));
        $form->text('pobox', __('Pobox'));
        $form->color('color', __('Color'));
        $form->text('slogan', __('Slogan'));
        $form->text('facebook', __('Facebook'));
        $form->text('twitter', __('Twitter'));
        $form->divider('Settings');
        $form->text('currency', __('Currency'))->default('USD')->rules('required');
        $form->radio('settings_worker_can_create_stock_item', __('Can Company work create stock item'))
            ->options([
                'Yes' => 'Yes',
                'No' => 'No'
            ])
            ->default('Yes');
        $form->radio('settings_worker_can_create_stock_record', __('Can Company worker create stock record?'))
            ->options([
            'Yes' => 'Yes',
            'No' => 'No'
            ])
            ->default('Yes');
        $form->radio('settings_worker_can_create_stock_category', __('Can Company worker create stock category?'))
            ->options([
            'Yes' => 'Yes',
            'No' => 'No'
            ])
            ->default('Yes');
        $form->radio('settings_worker_can_view_balance', __('Can Company worker view balance?'))
            ->options([
            'Yes' => 'Yes',
            'No' => 'No'
             ])
            ->default('Yes');
        $form->radio('settings_worker_can_view_stats', __('Can Company worker view stats'))
            ->options([
            'Yes' => 'Yes',
            'No' => 'No'
            ])
            ->default('Yes');

        $form->tools(function (Form\Tools $tools){
            $tools->disableDelete();
            $tools->disableView();
        });
        $form->disableCreatingCheck();
        $form->disableEditingCheck();
        $form->disableReset();
        $form->disableViewCheck();
        return $form;
    }
}
