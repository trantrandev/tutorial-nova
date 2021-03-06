<?php

namespace App\Nova;

use App\Nova\Actions\AttributeSetter;
use App\Nova\Filters\AuthorFilter;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Http\Requests\NovaRequest;

class Post extends Resource
{
    public static $model = \App\Models\Post::class;
    public static $title = 'name';
    public static $search = [
        'name',
        'content'
    ];
    public static $group = "Blog";
    public static function label()
    {
        return 'Article';
    }
  public static $with = ["categories"];

    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),
            Text::make('Title', 'name')
                ->rules('required', 'min:5')
                ->onlyOnForms(),
            Text::make('Title', function () {
                if ($this->id === null) return null;
                $route = route('post.show', $this->id);
                return <<<HTML
                        <a href="{$route}" class="no-underline dim text-primary font-bold">{$this->name}</a>
                    HTML;
            })->asHtml(),
            Text::make('Categories', function () {
                return $this->categories->map(function(\App\Models\Category $category) {
                    return $category->name;
                })->implode(', ');
            })->asHtml(),
            Text::make('URL', 'slug')->onlyOnForms(),
            Trix::make('Content', 'content'),
            Image::make('Image', 'image'),
            Boolean::make('Is online?', 'online'),
            DateTime::make('Date', 'created_at'),
            BelongsTo::make('Author', 'user', User::class),
            BelongsToMany::make('Categories', 'categories', Category::class)
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [
            new AuthorFilter()
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            new AttributeSetter('Make Online', 'online', 1),
            new AttributeSetter('Make Offline', 'online', 0)
        ];
    }
}
