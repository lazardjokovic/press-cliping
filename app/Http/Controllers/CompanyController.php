<?php

namespace App\Http\Controllers;

use App\Model\Keyword;
use Illuminate\Http\Request;
use App\Model\Company;
use Validator;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company = Company::with('keywords')->get();

        if($company)
            return response(array('success' => true, 'company' => $company), 200);
        return response(array('success' => false), 204);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator=Validator::make($request->all(), [
            'name'=>'required',
            'slug'=>'required'
        ]);

        if($validator->fails()){
            return response(array('success'=>false,'error'=>$validator->errors()),200);
        }

        try{
            $companyId = Company::create([
                'name' => $request->name,
                'slug' => $request->slug
            ])->id;

            foreach($request->keywords as $keyword) {
                Keyword::create([
                    'name' => $keyword['name'],
                    'slug' => $keyword['slug'],
                    'company_id'=>$companyId
                ]);
            }
            return response(array('success'=>true,'message'=> 'Company i keywords dodati'),200);
        }
        catch(\Exception $e){
            return response(array('success'=>false,'message'=> $e->getMessage()),200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        $companyDelete = Company::find($company->id)->first();

        try{
            $companyDelete->delete();
            return response(array('success'=>true,'message'=>'Kompanija obrisana'),200);
        }
        catch (\Exception $e){
            return response(array('success'=>false,'message'=>'test'),200);
        }
    }
}