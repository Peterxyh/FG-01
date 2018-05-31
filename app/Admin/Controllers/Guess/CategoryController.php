<?php
namespace App\Admin\Controllers\Guess;
/**
 * Created by PhpStorm.
 * User: Brian Bi
 * Date: 2018/5/29
 * Time: 15:13
 */

use App\Http\Controllers\Controller;
use App\Model\Guess\Category;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Tree;
use Encore\Admin\Widgets\Box;

class CategoryController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header(__('Guess Category'));
            $content->description(__('admin.list'));

            $content->row(function (Row $row) {
                $row->column(6, $this->treeView()->render());

                $row->column(6, function (Column $column) {
                    $form = new \Encore\Admin\Widgets\Form();
                    $form->action(admin_base_path('guess/categorys'));

                    $form->select('parent_id', trans('admin.parent_id'))->options(Category::selectOptions());
                    $form->text('title', trans('admin.title'))->rules('required');
                    $form->image('image', 'Images')->uniqueName()->removable();
                    $form->text('origin_id', 'Origin Id');
                    $form->url('url', 'Origin Url');
                    $form->hidden('_token')->default(csrf_token());

                    $column->append((new Box(trans('admin.new'), $form))->style('success'));
                });
            });
        });
    }

    /**
     * @return \Encore\Admin\Tree
     */
    protected function treeView()
    {
        return Category::tree(function (Tree $tree) {
            $tree->disableCreate();

            $tree->branch(function ($branch) {
                $payload = '';

                if (!isset($branch['children']))
                {
                    if ($branch['image'])
                    {
                        $payload .= "<img style='max-width: 25px' src='/media/{$branch['image']}'>&nbsp;&nbsp;&nbsp;&nbsp;";
                    }
                }

                $payload .= "<strong style='padding: 5px;'>{$branch['title']}</strong>";

                return $payload;
            });
        });
    }

    /**
     * Edit interface.
     *
     * @param string $id
     *
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header(__('admin_discuss.tags'));
            $content->description(__('admin.edit'));

            $content->row($this->form()->edit($id));
        });
    }

    /**
     * make form builder
     *
     * @return Form
     */
    protected function form()
    {
        return Category::form(function (Form $form) {
            $form->display('id', 'ID');

            $form->select('parent_id', trans('admin.parent_id'))->options(Category::selectOptions());
            $form->text('title', trans('admin.title'))->rules('required');
            $form->image('image', 'Images')->uniqueName()->removable();
            $form->switch('status', '状态')->default(1);
            $form->text('origin_id', 'Origin Id');
            $form->url('url', 'Origin Url');
            $form->display('created_at', trans('admin.created_at'));
            $form->display('updated_at', trans('admin.updated_at'));
        });
    }
}