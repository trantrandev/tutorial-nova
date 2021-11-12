<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
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

    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),
            Text::make('Title', 'name')
                ->rules('required', 'min:5')
                ->onlyOnForms(),
            Text::make('Title', 'name')
                ->rules('required', function () {
                    if($this->id === null) return null;
                    $route = route('post.show', $this->id);
                    return <<<HTML
                        <a href="{{ $route }}" class="no-underline dim text-primary font-bold">{{ this->name }}</a>
                    HTML;
                })->asHtml(),
            Text::make('URL', 'slug')->onlyOnForms(),
            Textarea::make('Content', 'content'),
            Image::make('Image', 'image'),
            Boolean::make('Is online?', 'online'),
            DateTime::make('Date', 'created_at'),
            BelongsTo::make('Author', 'user', User::class)
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
        return [];
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
        return [];
    }
}
