<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Showcase;
use App\Traits\ShowcaseValidator;
use App\Traits\IconAndScreenshots;
use App\Http\Controllers\Controller;

/**
 * Controller for the Showcase Application.
 *
 * @package Election API Website
 * @author Nyan Lynn Htut <naynlynnhtut@hexcores.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class ShowcaseController extends Controller
{
    use ShowcaseValidator, IconAndScreenshots;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $apps = Showcase::latest()->paginate(20);

        return view('showcase.dashboard.index', compact('apps'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('showcase.dashboard.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = $this->validator($data);

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        $data['published'] = 'd';
        $data['slug'] = str_slug($data['name']);

        $showcase = new Showcase($data);

        if ( $showcase->save()) {
            session()->flash('success', 'Showcase App is successfully created.');

            return redirect()->route('showcase.icon', $showcase->id);
        }

        session()->flash('error', 'Error occured to create showcase app.');

        return back()->withInput();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Request $request, $id)
    {
        $model = Showcase::findOrFail($id);

        $screenshots = $model->screenshots;

        return view('showcase.dashboard.form', compact('model', 'screenshots'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $showcase = Showcase::findOrFail($id);

        $data = $request->all();

        $validator = $this->validator($data);

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        if ( $showcase->fill($data)->save()) {
            session()->flash('success', 'Showcase App is successfully edited.');

            return redirect()->route('showcase');
        }

        session()->flash('error', 'Error occured to edit showcase app.');

        return back()->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Publish the showcase application.
     *
     * @param  int  $id
     * @return Response
     */
    public function publish($id)
    {
        $app = Showcase::findOrFail($id);

        $app->published = 'p';

        if ( $app->save()) {
            session()->flash('success', 'Showcase App is successfully published.');
        } else {
            session()->flash('error', 'Error occured to publish the showcase app.');
        }

        return redirect()->route('showcase');
    }

     /**
     * Make showcase application is draft mode.
     *
     * @param  int  $id
     * @return Response
     */
    public function draft($id)
    {
        $app = Showcase::findOrFail($id);

        $app->published = 'd';

        if ( $app->save()) {
            session()->flash('success', 'Showcase App is successfully draft.');
        } else {
            session()->flash('error', 'Error occured to draft the showcase app.');
        }

        return redirect()->route('showcase');
    }
}
