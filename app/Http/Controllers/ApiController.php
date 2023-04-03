<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function index(Request $request)
    {
        return view('api_view');
    }

    public function fetchData(Request $request)
    {
        $url = $request->input('url');
        $client = new \GuzzleHttp\Client();
        $errorMessage = '';
        

        try{
            $response = $client->request('GET', $url);
            $data = json_decode($response->getBody(), true);
            
            $randomIndex = rand(0, count($data['users']) - 1); //random index for random user
            $user = $data['users'][$randomIndex];
            
            $requiredFields = ['firstName', 'lastName', 'age', 'gender', 'phone'];
            $missingFields = [];
            foreach($requiredFields as $field)
            {
                if(!array_key_exists($field, $user)){
                    $missingFields[] = $field;
                }
            }

            //transform data
            if(empty($missingFields))
            {
                //Ensure email is lowercase
                $user['email'] = strtolower($user['email']);

                //ensure age is number
                $user['age'] = intval($user['age']);

                //Ensure the first character of the first and last name is Uppercase
                $user['firstName'] = ucwords($user['firstName']);
                $user['lastName'] = ucwords($user['lastName']);
            }


            $totalAge = 0;
            $emailDomainCount = [];
            $totalRecord = count($data['users']);

            foreach($data['users'] as $record)
            {
                $totalAge += $record['age'];
                $domain = substr(strrchr($record['email'], "@"), 1);
                $parts = explode(".", $domain);
                $extenstion = $domain;

                if(count($parts) >= 2)
                {
                    $extenstion = '.' . $parts[1];

                    if(count($parts) >= 3 ){
                        $extenstion .= '.' . $parts[2];
                    }
                } else {
                    $extenstion = '.'.$parts[0];
                }

                if(!isset($emailDomainCount[$extenstion]))
                {
                    $emailDomainCount[$extenstion] = 0;
                }

                $emailDomainCount[$extenstion]++;
            }

            $averageAge = $totalAge / count($data['users']); //average age
            
        } catch(\GuzzleHttp\Exception\RequestException $e){
            $errorMessage = $e->getMessage();
        }
        
        return view('api_view', [
            'user' => $user ?? null,
            'errorMessage' => $errorMessage,
            'missingFields' => $missingFields ?? null,
            'randomIndex' => $randomIndex ?? null,
            'averageAge' => $averageAge ?? null,
            'emailDomainCount' => $emailDomainCount ?? null,
            'totalRecord' => $totalRecord ?? null
        ]);

    }

    
}