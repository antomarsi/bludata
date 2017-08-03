<?php

namespace App\Http\Controllers;

use App\Person;
use Illuminate\Http\Request;
use App\Filters\PersonFilter;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PersonFilter $filter)
    {
        return Person::filter($filter)->paginate();

    }

    public function store()
    {
        $this->validate(request(), [
            'name' => 'required',
            'cpf' => 'required|cpf|formato_cpf|unique:people,cpf',
            'rg' => (config('app.state') == 'SC' ? 'required' : ''),
            'birth' => 'required|date|after:1900-01-01|'.(config('app.state') == 'PR' ? 'before:-18 years':'before:today'),
        ]);
        $data = request()->all();
        $data['birth'] = Carbon::parse($data['birth']);
        dd(request()->all());
        Person::create(request()->all());

        return response()->json([
            'message' => 'Successful created'
        ]);
    }

    public function update(Person $person)
    {
        $this->validate(request(), [
            'name' => 'sometimes|required',
            'email' => ['sometimes', 'required', 'cpf', Rule::unique('people')->ignore($person->id)],
            'rg' => (config('app.state') == 'SC' ? 'required' : ''),
            'birth' => 'required|date|after:1900-01-01|'.(config('app.state') == 'PR' ? 'before:-18 years':'before:today'),
        ]);
        $data = request()->all();
        $data['birth'] = Carbon::parse($data['birth']);
        $person->fill($data)->save();

        return response()->json([
            'message' => 'Successful edited'
        ]);
    }

    public function destroy(Person $person)
    {
        $person->delete();

        return response()->json([
            'message' => 'The person has been successfully deleted'
        ], Response::HTTP_ACCEPTED);
    }
}
